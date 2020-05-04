<?php

use App\Bank;
use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = new Bank;
        $bank->name = 'FastWallet';
        $bank->acronym = 'FW';
        $bank->properties = [
            'opening_costs' => 0,
            'obligation' => null,
            'daily_costs' => null,
            'transfer_costs' => 0.6,
            'withdraw_costs' => 0.9,
            'account_ceiling' => 100000,
            'daily_ceiling' => 5000,
            'transfer_ceiling' => 10000,
            'consumer_credit' => [1000, 5000, 4.85],
            'real_estate_credit' => null,
            'loan' => null,
            'overdraft' => null,
            'insurance' => null,
            'thrift' => null
        ];
        $bank->save();

        $bank = new Bank;
        $bank->name = 'Brastal United Credit';
        $bank->acronym = 'BUC';
        $bank->properties = [
            'opening_costs' => 750,
            'obligation' => 3,
            'daily_costs' => 24,
            'transfer_costs' => 0.2,
            'withdraw_costs' => 0.3,
            'account_ceiling' => 980000,
            'daily_ceiling' => 300000,
            'transfer_ceiling' => 500000,
            'consumer_credit' => [9000, 50000, 3.35],
            'real_estate_credit' => [150000, 2],
            'loan' => [280000, 2.55],
            'overdraft' => [-2500, 5],
            'insurance' => [10000, 75],
            'thrift' => [200000, 2]
        ];
        $bank->save();

        $bank = new Bank;
        $bank->name = 'Imperial Bank';
        $bank->acronym = 'IMPERIAL';
        $bank->properties = [
            'opening_costs' => 9700,
            'obligation' => 5,
            'daily_costs' => 559,
            'transfer_costs' => 0,
            'withdraw_costs' => 0,
            'account_ceiling' => 999999999999999999999,
            'daily_ceiling' => 20000000,
            'transfer_ceiling' => 5000000,
            'consumer_credit' => [75000, 1200000, 2.38],
            'real_estate_credit' => [1500000, 1.8],
            'loan' => [3000000, 2.3],
            'overdraft' => [-10000, 13],
            'insurance' => [100000, 85],
            'thrift' => [1500000, 2.8]
        ];
        $bank->save();

        $bank = new Bank;
        $bank->name = 'Crédit Nova';
        $bank->acronym = 'NOVA';
        $bank->properties = [];
        $bank->save();
    }
}
