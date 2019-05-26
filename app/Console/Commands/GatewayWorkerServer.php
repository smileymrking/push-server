<?php

namespace App\Console\Commands;

use App\GatewayWorker\Applications;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class GatewayWorkerServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway-worker:server {action} {--daemon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a GatewayWorker Server.';

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
        global $argv;

        if (in_array($action = $this->argument('action'), ['status', 'start', 'stop', 'restart', 'reload', 'connections'])) {

            $argv[0] = 'gateway-worker:server';
            $argv[1] = $action;
            $argv[2] = $this->option('daemon') ? '-d' : '';

            $applications = new Applications();
            $applications->run();

            Worker::runAll();

        }

        $this->error('Error Arguments');
    }
}
