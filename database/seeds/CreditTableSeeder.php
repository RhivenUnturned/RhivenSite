<?php

use App\Credit;
use Illuminate\Database\Seeder;

class CreditTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credit = new Credit;
        $credit->name = 'Entry Plan';
        $credit->properties = [
            'amount' => 1000,
            'period' => 5,
            'rate' => 4.85
        ];
        $credit->bank_id = 1;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Middle Plan';
        $credit->properties = [
            'amount' => 2500,
            'period' => 8,
            'rate' => 4.32
        ];
        $credit->bank_id = 1;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Top Plan';
        $credit->properties = [
            'amount' => 5000,
            'period' => 10,
            'rate' => 4.25
        ];
        $credit->bank_id = 1;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Azur Plan';
        $credit->properties = [
            'amount' => 9000,
            'period' => 5,
            'rate' => 3.35
        ];
        $credit->bank_id = 2;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Emerald Plan';
        $credit->properties = [
            'amount' => 30000,
            'period' => 8,
            'rate' => 3.32
        ];
        $credit->bank_id = 2;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Superior Plan';
        $credit->properties = [
            'amount' => 50000,
            'period' => 10,
            'rate' => 3.25
        ];
        $credit->bank_id = 2;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Silver Plan';
        $credit->properties = [
            'amount' => 75000,
            'period' => 4,
            'rate' => 2.38
        ];
        $credit->bank_id = 3;
        $credit->save();

        $credit = new Credit;
        $credit->name = 'Gold Plan';
        $credit->properties = [
            'amount' => 500000,
            'period' => 6,
            'rate' => 2.36
        ];
        $credit->bank_id = 3;
        $credit->save();
        
        $credit = new Credit;
        $credit->name = 'Diamond Plan';
        $credit->properties = [
            'amount' => 1200000,
            'period' => 10,
            'rate' => 2.34
        ];
        $credit->bank_id = 3;
        $credit->save();
    }
}
