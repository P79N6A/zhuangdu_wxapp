<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * url2qr.ctrl
 * 主办方二维码控制器
 */
$activityid =  $_GPC['activityid'];
$mid = createRandomNumber(8);
$activity = model_activity::getSingleActivity($activityid, '*');
if (!empty($activity)){
	if (empty($activity['midkey'])){
		pdo_update('fx_activity', array('midkey' => $mid), array('id' => $activityid));
	}else{
		$mid = 	$activity['midkey'];
	}
	$url =  app_url('member/signin/consumption', array('mid' => $mid, 'activityid' => $activityid));
	$qrcode = createQrcode::createverQrcode($url,$mid,$activityid,"merchant");
	echo '<img style="-webkit-user-select: none" src="'.tomedia($qrcode).'">';
}else{
	echo 'erro:当前活动不存在';
}