<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BankWeeklyCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bank:weeklycharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pay the user\'s bank charges every week';

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
        //Create an array
        $users = [];

        //Retrieve the list of cat_id's in use.
        $steamIds = DB::table('unt_bs_multi_accounts')->distinct()->select('steamId')->get();

        //for each cat_id in use, find the products associated and then add a collection of those products to the relevant array element
        foreach ($steamIds as $steamId) {
            $users[$steamId->steamId] = DB::table('unt_bs_multi_accounts')->where('steamId', $steamId->steamId)->orderBy('balance', 'desc')->first();
        }

        foreach ($users as $bankUser => $account) {
            $bankUser = strval($bankUser);
            $user = DB::table('users')->where('steamid', $bankUser)->get();

            $charges = DB::table('bank_charges')->where('steamId', $bankUser)->pluck('amount')->first();

            DB::table('unt_bs_multi_accounts')
                ->where('accountId', $account->accountId)
                ->decrement('balance', floatval($charges));

            DB::table('bank_charges')
                ->where('steamId', $bankUser)
                ->decrement('amount', floatval($charges));

            DB::table('unt_bs_account_history')
                ->insert([
                    [
                        'accountId' => $account->accountId,
                        'text' => '<color=#FF3C3C>-' . round(floatval($charges), 2) . '€</color>: Prélèvement automatique des frais de banque',
                        'lastUpdated' => Carbon::now()->toDateTimeString()
                    ]
                ]);
        }
    }
}
