<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * paytype.ctrl
 * 支付方式控制器
 */
defined('IN_IA') or exit('Access Denied');
session_start();
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = '支付方式'; //title
$recordid = $_GPC['recordid']; //支付订单ID
//借权支付
//$setting = uni_setting($_W['uniacid'], array('payment', 'recharge'));
$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
if ((intval($setting['payment']['wechat']['switch']) == 2 || intval($setting['payment']['wechat']['switch']) == 3)) {
	$code = $_GPC['code'];
	$scope = $_GPC['scope'];
	
	$uniacid = !empty($setting['payment']['wechat']['service']) ? $setting['payment']['wechat']['service'] : $setting['payment']['wechat']['borrow'];
	$acid = pdo_getcolumn('uni_account', array('uniacid' => $uniacid), 'default_acid');
	$setting = account_fetch($acid);
	$_W['account']['oauth'] = array(
		'key' => $setting['key'],
		'secret' => $setting['secret'],
		'type' => $setting['type'],
		'level' => $setting['level'],
		'acid' => $setting['acid'],
	);
	$oauth_account = WeAccount::create($_W['account']['oauth']);
	$oauth = $oauth_account->getOauthInfo($code);
	$payopenid = $oauth['openid'];
} 
if ((intval($setting['payment']['wechat']['switch']) == 2 || intval($setting['payment']['wechat']['switch']) == 3) && empty($payopenid)) {
	$setting = uni_setting($_W['uniacid'], array('payment', 'recharge'));
	$uniacid = !empty($setting['payment']['wechat']['service']) ? $setting['payment']['wechat']['service'] : $setting['payment']['wechat']['borrow'];
	$acid = pdo_getcolumn('uni_account', array('uniacid' => $uniacid), 'default_acid');
	$url = app_url('member/home');
	$callback = urlencode($url);
	$oauth_account = WeAccount::create($acid);
	$state = 'we7sid-'.$_W['session_id'];
	$forward = $oauth_account->getOauthCodeUrl($callback, $state);
	header('Location: ' . $forward);
}

$creditType = $_GPC['creditType']?$_GPC['creditType']:'pay'; // =recharge为余额充值。
if($creditType=='recharge'){ //余额充值订单
	$goods['gname'] = $pagetitle = '余额充值';
	//$order = pdo_fetch("select * from".tablename('tg_credit1rechargerecord')."where id={$recordid}");
	$order['pay_price'] = $order['num']; // 兼容：无论app还是微信充值 充值金额 都为 $order['pay_price']。
	$_W['_config']['paytype']['balancestatus'] = $setting['helpbuy'] = $_W['_config']['paytype']['deliverystatus'] = 0; //若为余额充值仅允许微信支付，其他支付置空。
}else{ //支付订单
	$_W['merchant']['name'] = $_W['_config']['sname'];
	$order = model_records::getSingleRecords($recordid);
	$activity = model_activity::getSingleActivity($order['activityid'], '*');
	if ($activity['merchantid']){
		$merchant  = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
		$_W['merchant']['name']      = $merchant['name'];
	}
}

$_paydata = array(
	'activityid' => $activity['id'],    //当前活动ID
	'buynum'    => $order['buynum'],   //当前支付订单参加名额
	'token' => $_W['token']
);
$_SESSION['pay'] = $_paydata;//设置验证相关参数SESSION

if($order['status']!=0 && $order['status']!=5)
fx_message("该订单已支付了.",app_url('records/records/list'),'warning'); // 判断订单是否已支付。

if(empty($order['openid'])){ //兼容缓存中openid为空的订单。
	//Util::deleteCache('order', $recordid);
	//$order = model_order::getSingleOrder($recordid, '*');
}
if($order['price'] <= 0) {
	fx_message("支付金额错误,支付金额需大于0元.");
}

if($op =='display'){
	$pay = $setting['payment'];
	if ($pay['credit']['pay_switch'] || $pay['credit']['switch']) {
		$credtis = mc_credit_fetch($_W['member']['uid']);
		$credit_pay_setting = mc_fetch($_W['member']['uid'], array('pay_password'));
		$credit_pay_setting = $credit_pay_setting['pay_password'];
	}
	$params = array(
		'tid'     => $order['orderno'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
		'ordersn' => $order['orderno'],  //收银台中显示的订单号
		'title'   => $activity['title'],          //收银台中显示的标题
		'fee'     => $order['price'],      //收银台中显示需要支付的金额,只能大于 0
		'user'    => $_W['openid'],     //付款用户, 付款的用户名(选填项)
		'module'  => $this->module['name'], //模块名称，请保证$this可用
		'unit'    => $_W['merchant']['name'],
	);
	//$this->pay($params);exit;
	//生成paylog记录
	$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
	if (empty($log)) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'acid' => $_W['acid'],
			'openid' => $_W['openid'],
			'uniontid' => $order['orderno'],
			'tid' => $params['tid'],
			'fee' => $params['fee'],
			'status' => '0',
			'module' => $this->module['name'], //模块名称，请保证$this可用
			'card_fee' => $params['fee'],
			'is_usecard' => '0',
		);
		pdo_insert('core_paylog', $data);
	}
	
	include $this->template('pay/paytype');
}
if ($_W['isajax'] && $op =='ajax') {

}

