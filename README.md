# myf-app

## 介绍

myf means 'my framework'

我一直想自己做个简单的不能再简单的PHP框架，为了少一件心事，所以抽了点时间搞出来。

## 原则

* 框架核心myf-core作为composer library发布
* 框架脚手架myf-app作为composer project发布，依赖myf-core包
* 支持多应用开发，共享公共代码
* 基于namespace的类自动加载
* 没有IOC容器，namespace本身就是单例
* 没有框架基类，不绑架开发者习惯，PHP原汁原味

## 使用脚手架

[myf/app](https://packagist.org/packages/myf/app)

## 组成

### common\...

公共代码，被多个应用共享访问，命名空间以common\为前缀，可以实现类自动加载

### app\...

单个应用，命名空间以\app为前缀

### common/config/myf.php

跨应用共享配置

### app/config/myf.php

单个应用的独有配置

### app/webroot/index.php

单个应用的入口文件，复制到其他应用不需要修改

```
<?php

// composer
require_once __DIR__ . '/../../vendor/autoload.php';

// 框架根目录
define('MYF_ROOT', __DIR__ . '/../../');

// 应用根目录
define('APP_ROOT', __DIR__ . '/../');

// 合并配置
$config = array_merge_recursive(require MYF_ROOT . 'common/config/myf.php', require APP_ROOT . 'config/myf.php');

// 启动框架
\myf\App::run($config);
```

### app\controller\...

controller层，实现接口

### app\model\...

model层，实现数据访问，调用Http、Mysql、Redis、Elasticsearch等，model仅仅是个分层而已

### app\service\...

service层，封装可复用的业务逻辑，调用model层获取数据

### app/view/...php

view层，模板文件，可以嵌套渲染

### common\model\...

通用的model，所有app共享

### common\service\...

通用的service，所有app共享

### common\view\...

通用的view，所有app共享

## 配置

### app/config/myf.php

应用特有的配置，比如路由配置。
```
<?php

return [
    // 调试模式
    'debug' => true,

    // 路由配置
    'route' => [
        // 静态路由
        'static' => [
            '/service' => ['Demo', 'service'],
            '/view' => ['Demo', 'view'],
            '/mysql' => ['Demo', 'mysql'],
            '/redis' => ['Demo', 'redis'],
            '/http' => ['Demo', 'http'],
            '/elasticsearch' => ['Demo', 'elasticsearch'],
        ],
        // pcre正则路由
        'regex' => [
            ['^/params/(\d+)$', 'Demo', 'params'],
        ],
    ],
];
```

### common配置

所有应用共享的配置，比如各种客户端的配置。
```
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
```

## my-core 框架核心

[框架核心composer library](https://github.com/owenliang/myf-core)
