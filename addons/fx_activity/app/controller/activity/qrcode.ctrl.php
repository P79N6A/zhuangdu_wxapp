<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * record.ctrl
 * 我的报名控制器
 */
defined('IN_IA') or exit('Access Denied');
$recordsid = intval($_GPC['id']);
$pagetitle = '活动凭证';
if($recordsid){
	$records = model_records::getSingleRecords($recordsid);
	$activity = model_activity::getSingleActivity($records['activityid']);
	$qrcodeurl = urlencode(app_url('records/check/', array('mid' => $records['orderno'])));
	if (empty($records['hexiaoma'])){
		$hexiaoma = createRandomNumber(8);
		pdo_update('fx_activity_records', array('hexiaoma' => $hexiaoma), array('id' => $recordsid));
		//生成二维码
		createQrcode::creategroupQrcode($records['orderno']);
		$records['hexiaoma'] = $hexiaoma;
	}
}
include $this->template ('redords/qrcode');