<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'age' => ['required', 'numeric', 'gte:1', 'lte:120'],
            'sex' => ['required', 'string', 'in:Homme,Femme'],
            'story' => ['required', 'string', 'max:1500']
        ]);

        $user = User::find($user->id);

        $user->name = $request->name;
        $user->email = $request->email;

        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->character->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'age' => $request->age,
            'sex' => $request->sex,
            'story' => $request->story
        ]);

        return redirect()->route('user.edit');
    }

    /**
     * Get the authentificated user's steamID
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSteamId(Request $request)
    {
        $user = Auth::user();

        $steamId = DB::table('users')
            ->select('steamid')
            ->where('id', $user->id)
            ->first();

        return response()->json([
            'steamId' => $steamId->steamid
        ]);
    }

    /**
     * Get the authentificated user's discordID
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDiscordId(Request $request)
    {
        $user = Auth::user();

        $discordId = DB::table('users')
            ->select('discordid')
            ->where('id', $user->id)
            ->first();

        return response()->json([
            'discordId' => $discordId->discordid
        ]);
    }
}
