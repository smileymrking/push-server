<?php
/**
 * Created by PhpStorm.
 * User: King
 * Date: 2019/8/23
 * Time: 14:37
 */
use App\GatewayWorker\Chat\Chat;

require_once __DIR__ . '/../../../vendor/autoload.php';

Chat::start();
