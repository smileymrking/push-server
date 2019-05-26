<?php


namespace App\GatewayWorker;

use App\GatewayWorker\Chat\Chat;

class Applications
{
    private $applications = [
        Chat::class,
    ];

    public function run()
    {
        collect($this->applications)->each(function ($class){
            (new $class)->start();
        });
    }
}