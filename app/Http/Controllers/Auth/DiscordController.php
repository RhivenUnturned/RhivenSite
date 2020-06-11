<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class DiscordController extends Controller
{
    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToDiscord()
    {
        // https://discord.com/api/oauth2/authorize?client_id=720555005790126112&redirect_uri=http%3A%2F%2Frhiven.localhost%2Fdiscord%2Fhandle&response_type=code&scope=identify

        $user = Auth::user();

        if (!is_null($user)) {
            $userid = $user->discordid;

            if (is_null($userid)) {
                // Rediriger vers page de connexion Discord

                return redirect('https://discord.com/api/oauth2/authorize?client_id=720555005790126112&redirect_uri=' . rawurlencode(\URL::to('/discord/handle')) . '&response_type=code&scope=identify');
            }
            return redirect('/');
        }
        return redirect('/');
    }

    /**
     * Get user code and make request for access token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle(Request $request)
    {
        $code = $request->query()['code'];

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://discord.com',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $response = $client->request('POST', '/api/oauth2/token', [
            'form_params' => [
                'client_id' => '720555005790126112',
                'client_secret' => 'RItyTZOMZtyUyPBCXnRENPriT6Ej9FRg',
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => \URL::to('/discord/handle'),
                'scope' => 'identify email connections'
            ]
        ]);

        $access_token = json_decode($response->getBody()->getContents())->access_token;

        $identityData = $client->request('GET', '/api/users/@me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
            ]
        ]);

        $discordID = json_decode($identityData->getBody()->getContents())->id;

        if (!is_null($discordID)) {
            $uniqueDiscordID = DB::table('users')
                ->where('discordid', $discordID)
                ->get();

            if (!$uniqueDiscordID->isEmpty()) {
                return redirect('/');
            }

            $user = $this->findOrNewUser($discordID);

            Auth::login($user, true); // redirect to site
        }
    }

    /**
     * Getting user by info or created if not exists
     *
     * @param $info
     * @return User
     */
    protected function findOrNewUser($discordID)
    {
        $user = Auth::user();

        if (is_null($user)) {
            return redirect('/');
        }

        $userid = $user->discordid;

        if (!is_null($userid)) {
            return redirect('/');
        }

        $user->discordid = $discordID;
        $user->save();

        return $user;
    }
}
