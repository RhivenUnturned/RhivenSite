<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Get the topics associated with given tag.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
