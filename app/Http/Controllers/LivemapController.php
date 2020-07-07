<?php

namespace App\Http\Controllers;

use Storage;
use Response;
use Illuminate\Http\Request;

class LivemapController extends Controller
{
    /**
     * Post livemap data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api(Request $request)
    {
        $data = $request->all();

        Storage::disk('public')->put('livemap.json', $data);
    }

    /**
     * Send to livemap current server data.
     *
     * @return \Illuminate\Http\Response
     */
    public function server()
    {
        $serverData = DB::table('unt_livemap_server')
            ->first();

        return response()->json(['Server' => $serverData]);
    }

    /**
     * Send updated livemap data.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {

        $playerData = DB::table('unt_livemap_data')
            ->get();

        $chatData = DB::table('unt_livemap_chat')
            ->get();

        return response()->json(['Players' => $playerData, 'Chat' => $chatData]);
    }
}
