<?php
//1.设置响应类型
header('Content-Type:text/html;charset=utf-8');
//2.设置项目路径
 define('APP_PATH', './application/');
//3.开启调试模式
define('APP_DEBUG', true);
//4.引入ThinkPHP核心文件
include_once '../ThinkPHP/ThinkPHP.php';