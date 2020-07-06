<?php

namespace App\Http\Controllers;

use App\Post;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::latest()->paginate(5));
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
            'title' => 'required',
            'body' => 'required',
            'quill' => 'required',
            'user_id' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $post = new Post;

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.imgbb.com/1/upload',
            // You can set any number of default request options.
            'timeout'  => 15.0,
        ]);

        $response = $client->request('POST', '?key=1fe8de127c43d082b9d15a4e7a977f1c', [
            'form_params' => [
                'image' => base64_encode(file_get_contents($request->file('image')))
            ]
        ]);

        $image_url = json_decode($response->getBody()->getContents())->data->url;
        $post->user_id = $request->user_id;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->quill = $request->quill;
        $post->image = $image_url;
        $post->save();

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'quill' => 'required'
        ]);

        $post->update($request->only(['title', 'body', 'quill']));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }

    public function all()
    {
        return view('landing', [
            'posts' => Post::latest()->paginate(5)
        ]);
    }

    public function single(Post $post)
    {
        return view('single', compact('post'));
    }
}
