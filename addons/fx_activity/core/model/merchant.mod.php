<?php
	
/**
 * [woniu] Copyright (c) 2017/3/23 
 * 商家model
 */
defined('IN_IA') or exit('Access Denied');

function merchant_get_by_params($params = '') {
	global $_W;
	if(!empty($params)){
		$params = ' where '. $params;
	}
	$sql = "SELECT * FROM " . tablename('fx_merchant') . " {$params} ";
	$goods = pdo_fetch($sql);
	return $goods;
}

function merchant_update_by_params($data,$params) {
	global $_W;
	$flag = pdo_update('fx_merchant',$data,$params);
	return $flag;
}

function merchant_get_by_id($id = '') {
	global $_W;
	if(empty($id)){
		return;
	}
	$sql = "SELECT * FROM " . tablename('fx_merchant') . " where uniacid = {$_W['uniacid']} and id = {$id}";
	$merchant = pdo_fetch($sql);
	return $merchant;
}

function  merchant_update_amount($money,$merchantid){
	global $_W;
	if($merchantid){
		$merchant = pdo_fetch("select amount from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid='{$merchantid}' ");
	}
	if(empty($merchant)){
		pdo_insert("fx_merchant_account",array('no_money'=>0,'merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'uid'=>$_W['uid'],'amount'=>$money,'updatetime'=>TIMESTAMP));
	}else{
		pdo_update("fx_merchant_account",array('amount'=>$merchant['amount']+$money),array('merchantid'=>$merchantid));
	}
	return $merchant;
}

function  merchant_update_no_money($money,$merchantid){
	global $_W;
	$f=FALSE;
	if($merchantid){
		$merchant = pdo_fetch("select no_money from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid={$merchantid}");
		if(empty($merchant)){
			pdo_insert("fx_merchant_account",array('no_money'=>0,'merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'uid'=>$_W['uid'],'amount'=>0,'updatetime'=>TIMESTAMP));
		}else{
			$m = $merchant['no_money']+$money;
			if($m<0){
				$f=FALSE;
			}else{
				$f=TRUE;
				pdo_update("fx_merchant_account",array('no_money'=>$merchant['no_money']+$money,'updatetime'=>TIMESTAMP),array('merchantid'=>$merchantid	));
			}
		}
	}
	return $f;
}

function  merchant_get_no_money($merchantid){
	global $_W;
	$merchant = pdo_fetch("select no_money from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid='{$merchantid}' ");
	return $merchant['no_money'];
}

function merchant_check($user) {
	if (empty($user) || !is_array($user)) {
		return false;
	}
	$where = ' WHERE 1 ';
	$params = array();
	if (!empty($user['uid'])) {
		$where .= ' AND `uid`=:uid';
		$params[':uid'] = intval($user['uid']);
	}
	if (!empty($user['uname'])) {
		$where .= ' AND `uname`=:uname';
		$params[':uname'] = $user['uname'];
	}
	if (!empty($user['open'])) {
		$where .= " AND `open`=:open";
		$params[':open'] = intval($user['open']);
	}
	if (empty($params)) {
		return false;
	}
	$sql = 'SELECT id FROM ' . tablename('fx_merchant') . "$where LIMIT 1";
	$record = pdo_fetch($sql, $params);
	if (empty($record)) {
		return false;
	}
	return true;
}