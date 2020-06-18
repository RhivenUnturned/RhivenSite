<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'job'];

    /**
     * Get the topics associated with given tag.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
