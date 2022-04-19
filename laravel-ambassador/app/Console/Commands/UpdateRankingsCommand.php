<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Redis;
use Illuminate\Console\Command;
use App\Services\UserService;
use App\Models\Order;

class UpdateRankingsCommand extends Command
{
    protected $signature = 'update:rankings';

    public function handle()
    {
        $users = collect((new UserService)->get('users'));

        $ambassadors =  $users->filter(fn ($user) => $user['is_admin'] === 0);

        $bar = $this->output->createProgressBar($ambassadors->count());

        $bar->start();

        $ambassadors->each(function ($user) use ($bar) { //->where('complete', 1)
            $orders = Order::where('user_id', $user->id)->get();
            $revenue =  $orders->sum(fn (Order $order) => $order->ambassador_revenue);

            Redis::zadd('rankings', (int)$revenue, $user->first_name . ' ' . $user->last_name);

            $bar->advance();
        });

        $bar->finish();
    }
}
