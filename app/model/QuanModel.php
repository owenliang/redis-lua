<?php
namespace app\model;

use myf\Redis;

class QuanModel
{
    /**
     * @return bool
     *
     * 基于Redis+Lua实现秒杀
     */
    public static function fetchFromRedis()
    {
        // 加载lua脚本
        $script = file_get_contents(__DIR__ . '/QuanFetch.lua');

        // 计算lua的sha1哈希
        $scriptSha1 = sha1($script);

        $redisMaster = Redis::master('default');

        // evalsha执行脚本, 完成秒杀
        $result = $redisMaster->evalSha($scriptSha1);
        if ($redisMaster->getLastError()) { // 如果evalsha报错, 则进行一次script load
            if (!$redisMaster->script('load', $script)) {
                return false;
            }
            // 然后重试脚本
            $result = $redisMaster->evalSha($scriptSha1);
        }

        return $result;
    }
}