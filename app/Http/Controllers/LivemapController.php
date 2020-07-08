<?php

namespace App\Http\Controllers;

use Storage;
use Response;
use Illuminate\Http\Request;

class LivemapController extends Controller
{
    /**
     * Store livemap data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Storage::disk('public')->put('livemap.json', $request->getContent());
    }

    /**
     * Post server data.
     *
     * @return \Illuminate\Http\Response
     */
    public function server()
    {
        $exists = Storage::disk('public')->exists('livemap.json');

        if ($exists) {
            $json = json_decode(Storage::disk('public')->get('livemap.json'), true);
            return response()->json(['Server' => $json['Server']]);
        } else {
            abort(404);
        }
    }

    /**
     * Post players data.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $exists = Storage::disk('public')->exists('livemap.json');

        if ($exists) {
            $json = json_decode(Storage::disk('public')->get('livemap.json'), true);
            return response()->json(['Server' => $json['Server'], 'Players' => $json['Players']]);
        } else {
            abort(404);
        }
    }
}
