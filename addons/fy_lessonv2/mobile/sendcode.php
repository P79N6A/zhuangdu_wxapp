<?php
/**
 * 短信发送
 * ============================================================================
 * 版权所有 2015-2017 风影随行，并保留所有权利。
 * 网站地址: http://www.5kym.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！不允许对程序代码以任何形式任何目的的再发布，作者将保留
 * 追究法律责任的权力和最终解释权。
 */


$mobile = trim($_GPC['mobile']);
if(!(preg_match("/13\d{9}|15\d{9}|16\d{9}|17\d{9}|18\d{9}|19\d{9}/",$mobile))){
	$data = array(
		'code' => -1,
		'msg'  => '手机号码有误'
	);
    $this->resultJson($data);
}


$sms = json_decode($setting['dayu_sms'], true);
$param['code'] = strval(rand(1234,9999));

session_start();
$_SESSION['mobile_code'] = $param['code'];

$this->sendSMS($sms, $mobile, $sms['verify_code'], $param);

$data = array(
	'code' => 0,
	'msg'  => '验证码发送成功',
);
$this->resultJson($data);