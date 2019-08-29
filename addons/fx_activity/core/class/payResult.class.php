<?php 

// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020.
// +----------------------------------------------------------------------
// | Describe: 支付结果处理类
// +----------------------------------------------------------------------
// | Author: woniu
// +----------------------------------------------------------------------

class payResult{
	/** 
	* 异步支付结果回调 ，处理业务逻辑
	* 
	* @access public
	* @name  
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function payNotify($params){
		global $_W;
		if($params['result'] == 'success') {
			$records = pdo_get('fx_activity_records', array('orderno' => $params['tid'], 'uniacid' => $params['uniacid']));
			$activity = model_activity::getSingleActivity($records['activityid'], '*');
			$_W['oauth']['uniacid'] = $params['user'] != $records['openid'] ? '':$_W['uniacid'];
			$_W['uniacid'] = $params['uniacid'];
			
			if(!empty($records)) {
				$data = array(
					'paytype' => $params['type'],
					'status' => 1,
					'paytime' => date('Y-m-d H:i:s',TIMESTAMP),
					'payprice' => $params['fee']
				);
				$fee = $params['fee'];
				if ($params['from'] == 'notify'){
					//Array ( [transaction_id] => 4001602001201611230627952393) [is_usecard] => 0 [card_type] => 0 [card_fee] => 0.10 [card_id] => 0)
					if(!empty($params['tag'])) {
						$params['tag'] = iunserializer($params['tag']);	
						$data['uniontid'] = $params['uniontid'];					
						$data['transid'] = $params['tag']['transaction_id'];
					}else{
						fx_message ('抱歉，请正确支付您的订单！', '', 'error');
					}
				}
				
				if ($records['status'] == 0) {
					//$listNum = pdo_fetchall("SELECT hexiaoma as num FROM " . tablename ('fx_activity_records') . " WHERE activityid = ".$activity['id']);
					//$data['hexiaoma'] = createNumber($listNum);
					$data['hexiaoma'] = createRandomNumber(8);
					
					if(!empty($activity['merchantid'])){
						model_merchant::updateAmount($fee, $activity['merchantid'],$records['id'],1,'订单支付成功');  //主办方进账
						pdo_insert("fx_merchant_money_record",array('merchantid'=>$activity['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>1,'detail'=>'支付成功<br>订单号：' . $records['orderno']));
					}
					$pay_result = pdo_update('fx_activity_records', $data, array('orderno' => $params['tid'], 'uniacid' => $params['uniacid']));
					
					//生成二维码
					createQrcode::creategroupQrcode($params['tid']);
					if (!$activity['switch']['joinreview']) {//活动不开启审核发送通知
						// 报名成功通知
						message::join_success($records['openid'], $activity, $records['id'], app_url('records/records/list'));
						if($activity['smsswitch']){//短信通知
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
						message::join_review($records['openid'], $activity, 2, app_url('records/records/list'));
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
											
					//积分变更
					if ($_W['_config']['creditstatus'] == 1){
						fx_load()->model('credit');
						if($activity['prize']['credit'] > 0){
							$credit = $orderMarket['credit_double'] ? $activity['prize']['credit'] * 2 : $activity['prize']['credit'];
							$credit_result = credit_update_credit1($records['uid'], $credit, "报名：增加" . $credit . '积分', $activity['merchantid']);
						}		
						if($orderMarket['deduct']){
							$deduct = $orderMarket['deduct'];
							if($deduct[0]) credit_update_credit1($records['uid'], -1 * $deduct[0], "积分抵扣：减少" . $deduct[0] . '积分', $activity['merchantid']);
						}						
						if($activity['costcredit']){
							credit_update_credit1($records['uid'], -1 * $activity['costcredit'], "参与活动消耗：减少" .$activity['costcredit'] . '积分', $activity['merchantid']);
						}
					}
				}
				
				if ($params['type'] == 'credit'){
					header("location:".app_url('pay/success/display', array('recordid' => $records['id'])));
				}
			}
		}
	}
	/** 
	* 函数的含义说明 
	* 
	* @access public
	* @name 方法名称 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function payReturn($params){
		global $_W;

	}
	/** 
	* 函数的含义说明 
	* 
	* @access public
	* @name 方法名称 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getPayData($params,$order_out,$goodsInfo){
	 	global $_W;
	
	}
}



?>