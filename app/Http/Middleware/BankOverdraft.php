<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BankOverdraft
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $accounts = DB::table('unt_bs_multi_accounts')
            ->where('steamid', $user->steamid)
            ->get();

        $sumBalance = 0;
        foreach ($accounts as $account) {
            $sumBalance += $account->balance;
        }

        if (isset($user->bank->properties['overdraft'][0])) {
            if ($sumBalance < $user->bank->properties['overdraft'][0]) {
                return redirect()->route('bank.overdraft');
            } else {
                DB::table('topics')->where('title', 'Demande de saisie: ' . Auth::user()->name)->delete();
            }
        } elseif ($sumBalance < 0) {
            return redirect()->route('bank.overdraft');
        } else {
            DB::table('topics')->where('title', 'Demande de saisie: ' . Auth::user()->name)->delete();
        }

        return $next($request);
    }
}