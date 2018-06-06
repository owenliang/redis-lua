<?php
namespace app\controller;

use app\service\QuanService;
use myf\Http;

/**
 * Class Quan
 * @package app\controller
 *
 * 优惠券秒杀DEMO
 */
class Quan
{
    private function response($errno = 0, $msg = '', $data = [])
    {
        echo json_encode(['errno' => $errno, 'msg' => $msg, 'data' => $data]);
        return;
    }

    /**
     * 秒杀优惠券
     */
    public function fetch()
    {
        $uid = isset($_GET['uid']) ? $_GET['uid'] : 0; // 仅用于演示
        $quanId = isset($_GET['quanId']) ? $_GET['quanId'] : '';
        if (empty($uid) || empty($quanId)) {
            return $this->response(-1, '参数不合法');
        }

        // 某个用户抢某个优惠券
        $result = QuanService::fetch($uid, $quanId);
        if (empty($result)) {
            return $this->response(1, '服务端异常');
        }
        return $this->response($result['errno'], $result['msg'], $result['data']);
    }

    /**
     * 模拟上传一个批次的优惠券
     */
    public function upload()
    {
        $quanId = isset($_GET['quanId']) ? $_GET['quanId'] : '';
        $count =  isset($_GET['count']) ? $_GET['count'] : '';

        if (empty($quanId) || empty($count)) {
            return $this->response(-1, '参数不合法');
        }

        // 模拟一个批次ID，实际上应该走数据库去生成
        $batchId = time();

        $result = QuanService::upload($quanId, $batchId, $count);
        if (empty($result)) {
            return $this->response(1, '服务端异常');
        }
        return $this->response(0, '生成成功', $result);
    }

    /**
     * 命令行循环模拟uid压测
     */
    public function test($quanId, $times)
    {
        $s = microtime(true);
        for ($i = 0; $i < $times; ++$i) {
            $uid = rand(0, 100000000);
            // 某个用户抢某个优惠券
            $result = QuanService::fetch($uid, $quanId);
            $u = intval( (microtime(true) - $s) * 1000 * 1000)  . PHP_EOL;
            if ($i % 10000 == 0) {
                echo $i / (microtime(true) - $s) . PHP_EOL;
            }
        }
    }
}