<?php

/**

 * [WeEngine System] Copyright (c) 2014 WE7.CC

 * WeEngine is NOT a free software, it under the license terms, visited http://bbs.mhyma.com/ for more details.

 */

define('IN_MOBILE', true);

//验证订单

$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';

$log = pdo_fetch($sql, array(':plid' => $params['plid']));

if(!empty($log) && $log['status'] != '0') {

	die(json_encode(array('errno'=>1,'message'=>"这个订单已经支付成功, 不需要重复支付.")));

}

//if(!isset($_SESSION['sms_last']) || (time()-$_SESSION['sms_last']) > 600)

if(!isset($_SESSION['pay']))die(json_encode(array('errno'=>1,'message'=>"页面过期，请刷新页面")));

//验证名额，每次进入加载剩余名额数量、及规格

$activity = model_activity::getSingleActivity($_SESSION['pay']['activityid'], '*');

$records = pdo_get('fx_activity_records', array('orderno' => $log['tid'], 'uniacid' => $_W['uniacid']));

$condition = " activityid = {$_SESSION['pay']['activityid']} and status <> 5 and (status <> 0 or paytype = 'delivery')";

if ($records['optionid'] && $activity['hasoption']){//规格选项

	$option = model_activity::getSingleActivityOption($records['optionid']);

	$activity['falsedata']['num'] = $option['falsenum'] ? $option['falsenum'] : 0;

}

$gnum = $option['gnum']?$option['gnum']:$activity['gnum'];

if ($gnum > 0){

	$joinnum = model_records::getJoinNum($activity['id'], $records['optionid']) + $activity['falsedata']['num'];

	if($joinnum >= $gnum) {

		die(json_encode(array('errno'=>1,'message'=>"很遗憾！名额已经满了")));

	}elseif($joinnum + $_SESSION['pay']['buynum'] > $gnum){

		die(json_encode(array('errno'=>1,'message'=>"当前活动仅剩 " . ($gnum - $joinnum) . ' 个名额')));

	}

}

//名额验证结束

$auth = sha1($sl . $log['uniacid'] . $_W['config']['setting']['authkey']);

if($auth != $auth_cash) {

	die(json_encode(array('errno'=>1,'message'=>"参数传输错误.")));

}

load()->model('payment');

$_W['uniacid'] = $log['uniacid'];

$payopenid = $payopenid ? $payopenid : $log['openid'];

$setting = uni_setting($_W['uniacid'], array('payment'));

if(!is_array($setting['payment'])) {

	die(json_encode(array('errno'=>1,'message'=>"没有设定支付参数.")));

}

$wechat = $setting['payment']['wechat'];

$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';

$row = pdo_fetch($sql, array(':acid' => $wechat['account']));

$wechat['appid'] = $row['key'];

$wechat['secret'] = $row['secret'];

$wechat['openid'] = $payopenid;

$params = array(

	'tid' => $log['tid'],

	'fee' => $log['card_fee'],

	'user' => $payopenid,

	'title' => urldecode($params['title']),

	'uniontid' => $log['uniontid'],

);

if (intval($wechat['switch']) == 2 || intval($wechat['switch']) == 3) {

	$wOpt = wechat_proxy_build($params, $wechat);

} else {

	unset($wechat['sub_mch_id']);

	$wOpt = wechat_build($params, $wechat);

}

if (is_error($wOpt)) {

	if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {

		$id = date('YmdH');

		pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));

		pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");

		die(json_encode(array('errno'=>1,'message'=>"抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。")));

	}

	die(json_encode(array('errno'=>1,'message'=>"抱歉，发起支付失败，具体原因为：{$wOpt['errno']}:{$wOpt['message']}。请及时联系站点管理员。")));

	exit;

}

die(json_encode(array('errno'=>0,'message'=>"支付成功!",'data'=>$wOpt)));

?>