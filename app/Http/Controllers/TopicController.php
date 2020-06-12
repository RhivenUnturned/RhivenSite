<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TopicResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;

class TopicController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::latest()->where('created_at', '<', Carbon::now())->paginate(10);

        return view('forum.list', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('forum.create', compact('category'));
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
            'title' => 'required|min:5',
            'body' => 'required|min:5',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $topic = new Topic;

        $topic->title = $request->title;
        $topic->body = $request->body;
        $topic->quill = $request->quill;
        $topic->user_id = auth()->user()->id;

        $topic->category()->associate($request->category)->save();

        $topic->save();

        return redirect()->route('topics.show', $topic->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        if (isset($topic->category()->first()->tag->job)) {
            $userJobNames = Auth::user()->jobs()->pluck('name')->all();
            if (!in_array($topic->category()->first()->tag->job, $userJobNames)) {
                return redirect()->route('forum.index');
            }
        }

        return view('forum.show', compact('topic'));
    }

    public function showFromNotification(Topic $topic, DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return view('forum.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);

        return view('forum.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $this->validate($request, [
            'title' => 'required|min:5',
            'body' => 'required|min:5',
        ]);

        $topic->update($request->only(['title', 'body']));

        return redirect()->route('topics.show', $topic->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('update', $topic);

        Topic::destroy($topic->id);

        return redirect()->route('forum.index');
    }
}
