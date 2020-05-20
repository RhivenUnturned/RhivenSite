<?php

namespace App\Http\Controllers;

use App\Adress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImmoController extends Controller
{
    /**
     * Show the application home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adresses = Adress::all();

        return view('immo.index', compact('adresses'));
    }

    /**
     * Show the application map.
     *
     * @return \Illuminate\Http\Response
     */
    public function map()
    {
        $adresses = Adress::all();

        return view('immo.map', compact('adresses'));
    }

    /**
     * Show the page to buy an adress.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function achat($id)
    {
        $adress = Adress::where('id', $id)->first();

        return view('immo.achat', compact('adress'));
    }

    /**
     * Buy and store an adress into user account
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        if (Auth::user()) {
            if (Auth::user()->steamid) {
                $adress = Adress::where('id', $id)->first();
                if ($adress->libre === 1) {
                    $accounts = DB::table('unt_bs_multi_accounts')
                        ->where('steamId', Auth::user()->steamid)
                        ->orderBy('balance', 'desc')
                        ->get();

                    $sumBalance = 0;
                    foreach ($accounts as $account) {
                        $sumBalance += $account->balance;
                    }

                    if ($sumBalance >= $adress->prix) {

                        $account = DB::table('unt_bs_multi_accounts')
                            ->where('steamId', Auth::user()->steamid)
                            ->orderBy('balance', 'desc')
                            ->first();

                        DB::table('unt_bs_multi_accounts')
                            ->where('accountId', $account->accountId)
                            ->decrement('balance', ($adress->prix - ($adress->prix / 100  * ($adress->offre * -1))));

                        DB::table('unt_bs_account_history')
                            ->insert([
                                [
                                    'accountId' => $account->accountId,
                                    'text' => '<color=#FF3C3C>-' . round(($adress->prix - ($adress->prix / 100  * ($adress->offre * -1))), 2) . '€</color>: Achat d\'un bien immobilier',
                                    'lastUpdated' => Carbon::now()->toDateTimeString()
                                ]
                            ]);

                        $adress->owner()->associate(auth()->user())->save();
                        $adress->update(['libre' => 0]);
                    } else {
                        return back()->withError('Vous ne possédez pas la somme nécessaire pour acheter ce bien');
                    }

                    return redirect()->route('immo.achat', $id);
                } else {
                    return redirect('/');
                }
            } else {
                return redirect('/steam');
            }
        } else {
            return redirect('/login');
        }
    }
}
