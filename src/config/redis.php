<?php
/**
 * Created by PhpStorm.
 * User: zhangjincheng
 * Date: 16-7-14
 * Time: 下午1:58
 */

/**
 * 选择数据库环境
 */
$config['redis']['enable'] = true;
$config['redis']['active'] = 'local2';

/**
 * 本地环境
 */
$config['redis']['local']['ip'] = '47.98.112.137';
$config['redis']['local']['port'] = 6379;
$config['redis']['local']['select'] = 0;
$config['redis']['local']['auth'] = 'actors';

/**
 * 本地环境2
 */
$config['redis']['local2']['ip'] = '127.0.0.1';
$config['redis']['local2']['port'] = 6379;
$config['redis']['local2']['select'] = 2;

//$config['redis']['asyn_max_count'] = 10;
/**
 * 最终的返回，固定写这里
 */
return $config;
