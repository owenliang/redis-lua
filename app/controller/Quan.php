<?php
namespace app\controller;

use app\service\QuanService;

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

        $result = QuanService::fetch($uid, $quanId);
        if (empty($result)) {
            return $this->response(1, '服务端异常');
        }
        return $this->response(0, '请求成功', $result);
    }
}