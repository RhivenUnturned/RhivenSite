<?php

namespace App\Console\Commands;

use DB;
use App\Credit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BankBalanceHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bank:balancehistory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the actual user\'s balance in the balance history db table';

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
        $accounts = DB::table('unt_bs_multi_accounts')->get();

        foreach ($accounts as $account) {
            $dailyLog = DB::table('balance_history')->whereDate('lastUpdated', Carbon::today())->where('accountId', $account->accountId)->first();

            if (isset($dailyLog)) {
                DB::table('balance_history')
                ->where('accountId', $account->accountId)
                ->update(['balance' => $account->balance, 'lastUpdated' => Carbon::now()]);
            } else {
                DB::table('balance_history')
                ->insert([
                    'steamId' => $account->steamId,
                    'accountId' => $account->accountId,
                    'balance' => $account->balance,
                    'lastUpdated' => Carbon::now()
                ]);
            }
        }
    }
}
