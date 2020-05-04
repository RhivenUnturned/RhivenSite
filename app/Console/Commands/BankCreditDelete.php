<?php

namespace App\Console\Commands;

use App\Credit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BankCreditDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bank:creditdelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes the expired credits';

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
                if($user->pivot->created_at->addDays($credit->properties['period'])->diffInSeconds(Carbon::now(), false) > 0) {
                    $user->credits()->detach($credit->id);

                    if($credit->name === 'Consolidation' . $user->steamid) {
                        Credit::where('name', 'Consolidation' . $user->steamid)->delete();
                    }
                }
            }
        }
    }
}
