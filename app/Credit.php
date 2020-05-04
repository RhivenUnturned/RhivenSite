<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $casts = [
        'properties' => 'array'
    ];

    public $timestamps = false;

    protected $name = [
        'FastWallet' => 'FW',
        'Brastal United Credit' => 'BUC',
        'Imperial Bank' => 'IMPERIAL'
    ];

    public function abbreviation()
    {
        return $this->name[
            $this->tinyName
        ];
    }

    /**
     * Get the bank for that credit.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
