<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * entry.ctrl
 * 核销管理控制器
 */
defined('IN_IA') or exit('Access Denied');
$op  = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$opp = !empty($_GPC['opp']) ? $_GPC['opp'] : 'display';

if ($op == 'display') {
	$_W['page']['title'] = '会员列表 - 会员 - 会员中心';
	$condition = " uniacid={$_W['uniacid']} ";
	$params = array();
	$type = intval($_GPC['type']);
	$keyword = trim($_GPC['keyword']);

	if (!empty($keyword)) {
		switch($type) {
			case 2 :
				$condition .= " AND mobile LIKE :mobile";
				$params[':mobile'] = "%{$keyword}%";
				break;
			case 3 :
				$condition .= " AND nickname LIKE :nickname";
				$params[':nickname'] = "%{$keyword}%";
				break;
			default :
				$condition .= " AND realname LIKE :realname";
				$params[':realname'] = "%{$keyword}%";
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	//当前页码
	$psize = 15;
	//设置分页大小
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('mc_members')." WHERE $condition", $params);
	$sqlData = "SELECT * FROM " . tablename ('mc_members') . " WHERE $condition ORDER BY `credit1` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$list = pdo_fetchall ($sqlData,$params);
	foreach ($list as &$s) {
		$blacklist = pdo_get('fx_member_blacklist', array('uid' => $s['uid']), array('uid'));
		$s['blacklist'] = $blacklist['uid'];
	}
	$pager = pagination($total, $pindex, $psize);
	include fx_template('member/member');
}

if ($op == 'credit_record') {
	$uid = $_GPC['uid'];
	load()->model('mc');
	$member = mc_fetch($uid);
	$type = trim($_GPC['type']) ? trim($_GPC['type']) : 'credit1';
	$pindex = max(1, intval($_GPC['page']));//当前页码
	$psize = 50;//设置分页大小
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_credits_record') . ' WHERE uid = :uid AND uniacid = :uniacid AND credittype = :credittype ', array(':uniacid' => $_W['uniacid'], ':uid' => $uid, ':credittype' => $type));
	$credits_records = pdo_fetchall("SELECT * FROM " . tablename('mc_credits_record') . ' WHERE uid = :uid AND uniacid = :uniacid AND credittype = :credittype ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':uniacid' => $_W['uniacid'], ':uid' => $uid, ':credittype' => $type));
	$pager = pagination($total, $pindex, $psize);
	$modules = pdo_getall('modules', array('issystem' => 0), array('title', 'name'), 'name');
	$modules['card'] = array('title' => '会员卡', 'name' => 'card');
	include fx_template('member/member');
	exit;
}

if ($op == 'selectmember') {
	$con     = "A.uniacid='{$_W['uniacid']}' and A.uid = B.uid ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and (A.nickname LIKE '%{$keyword}%' or B.openid LIKE '%{$keyword}%') ";
	}
	$field  = "A.uid, A.nickname, avatar, B.openid";
	$inner  = tablename ('mc_members') . "A , " . tablename ('mc_mapping_fans') . "B ";
	$ds = pdo_fetchall("select ".$field." from" . $inner . "where $con");
	include fx_template('member/query_member');
	exit;
}

if ($op == 'blacklist') {
	$blacklist = intval($_GPC['blacklist']);
	if(!$blacklist){
		$result = pdo_delete('fx_member_blacklist', array('uid' => $_GPC['uid']));
		die(json_encode(array("errno" => $result ? 0 : 1,'message' => $result?'设置成功！':"设置失败！")));
		exit;
	}else{
		$result = pdo_insert ('fx_member_blacklist', array (
			'uid' => $_GPC['uid'],
			'uniacid' => $_W['uniacid']
		));
		die(json_encode(array("errno" => $result ? 0 : 1,'message' => $result?'设置成功！':"设置失败！")));
		exit;
	}
}