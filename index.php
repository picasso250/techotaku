<?php
/**
 * @file    index
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 30, 2012 10:38:22 AM
 * index
 */

// 打开错误提示
error_reporting(E_ALL);

if (isset($_SERVER['HTTP_APPNAME'])) {
    define('DEPLOY_ENV', 'prd');
} else {
    define('DEPLOY_ENV', 'dev');
}

include __DIR__.'/autoload.php';

date_default_timezone_set('PRC');
ob_start();
session_start();

\xc\orm\PdoWrapper::config(Func::config('db'));

if (!isset($_SERVER['PATH_INFO'])) {
    $arr = explode('?', $_SERVER['REQUEST_URI']);
    $_SERVER['PATH_INFO'] = $arr[0];
}

$routers = Func::config('routers');

foreach ($routers as $value) {
    list($method, $url, $ca) = $value;
    $regex = preg_replace_callback('/\[:(\w+)\]/', function ($m) {
        return '(?<'.$m[1].'>\w+)';
    }, $url);
    $regex = '/^'.str_replace('/', '\\/', $regex).'$/';
    if ($_SERVER['REQUEST_METHOD'] == $method && preg_match($regex, $_SERVER['PATH_INFO'], $matches)) {
        Func::dispatch(key($ca), reset($ca), array_merge($_REQUEST, $matches));
        exit;
    }
}

$arr = explode('/', $_SERVER['PATH_INFO']);
$c = isset($arr[1]) && $arr[1] ? ucfirst($arr[1]) : 'Index';
$a = isset($arr[2]) && $arr[2] ? $arr[2] : 'index';
Func::dispatch($c, $a, $_REQUEST);
