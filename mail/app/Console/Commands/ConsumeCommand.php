<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'consume kafka';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $conf = new \RdKafka\Conf();
        $conf->set('bootstrap.servers', 'pkc-ldvr1.asia-southeast1.gcp.confluent.cloud:9092');
        $conf->set('security.protocol', 'SASL_SSL');
        $conf->set('sasl.mechanism', 'PLAIN');
        $conf->set('sasl.username', '7JIOZKKETAA55USW');
        $conf->set('sasl.password', 'MfUNnqti9k6IB38VmUeNaHOFkEnV16OMridGbGM/raPamS6kCFWWY0k98gyQ5194');
        $conf->set('group.id', 'myGroup2');
        $conf->set('auto.offset.reset', 'earliest');

        $consumer = new \RdKafka\KafkaConsumer($conf);
        $this->info("Start..");
        while (true) {
            $consumer->subscribe(['default']);
            $message = $consumer->consume(120 * 1000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $this->info("RD_KAFKA_RESP_ERR_NO_ERROR");
                    $this->info($message->payload);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    $this->info("- No more messages; will wait for more -");
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $this->info("- Timed out -");
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }
}
