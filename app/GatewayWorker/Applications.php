<?php


namespace App\GatewayWorker;

use App\GatewayWorker\Chat\Chat;
use App\GatewayWorker\Push\Push;

class Applications
{
    public static $applications = [
        'chat' => Chat::class,
        'push' => Push::class,
    ];
}
