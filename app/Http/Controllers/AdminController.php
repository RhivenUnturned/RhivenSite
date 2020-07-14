<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (request()->user()->hasRole('admin')) {
            $userCount = User::count();
            $topicCount = Topic::count();
            $postCount = Post::count();

            $balancehistory = DB::table('balance_history')
                ->where('lastUpdated', '>=', Carbon::today()->subWeek())
                ->orderBy('lastUpdated', 'asc')
                ->get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->lastUpdated)->format('d/m/y');
                });
            $moneyCount = collect([]);
            foreach ($balancehistory as $key => $date) {
                $total = 0;
                foreach ($date as $account) {
                    $total += $account->balance;
                }
                $moneyCount->put($key, $total);
            }

            $collection = collect(['userCount' => $userCount, 'topicCount' => $topicCount, 'postCount' => $postCount, 'moneyCount' => $moneyCount]);
            
            return view('admin.dashboard', compact('collection'));
        } else {
            return redirect('/home');
        }
    }
}
