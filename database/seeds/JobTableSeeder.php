<?php

use App\Job;
use Illuminate\Database\Seeder;

class JobTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $job = new Job;
        $job->name = 'gendarme';
        $job->description = 'Gardien de la paix';
        $job->save();

        $job = new Job;
        $job->name = 'samu';
        $job->description = 'Au service de tous';
        $job->save();

        $job = new Job;
        $job->name = 'justice';
        $job->description = 'La loi doit être connue de tous';
        $job->save();
    }
}
