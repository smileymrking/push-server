<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GatewayWorkerServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway-worker {server} {action} {--d}';

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
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function handle()
    {
        if (in_array($action = $this->argument('action'), ['status', 'start', 'stop', 'restart', 'reload', 'connections'])) {

            $server = $this->argument('server');
            $daemon = $this->option('d') ? '-d' : '';

            $class = config("gateway.{$server}.service");

            if (empty($class)) {
                $this->error("{$server}'s workerman service doesn't exist");
            } else {
                $classInfo = new \ReflectionClass($class);      //所要查询的类名
                $dir = dirname($classInfo->getFileName());

                $command = 'cd ' . $dir . " && php start.php {$action} {$daemon}";
                exec($command, $output);
                collect($output)->each(function ($info) use ($server, $classInfo) {
                    $info = str_replace('php start.php', "php artisan gateway-worker {$server}", $info);
                    $info = str_replace('start.php', $classInfo->getShortName(), $info);
                    $this->line($info);
                });
            }

        } else {
            $this->error('Error Arguments');
        }
    }
}
