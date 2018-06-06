<?php

return [
    // 调试模式
    'debug' => true,

    // 路由配置
    'route' => [
        // 静态路由
        'static' => [
            '/quan/fetch' => ['Quan', 'fetch'],
            '/quan/upload' => ['Quan', 'upload'],
            '/quan/test' => ['Quan', 'test'],
        ],
        // pcre正则路由
        'regex' => [],
    ],
];