<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * user.ctrl
 * 前端用户控制器
 */
defined('IN_IA') or exit('Access Denied');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = '已报名用户';
$activityid = intval($_GPC['activityid']);
if($op =='display'){
	$sql = 'SELECT * FROM ' . tablename('fx_activity_records') . ' WHERE activityid = :activityid AND openid = :openid AND `status` <> :status';
	$join  = pdo_fetch($sql, array(':activityid' => $activityid, ':status' => 5,':openid' =>$_W['openid']));
	$activity = model_activity::getSingleActivity($activityid, '*');
	$activity['falsedata']['nickname'] = explode('，',$activity['falsedata']['nickname']);
	$condition = " uniacid = '{$_W['uniacid']}' and status <> 5 and (status <> 0 or paytype = 'delivery')";
	if (!empty($activityid)) {
		$condition .= " AND activityid = $activityid";
	}
	$records = pdo_fetchall ("SELECT * FROM " . tablename ('fx_activity_records') . " WHERE $condition ORDER BY id DESC");	
	if ($activity['hasoption']){//虚拟名额
		$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = $activityid");
		$activity['falsedata']['num'] = $opt['falsenum'] ? $opt['falsenum'] : 0;
	}	
	$joinnum = model_records::getJoinNum($activityid) + $activity['falsedata']['num'];
	include $this->template ('userlist');
	exit;
}
if($op =='user'){
	$pagetitle = '参与者';
	$rid = intval($_GPC['rid']);
	$records = model_records::getSingleRecords($rid);
	$activity = model_activity::getSingleActivity($records['activityid'], '*');
	$formdata = model_records::getNumFormData($rid);
	$records['mobile'] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$records['mobile']);
	include $this->template ('user');
	exit;
}