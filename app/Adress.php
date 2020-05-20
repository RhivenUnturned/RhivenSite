<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adress extends Model
{
    protected $fillable = ['owner_id', 'libre'];

    public $timestamps = false;

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
