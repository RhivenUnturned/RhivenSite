<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Reply;
use App\Notifications\NewReplyPosted;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Topic $topic)
    {
        request()->validate([
            'body' => 'required|min:5'
        ]);

        $reply = new Reply;

        $reply->body =  request()->body;
        $reply->user_id = auth()->user()->id;
        $topic->replies()->save($reply);

        //Notif
        $topic->user->notify(new NewReplyPosted($topic, auth()->user()));

        return redirect()->route('topics.show', $topic->id);
    }

    public function storeCommentReply(Reply $reply)
    {
        request()->validate([
            'replyComment' => 'required'
        ]);

        $replyComment = new Reply;

        $replyComment->body =  request()->replyComment;
        $replyComment->user_id = auth()->user()->id;
        $reply->replies()->save($replyComment);

        return redirect()->back();
    }
}
