<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Support\Facades\DB;

class LivemapController extends Controller
{
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
