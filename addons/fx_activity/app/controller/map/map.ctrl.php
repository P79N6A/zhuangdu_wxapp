<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * map.ctrl
 * 地图控制器
 */
defined('IN_IA') or exit('Access Denied');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($op =='tencent'){
	$pagetitle = '腾讯地图开放API - 轻快小巧,简单易用!';
	include $this->template ('map/map_tencent');
	exit;
}