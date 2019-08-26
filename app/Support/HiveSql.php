<?php
/**
 * Created by PhpStorm.
 * User: King
 * Date: 2019/2/12
 * Time: 11:48
 */

namespace App\Support\Hive;

use ThriftSQL\Hive;

class HiveSql
{
    /**
     * @var \ThriftSQL\Hive
     */
    private $hive;

    /**
     * @var \ThriftSQL\Hive
     */
    private $client;

    public function __construct()
    {
        $this->connection(config('database.hive.default'));
    }

    /**
     * 连接Hive
     *
     * @param $connection
     *
     * @return $this
     */
    public function connection($connection)
    {
        if (!empty($this->hive)){
            $this->close();
        }
        $this->hive = new Hive(
            config('database.hive.connections.' . $connection . '.host'),
            config('database.hive.connections.' . $connection . '.port'),
            config('database.hive.connections.' . $connection . '.username'),
            config('database.hive.connections.' . $connection . '.password'),
            600
        );
        $this->client = $this->hive->setSasl(false)->connect();
        //选择数据库
        $this->setDatabase(config('database.hive.connections.' . $connection . '.database'));
        return $this;
    }

    /**
     * @param $database
     *
     * @return $this
     */
    public function setDatabase($database)
    {
        //选择数据库
        $this->execute('use ' . $database . ';');
        return $this;
    }

    /**
     * 执行HQL
     *
     * @param $queryStr
     *
     * @return array
     */
    public function execute($queryStr){
        return $this->client->queryAndFetchAll($queryStr);
    }

    /**
     * 配合$results->wait();和$results->fetch(1);用for或while循环分批取数据
     *
     * @param $queryStr
     *
     * @return \ThriftSQL\HiveQuery|\ThriftSQLQuery
     */
    public function query($queryStr)
    {
        return $this->client->query($queryStr);
    }

    /**
     * 迭代器 使用foreach循环取数据 类似fetch(1)
     *
     * @param $queryStr
     *
     * @return \ThriftSQL\Utils\Iterator
     */
    public function getIterator($queryStr)
    {
        return $this->client->getIterator($queryStr);
    }

    /**
     * 关闭套接字连接
     */
    public function close()
    {
        $this->hive->disconnect();
        $this->hive = null;
        $this->client = null;
    }

    public function __destruct()
    {
        $this->close();
    }
}
