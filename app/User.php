<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'firstname', 'lastname', 'email', 'password', 'steamid', 'steamavatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function checkRoles($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!$this->hasAnyRole($roles)) {
            auth()->logout();
            abort(404);
        }
    }

    public function hasAnyRole($roles): bool
    {
        return (bool) $this->roles()->whereIn('name', $roles)->first();
    }

    public function hasRole($role): bool
    {
        return (bool) $this->roles()->where('name', $role)->first();
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class);
    }

    public function checkJobs($jobs)
    {
        if (!is_array($jobs)) {
            $jobs = [$jobs];
        }

        if (!$this->hasAnyRole($jobs)) {
            auth()->logout();
            abort(404);
        }
    }

    public function hasAnyJob($jobs): bool
    {
        return (bool) $this->jobs()->whereIn('name', $jobs)->first();
    }

    public function hasJob($job): bool
    {
        return (bool) $this->jobs()->where('name', $job)->first();
    }

    public function hasCredit($credit): bool
    {
        return (bool) $this->credits()->where('name', $credit)->first();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function credits()
    {
        return $this->belongsToMany(Credit::class)->withTimestamps();
    }

    /**
     * Get the bank mails sent to the user
     */
    public function bankmails()
    {
        return $this->hasMany(Bankmail::class);
    }
}
