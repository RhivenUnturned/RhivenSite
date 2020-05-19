<?php

namespace App\Http\Controllers;

use App\Adress;
use Illuminate\Http\Request;

class ImmoController extends Controller
{
    /**
     * Show the application home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adresses = Adress::all();

        return view('immo.index', compact('adresses'));
    }
}
