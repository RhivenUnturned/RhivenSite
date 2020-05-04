<?php

namespace App\Console\Commands;

use DB;
use App\Credit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BankCreditDebit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bank:creditdailypayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Withdraws the credit daily payment from bank accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $credits = Credit::get();

        foreach ($credits as $credit) {
            foreach ($credit->users as $user) {
                $account = DB::table('unt_bs_multi_accounts')
                    ->where('steamid', $user->steamid)
                    ->orderBy('balance', 'desc')
                    ->first();

                $dailyAmount = ($credit->properties['amount'] + ($credit->properties['amount'] / 100 * $credit->properties['rate'])) / $credit->properties['period'];

                DB::table('unt_bs_multi_accounts')
                    ->where('accountId', $account->accountId)
                    ->decrement('balance', $dailyAmount);

                DB::table('unt_bs_account_history')
                    ->insert([
                        [
                            'accountId' => $account->accountId,
                            'text' => '<color=#FF3C3C>-' . round($dailyAmount, 2) . '€</color>: Prélèvement automatique du crédit '. $credit->name,
                            'lastUpdated' => Carbon::now()->toDateTimeString()
                        ]
                    ]);
            }
        }
    }
}
