<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Invisnik\LaravelSteamAuth\SteamAuth;

class SteamController extends Controller
{
    // https://github.com/invisnik/laravel-steam-auth

    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/';

    /**
     * AuthController constructor.
     * 
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam()
    {
        $user = Auth::user();

        if (!is_null($user)) {
            $userid = $user->steamid;

            if (is_null($userid)) {
                return $this->steam->redirect();
            }
            return redirect('/');
        }
        return redirect('/');
    }

    /**
     * Get user info and log in
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $uniqueSteamID = DB::table('users')
                    ->where('steamid', $info->steamid)
                    ->get();

                if (!is_null($uniqueSteamID)) {
                    return redirect('/');
                }

                $user = $this->findOrNewUser($info);

                Auth::login($user, true);

                return redirect($this->redirectURL); // redirect to site
            }
        }
        return $this->redirectToSteam();
    }

    /**
     * Getting user by info or created if not exists
     *
     * @param $info
     * @return User
     */
    protected function findOrNewUser($info)
    {
        $user = Auth::user();

        if (is_null($user)) {
            return redirect('/');
        }

        $userid = $user->steamid;

        if (!is_null($userid)) {
            return redirect('/');
        }

        $user->steamid = $info->steamID64;
        $user->steamavatar = $info->avatarfull;
        $user->save();

        DB::table('bank_charges')->insert([
            [
                'steamId' => $info->steamID64,
                'amount' => 0
            ],
            [
                'steamId' => $info->steamID64 . '-retrait',
                'amount' => 0
            ]
        ]);

        $newAccountId = $user->steamid . '-00';

        DB::table('unt_bs_multi_accounts')->insert(
            [
                'steamId' => $user->steamid,
                'accountId' => $newAccountId,
                'accountName' => 'Nouveau compte',
                'balance' => 1500.00,
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

        return $user;
    }
}
