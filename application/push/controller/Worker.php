<?php
namespace app\push\controller;

use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'websocket://youkang.com:2346';

    /**
     * @param $connection
     * @param $data
     * [onMessage  收到信息]
     * @autor [王生功][1064860088@qq.com]
     */
    public function onMessage($connection, $data)
    {
        $connection -> send('我收到你的信息了45645646');
    }

    /**
     * @param $connection
     * [onConnect  建立链接时候触发回调函数]
     * @autor [王生功][1064860088@qq.com]
     */
    public function onConnect($connection)
    {
        $connection -> send('我要建立链接了2');
    }

    /**
     * @param $connection
     * [onClose  链接断开是触发函数]
     * @autor [王生功][1064860088@qq.com]
     */
    public function onClose($connection)
    {
        $connection -> send('我要离开了');
    }

    /**
     * @param $connection
     * @param $code
     * @param $msg
     * [onError  链接发生错误时触发]
     * @autor [王生功][1064860088@qq.com]
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }
    /**
     * 每个进程启动
     */
    public function onWorkerStart($worker)
    {

    }
}
