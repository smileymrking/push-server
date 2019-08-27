<?php
/**
 * Created by PhpStorm.
 * User: King
 * Date: 2019/8/26
 * Time: 14:30
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Gateway Worker Server
    |--------------------------------------------------------------------------
    */

    'push_server_url' => env('PUSH_SERVER_URL',''),

    'push' => [
        'service' => \App\GatewayWorker\Push\Push::class,
        'gateway' => env('PUSH_GATEWAY', true),
        'business_worker' => env('PUSH_BUSINESS_WORKER', true),
        'register' => env('PUSH_REGISTER', true),
        'lan_ip' => env('PUSH_LAN_IP', '127.0.0.1'),
    ],

];
