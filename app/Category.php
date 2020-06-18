<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'tag_id'];

    /**
     * Get the topics associated with given category.
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Get the tag associated with given category.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
