<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (!Auth::user()) {
            return redirect('/blog');
        }

        if ($request->user()->hasRole('user')) {
            return redirect('/blog');
        }

        if ($request->user()->hasRole('admin')) {
            return view('misc.accueil', [
                'posts' => Post::latest()->take(3)->get(),
                'users' => User::count()
            ]);
        }
    }

    /**
     * Show the application new user page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome(Request $request)
    {
        return view('misc.accueil', [
            'posts' => Post::latest()->take(3)->get(),
            'users' => User::count()
        ]);
    }
}
