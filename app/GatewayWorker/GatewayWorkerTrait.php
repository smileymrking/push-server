<?php
/**
 * Created by PhpStorm.
 * User: King
 * Date: 2019/8/26
 * Time: 14:58
 */

namespace App\GatewayWorker;

use Workerman\Worker;

trait GatewayWorkerTrait
{
    public function start()
    {
        $path = 'gateway.' . self::NAME ;
        if (config("{$path}.gateway")) $this->startGateWay();
        if (config("{$path}.business_worker")) $this->startBusinessWorker();
        if (config("{$path}.register")) $this->startRegister();
        Worker::runAll();
    }
}
