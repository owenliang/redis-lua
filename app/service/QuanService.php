<?php
namespace app\service;

use app\model\QuanModel;

class QuanService
{
    public static function fetch($uid, $quanId)
    {
        return QuanModel::fetchFromRedis($uid, $quanId);
    }
}