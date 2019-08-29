<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * success.ctrl
 * 支付成功控制器
 */
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$_W['page']['title'] = '支付结果';//这里的标题借用系统全局变量解决外部方法获取不到标题的问题
if($op =='display'){
	$records = model_records::getSingleRecords($_GPC['recordid']);
	$activity = model_activity::getSingleActivity($records['activityid'], '*');
	$_W['lottery'] = $activity['prize']['lottery'];//抽奖
	
	if ($_W['_config']['creditstatus'] == 1 && $activity['prize']['credit'] > 0){
		$credit = $orderMarket['credit_double'] ? $activity['prize']['credit'] * 2 : $activity['prize']['credit'];
		$cardRemark = $orderMarket['credit_double'] ? '，' . $yearcard['name'] . '积分奖励翻倍' : '';
		$credit_remark = '<br>系统奖励您<font color=" color="#FF7B33">' . $credit . '</font>积分'.$cardRemark;
		
	}
	$tmplmsg = $activity['tmplmsg'];
	$remark  = empty($tmplmsg['joinremark']) ? $activity['title'] : $tmplmsg['joinremark'];
	
	if ($records['payprice']){
		$msg_title = '支付成功<br>实付:<em>'.$_GPC['payprice'].'</em>元';
	}else{
		$msg_title = '恭喜，你已报名成功';
	}
	fx_message($msg_title, app_url('records/records/list'), 'success', $remark . $credit_remark);
}
if($op =='delivery'){
	if(empty($_SESSION['pay']))fx_message("页面过期，请刷新页面", '', 'error');
	$params = @json_decode(base64_decode($_GPC['params']), true);
	$records = pdo_get('fx_activity_records', array('orderno' => $params['tid']));
	//验证名额，每次进入加载剩余名额数量、及规格
	$activity = model_activity::getSingleActivity($_SESSION['pay']['activityid'], '*');
	if ($activity['hasoption']){//规格选项
		$option = model_activity::getSingleActivityOption($records['optionid']);
		$activity['falsedata']['num'] = $option['falsenum'] ? $option['falsenum'] : 0;
	}
	$gnum = $option['gnum']?$option['gnum']:$activity['gnum'];
	if ($gnum > 0){
		$joinnum = model_records::getJoinNum($activity['id'], $records['optionid']) + $activity['falsedata']['num'];
		if($joinnum >= $gnum) {
			fx_message("很遗憾！名额已经满了", '', 'error');
		}elseif($joinnum + $_SESSION['pay']['buynum'] > $gnum){
			fx_message("当前活动仅剩 " . ($gnum - $joinnum) . ' 个名额', '', 'error');
		}
	}
	//保存修改信息
	//$listNum = pdo_fetchall("SELECT hexiaoma as num FROM " . tablename ('fx_activity_records') . " WHERE activityid = ".$activity['id']);
	//$hexiaoma = createNumber($listNum);
	$hexiaoma = createRandomNumber(8);
	$data = array('paytype' => $op,'hexiaoma'=>$hexiaoma);
	$result = pdo_update('fx_activity_records', $data, array('orderno' => $params['tid'], 'uniacid' => $_W['uniacid']));
	if ($result){
		//积分变更
		if ($_W['_config']['creditstatus'] == 1){
			if($activity['costcredit']){
				fx_load()->model('credit');
				credit_update_credit1($_W['member']['uid'], -1 * $activity['costcredit'], "参与活动消耗：减少" . $activity['costcredit'] . '积分', $activity['merchantid']);
			}
		}
		//二维码
		createQrcode::creategroupQrcode($params['tid']);
		if (!$activity['switch']['joinreview']) {//活动不开启审核发送通知
			$url = app_url('records/records/list'); // 报名成功通知
			message::join_success($_W['openid'], $activity, $records['id'], $url);		
			if($activity['smsswitch']){//发送短信
				$smsparams=array(
					'product' => $_W['_config']['sname'],
					'item'    => $activity['title'],
					'name'    => $records['realname'],
					'timestr' => date('m月d日 H:i',strtotime($activity['starttime'])),
					'idcode'  => $data['hexiaoma']
				);
				$template_id = empty($activity['smsnotify']) ? $_W['_config']['sms_notify'] : $activity['smsnotify'];
				sendSMS($records['mobile'], $smsparams, $template_id, $_W['_config']['sms_type']);
			}
		}else{
			//审核通知
			message::join_review($_W['openid'], $activity, 2, app_url('records/records/list'));
		}

		if ($_W['_config']['mmsg']){//管理通知
			if ($activity['merchantid'])
			$merchant = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
			$openids = $activity['openids'];
			$openids = !empty($openids) ? $openids : unserialize($merchant['messageopenid']);
			$openids = !empty($openids) ? $openids : $_W['_config']['openids'];
			if (!empty($openids)){
				foreach($openids as $key=> $value){
					message::admin_notice($value, $activity, $records['id'], '');
				}
			}
		}
	}
	$tmplmsg = $activity['tmplmsg'];
	$remark  = empty($tmplmsg['joinremark'])?$activity['title']:$tmplmsg['joinremark'];
	fx_message('恭喜，你已报名成功<br>付款方式：<em>线下付款</em>', app_url('records/records/list'), 'success', $remark);
}
if($op =='activity_ajax'){

}