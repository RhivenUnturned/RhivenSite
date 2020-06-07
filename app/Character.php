<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'age', 'sex', 'story'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
