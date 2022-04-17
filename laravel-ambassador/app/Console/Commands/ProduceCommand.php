<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Jobs\ProduceJob;
use App\Jobs\OrderCompletedJob;

class ProduceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'produce';

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
     * @return int
     */
    public function handle()
    {
        // ProduceJob::dispatch();

        $order = Order::find(1);

        $array = $order->toArray();
        $array['ambassador_revenue'] = $order->ambassador_revenue;

        OrderCompletedJob::dispatch($array)->onQueue('email_topic');
    }
}
