<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bankmail extends Model
{
    protected $fillable = ['title', 'body', 'category'];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
