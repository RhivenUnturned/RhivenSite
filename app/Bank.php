<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $casts = [
        'properties' => 'array'
    ];

    public $timestamps = false;

    /**
     * Get the users for the bank.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the credits for the bank.
     */
    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * Get the mails sent by the bank.
     */
    public function bankmails()
    {
        return $this->hasMany(Bankmail::class);
    }
}
