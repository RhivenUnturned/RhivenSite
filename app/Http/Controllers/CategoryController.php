<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Topic;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Category::with('tag')->orderby('id')->paginate(15));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'tag_id' => 'required'
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->tag_id = $request->tag_id;
        $category->save();

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required',
            'tag_id' => 'required'
        ]);

        $category->update($request->only(['name', 'tag_id']));

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }

    /**
     * Display a listing of the user's allowed categories
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function list(Category $category)
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
