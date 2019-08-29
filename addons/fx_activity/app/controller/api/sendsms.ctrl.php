<?php
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'code';
$mobile = $_GPC['mobile'];
if($_W['isajax'] && $op =='code'){//验证码；
	$smscode = createRandomNumber(6);
	setcookie("sms_mobile", $mobile, time()+600);
	setcookie("sms_code", $smscode, time()+600);
	$params=array(
		'code'    => $smscode
	);
	die(json_encode(sendSMS($mobile, $params, $_W['_config']['sms_code'], $_W['_config']['sms_type'])));
}

if($_W['isajax'] && $op =='notify'){//短信通知；
	$params=array(
		'product' => $_W['account']['name'],
		'item'    => $_GPC['title']
	);
	die(json_encode(sendSMS($mobile,$params,$_W['_config']['sms_notify'], $_W['_config']['sms_type'])));
}