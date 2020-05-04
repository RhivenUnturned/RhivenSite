<?php 

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleTableSeeder::class,
            JobTableSeeder::class,
            BankTableSeeder::class,
            CreditTableSeeder::class,
            PostTableSeeder::class,
            TagTableSeeder::class,
            CategoryTableSeeder::class,
            UserTableSeeder::class,
        ]);
    }
}