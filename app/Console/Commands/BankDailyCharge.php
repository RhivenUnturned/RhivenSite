<?php

namespace App\Console\Commands;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BankDailyCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bank:dailycharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the user\'s bank charges every day';

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
        $bankUsers = [];

        //Retrieve the list of cat_id's in use.
        $steamIds = DB::table('unt_bs_multi_accounts')->distinct()->select('steamId')->get();

        //for each cat_id in use, find the products associated and then add a collection of those products to the relevant array element
        foreach ($steamIds as $steamId) {
            $bankUsers[$steamId->steamId] = DB::table('unt_bs_multi_accounts')->where('steamId', $steamId->steamId)->get();
        }

        foreach ($bankUsers as $bankUser => $accounts) {
            $bankUser = strval($bankUser);
            $user = User::where('steamid', $bankUser)->first();

            if (isset($user)) {

                $withdraws = DB::table('unt_bs_account_history')
                    ->where('accountId', 'like', $bankUser . '%')
                    ->where('text', 'like', '% Retrait de %')
                    ->get();

                $sumWithdraw = 0;
                foreach ($withdraws as $withdraw) {
                    if (floatval(\Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($withdraw->text, '>'), '<')) < 0) {
                        $sumWithdraw += -1 * floatval(\Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($withdraw->text, '>'), '<'));
                    }
                }

                if ($sumWithdraw > $user->bank->properties['daily_ceiling']) {
                    $sumWithdraw += ($sumWithdraw - $user->bank->properties['daily_ceiling']) / 100 * 20;
                }

                DB::table('bank_charges')
                    ->where('steamId', $bankUser . '-retrait')
                    ->increment('amount', ($sumWithdraw / 100 * $user->bank->properties['withdraw_costs']));

                $sumBalance = 0;
                foreach ($accounts as $account) {
                    $sumBalance += $account->balance;
                }

                DB::table('bank_charges')
                    ->where('steamId', $bankUser)
                    ->increment('amount', $user->bank->properties['daily_costs']);

                if ($sumBalance < 0) {
                    DB::table('bank_charges')
                        ->where('steamId', $bankUser)
                        ->increment('amount', ($sumBalance / 100 * $user->bank->properties['overdraft'][1]) * -1);

                    if ($user->bank->acronym === 'FW') {
                        $bodyHeader = 'Bonjour ' . $user->firstname . ',';
                        $bodyfooter = 'Sonia MIRINE
                      Service Client FastWallet';
                      } elseif ($user->bank->acronym === 'BUC') {
                        $bodyHeader = 'Bonjour M./Mme. ' . $user->lastname . ',';
                        $bodyfooter = 'Antoine WOJTEK
                      Votre conseiller United Credit';
                      } elseif ($user->bank->acronym === 'IMPERIAL') {
                        $bodyHeader = 'Bonjour M./Mme. ' . $user->lastname . ',';
                        $bodyfooter = 'Felix ROHATYN
                      Votre agent Imperial Bank';
                      }
                      $bankmail = new \App\Bankmail;
                      $bankmail->category = 'bienvenue';
                      $bankmail->title = 'Irrégularités: vous serez facturé ' . number_format((($sumBalance / 100 * $user->bank->properties['overdraft'][1]) * -1), 2, ',', ' ') . '€';
                      $bankmail->body = $bodyHeader . '
                      
                      Nous avons remarqué des irrégularités sur le solde de vos compte. En outre, votre solde était négatif lors de notre dernière vérification. Nous allons donc vous facturer une somme telle que convenue dans les clauses de notre banque. Ce prélèvement automatique est prévu au début de la semaine prochaine.
                      

                      Cordialement,
                      
                      ' . $bodyfooter;
              
                      $bankmail->user_id = $user->id;
                      $bankmail->bank_id = $user->bank->id;
              
                      $bankmail->save();
                }
            }
        }
    }
}
