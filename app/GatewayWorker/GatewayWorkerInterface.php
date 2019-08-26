<?php
/**
 * Created by PhpStorm.
 * User: King
 * Date: 2019/8/26
 * Time: 14:22
 */

namespace App\GatewayWorker;

interface GatewayWorkerInterface
{
    public function start();

    public function startBusinessWorker();

    public function startGateWay();

    public function startRegister();
}
