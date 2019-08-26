<?php


namespace App\GatewayWorker\Push;

use App\GatewayWorker\GatewayWorkerTrait;
use App\GatewayWorker\GatewayWorkerInterface;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;

class Push implements GatewayWorkerInterface
{
    use GatewayWorkerTrait;

    const NAME = 'push';

    public function startBusinessWorker()
    {
        $worker                  = new BusinessWorker();
        $worker->name            = 'PushBusinessWorker';                    #设置BusinessWorker进程的名称
        $worker->count           = 1;                                       #设置BusinessWorker进程的数量
        $worker->registerAddress = '127.0.0.1:20000';                       #注册服务地址
        // 设置使用哪个类来处理业务,业务类至少要实现onMessage静态方法，onConnect和onClose静态方法可以不用实现
        $worker->eventHandler    = Events::class;
    }

    public function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:30000");  #连接服务的端口
        $gateway->name                 = 'PushGateway';                     #设置Gateway进程的名称，方便status命令中查看统计
        $gateway->count                = 1;                                 #进程的数量
        $gateway->lanIp                = config("gateway." . self::NAME . ".business_worker");                   #内网ip,多服务器分布式部署的时候需要填写真实的内网ip
        $gateway->startPort            = 20100;                             #监听本机端口的起始端口
        $gateway->pingInterval         = 55;
        $gateway->pingNotResponseLimit = 1;                                 # 0 服务端主动发送心跳, 1 客户端主动发送心跳
        $gateway->pingData             = '{"type":"heart"}';                # 服务端主动发送心跳的数据
        $gateway->registerAddress      = '127.0.0.1:20000';                 #注册服务地址
    }

    public function startRegister()
    {
        new Register('text://0.0.0.0:20000');
    }
}
