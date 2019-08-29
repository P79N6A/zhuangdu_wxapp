<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * record.ctrl
 * 我的报名控制器
 */
defined('IN_IA') or exit('Access Denied');
$recordsid = intval($_GPC['id']);
$pagetitle = '活动凭证';
$_W['merchant']['id']        = 0;
$_W['merchant']['uid']       = 0;
$_W['merchant']['name']      = $_W['_config']['sname'];
$_W['merchant']['logo']      = $_W['_config']['slogo'];
$_W['merchant']['detail']    = $_W['_config']['detail'];
$_W['merchant']['lng']       = $_W['_config']['lng'];
$_W['merchant']['lat']       = $_W['_config']['lat'];
$_W['merchant']['mobile']    = $_W['_config']['mobile'];
$_W['merchant']['address']   = $_W['_config']['address'];
$_W['merchant']['storename'] = $_W['_config']['storename'];
$_W['merchant']['followno']  = $_W['_config']['followno'];
if($recordsid){
	$records = model_records::getSingleRecords($recordsid);
	if (!in_array($records['status'], array('1','2','3'))){
		fx_message ('此订单状态不可核销', '', 'warning','点击确定返回');
	}
	$activity = model_activity::getSingleActivity($records['activityid'], '*');
	if ($activity['merchantid']){
		$merchant  = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
		$_W['merchant']['id']        = $merchant['id'];
		$_W['merchant']['uid']       = $merchant['uid'];
		$_W['merchant']['name']      = $merchant['name'];
		$_W['merchant']['logo']      = $merchant['logo'];
		$_W['merchant']['detail']    = $merchant['detail'];
		$_W['merchant']['lng']       = $merchant['lng'];
		$_W['merchant']['lat']       = $merchant['lat'];
		$_W['merchant']['mobile']    = $merchant['linkman_mobile'];
		$_W['merchant']['address']   = $merchant['address'];
		$_W['merchant']['storename'] = $merchant['storename'];
		$_W['merchant']['followno']  = $merchant['followno'];
	}
	
	if ($activity['hasstore']){//判断位置是否活动中定义
		$_W['merchant']['lng']       = $activity['lng'];
		$_W['merchant']['lat']       = $activity['lat'];
		$_W['merchant']['mobile']    = $activity['tel'];
		$_W['merchant']['address']   = $activity['address'];
		$_W['merchant']['storename'] = $activity['addname'];
	}elseif (!empty($activity['storeids'])){//判断活动门店
		$stores = model_activity::getNumActivityStore($activity['storeids']);
	}
	if (empty($records['orderno'])){
		$orderno = date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		pdo_update('fx_activity_records', array('orderno' => $orderno), array('id' => $recordsid));
		$records['orderno'] = $orderno;
	}
	$qrcodeurl = urlencode(app_url('records/check/', array('mid' => $records['orderno'])));
	if (empty($records['hexiaoma'])){
		$hexiaoma = createRandomNumber(8);
		pdo_update('fx_activity_records', array('hexiaoma' => $hexiaoma), array('id' => $recordsid));
		$records['hexiaoma'] = $hexiaoma;
	}
	//生成二维码
	createQrcode::creategroupQrcode($records['orderno']);
	$cond = " uniacid = '{$_W['uniacid']}' and activityid = {$records['activityid']} and (status in(1,2,3) or (status=0 and paytype='delivery'))";
	$NO = pdo_fetchcolumn("SELECT rownum FROM (SELECT (@rownum:=@rownum+1) AS rownum, a.* FROM ".tablename('fx_activity_records')." a, (SELECT @rownum:= 0) r WHERE $cond ORDER BY a.`id` ASC) AS b  WHERE id=$recordsid");
}
include $this->template ('records/qrcode');