<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $userJobNames = Auth::user()->jobs()->pluck('name')->all();

            $category_by_tag = Tag::with('categories')->whereIn('job', $userJobNames)->orWhere('job', null)->orderBy('name', 'desc')->get();

            return view('forum.home', compact('category_by_tag'));
        } else {
            $category_by_tag = Tag::with('categories')->where('job', null)->orderBy('name', 'desc')->get();

            return view('forum.home', compact('category_by_tag'));
        }
    }
}
