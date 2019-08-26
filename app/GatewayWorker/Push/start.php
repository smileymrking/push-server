<?php
/**
 * Created by PhpStorm.
 * User: King
 * Date: 2019/8/23
 * Time: 14:37
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../../bootstrap/app.php';

// 加载配置
(new \Illuminate\Foundation\Bootstrap\LoadConfiguration)->bootstrap($app);

(new \App\GatewayWorker\Push\Push())->start();
