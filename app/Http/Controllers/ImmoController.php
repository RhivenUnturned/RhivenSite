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
    public function carte()
    {
        $adresses = Adress::all();

        return view('immo.carte', compact('adresses'));
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
}
