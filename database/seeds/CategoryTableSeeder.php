<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category;
        $category->name = 'Réglement Survie';
        $category->tag_id = 1;
        $category->save();

        $category = new Category;
        $category->name = 'Besoin d\'aide en survie ?';
        $category->tag_id = 1;
        $category->save();

        $category = new Category;
        $category->name = 'Présentation RP';
        $category->tag_id = 2;
        $category->save();

        $category = new Category;
        $category->name = 'Réglement RP';
        $category->tag_id = 2;
        $category->save();

        $category = new Category;
        $category->name = 'Parlez de vous';
        $category->tag_id = 3;
        $category->save();

        $category = new Category;
        $category->name = 'Photos d\'animaux';
        $category->tag_id = 3;
        $category->save();

        $category = new Category;
        $category->name = 'Demandes de saisie';
        $category->tag_id = 4;
        $category->save();

        $category = new Category;
        $category->name = 'Demandes d\'enquête';
        $category->tag_id = 5;
        $category->save();
    }
}
