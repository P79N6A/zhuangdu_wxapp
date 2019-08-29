<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * home.ctrl
 * 个人中心首页控制器
 */
defined('IN_IA') or exit('Access Denied');
if (empty($_W['fans']['nickname'])){
	$_W['fans'] = getInfo();
}
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = '我的';
fx_load()->model('credit');
$credits = credit_get_by_uid($_W['member']['uid'],1);

if($op =='display'){
	$member = getMember($_W['openid']);
	$member = empty($member)?$_W['fans']:$member;
	$member['avatar'] = tomedia($member['avatar']);
	$member['gender'] = $member['gender']==0 ? '保密' : ($member['gender']==1?'男':'女');
	$condition = " uniacid = '{$_W['uniacid']}' and (openid = '{$_W['openid']}' or uid='{$_W['member']['uid']}')";
	$total1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 0");
	$total2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 1");
	$total3 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 2");
	$total4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 3");
	$total5 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 5");
	$total7 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 7");
	include $this->template ('member/home');
	exit;
}
//更新个人信息
if ($op == 'mc' && $_W['isajax']) {
	$type = $_GPC['type'];
	$data = array();
	switch($type){
		case 'nickname':$data['nickname'] = trim($_GPC['nickname']);break;
		case 'realname':$data['realname'] = trim($_GPC['realname']);break;
		case 'qq'      :$data['qq'] = trim($_GPC['qq']);break;
		case 'gender'  :$data['gender'] = trim($_GPC['gender']);break;
		default:;
	}
	
	if (mc_update($_W['member']['uid'], $data)) {
		message('更新资料成功！', referer(), 'success');
	}else{
		message('更新失败！', referer(), 'error');
	}
	exit;
}