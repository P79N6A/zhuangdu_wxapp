<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * url2qr.ctrl
 * 主办方二维码控制器
 */
$activityid =  $_GPC['activityid'];
$pagetitle = "用户签到入口";
$mid = createRandomNumber(8);
$activity = model_activity::getSingleActivity($activityid, '*');
$merchant  = model_activity::getActivityMerchant($activity['merchantid']);//读取主办方

if (!empty($activity)){
	if (empty($activity['midkey'])){
		pdo_update('fx_activity', array('midkey' => $mid), array('id' => $activityid));
	}else{
		$mid = 	$activity['midkey'];
	}
	$url =  app_url('member/signin/consumption', array('mid' => $mid, 'activityid' => $activityid));
	$qrcode = createQrcode::createverQrcode($url,$mid,$activityid,"merchant");
	include $this->template ('activity/url2qr');
	//echo '<img style="-webkit-user-select: none" width="100%" src="'.tomedia($qrcode).'">';
}else{
	fx_message('访问失败', '', 'warning', '活动可能不存在');
}