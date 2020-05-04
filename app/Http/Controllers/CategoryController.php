<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        if (isset($category->tag()->first()->job)) {
            $userJobNames = Auth::user()->jobs()->pluck('name')->all();
            if (!in_array($category->tag()->first()->job, $userJobNames)) {
                return redirect()->route('forum.index');
            }
        }

        $topics = $category->topics()->where('created_at', '<', Carbon::now())->orderBy('created_at', 'desc')->paginate(8);

        return view('forum.category', [
            'category' => $category,
            'topics' => $topics
        ]);
    }
}
