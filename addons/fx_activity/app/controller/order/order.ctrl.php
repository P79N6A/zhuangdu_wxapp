<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * order.ctrl
 * 报名订单控制器
 */
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle  = '报名管理';
$activityid = intval($_GPC['activityid']);
$keyword    = trim($_GPC['keyword']);

if($op =='display'){
	$activity  = model_activity::getSingleActivity($activityid, '*');
	if ($activity['merchantid']!=MERCHANTID){
		fx_message ('无权限访问', '', 'warning','点击确定返回');
	}	
	include $this->template ('order/order_list');
	exit;
}

if($op =='detail'){
	global $_W;
	$pagetitle  = '报名详情';
	$id = intval($_GPC['id']);
	$type     = trim($_GPC['type']);
	$records  = model_records::getSingleRecords($id, '*');
	if ($records['merchantid']!=MERCHANTID && $records['openid']!=$_W['openid']){
		fx_message ('无权限访问', '', 'warning','点击确定返回');
	}	
	$activity = model_activity::getSingleActivity($records['activityid'], '*');
	$merchant = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
	
	$formdata = model_records::getNumFormData($records['id']);
	$formdata_common = Util::getSingelData('*', 'fx_form_data_common', array('rid'=>$records['id']));
	$forms = model_activity::getNumActivityForm($records['activityid']);
	
	include $this->template ('order/order');
	exit;
}

if($_W['isajax'] && $op =='getOrder'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$status = trim($_GPC['status']);
	$review = trim($_GPC['review']);
	
	$where = array('activityid' => $activityid);
	
	if ($status!='') {
		if ($status==1 || $status==2){
			$where['#status'] = "(1,2)";
		}else{
			$where['status'] = $status;
		}
	}
	if ($review!='') $where['review'] = $review;
	if (!empty($keyword)){
		$where['sql'][] = "(INSTR(`realname`, '$keyword') or INSTR(`mobile`, '$keyword') or INSTR(`nickname`, '$keyword'))";
	}
	
	$order = "id DESC";
	$orderData = Util::getNumData('*', 'fx_activity_records', $where, $order, $pindex, $psize, 1);
	foreach ($orderData[0] as &$s) {
		$user_info = mc_fetch_one($s['uid']);
		$s['realname'] = empty($s['realname']) ? $user_info['nickname'] : $s['realname'];
		$s['avatar'] = tomedia($user_info['avatar']);
		$marketing = unserialize($s['marketing']);
		$s['market_price'] = $marketing['market_price'] ? $marketing['market_price'] : 0;
	}
	$data['list'] = $orderData[0];
	$data['total'] = $orderData[2];
	$data['tpage'] = (empty($psize) || $psize < 0) ? 1 : ceil($orderData[2] / $psize);
	die(json_encode($data));
	exit;
}
if($_W['ispost'] && $op =='refund'){
	$rid = intval($_GPC['rid']);
	$result = pdo_update('fx_activity_records', array('status'=>6), array('id' => $rid));
	if($result){
		$records = model_records::getSingleRecords($rid);
		$url = app_url('order/order/detail',array('type'=>'u', 'id'=>$rid)); // 通知
		message::refund($records['openid'], $records, $url);
	}
	die(json_encode(array('errno' => $result ? 0 : 1)));
	exit;
}