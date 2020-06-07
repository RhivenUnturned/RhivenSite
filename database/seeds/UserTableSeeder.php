<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Job;
use App\User;
use App\Role;
use App\Bank;
use App\Credit;

class UserTableSeeder extends Seeder
{

    public function run()
    {
        $user = new User;
        $user->name = 'Samuel Jackson';
        $user->email = 'samueljackson@jackson.com';
        $user->password = Hash::make('test');
        $user->bank()->associate(Bank::where('name', 'FastWallet')->first());
        $user->save();
        $user->roles()->attach(Role::where('name', 'user')->first());
        $user->jobs()->attach(Job::where('name', 'gendarme')->first());

        $admin = new User;
        $admin->name = 'Ceans';
        $admin->email = 'ebriantais@orange.fr';
        $admin->password = Hash::make('test');
        $admin->steamid = '76561198143359760';
        $admin->steamavatar = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/cd/cd04219bf199a7d70f083214c32da8f0dfbe53af_full.jpg';
        $admin->bank()->associate(Bank::where('name', 'Imperial Bank')->first());
        $admin->save();
        $admin->roles()->attach(Role::where('name', 'admin')->first());
    }
}
