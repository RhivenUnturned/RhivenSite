<?php

namespace App\Http\Controllers;

use App\User;
use App\Credit;
use App\Bankmail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('App\Http\Middleware\SteamId');

        $this->middleware('App\Http\Middleware\BankOverdraft', ['except' => ['add']]);
    }

    /**
     * Show the bank page to get credits.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credits = Auth::user()->credits()->orderBy('properties->amount', 'desc')->get();

        $accounts = DB::table('unt_bs_multi_accounts')
            ->where('steamId', Auth::user()->steamid)
            ->get();

        $balancehistory = DB::table('balance_history')
            ->where('steamId', Auth::user()->steamid)
            ->where('lastUpdated', '>=', Carbon::today()->subWeek())
            ->orderBy('lastUpdated', 'desc')
            ->get()
            ->groupBy(function ($val) {
                return $val->balance;
            });

        return view('bank.credits', compact('credits', 'accounts', 'balancehistory'));
    }

    /**
     * Adds a bank credit to the user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        if ($request->name === 'conso5' || $request->name === 'conso10') {
            if (Auth::user()->hasCredit('Consolidation' . Auth::user()->steamid)) {
                Auth::user()->credits()->detach(Credit::where('name', 'Consolidation' . Auth::user()->steamid)->first());
                Credit::where('name', 'Consolidation' . Auth::user()->steamid)->delete();
            }
            $accounts = DB::table('unt_bs_multi_accounts')
                ->where('steamId', Auth::user()->steamid)
                ->get();

            foreach ($accounts as $account) {
                DB::table('unt_bs_multi_accounts')
                    ->where('accountId', $account->accountId)
                    ->update(['balance' => 0]);
            }

            $eligibleAccount = DB::table('unt_bs_multi_accounts')
                ->where('steamId', Auth::user()->steamid)
                ->orderBy('accountName', 'desc')
                ->first();

            DB::table('unt_bs_multi_accounts')
                ->where('accountId', $eligibleAccount->accountId)
                ->increment('balance', $request->debt);

            DB::table('unt_bs_account_history')
                ->insert([
                    [
                        'accountId' => $eligibleAccount->accountId,
                        'text' => '<color=#FF3C3C>+' . round($request->debt, 2) . '€</color>: Virement du crédit Consolidation NOVA',
                        'lastUpdated' => Carbon::now()->toDateTimeString()
                    ]
                ]);

            if ($request->name === 'conso5') {
                $credit = new Credit;
                $credit->name = 'Consolidation' . Auth::user()->steamid;
                $credit->properties = [
                    'amount' => $request->debt,
                    'period' => 5,
                    'rate' => 4.12
                ];
                $credit->bank_id = 4;
                $credit->save();
            } else {
                $credit = new Credit;
                $credit->name = 'Consolidation' . Auth::user()->steamid;
                $credit->properties = [
                    'amount' => $request->debt,
                    'period' => 10,
                    'rate' => 4.23
                ];
                $credit->bank_id = 4;
                $credit->save();
            }

            Auth::user()->credits()->attach(Credit::where('name', 'Consolidation' . Auth::user()->steamid)->first());

            $bankmail = new Bankmail;
            $bankmail->category = 'consolidation';
            $bankmail->title = 'Consolidation de dette accordée';
            $bankmail->body = 'Bonjour M./Mme. ' . Auth::user()->lastname . ',
            
            Nous avons examiné votre dossier.
            Vous êtes bien éligible à notre offre de Consolidation de dette, et le solde du prêt à été crédité sur votre compte.
            
            Vous serez facturé du premier versement dès demain.
            Nous vous remercions pour votre confiance, et nous nous tenons à votre service si vous avez besoin de renseignements.
            
            
            Cordialement,
            Lucious NEXUM, votre référent NOVA Crédit';

            $bankmail->user_id = Auth::user()->id;
            $bankmail->bank_id = 4;

            $bankmail->save();

            return redirect()->route('bank.credits');
        } elseif ($request->name === 'entreprise') {

            $mail = Bankmail::where('id', $request->mail)->first();
            $mail->read = true;
            $mail->save();
            $user = User::where('id', $mail->user_id)->first();
            $bank = $user->bank;

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
                        'text' => '<color=#FF3C3C>+' . round($request->amount, 2) . '€</color>: Virement du crédit Entreprise',
                        'lastUpdated' => Carbon::now()->toDateTimeString()
                    ]
                ]);

            $credit = new Credit;
            $credit->name = 'Entreprise' . $user->steamid;
            $credit->properties = [
                'amount' => $request->amount,
                'period' => $request->period,
                'rate' => $request->rate,
            ];
            $credit->bank_id = $bank->id;
            $credit->save();

            $user->credits()->attach(Credit::where('name', 'Entreprise' . $user->steamid)->first());

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

            $bankmail = new Bankmail;
            $bankmail->category = 'reponse';
            $bankmail->title = 'Crédit d\'entreprise accordé';
            $bankmail->body = $bodyHeader . '

            Nous avons examiné votre dossier.
            Vous êtes bien éligible à un crédit pour votre entreprise. Le montant qui vous a été accordé a été versé sur un de vos comptes.
            
            Vous serez facturé du premier versement dès demain.
            Nous vous remercions pour votre confiance, et nous nous tenons à votre service si vous avez besoin de renseignements.


            Cordialement,

            ' . $bodyfooter;
            $bankmail->user_id = $user->id;
            $bankmail->bank_id = $bank->id;
            $bankmail->save();

            return redirect()->route('bank.administration');
        } else {
            $credit = DB::table('credits')
                ->where('name', $request->name)
                ->first();

            $amount = json_decode($credit->properties)->amount;

            $eligibleAmountRemaining = floatval(Auth::user()->bank->properties['account_ceiling'] - $amount);

            $eligibleAccount = DB::table('unt_bs_multi_accounts')
                ->where('steamId', Auth::user()->steamid)
                ->where('balance', '<=', $eligibleAmountRemaining)
                ->orderBy('balance', 'desc')
                ->first();

            if (Auth::user()->hasCredit($credit->name)) {
                return back()->withError('Vous possédez déjà ce crédit.');
            } else {
                DB::table('unt_bs_multi_accounts')
                    ->where('accountId', $eligibleAccount->accountId)
                    ->increment('balance', $amount);

                DB::table('unt_bs_account_history')
                    ->insert([
                        [
                            'accountId' => $eligibleAccount->accountId,
                            'text' => '<color=#FF3C3C>+' . round($amount, 2) . '€</color>: Virement du crédit ' . $credit->name,
                            'lastUpdated' => Carbon::now()->toDateTimeString()
                        ]
                    ]);

                Auth::user()->credits()->attach(Credit::where('name', $credit->name)->first());

                $creditbank = Credit::where('name', $credit->name)->first()->bank;

                if ($creditbank->acronym === 'FW') {
                    $bodyHeader = 'Bonjour ' . Auth::user()->firstname . ',';
                    $bodyfooter = 'Sonia MIRINE
                    Service Client FastWallet';
                } elseif ($creditbank->acronym === 'BUC') {
                    $bodyHeader = 'Bonjour M./Mme. ' . Auth::user()->lastname . ',';
                    $bodyfooter = 'Antoine WOJTEK
                    Votre conseiller United Credit';
                } elseif ($creditbank->acronym === 'IMPERIAL') {
                    $bodyHeader = 'Bonjour M./Mme. ' . Auth::user()->lastname . ',';
                    $bodyfooter = 'Felix ROHATYN
                    Votre agent Imperial Bank';
                }

                $bankmail = new Bankmail;
                $bankmail->category = 'consommation';
                $bankmail->title = 'Crédit consommation accordé';
                $bankmail->body = $bodyHeader . '
                
                Nous avons examiné votre dossier.
                Vous êtes bien éligible à notre offre ' . $credit->name . ', et le solde du prêt à été crédité sur votre compte.
                
                Vous serez facturé du premier versement dès demain.
                Nous vous remercions pour votre confiance, et nous tenons à votre service si vous besoin de renseignements.
                
                ' . $bodyfooter;

                $bankmail->user_id = Auth::user()->id;
                $bankmail->bank_id = $creditbank->id;

                $bankmail->save();

                return redirect()->route('bank.credits');
            }
        }
    }

    /**
     * Immediately reimburse a credit in its totality.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        $credits = Credit::with('users')
            ->where('name', $request->name)
            ->get();

        $credit = $credits->first();

        $pivot = DB::table('credit_user')
            ->where('credit_id', $credit->id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (Auth::user()->hasCredit($credit->name)) {
            $amount = $credit->properties['amount'];
            $period = $credit->properties['period'];
            $rate = $credit->properties['rate'];

            $amountToPay = $amount / $period * Carbon::parse($pivot->created_at)->addDays($period)->add(1, 'day')->diffInDays(Carbon::now()) - ($amount / $period) + ($amount / $period + ($amount / $period / 100 * $rate));

            $eligibleAccount = DB::table('unt_bs_multi_accounts')
                ->where('steamId', Auth::user()->steamid)
                ->where('balance', '>=', $amountToPay)
                ->orderBy('balance', 'desc')
                ->first();

            if ($eligibleAccount) {
                DB::table('unt_bs_multi_accounts')
                    ->where('accountId', $eligibleAccount->accountId)
                    ->decrement('balance', $amountToPay);

                DB::table('unt_bs_account_history')
                    ->insert([
                        [
                            'accountId' => $eligibleAccount->accountId,
                            'text' => '<color=#FF3C3C>-' . round($amountToPay, 2) . '€</color>: Remboursement manuel du crédit ' . $credit->name,
                            'lastUpdated' => Carbon::now()->toDateTimeString()
                        ]
                    ]);

                Auth::user()->credits()->detach(Credit::where('name', $credit->name)->first());

                if (Auth::user()->bank->acronym === 'FW') {
                    $bodyHeader = 'Bonjour ' . Auth::user()->firstname . ',';
                    $bodyfooter = 'Sonia MIRINE
                  Service Client FastWallet';
                } elseif (Auth::user()->bank->acronym === 'BUC') {
                    $bodyHeader = 'Bonjour M./Mme. ' . Auth::user()->lastname . ',';
                    $bodyfooter = 'Antoine WOJTEK
                  Votre conseiller United Credit';
                } elseif (Auth::user()->bank->acronym === 'IMPERIAL') {
                    $bodyHeader = 'Bonjour M./Mme. ' . Auth::user()->lastname . ',';
                    $bodyfooter = 'Felix ROHATYN
                  Votre agent Imperial Bank';
                }

                $bankmail = new Bankmail;
                $bankmail->category = 'remboursement';
                $bankmail->title = 'Votre crédit a été remboursé avec succès';
                $bankmail->body = $bodyHeader . '
                  
                  Nous avons bien pris en compte votre remboursement manuel du crédit ' . $credit->name . '. Nous vous remercions pour votre confiance.
                  

                  Cordialement,
                  
                  ' . $bodyfooter;

                $bankmail->user_id = Auth::user()->id;
                $bankmail->bank_id = $credit->bank->id;

                $bankmail->save();

                if ($credit->name === 'Consolidation' . Auth::user()->steamid) {
                    Credit::where('name', 'Consolidation' . Auth::user()->steamid)->delete();
                }

                return redirect()->route('bank.credits');
            } else {
                return back()->withError('Vous ne possédez pas assez d\'argent pour rembourser ce crédit.');
            }
        } else {
            return back()->withError('Vous ne possédez pas ce crédit.');
        }
    }
}
