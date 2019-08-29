<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * activity.ctrl
 * 我的报名控制器
 */
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
$pagetitle = '我的报名';

if($op =='list'){
	$index = $_GPC['index'];
	include $this->template ('records/records_list');
	exit;
}

if($op =='ajax'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = max(1, intval($_GPC['pagesize']));
	$condition = " B.uniacid = '{$_W['uniacid']}' and A.id = activityid and openid = '{$_W['openid']}'";//{$_W['openid']}o7CHdvlvTW37Kg51Hmycd0y9EmUk
	switch($_GPC['status']){
		case 0:$condition .=" and status = 0";break;
		case 1:$condition .=" and (status = 1 or status = 2)";break;
		case 3:$condition .=" and status = 3 ";break;
		case 5:$condition .=" and status = 5";break;
		case 7:$condition .=" and status = 7";break;
		default:'';
	}
	
	$field  = "B.id, activityid, buynum, jointime, A.title, A.merchantid, starttime, endtime, joinstime, joinetime, B.price, B.aprice, paytype, payprice, thumb, status, ishexiao, hasoption, optionid, B.review, switch";
	$inner  = tablename ('fx_activity') . " A, " . tablename ('fx_activity_records') . " B";
	//echo $join;exit;
	$list = pdo_fetchall ("SELECT $field FROM " . $inner . " WHERE $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	//读取规格名额
	//foreach ($list as &$s) {
		//if($s['hasoption']==1 && !empty($s['optionid'])){
			//$option = pdo_fetch("SELECT aprice FROM " . tablename('fx_spec_option') . " WHERE id = " .$s['optionid']);
			//$s['aprice'] = $option['aprice'];
		//}
	//}
	//读取商户信息
	foreach ($list as &$s) {
		if ($s['merchantid']){
			$merchant = model_merchant::getSingleMerchant($s['merchantid'], '*');
			$s['merchant']['name']  = $merchant['name'];
			$s['merchant']['logo'] = tomedia($merchant['logo']);
		}else{
			$s['merchant']['name']  = $_W['_config']['sname'];
			$s['merchant']['logo'] = tomedia($_W['_config']['slogo']);
		}
		$s['switch'] = unserialize($s['switch']);
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . $inner . " WHERE $condition");	
	//array_merge数组拼接
	$data['list']=$list;
	$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($total / $psize);
	$data['total']=$total;
	die(json_encode($data));
	exit;
}

if($op =='detail'){

}

if($op =='cancel'){//取消我的报名
	$activityid = intval($_GPC['activityid']);
	$result = pdo_update ('fx_activity_records', array ('status' => 5) , array ('id' => $_GPC['recordid']));
	$activity = model_activity::getSingleActivity($activityid, '*');
	$credit = $activity['prize']['creditoff'];
	//积分变更，如果符合条件的话
	if ($result && $_W['_config']['creditstatus'] == 1 && $credit > 0){
		fx_load()->model('credit');
		$credit_type = $_W['_config']['credit_type']?$_W['_config']['credit_type']:1;
	}
	
	$url = app_url('records/records/list'); // 取消报名通知
	message::join_cancel($_W['openid'], $activity['title'], $activity['starttime'],$_GPC['recordid'], $url);
	die(json_encode(array("result" => $result)));
	exit;
}

//去支付
if($op =='topay'){
	$recordid = $_GPC['recordid'];
	if($recordid){
		$order = pdo_get('fx_activity_records', array('id' => $recordid));
		if($order['status'] == 0){
			$goods =  pdo_get('fx_activity', array('id' => $order['activityid']));
			if(TIMESTAMP < strtotime($goods['endtime'])){ 
				die(json_encode(array("status" => 1)));
			}else{
				die(json_encode(array("status" => 0, "result" => '活动结束')));
			}
		}else{
			die(json_encode(array("status" => 0, "result" => '订单状态错误')));
		}
	}else{
		die(json_encode(array("status" => 0, "result" => '缺少订单号')));
	}
	exit;
}

if($op =='receipt'){

}

if($op =='tip'){

}
