<?php
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$activityid = intval($_GPC['activityid']);
$midkey = $_GPC['mid'];
$result = $_GPC['result'];
$_W['page']['title'] = '签到结果';
$redirect = 'javascript:wx.closeWindow();';
if (!$activityid)fx_message ('请重新扫描二维码', $redirect, 'warning', '点击确定，可返回微信');
if ($op=="consumption"){
	$activity = model_activity::getSingleActivity($activityid, '*');
	if (!$activity['switch']['signin']){
		fx_message ('非签到时间', $redirect, 'warning', '点击确定，可返回微信');
	}
	$condition = "activityid = $activityid and status <> 5 and status <> 0 and review=1 and openid='{$_W['openid']}'";
	$records = pdo_fetch("select * from".tablename('fx_activity_records')." where $condition");
	if (empty($records) || $activity['midkey']!=$midkey){
		fx_message ('签到失败', $redirect, 'error', '无权限,可能您没有参与当前活动<br>点击确定，可返回微信');
	}
	if (!$records['ishexiao']) {
		$data = array(
			'payprice'  => $records['price'], 
			'status'    => 3,
			'ishexiao'  => 1,
			'veropenid' => $_W['openid'],
			'sendtime'  => date('Y-m-d H:i:s',TIMESTAMP)
		);
		$result = pdo_update('fx_activity_records', $data ,array('orderno'=>$records['orderno']));
		if(!empty($records['merchantid']) && !empty($records['price']) && !$records['ishexiao']){
			pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>2,'detail'=>'二维码核销成功：核销订单号：'.$records['orderno']));
			if($records['paytype']=='wechat' || $records['paytype']=='alipay')//只的微信、支付宝成功支付的才可更新结算金额
			model_merchant::updateNoSettlementMoney($records['price'], $records['merchantid']);//更新可结算金额
		}
		$url = app_url('records/records/list'); // 核销成功通知
		if ($records['ishexiao'] == 0)message::hexiao_notice($_W['openid'], $activity, $url);
	}
	$time1 = date('Y-m-d');
	$time2 = !empty($records['sendtime']) ? substr($records['sendtime'],0,10) : '';
	//签到
	if ($time1 != $time2) {
		$data = array(
			'signin'    => $records['signin']+1,
			'sendtime'  => date('Y-m-d H:i:s',TIMESTAMP)
		);
		pdo_update('fx_activity_records', $data ,array('orderno'=>$records['orderno']));
		//积分奖励
		if ($_W['_config']['creditstatus'] == 1 && $activity['prize']['sign_credit'] > 0) {
			$credit = intval($activity['prize']['sign_credit']);//赠送积分额度
			$credit_type = $_W['_config']['credit_type']?$_W['_config']['credit_type']:1;
			fx_load()->model('credit');
			$result = credit_update_credit1($_W['member']['uid'], $credit, "签到获取" . $credit . '积分', $activity['merchantid']);
			fx_message ('签到成功，并获取 <font color="#FF0000">' . $credit . '</font> 积分', $redirect, 'success', '点击确定，可返回微信');
		}
		fx_message ('签到成功', $redirect, 'success', '点击确定，可返回微信');
	} else {
		fx_message ('当天不可重复签到', $redirect, 'error', '点击确定，可返回微信');
	}
}