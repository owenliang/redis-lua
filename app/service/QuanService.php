<?php
namespace app\service;

use app\model\QuanModel;

class QuanService
{
    public static function fetch($uid, $quanId)
    {
        return QuanModel::fetchFromRedis($uid, $quanId);
    }

    public static function upload($quanId, $batchId, $count)
    {
        $coupons = [];

        // 为批次模拟生成count个券码
        for ($i = 0; $i < $count; ++$i) {
            $bytes = openssl_random_pseudo_bytes(8);
            $coupon = base_convert(bin2hex($bytes), 16, 32);
            $coupons[] = $coupon;
        }

        // 投递券码
        $batchSize = QuanModel::uploadBatchToRedis($quanId, $batchId, $coupons);
        return ['batchId' => $batchId, 'batchSize' => $batchSize, 'coupons' => $coupons];
    }
}