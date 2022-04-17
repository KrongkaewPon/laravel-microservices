<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Message;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class OrderCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        var_dump("sending emails");
        // var_dump($this->data);

        \Mail::send('admin', ['order' => $this->data], function (Message $message) {
            $message->subject('An Order has been completed');
            $message->to('admin@admin.com');
        });

        \Mail::send('ambassador', ['order' => $this->data], function (Message $message) {
            $message->subject('An Order has been completed');
            $message->to($this->data['ambassador_email']);
        });

        var_dump("emails sent");
    }
}
