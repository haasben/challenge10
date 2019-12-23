<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');

define('THIS_URL','http://game.io');
define('WEB_URL','http://10s.times168.net');
define('APPID','wxd3a3cc7c2d73047d');
define('APPSECRET','b6d59a934ce8d9bc9aa7e04ccb96b53a');
//ini_set('session.cookie_domain', '.times168.net');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
