<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * map.ctrl
 * 地图控制器
 */
defined('IN_IA') or exit('Access Denied');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($op =='tencent'){
	include fx_template ('map/map_tencent');
	exit;
}