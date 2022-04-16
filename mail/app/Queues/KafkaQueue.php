<?php

namespace App\Queues;

use Illuminate\Queue\Queue;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class KafkaQueue extends Queue implements QueueContract
{
    protected $consumer;

    public function __construct($consumer)
    {
        $this->consumer = $consumer;
    }

    public function size($queue = null)
    {
    }

    public function push($job, $data = '', $queue = null)
    {
    }

    public function pushOn($queue, $job, $data = '')
    {
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
    }

    public function laterOn($queue, $delay, $job, $data = '')
    {
    }

    public function bulk($jobs, $data = '', $queue = null)
    {
    }

    public function pop($queue = null)
    {
        $this->consumer->subscribe(['default']);
        $message = $this->consumer->consume(120 * 1000);

        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                echo "RD_KAFKA_RESP_ERR_NO_ERROR";
                // var_dump($message->payload);
                $job = unserialize($message->payload);
                $job->handle();
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                echo "- No more messages; will wait for more -";
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                echo "- Timed out -";
                break;
            default:
                throw new \Exception($message->errstr(), $message->err);
                break;
        }
    }

    // public function getConnectionName()
    // {
    // }

    // public function setConnectionName($name)
    // {
    // }
}
