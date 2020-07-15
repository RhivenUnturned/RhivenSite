<?php

namespace App;

use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'quill', 'image'];

    public function getSlugAttribute(): string
    {
        return (new Slugify)->activateRuleSet('french')->slugify($this->title);
    }

    public function getUrlAttribute(): string
    {
        return action('PostController@single', [$this->id, $this->slug]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
