<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * entry.ctrl
 * 核销管理控制器
 */
defined('IN_IA') or exit('Access Denied');
$op  = !empty($_GPC['op']) ? $_GPC['op'] : 'entry';
$opp = !empty($_GPC['opp']) ? $_GPC['opp'] : 'display';
$pagetitle = '我的活动';
if (MERCHANTID){
	$merchant = model_merchant::getSingleMerchant($_SESSION['role_id'], 'id,name');
}else{
	$merchantsData = model_merchant::getNumMerchant(0,0,0,$_SESSION['role_id']);
	$merchants = $merchantsData[0];
}
if ($op == 'entry') {
	$rule = pdo_fetch("select id from " . tablename('rule') . 'where uniacid=:uniacid and module=:module and name=:name', array(
		':uniacid' => $_W['uniacid'],
		':module' => MODULE_NAME,
		':name' => "活动报名核销回复"
	));
	if ($rule) {
		$set = pdo_fetch("select content from " . tablename('rule_keyword') . 'where uniacid=:uniacid and rid=:rid', array(
			':uniacid' => $_W['uniacid'],
			':rid' => $rule['id']
		));
	}
	if (checksubmit('keysubmit')) {
		$keyword = empty($_GPC['keyword']) ? '核销' : $_GPC['keyword'];
		if (empty($rule)) {
			$rule_data = array(
				'uniacid' => $_W['uniacid'],
				'name' => '活动报名核销回复',
				'module' => MODULE_NAME,
				'displayorder' => 0,
				'status' => 1
			);
			pdo_insert('rule', $rule_data);
			$rid          = pdo_insertid();
			$keyword_data = array(
				'uniacid' => $_W['uniacid'],
				'rid' => $rid,
				'module' => MODULE_NAME,
				'content' => trim($keyword),
				'type' => 1,
				'displayorder' => 0,
				'status' => 1
			);
			pdo_insert('rule_keyword', $keyword_data);
		} else {
			pdo_update('rule_keyword', array(
				'content' => trim($keyword)
			), array(
				'rid' => $rule['id']
			));
		}
		message('核销关键字设置成功!', referer(), 'success');
	}
	include fx_template('application/hexiao');
}
if ($op == 'store') {
	if ($opp == 'display') {
		$list = pdo_fetchall("select * from" . tablename('fx_store') . "where uniacid='{$_W['uniacid']}' ".FX_MERCHANTID."");
		foreach($list as$key=>&$value){
			$value['merchant'] = pdo_fetch("SELECT * FROM " . tablename('fx_merchant') . " WHERE uniacid = '{$_W['uniacid']}' and id={$value['merchantid']}");
		}
	} elseif ($opp == 'post') {
		$id = $_GPC['id'];
		if ($id) {
			$item = pdo_fetch("select * from" . tablename('fx_store') . "where uniacid='{$_W['uniacid']}' and id = '{$id}'");
			$item['storehours'] = unserialize($item['storehours']);
		}
		if (checksubmit('storesubmit')) {
			$id   = $_GPC['id'];
			$data = array(
				'uniacid' => $_W['uniacid'],
				'storename' => $_GPC['storename'],
				'address' => $_GPC['address'],
				'tel' => $_GPC['tel'],
				'lng' => $_GPC['map']['lng'],
				'lat' => $_GPC['map']['lat'],
				'adinfo' => $_GPC['map']['adinfo'],
				'status' => $_GPC['qiyongstatus'],
				'merchantid'=>$_GPC['merchantid']
			);
			if (trim($data['storename']) == '') {
				message('必须填写门店名称！', referer(), 'error');
				exit;
			}	
			$registerdate     = $_GPC['registerdate'];
			$data['storehours']= serialize($registerdate);
			if ($id) {
				pdo_update('fx_store', $data, array(
					'id' => $id
				));
			} else {
				pdo_insert('fx_store', $data);
			}
			message('操作成功！', web_url('application/hexiao/store'), 'success');
		}
	} elseif ($opp == 'delete') {
		$id = $_GPC['id'];
		pdo_delete('fx_store', array(
			'id' => $id
		));
		message('删除成功！', referer(), 'success');
	}
	include fx_template('application/hexiao');
}

if ($op == 'saler') {
	fx_load() -> model('member');
	if ($opp == 'display') {
		$list = pdo_fetchall("select * from" . tablename('fx_saler') . " where uniacid='{$_W['uniacid']}' ".FX_MERCHANTID."");
		foreach ($list as $key => $value) {
			$openids_arr = explode(',', $value['openid']);
			$storeid_arr = explode(',', $value['storeid']);
			$salername   = '';
			$storename   = '';
			//所属门店
			foreach ($storeid_arr as $k => $v) {
				if ($v) {
					$store = pdo_fetch("select * from" . tablename('fx_store') . "where id='{$v}'");
					$storename .= $store['storename'] . "/";
				}
			}
			$storename               = substr($storename, 0, strlen($storename) - 1);
			//所有核销员昵称
			foreach ($openids_arr as $k => $v) {
				if ($v) {
					$member = getMember($v);
					$salername .= $member['nickname'] . "/";
				}
			}
			$salername               = substr($salername, 0, strlen($salername) - 1);
			$list[$key]['salername'] = $salername;
			$list[$key]['storename'] = $storename;
			$list[$key]['merchant'] = pdo_fetch("SELECT * FROM " . tablename('fx_merchant') . " WHERE uniacid = '{$_W['uniacid']}' and id={$value['merchantid']}");
		}
	} elseif ($opp == 'post') {
		$id = $_GPC['id'];
		if ($id) {
			$saler       = pdo_fetch("select * from" . tablename('fx_saler') . "where uniacid='{$_W['uniacid']}' and id = '{$id}'");
			$storeid_arr = explode(',', $saler['storeid']);
			$openids     = explode(',', $saler['openid']);
			foreach ($storeid_arr as $k => $v) {
				if ($v) {
					$stores[$k] = pdo_fetch("select * from" . tablename('fx_store') . "where id='{$v}' and uniacid='{$_W['uniacid']}'");
				}
			}
		}
		if (checksubmit('salersubmit')) {
			load()->model('mc');
			$id       = $_GPC['id'];
			$storeid  = '';
			$storeids = $_GPC['storeids'];
			if ($storeids) {
				foreach ($storeids as $key => $value) {
					if ($value) {
						$storeid .= $value . ",";
					}
				}
			}
			$openid  = '';
			$openids = $_GPC['openids'];
			if ($openids) {
				foreach ($openids as $key => $value) {
					if ($value) {
						$openid .= $value . ",";
					}
				}
			}
			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid'  => $openid,
				'storeid' => $storeid,
				'status'  => $_GPC['salerstatus'],
				'merchantid'=>$_GPC['merchantid']
			);
			if ($data['openid'] == '') {
				message('必须选择核销员！', referer(), 'error');
				exit;
			}
			$uid  = mc_openid2uid($data['openid']);
			$info = mc_fetch($uid, array('nickname','avatar'));
			//$info             = member_get_by_params(" openid = '{$data['openid']}'");
			$data['avatar']   = $info['avatar'];
			$data['nickname'] = $info['nickname'];
			if ($id) {
				pdo_update('fx_saler', $data, array(
					'id' => $id
				));
			} else {
				pdo_insert('fx_saler', $data);
			}
			message('操作成功！', web_url('application/hexiao/saler'), 'success');
		}
	} elseif ($opp == 'delete') {
		$id = $_GPC['id'];
		pdo_delete('fx_saler', array(
			'id' => $id
		));
		message('删除成功！', referer(), 'success');
	}
	include fx_template('application/hexiao');
} 

if ($op == 'selectstore') {
	$con     = "uniacid='{$_W['uniacid']}' and status=1 ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and storename LIKE '%{$keyword}%' ";
	}
	$con .= FX_MERCHANTID ? FX_MERCHANTID :' and merchantid='.$_GPC['merchantid'];
	$ds = pdo_fetchall("select * from" . tablename('fx_store') . "where $con");
	include fx_template('application/query_store');
	exit;
} elseif ($op == 'selectsaler') {
	$con     = "A.uniacid='{$_W['uniacid']}' and A.uid = B.uid ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and (A.nickname LIKE '%{$keyword}%' or B.openid LIKE '%{$keyword}%') ";
	}
	$field  = "A.nickname, avatar, B.openid";
	$inner  = tablename ('mc_members') . "A , " . tablename ('mc_mapping_fans') . "B ";
	$ds = pdo_fetchall("select ".$field." from" . $inner . "where $con");
	include fx_template('application/query_saler');
	exit;
}