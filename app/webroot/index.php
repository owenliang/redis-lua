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