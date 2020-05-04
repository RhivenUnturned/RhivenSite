<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
