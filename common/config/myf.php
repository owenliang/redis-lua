<?php

return [
    // mysql配置
    'mysql' => [
        'default' => [
            'dbname' => 'test_db',
            'username' => 'test_user',
            'password' => 'test_pass',
            'charset' => 'utf8',
            'master' => [
                [
                    'host' => 'localhost',
                    'port' => 3306
                ],
            ],
            'slave' => [
                [
                    'host' => 'localhost',
                    'port' => 3306
                ],
            ]
        ]
    ],

    // redis配置
    'redis' => [
        'default' => [
            'dbIndex' => 0,
            'password' => false,
            'isCluster' => false,
            'timeout' => 2,
            'readTimeout' => 2,
            'master' => [
                [
                    'host' => 'localhost',
                    'port' => 6379,
                ]
            ],
            'slave' => [
                [
                    'host' => 'localhost',
                    'port' => 6379,
                ]
            ]
        ],
        'myCluster' => [
            'dbIndex' => 0,
            'password' => false,
            'isCluster' => true,
            'timeout' => 2,
            'readTimeout' => 2, // 高版本phpredis支持此选项
            'master' => [
                [
                    'host' => 'localhost',
                    'port' => 6379,
                ],
                [
                    'host' => 'localhost',
                    'port' => 6379,
                ]
            ],
        ]
    ],

    // elasticsearch配置
    'elasticsearch' => [
        'default' => [
            'hosts' => ['localhost:9200', ],
            'retries' => 2,
            'connectionParams' => [
                'client' => [
                    'timeout' => 2,
                    'connect_timeout' => 2,
                ]
            ]
        ]
    ],

    // http客户端配置
    'http' => [
        'connectTimeout' => 1,  // 连接超时1秒
        'timeout' => 1, // 请求超时1秒
    ]
];