<?php

use App\User;
use App\Character;
use Illuminate\Database\Seeder;

class CharacterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $character = new Character;
        $character->firstname = 'Samuel';
        $character->lastname = 'Jackson';
        $character->age = 22;
        $character->sex = 'Femme';
        $character->story = 'Une femme avec un passé sombre.
        
        Rien d\'autre à dire';
        $character->user()->associate(User::where('id', '1')->first());
        $character->save();

        $character = new Character;
        $character->firstname = 'Kurpatov';
        $character->lastname = 'Yermolayevitch';
        $character->age = 35;
        $character->sex = 'Homme';
        $character->story = 'Un homme avec un passé sombre.
        
        Rien d\'autre à dire';
        $character->user()->associate(User::where('id', '2')->first());
        $character->save();
    }
}
