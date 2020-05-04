<?php

namespace App\Http\Controllers;

use App\User;
use App\Credit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BankGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('App\Http\Middleware\SteamId');

        $this->middleware('App\Http\Middleware\BankOverdraft');
    }

    /**
     * Show a list of all of the user's group bank accounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $accountsIds = DB::table('unt_bs_group_info')
            ->where('steamId', $user->steamid)
            ->pluck('accountId')
            ->all();

        $accounts = DB::table('unt_bs_group_accounts')
            ->whereIn('accountId', $accountsIds)
            ->orderBy('balance', 'desc')
            ->get();

        $accountsHistory = DB::table('unt_bs_account_history')
            ->whereIn('accountId', $accounts->pluck('accountId'))
            ->where('lastUpdated', '>=', Carbon::today()->subWeek())
            ->orderBy('lastUpdated', 'desc')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->lastUpdated)->format('d/m/y');
            });

        $balanceHistory = DB::table('balance_history')
            ->whereIn('accountId', $accounts->pluck('accountId'))
            ->where('lastUpdated', '>=', Carbon::today()->subWeek())
            ->orderBy('lastUpdated', 'desc')
            ->get()
            ->groupBy(function ($val) {
                return $val->balance;
            });

        return view('bank.group.index', compact('accounts', 'accountsHistory', 'balanceHistory'));
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

        $accounts = DB::table('unt_bs_group_accounts')
            ->where('ownerId', $user->steamid)
            ->get();

        // 5 = limite de comptes (0, 1, 2, 3, 4)
        if (count($accounts) < 5) {

            $randId = '';
            for ($i = 0; $i < 14; $i++) {
                $randId .= mt_rand(0, 9);
            }

            while (DB::table('unt_bs_group_accounts')->where('accountId', $randId)->exists()) {
                $randId = '';
                for ($i = 0; $i < 14; $i++) {
                    $randId .= mt_rand(0, 9);
                }
            }

            DB::table('unt_bs_group_accounts')->insert([
                'accountId' => $randId,
                'accountName' => $request->name,
                'balance' => 0.00,
                'ownerId' => $user->steamid,
                'lastUpdated' => Carbon::now()->toDateTimeString()
            ]);

            DB::table('unt_bs_group_info')->insert([
                'accountId' => $randId,
                'steamId' => $user->steamid,
                'isAdmin' => 1,
                'lastUpdated' => Carbon::now()->toDateTimeString()
            ]);

            return redirect()->route('bank.groupIndex');
        } else {
            return back()->withError('Vous possédez déjà la limite de comptes.');
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
        $user = Auth::user();

        $accountsIds = DB::table('unt_bs_group_info')
            ->where('steamId', $user->steamid)
            ->pluck('accountId')
            ->all();

        $accounts = DB::table('unt_bs_group_accounts')
            ->whereIn('accountId', $accountsIds)
            ->orderBy('balance', 'desc')
            ->get();

        $activeAccount = DB::table('unt_bs_group_accounts')
            ->where('accountId', $id)
            ->first();
        $accountOwner = $activeAccount->ownerId;

        $accountMembers = DB::table('unt_bs_group_info')
            ->where('accountId', $activeAccount->accountId)
            ->get();

        $accountUsers = User::whereIn('steamId', $accountMembers->pluck('steamId'))
            ->orderBy('name', 'asc')
            ->get();

        if ($accountMembers->contains('steamId', $user->steamid)) {

            $accountHistory = DB::table('unt_bs_account_history')
                ->where('accountId', $id)
                ->where('lastUpdated', '>=', Carbon::today()->subWeek())
                ->orderBy('lastUpdated', 'desc')
                ->get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->lastUpdated)->format('d/m/y');
                });

            $balanceHistory = DB::table('balance_history')
                ->where('accountId', $id)
                ->orderBy('lastUpdated', 'desc')
                ->limit(7)
                ->get()
                ->groupBy(function ($val) {
                    return $val->balance;
                });


            return view('bank.group.show', compact('accounts', 'activeAccount', 'accountOwner', 'accountMembers', 'accountHistory', 'balanceHistory', 'accountUsers'));
        } else {
            return redirect('/');
        }
    }

    /**
     * Update the specified group account in storage.
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

        $account = DB::table('unt_bs_group_accounts')
            ->where('accountId', $id)
            ->first();
        $accountOwner = $account->ownerId;

        if (auth()->user()->steamid === $accountOwner) {

            DB::table('unt_bs_group_accounts')
                ->where('accountId', $id)
                ->update(['accountName' => $request->name]);

            return redirect()->route('bank.groupShow', $id);
        } else {
            return redirect('/');
        }
    }

    /**
     * Remove the specified group account from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = DB::table('unt_bs_group_accounts')
            ->where('accountId', $id)
            ->first();

        $accountMembers = DB::table('unt_bs_group_info')
            ->where('accountId', $id)
            ->get();

        if ($account->balance != 0.00) {
            return back()->withError("Votre compte n'est pas vide, veuillez retirer tout l'argent qui s'y trouve, ou rembourser tous vos crédits.");
        }

        $accountOwner = $account->ownerId;
        if (auth()->user()->steamid === $accountOwner) {
            DB::table('unt_bs_group_accounts')->where('accountId', $id)->delete();
            DB::table('unt_bs_account_history')->where('accountId', $id)->delete();
            DB::table('unt_bs_group_info')->where('accountId', $id)->delete();
            DB::table('balance_history')->where('accountId', $id)->delete();

            return redirect()->route('bank.groupIndex');
        } else {
            return redirect('/');
        }
    }

    /**
     * Remove the user from the specified group account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function quit($id)
    {
        $account = DB::table('unt_bs_group_accounts')
            ->where('accountId', $id)
            ->first();

        $accountMembers = DB::table('unt_bs_group_info')
            ->where('accountId', $id)
            ->get();

        if (count($accountMembers) <= 1) {
            return back()->withError("Vous êtes le dernier membre de ce compte, si vous ne souhaitez plus en faire partie, veuillez le supprimer.");
        }

        if ($accountMembers->contains('steamId', auth()->user()->steamid)) {
            $accountAdmins = DB::table('unt_bs_group_info')
                ->where('accountId', $id)
                ->where('isAdmin', 1)
                ->get();

            if ($accountAdmins->contains('steamId', auth()->user()->steamid)) {
                if (count($accountAdmins) <= 1) {
                    return back()->withError("Vous êtes le dernier administrateur de ce compte, si vous ne souhaitez plus en faire partie, veuillez en nommer un nouveau avant de le quitter.");
                } else {
                    DB::table('unt_bs_group_info')->where('accountId', $id)->where('steamId', auth()->user()->steamid)->delete();
                }
            } else {
                DB::table('unt_bs_group_info')->where('accountId', $id)->where('steamId', auth()->user()->steamid)->delete();
            }

            return redirect()->route('bank.groupIndex');
        } else {
            return redirect('/');
        }
    }

    /**
     * Add a user to the specified group account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $id)
    {
        $this->validate($request, [
            'userToAdd' => 'required'
        ]);

        $account = DB::table('unt_bs_group_accounts')
            ->where('accountId', $id)
            ->first();

        $accountMembers = DB::table('unt_bs_group_info')
            ->where('accountId', $id)
            ->get();

        if (!$accountMembers->contains('steamId', $request->userToAdd)) {
            $accountAdmins = DB::table('unt_bs_group_info')
                ->where('accountId', $id)
                ->where('isAdmin', 1)
                ->get();

            if ($accountAdmins->contains('steamId', auth()->user()->steamid)) {
                DB::table('unt_bs_group_info')->insert([
                    'accountId' => $id,
                    'steamId' => $request->userToAdd,
                    'isAdmin' => 0,
                    'lastUpdated' => Carbon::now()->toDateTimeString()
                ]);
            } else {
                return redirect()->route('bank.groupIndex');
            }

            return redirect()->route('bank.groupShow', $id);
        } else {
            return redirect()->route('bank.groupShow', $id);
        }
    }

    /**
     * Add a user to the specified group account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request, $id)
    {
        $this->validate($request, [
            'accountUsers' => 'required',
            'manageOption' => 'required'
        ]);

        $accountUsers = $request->accountUsers;

        if ($request->manageOption === 'remove') {
            $accountMembers = DB::table('unt_bs_group_info')
                ->where('accountId', $id)
                ->get();

            if (count($accountUsers) < count($accountMembers)) {
                foreach ($accountUsers as $accountUser) {
                    $user = DB::table('users')->where('email', $accountUser)->first();
                    DB::table('unt_bs_group_info')->where('accountId', $id)->where('steamId', $user->steamid)->delete();
                }
                return redirect()->route('bank.groupShow', $id);
            } else {
                return back()->withError("Vous ne pouvez pas retirer tous les utilisateurs du compte.");
            }
        } elseif ($request->manageOption === 'admin') {
            foreach ($accountUsers as $accountUser) {
                $user = DB::table('users')->where('email', $accountUser)->first();
                DB::table('unt_bs_group_info')->where('accountId', $id)->where('steamId', $user->steamid)->update(['isAdmin' => 1]);
            }
            return redirect()->route('bank.groupShow', $id);
        } else {
            return back()->withError("Veuillez choisir une action à effectuer avec les utilisateurs sélectionnés.");
        }
    }

    /**
     * Get the users corresponding to current request (autocomplete).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $accounts = DB::table('users')
                ->whereNotNull('steamid')
                ->orderby('name', 'asc')
                ->select('name', 'steamid')
                ->limit(5)
                ->get();
        } else {
            $accounts = DB::table('users')
                ->whereNotNull('steamid')
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('steamid', 'like', '%' . $search . '%')
                ->orderby('name', 'asc')
                ->select('name', 'steamid')
                ->limit(5)
                ->get();
        }

        $response = array();
        foreach ($accounts as $account) {
            $response[] = array(
                "value" => $account->steamid,
                "label" => $account->name
            );
        }

        echo json_encode($response);
        exit;
    }
}
