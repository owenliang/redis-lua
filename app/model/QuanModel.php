<?php
namespace app\model;

use myf\Redis;

class QuanModel
{
    /**
     * @param $uid
     * @param $quanId
     * @return bool
     *
     * 基于Redis+Lua实现秒杀
     */
    public static function fetchFromRedis($uid, $quanId)
    {
        // 加载lua脚本
        $script = file_get_contents(__DIR__ . '/QuanFetch.lua');

        // 计算lua的sha1哈希
        $scriptSha1 = sha1($script);

        $redisMaster = Redis::master('default');

        // evalsha执行脚本, 完成秒杀
        $result = $redisMaster->evalSha($scriptSha1, [$quanId, $uid], 1); // 按quanid做路由
        if ($redisMaster->getLastError()) { // 如果evalsha报错, 则进行一次script load
            if (!$redisMaster->script('load', $script)) {
                return false;
            }
            // 然后重试脚本
            $result = $redisMaster->evalSha($scriptSha1, [$quanId, $uid], 1);
        }

        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 上传N个券码到批次的券码池
     * @param $quanId
     * @param $batchId
     * @param $coupons
     */
    public static function uploadBatchToRedis($quanId, $batchId, $coupons)
    {
        $redisMaster = Redis::master('default');

        $quanKey = "QUAN_{" . $quanId . "}"; // {quanid} redis hash tags
        $batchKey = "BATCH_{" . $quanId . "}_" . $batchId; // {quanid} redis hash tags


        $tran = $redisMaster->multi(\Redis::PIPELINE);
        // 券码加入集合
        foreach ($coupons as $coupon) {
            $tran->zAdd($batchKey, 0, $coupon);
        }
        // 批次加入优惠券
        $tran->hSet($quanKey, $batchKey, json_encode(['online' => true]));
        $tran->exec();

        return $redisMaster->zCard($batchKey);
    }
}