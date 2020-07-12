<?php

namespace App\Http\Controllers;

use App\User;
use App\Bank;
use App\Topic;
use App\Credit;
use App\Bankmail;
use App\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BankController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');

    $this->middleware('App\Http\Middleware\SteamId');

    $this->middleware('App\Http\Middleware\BankOverdraft', ['except' => ['overdraft', 'liquidation', 'nova', 'administration']]);
  }

  /**
   * Show a list of all of the user's bank accounts.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = Auth::user();

    $accounts = DB::table('unt_bs_multi_accounts')
      ->where('steamid', $user->steamid)
      ->orderBy('balance', 'desc')
      ->get();

    $accountsHistory = DB::table('unt_bs_account_history')
      ->where('accountId', 'like', '%' . $user->steamid . '%')
      ->where('lastUpdated', '>=', Carbon::today()->subWeek())
      ->orderBy('lastUpdated', 'desc')
      ->get()
      ->groupBy(function ($val) {
        return Carbon::parse($val->lastUpdated)->format('d/m/y');
      });

    $balanceHistory = DB::table('balance_history')
      ->where('accountId', 'like', '%' . $user->steamid . '%')
      ->where('lastUpdated', '>=', Carbon::today()->subWeek())
      ->orderBy('lastUpdated', 'desc')
      ->get()
      ->groupBy(function ($val) {
        return $val->balance;
      });

    $credits = $user->credits()->orderBy('name')->get();

    return view('bank.index', compact('accounts', 'accountsHistory', 'balanceHistory', 'credits'));
  }

  /**
   * Store a newly created account in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|min:2'
    ]);

    $user = Auth::user();

    $lastAccount = DB::table('unt_bs_multi_accounts')
      ->where('steamid', $user->steamid)
      ->orderBy('accountId', 'desc')
      ->first();

    if ($lastAccount) {
      // 5 = limite de comptes (0, 1, 2, 3, 4)
      if ((substr($lastAccount->accountId, -1) + 1) < 5) {

        $newAccountId = substr($lastAccount->accountId, 0, -1) . (substr($lastAccount->accountId, -1) + 1);

        DB::table('unt_bs_multi_accounts')->insert(
          [
            'steamId' => $user->steamid,
            'accountId' => $newAccountId,
            'accountName' => $request->name,
            'balance' => 0.00,
            'lastUpdated' => Carbon::now()->toDateTimeString()
          ]
        );

        return redirect()->route('bank.index');
      } else {
        return back()->withError('Vous possédez déjà la limite de comptes.');
      }
    } else {
      $newAccountId = $user->steamid . '-00';

      DB::table('unt_bs_multi_accounts')->insert(
        [
          'steamId' => $user->steamid,
          'accountId' => $newAccountId,
          'accountName' => $request->name,
          'balance' => 0.00,
          'lastUpdated' => Carbon::now()->toDateTimeString()
        ]
      );

      DB::table('unt_bs_account_info')->insert(
        [
          'steamId' => $user->steamid,
          'accountId' => $newAccountId,
          'lastUpdated' => Carbon::now()->toDateTimeString()
        ]
      );

      return redirect()->route('bank.index');
    }
  }

  /**
   * Display the specified bank account.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $activeAccount = DB::table('unt_bs_multi_accounts')
      ->where('accountId', $id)
      ->first();
    $accountOwner = $activeAccount->steamId;

    $accounts = DB::table('unt_bs_multi_accounts')
      ->where('steamid', $accountOwner)
      ->orderBy('balance', 'desc')
      ->get();

    if (Auth::user()->steamid === $accountOwner) {

      $accounthistory = DB::table('unt_bs_account_history')
        ->where('accountId', $id)
        ->where('lastUpdated', '>=', Carbon::today()->subWeek())
        ->orderBy('lastUpdated', 'desc')
        ->get()
        ->groupBy(function ($val) {
          return Carbon::parse($val->lastUpdated)->format('d/m/y');
        });

      $balancehistory = DB::table('balance_history')
        ->where('accountId', $id)
        ->orderBy('lastUpdated', 'desc')
        ->limit(7)
        ->get()
        ->groupBy(function ($val) {
          return $val->balance;
        });

      return view('bank.show', compact('activeAccount', 'accounts', 'accounthistory', 'balancehistory'));
    } else {
      return redirect('/');
    }
  }

  /**
   * Update the specified account in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required|min:2'
    ]);

    $account = DB::table('unt_bs_multi_accounts')
      ->where('accountId', $id)
      ->first();
    $accountOwner = $account->steamId;

    if (Auth::user()->steamid === $accountOwner) {

      DB::table('unt_bs_multi_accounts')
        ->where('accountId', $id)
        ->update(['accountName' => $request->name]);

      return redirect()->route('bank.show', $id);
    } else {
      return redirect('/');
    }
  }

  /**
   * Remove the specified account from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $account = DB::table('unt_bs_multi_accounts')
      ->where('accountId', $id)
      ->first();

    if ($account->balance != 0.00) {
      return back()->withError("Votre compte n'est pas vide, veuillez retirer tout l'argent qui s'y trouve, ou rembourser tous vos crédits.");
    }

    $accountOwner = $account->steamId;
    if (Auth::user()->steamid === $accountOwner) {
      DB::table('unt_bs_multi_accounts')->where('accountId', $id)->delete();
      DB::table('unt_bs_account_history')->where('accountId', $id)->delete();
      DB::table('unt_bs_account_info')->where('accountId', $id)->delete();
      DB::table('balance_history')->where('accountId', $id)->delete();

      return redirect()->route('bank.index');
    } else {
      return redirect('/');
    }
  }

  /**
   * Show the form for transfering money from one account to another.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function transfer()
  {
    $user = Auth::user();

    $accounts = DB::table('unt_bs_multi_accounts')
      ->where('steamId', $user->steamid)
      ->orderBy('balance', 'desc')
      ->get();

    $groupAccountsIds = DB::table('unt_bs_group_info')
      ->where('steamId', $user->steamid)
      ->pluck('accountId')
      ->all();

    $groupAccounts = DB::table('unt_bs_group_accounts')
      ->whereIn('accountId', $groupAccountsIds)
      ->orderBy('balance', 'desc')
      ->get();

    return view('bank.transfer', compact('accounts', 'groupAccounts'));
  }

  /**
   * Get the accounts corresponding to current request (autocomplete).
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getAccounts(Request $request)
  {

    $search = $request->search;

    if ($search == '') {
      $accounts = DB::table('unt_bs_multi_accounts')
        ->orderby('accountName', 'asc')
        ->select('accountId', 'accountName')
        ->limit(5)
        ->get();
    } else {
      $soloAccounts = DB::table('unt_bs_multi_accounts')
        ->where('accountName', 'like', '%' . $search . '%')
        ->orWhere('accountId', 'like', '%' . $search . '%')
        ->orderby('accountName', 'asc')
        ->select('accountId', 'accountName')
        ->limit(5)
        ->get();

      $groupAccounts = DB::table('unt_bs_group_accounts')
        ->where('accountName', 'like', '%' . $search . '%')
        ->orWhere('accountId', 'like', '%' . $search . '%')
        ->orderby('accountName', 'asc')
        ->select('accountId', 'accountName')
        ->limit(5)
        ->get();

      $accounts = $soloAccounts->merge($groupAccounts);
    }

    $response = array();
    foreach ($accounts as $account) {
      $response[] = array(
        "value" => $account->accountId,
        "label" => $account->accountName
      );
    }

    echo json_encode($response);
    exit;
  }

  /**
   * Show the form for transfering money from one account to another.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function exchange(Request $request)
  {
    $this->validate($request, [
      'to' => 'required',
      'amount' => 'required',
      'from' => 'required'
    ]);

    $formatter = new \NumberFormatter('fr_FR', \NumberFormatter::CURRENCY);
    $amount = floatval($formatter->parseCurrency($request->amount, $curr));

    $user = Auth::user();

    if ($amount > 0) {
      if ($amount <= $user->bank->properties['transfer_ceiling']) {

        if (strlen($request->from) === 14) {
          $from = DB::table('unt_bs_group_accounts')
            ->where('accountId', $request->from)
            ->first();

          $accountOwner = $from->ownerId;
        } elseif (strlen($request->from) === 20) {
          $from = DB::table('unt_bs_multi_accounts')
            ->where('accountId', $request->from)
            ->first();

          $accountOwner = $from->steamId;
        }

        if (strlen($request->to) === 14) {
          $to = DB::table('unt_bs_group_accounts')
            ->where('accountId', $request->to)
            ->first();

          $toUser = User::where('steamid', $to->ownerId)
            ->first();
        } elseif (strlen($request->to) === 20) {
          $to = DB::table('unt_bs_multi_accounts')
            ->where('accountId', $request->to)
            ->first();

          $toUser = User::where('steamid', $to->steamId)
            ->first();
        }

        if ($user->steamid === $accountOwner) {

          $fromBalance = $from->balance;
          $toBalance = $to->balance;

          if ($fromBalance >= ($amount + $amount_costs)) {

            $amount_costs = ($amount / 100 * $user->bank->properties['transfer_costs']);

            if (($toBalance + $amount) <= $toUser->bank->properties['account_ceiling']) {

              if (strlen($request->from) === 14) {
                DB::table('unt_bs_group_accounts')
                  ->where('accountId', $request->from)
                  ->decrement('balance', ($amount + $amount_costs));
              } elseif (strlen($request->from) === 20) {
                DB::table('unt_bs_multi_accounts')
                  ->where('accountId', $request->from)
                  ->decrement('balance', ($amount + $amount_costs));
              }

              if (strlen($request->to) === 14) {
                DB::table('unt_bs_group_accounts')
                  ->where('accountId', $request->to)
                  ->increment('balance', $amount);
              } elseif (strlen($request->to) === 20) {
                DB::table('unt_bs_multi_accounts')
                  ->where('accountId', $request->to)
                  ->increment('balance', $amount);
              }
            } else {
              $amount = ($toUser->bank->properties['account_ceiling'] - $toBalance);
              $amount_costs = ($amount / 100 * $user->bank->properties['transfer_costs']);

              if (strlen($request->from) === 14) {
                DB::table('unt_bs_group_accounts')
                  ->where('accountId', $request->from)
                  ->decrement('balance', ($amount + $amount_costs));
              } elseif (strlen($request->from) === 20) {
                DB::table('unt_bs_multi_accounts')
                  ->where('accountId', $request->from)
                  ->decrement('balance', ($amount + $amount_costs));
              }

              if (strlen($request->to) === 14) {
                DB::table('unt_bs_group_accounts')
                  ->where('accountId', $request->to)
                  ->increment('balance', $amount);
              } elseif (strlen($request->to) === 20) {
                DB::table('unt_bs_multi_accounts')
                  ->where('accountId', $request->to)
                  ->increment('balance', $amount);
              }
            }

            if ($request->message != '') {
              DB::table('unt_bs_account_history')
                ->insert([
                  [
                    'accountId' => $request->from,
                    'text' => '<color=#FF3C3C>-' . round($amount, 2) . '€</color>: ' . $request->message,
                    'lastUpdated' => Carbon::now()->toDateTimeString()
                  ],
                  [
                    'accountId' => $request->to,
                    'text' => '<color=#FF3C3C>+' . round(($amount - $amount_costs), 2) . '€</color>: ' . $request->message,
                    'lastUpdated' => Carbon::now()->toDateTimeString()
                  ]
                ]);
            } else {
              DB::table('unt_bs_account_history')
                ->insert([
                  [
                    'accountId' => $request->from,
                    'text' => '<color=#FF3C3C>-' . round($amount, 2) . '€</color>: Virement à ' . $toUser->firstname . ' ' . $toUser->lastname,
                    'lastUpdated' => Carbon::now()->toDateTimeString()
                  ],
                  [
                    'accountId' => $request->to,
                    'text' => '<color=#FF3C3C>+' . round(($amount - $amount_costs), 2) . '€</color>: Virement de ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname,
                    'lastUpdated' => Carbon::now()->toDateTimeString()
                  ]
                ]);
            }

            if (strlen($request->from) === 14) {
              return redirect()->route('bank.groupShow', $request->from);
            } elseif (strlen($request->from) === 20) {
              return redirect()->route('bank.show', $request->from);
            }
          } else {
            return back()->withError('Vous ne pouvez pas transférer autant. N\'oubliez pas les frais de transfert.');
          }
        } else {
          return redirect('/');
        }
      } else {
        return back()->withError('La somme que vous souhaitez transférer dépasse le plafond autorisé par votre banque.');
      }
    } else {
      return back()->withError('Vous devez transférer une somme positive');
    }
  }

  /**
   * Show the option for when the user bank account is over the overdraft limit
   *
   * @return \Illuminate\Http\Response
   */
  public function overdraft()
  {

    $accounts = DB::table('unt_bs_multi_accounts')->where('steamid', Auth::user()->steamid)->get();

    $sumBalance = 0;
    foreach ($accounts as $account) {
      $sumBalance += $account->balance;
    }

    $overdraft = isset(Auth::user()->bank->properties['overdraft'][0]) ? Auth::user()->bank->properties['overdraft'][0] : 0;

    if ($sumBalance < $overdraft) {

      $credits = Auth::user()->credits()->get();

      $accountsHistory = DB::table('unt_bs_account_history')
        ->where('accountId', 'like', '%' . Auth::user()->steamid . '%')
        ->where('lastUpdated', '>=', Carbon::today()->subWeek())
        ->orderBy('lastUpdated', 'desc')
        ->get()
        ->groupBy(function ($val) {
          return Carbon::parse($val->lastUpdated)->format('d/m/y');
        });

      $sumCredit = 0;
      foreach ($credits as $credit) {
        $pivot = DB::table('credit_user')
          ->where('credit_id', $credit->id)
          ->where('user_id', Auth::user()->id)
          ->first();

        $amount = $credit->properties['amount'];
        $period = $credit->properties['period'];
        $rate = $credit->properties['rate'];

        $sumCredit += $amount / $period * Carbon::parse($pivot->created_at)->addDays($period)->add(1, 'day')->diffInDays(Carbon::now()) - ($amount / $period) + ($amount / $period + ($amount / $period / 100 * $rate));
      }

      $liquidationMessage = DB::table('topics')->where('title', 'Demande de saisie: ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname)->first();

      if (!isset($liquidationMessage)) {
        $category = Category::where('name', 'Demandes de saisie')->first();

        $topic = new Topic;

        $topic->title = 'Demande de saisie: ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname;
        $topic->body = 'Bonjour,
    
          Un de nos utilisateurs est actuellement endetté, et a souhaité nous rembourser en mettant en vente ses biens. Merci de vous rendre au domicile de ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname . ' et d’effectuer la saisie de ses biens pour une valeur de:
          
          ' . number_format((-$sumBalance + $sumCredit), 2, ',', ' ') . ' €
          
          Après la saisie, merci de répondre à ce message avec le statut de la saisie (réussie/non-réussie), et si elle n\'a pas été réussie, la cause (domicile non-trouvé, valeur de la saisie inférieure à celle de la dette...).
          
          Cordialement,
          Banque ' . Auth::user()->bank->name;
        $topic->user_id = 2;

        $topic->category()->associate($category)->save();

        $topic->created_at = Carbon::now()->add(6, 'day');
        $topic->updated_at = Carbon::now()->add(6, 'day');

        $topic->save(['timestamps' => false]);
      }

      $deadline = DB::table('topics')->where('title', 'Demande de saisie: ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname)->pluck('created_at')->first();

      return view('bank.overdraft', compact('accounts', 'credits', 'accountsHistory', 'deadline'));
    } else {
      return redirect('/');
    }
  }

  /**
   * Begin the liquidation process
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function liquidation(Request $request)
  {
    $accounts = DB::table('unt_bs_multi_accounts')->where('steamid', Auth::user()->steamid)->get();

    $sumBalance = 0;
    foreach ($accounts as $account) {
      $sumBalance += $account->balance;
    }

    $overdraft = isset(Auth::user()->bank->properties['overdraft'][0]) ? Auth::user()->bank->properties['overdraft'][0] : 0;

    if ($sumBalance < $overdraft) {

      $credits = Auth::user()->credits()->get();

      $sumCredit = 0;
      foreach ($credits as $credit) {
        $pivot = DB::table('credit_user')
          ->where('credit_id', $credit->id)
          ->where('user_id', Auth::user()->id)
          ->first();

        $amount = $credit->properties['amount'];
        $period = $credit->properties['period'];
        $rate = $credit->properties['rate'];

        $sumCredit += $amount / $period * Carbon::parse($pivot->created_at)->addDays($period)->add(1, 'day')->diffInDays(Carbon::now()) - ($amount / $period) + ($amount / $period + ($amount / $period / 100 * $rate));
      }

      $sumDebtPerAccount = ((-$sumBalance + $sumCredit) / count($accounts)) * -1;

      foreach ($accounts as $account) {
        DB::table('unt_bs_multi_accounts')->where('accountId', $account->accountId)->update(['balance' => $sumDebtPerAccount]);
      }

      foreach ($credits as $credit) {
        Auth::user()->credits()->detach($credit->id);
      }

      $liquidationMessage = DB::table('topics')->where('title', 'Demande de saisie: ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname)->update(['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);

      return redirect()->route('bank.overdraft');
    } else {
      return redirect('/');
    }
  }

  /**
   * Direct to nova credit page
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function nova(Request $request)
  {
    $accounts = DB::table('unt_bs_multi_accounts')->where('steamid', Auth::user()->steamid)->get();

    $sumBalance = 0;
    foreach ($accounts as $account) {
      $sumBalance += $account->balance;
    }

    $overdraft = isset(Auth::user()->bank->properties['overdraft'][0]) ? Auth::user()->bank->properties['overdraft'][0] : 0;

    if ($sumBalance < $overdraft) {

      $credits = Auth::user()->credits()->get();

      $sumCredit = 0;
      foreach ($credits as $credit) {
        $pivot = DB::table('credit_user')
          ->where('credit_id', $credit->id)
          ->where('user_id', Auth::user()->id)
          ->first();

        $amount = $credit->properties['amount'];
        $period = $credit->properties['period'];
        $rate = $credit->properties['rate'];

        $sumCredit += $amount / $period * Carbon::parse($pivot->created_at)->addDays($period)->add(1, 'day')->diffInDays(Carbon::now()) - ($amount / $period) + ($amount / $period + ($amount / $period / 100 * $rate));
      }

      $balancehistory = DB::table('balance_history')
        ->where('steamId', Auth::user()->steamid)
        ->where('lastUpdated', '>=', Carbon::today()->subWeek())
        ->orderBy('lastUpdated', 'desc')
        ->get()
        ->groupBy(function ($val) {
          return $val->balance;
        });

      return view('bank.nova', compact('accounts', 'credits', 'sumBalance', 'sumCredit', 'balancehistory'));
    } else {
      return redirect('/');
    }
  }

  /**
   * Show the mails page with user's mails
   *
   * @return \Illuminate\Http\Response
   */
  public function mails()
  {
    $mails = Auth::user()
      ->bankmails
      ->where('created_at', '>=', Carbon::today()->subWeeks(3))
      ->where('category', '!=', 'demande')
      ->sortByDesc('created_at');

    return view('bank.mails', compact('mails'));
  }

  /**
   * Set mail as read
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function readMail(Request $request)
  {
    if ($request->id === 'all') {
      $mails = Auth::user()
        ->bankmails
        ->where('read', 0)
        ->all();

      foreach ($mails as $mail) {
        $mail->read = true;
        $mail->save();
      }

      return redirect()->route('bank.mails');
    } else {

      $mail = Auth::user()
        ->bankmails
        ->where('id', $request->id)
        ->first();

      $mail->read = true;
      $mail->save();

      return redirect()->route('bank.mails');
    }
  }

  /**
   * Sends mail
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeMail(Request $request)
  {
    if (isset($request->company)) {
      $this->validate($request, [
        'company' => 'required|min:2',
        'address' => 'required',
        'amount' => 'required',
        'period' => 'required|numeric',
        'body' => 'required|min:2',
      ]);

      $formatter = new \NumberFormatter('fr_FR', \NumberFormatter::CURRENCY);
      $amount = floatval($formatter->parseCurrency($request->amount, $curr));

      $bankmail = new Bankmail;
      $bankmail->category = 'demande';
      $bankmail->title = 'Demande de prêt d\'entreprise (' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname . ')';
      $bankmail->body = 'Demande: Prêt pour une entreprise
      Envoyée par: ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname . '
      Entreprise: ' . $request->company . '
      Adresse du siège: ' . $request->address . '
      Montant demandé: ' . $request->amount . '
      Période d\'étalement: ' . $request->period . ' jours
      
      Contenu du message: 
      ' . $request->body;
      $bankmail->user_id = Auth::user()->id;
      $bankmail->bank_id = Auth::user()->bank->id;
      $bankmail->save();

      $bankmail = new Bankmail;
      $bankmail->category = 'confirmation';
      $bankmail->title = 'Confirmation d\'envoi';
      $bankmail->body = 'Bonjour,
      
      Votre demande de prêt a bien été envoyé. Une réponse vous sera envoyée dans les plus brefs délais.
      

      Cordialement,
      Le répondeur automatique';
      $bankmail->user_id = Auth::user()->id;
      $bankmail->bank_id = Auth::user()->bank->id;
      $bankmail->save();

      return redirect()->route('bank.mails');
    } elseif (isset($request->complaint)) {
      $this->validate($request, [
        'complaint' => 'required|min:2'
      ]);

      $data = json_decode($request->complaint);

      $objects = '';
      foreach ($data as $object) {
        $objects .= "Bien: " . $object->nomdubien . "; Valeur: " . $object->valeurestimee . "€; Preuve de possession: " . $object->preuvedepossessionlien . "; Preuve de perte: " . $object->preuvedepertelien . "; Détails supplémentaires: " . $object->detailssupplementairesdatedachatlieudestockage . "\n \n";
      }

      $bankmail = new Bankmail;
      $bankmail->category = 'demande';
      $bankmail->title = 'Réclamation d\'assurance (' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname . ')';
      $bankmail->body = 'Demande: Remboursement par l\'assurance
      Envoyée par: ' . Auth::user()->character->firstname . ' ' . Auth::user()->character->lastname . '

      Biens à rembourser:
      ' . $objects . '
      Contenu du message: 
      ' . $request->body;
      $bankmail->user_id = Auth::user()->id;
      $bankmail->bank_id = Auth::user()->bank->id;
      $bankmail->save();

      $bankmail = new Bankmail;
      $bankmail->category = 'confirmation';
      $bankmail->title = 'Confirmation d\'envoi';
      $bankmail->body = 'Bonjour,
      
      Votre réclamation a bien été envoyé à votre assurance. Une réponse vous sera envoyée dans les plus brefs délais.
      

      Cordialement,
      Le répondeur automatique';
      $bankmail->user_id = Auth::user()->id;
      $bankmail->bank_id = Auth::user()->bank->id;
      $bankmail->save();

      return redirect()->route('bank.mails');
    } else {
      return redirect()->route('bank.index');
    }
  }

  /**
   * Send refused mail
   *
   * @param  \Illuminate\Http\Request  $request
   * @param $mail
   * @return \Illuminate\Http\Response
   */
  public function refuseMail(Request $request, $mail)
  {
    $mail = Bankmail::where('id', $mail)->first();
    $mail->read = true;
    $mail->save();
    $user = User::where('id', $mail->user_id)->first();
    $bank = $user->bank;

    if ($bank->acronym === 'FW') {
      $bodyHeader = 'Bonjour ' . $user->firstname . ',';
      $bodyfooter = 'Sonia MIRINE
    Service Client FastWallet';
    } elseif ($bank->acronym === 'BUC') {
      $bodyHeader = 'Bonjour M./Mme. ' . $user->lastname . ',';
      $bodyfooter = 'Antoine WOJTEK
    Votre conseiller United Credit';
    } elseif ($bank->acronym === 'IMPERIAL') {
      $bodyHeader = 'Bonjour M./Mme. ' . $user->lastname . ',';
      $bodyfooter = 'Felix ROHATYN
    Votre agent Imperial Bank';
    }

    if (strpos($mail->title, 'assurance') !== false) {
      $bankmail = new Bankmail;
      $bankmail->category = 'reponse';
      $bankmail->title = 'RE: Demande de réclamation d\'assurance';
      $bankmail->body = $bodyHeader . '

      Nous sommes dans le regret de vous annoncer que votre réclamation auprès de notre assurance n\'a pas été retenue, pour le motif suivant:
      
      "' . $request->reason . '"


      Cordialement,

      ' . $bodyfooter;

      $bankmail->user_id = $user->id;
      $bankmail->bank_id = $bank->id;
      $bankmail->save();

      return redirect()->route('bank.administration');
    } else {
      $bankmail = new Bankmail;
      $bankmail->category = 'reponse';
      $bankmail->title = 'RE: Demande de prêt d\'entreprise';
      $bankmail->body = $bodyHeader . '

      Nous sommes dans le regret de vous annoncer que votre demande de prêt d\'entreprise n\'a pas été retenue, pour le motif suivant:
      
      "' . $request->reason . '"


      Cordialement,

      ' . $bodyfooter;

      $bankmail->user_id = $user->id;
      $bankmail->bank_id = $bank->id;
      $bankmail->save();

      return redirect()->route('bank.administration');
    }
  }

  /**
   * Send accept mail and do the corresponding action
   *
   * @param  \Illuminate\Http\Request  $request
   * @param $mail
   * @return \Illuminate\Http\Response
   */
  public function acceptMail(Request $request, $mail)
  {
    $mail = Bankmail::where('id', $mail)->first();
    $mail->read = true;
    $mail->save();
    $user = User::where('id', $mail->user_id)->first();
    $bank = $user->bank;

    if ($bank->acronym === 'FW') {
      $bodyHeader = 'Bonjour ' . $user->firstname . ',';
      $bodyfooter = 'Sonia MIRINE
    Service Client FastWallet';
    } elseif ($bank->acronym === 'BUC') {
      $bodyHeader = 'Bonjour M./Mme. ' . $user->lastname . ',';
      $bodyfooter = 'Antoine WOJTEK
    Votre conseiller United Credit';
    } elseif ($bank->acronym === 'IMPERIAL') {
      $bodyHeader = 'Bonjour M./Mme. ' . $user->lastname . ',';
      $bodyfooter = 'Felix ROHATYN
    Votre agent Imperial Bank';
    }

    if (strpos($mail->title, 'assurance') !== false) {
      $account = DB::table('unt_bs_multi_accounts')
        ->where('steamId', $user->steamid)
        ->orderBy('balance', 'asc')
        ->first();

      DB::table('unt_bs_multi_accounts')
        ->where('accountId', $account->accountId)
        ->increment('balance', $request->amount);

      DB::table('unt_bs_account_history')
        ->insert([
          [
            'accountId' => $account->accountId,
            'text' => '<color=#FF3C3C>+' . round($request->amount, 2) . '€</color>: Virement de votre assurance',
            'lastUpdated' => Carbon::now()->toDateTimeString()
          ]
        ]);

      $bankmail = new Bankmail;
      $bankmail->category = 'reponse';
      $bankmail->title = 'RE: Demande de réclamation d\'assurance';
      $bankmail->body = $bodyHeader . '

      Nous avons le plaisir de vous annoncer que votre réclamation auprès de notre assurance a été acceptée. Vous avez été remboursé de la somme de ' . number_format($request->amount, 2, ',', ' ') . '€.
      
      Voici quelques explications supplémentaires concernant l\'analyse de votre demande:

      "' . $request->reason . '"


      Cordialement,

      ' . $bodyfooter;

      $bankmail->user_id = $user->id;
      $bankmail->bank_id = $bank->id;
      $bankmail->save();

      return redirect()->route('bank.administration');
    }
  }

  /**
   * Change the user bank
   *
   * @param  $bank
   * @return \Illuminate\Http\Response
   */
  public function changeBank($bank)
  {
    $bank = Bank::where('id', $bank)->first();

    if (isset($bank)) {

      $lastChange = DB::table('unt_bs_account_history')
        ->where('accountId', 'like', '%' . Auth::user()->steamid . '%')
        ->where('text', 'like', '%Paiement des frais d\'ouverture de compte')
        ->orderBy('lastUpdated', 'desc')
        ->first();

      if (isset($lastChange)) {
        $date = Carbon::parse($lastChange->lastUpdated);
        $diff = $date->diffInDays(Carbon::now());
      } else {
        $diff = 999;
      }

      if ($diff >= Auth::user()->bank->properties['obligation']) {
        if ($bank->properties['opening_costs'] > 0) {
          $accounts = DB::table('unt_bs_multi_accounts')
            ->where('steamId', Auth::user()->steamid)
            ->orderBy('balance', 'desc')
            ->get();

          $sumBalance = 0;
          foreach ($accounts as $account) {
            $sumBalance += $account->balance;
          }

          if ($sumBalance >= $bank->properties['opening_costs']) {

            $account = DB::table('unt_bs_multi_accounts')
              ->where('steamId', Auth::user()->steamid)
              ->orderBy('balance', 'desc')
              ->first();

            DB::table('unt_bs_multi_accounts')
              ->where('accountId', $account->accountId)
              ->decrement('balance', $bank->properties['opening_costs']);

            DB::table('unt_bs_account_history')
              ->insert([
                [
                  'accountId' => $account->accountId,
                  'text' => '<color=#FF3C3C>-' . round($bank->properties['opening_costs'], 2) . '€</color>: Paiement des frais d\'ouverture de compte',
                  'lastUpdated' => Carbon::now()->toDateTimeString()
                ]
              ]);
          } else {
            return back()->withError('Vous ne possédez pas la somme nécessaire pour payer les frais d\'ouverture de compte');
          }
        }

        Auth::user()->bank()->associate($bank)->save();

        if ($bank->acronym === 'FW') {
          $bodyHeader = 'Bonjour ' . Auth::user()->character->firstname . ',';
          $bodyfooter = 'Sonia MIRINE
        Service Client FastWallet';
        } elseif ($bank->acronym === 'BUC') {
          $bodyHeader = 'Bonjour M./Mme. ' . Auth::user()->character->lastname . ',';
          $bodyfooter = 'Antoine WOJTEK
        Votre conseiller United Credit';
        } elseif ($bank->acronym === 'IMPERIAL') {
          $bodyHeader = 'Bonjour M./Mme. ' . Auth::user()->character->lastname . ',';
          $bodyfooter = 'Felix ROHATYN
        Votre agent Imperial Bank';
        }
        $bankmail = new Bankmail;
        $bankmail->category = 'bienvenue';
        $bankmail->title = 'Bienvenue chez ' . $bank->name;
        $bankmail->body = $bodyHeader . '
        
        Cordialement,
        
        ' . $bodyfooter;

        $bankmail->user_id = Auth::user()->id;
        $bankmail->bank_id = $bank->id;

        $bankmail->save();

        return redirect()->route('bank.index');
      } else {
        return back()->withError('Vous êtes encore soumis à votre engagement de ' . Auth::user()->bank->properties['obligation'] . ' jours. Vous pourrez changer à nouveau le ' . Carbon::now()->subDays($diff)->addDays(Auth::user()->bank->properties['obligation'])->format('d/m/y') . '.');
      }
    } else {
      return redirect('/');
    }
  }

  /**
   * Show the bank's administration page
   *
   * @return \Illuminate\Http\Response
   */
  public function administration()
  {
    if (Auth::user()->hasRole('admin') or Auth::user()->hasJob('banquier')) {

      $mails = Bankmail::where('category', 'demande')->orderBy('created_at', 'desc')->get();

      return view('bank.administration', compact('mails'));
    } else {
      return redirect('/');
    }
  }
}
