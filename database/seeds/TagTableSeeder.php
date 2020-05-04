<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag = new Tag;
        $tag->name = 'Serveur Survie';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'Serveur RP';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'Divers';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'Gendarmerie';
        $tag->job = 'gendarme';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'Justice';
        $tag->job = 'justice';
        $tag->save();
    }
}
