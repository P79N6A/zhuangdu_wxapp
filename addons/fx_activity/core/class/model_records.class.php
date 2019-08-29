<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020.
// +----------------------------------------------------------------------
// | Describe: 报名操作模型类
// +----------------------------------------------------------------------
// | Author: woniu
// +----------------------------------------------------------------------
 class model_records
 {
	/** 
	* 获取单条报名信息
	* 
	* @access static
	* @name getSingleRecords 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleRecords($id){
		global $_W;
		$records = pdo_get('fx_activity_records', array('uniacid'=>$_W['uniacid'],'id' => $id));
		$records['marketing'] = unserialize($records['marketing']);
		return $records;
	}
	
	static function getNumFormData($id){
		global $_W;
		$formdata = pdo_fetchall("select * from " . tablename('fx_form_data') . " where recordid=".$id." order by id asc");
		return $formdata;
	}
	
	static function getSingleFormData($id,$formid){
		global $_W;
		$formdata = pdo_get("fx_form_data",array('recordid' => $id,'formid' => $formid));
		return $formdata;
	}
	/** 
	* 统计当前已报名数量 
	*/
	static function getJoinNum($id, $optionid = 0){
		global $_W;
		$condition = "uniacid={$_W['uniacid']} and activityid = $id and status <> 5 and (status <> 0 or paytype = 'delivery')";
		$condition.= $optionid ? " and optionid = $optionid" :'';
		$joinnum = pdo_fetchcolumn("SELECT SUM(buynum) FROM " . tablename('fx_activity_records') . " WHERE $condition");
		$joinnum = $joinnum?$joinnum:0;
		return $joinnum;
	}
	
	/** 
	* 单个订单退款 
	* 
	* @access static
	* @name refundMoney 
	* @param $orderno  订单号
	* @param $money    退款金额
	* @param $refund_reason  退款原因
	* @param $type     退款类型
	* @return array 
	*/  
	static function refundMoney($id,$money,$refund_reason,$type=5){
		global $_W;	
		$records  = model_records::getSingleRecords($id, '*');
		$activity = model_activity::getSingleActivity($records['activityid'], '*');
		
		if($records['paytype']=='balance')$records['transid']='余额支付';
		if($money<=0 || $records['price']<=0 || empty($records['transid'])){
			pdo_update("fx_activity_records", array('status'=>7), array('id'=>$records['id']));
			Util::deleteCache('records', $id);
			$res['status'] = false;
			$res['message'] = '退款额度必须大于0元';
			$res['error_code'] = '余额退款失败';
			return $res;
		}
		if ($records['merchantid'] && $records['ishexiao']){//已参与报名用户特殊情况退款需要核对主办方可结算额度
			$no_money = model_merchant::getNoSettlementMoney($records['merchantid']);
			if ($no_money < $money) {
				Util::deleteCache('records', $id);
				$res['status'] = false;
				$res['message'] = '可结算额度不足';
				$res['error_code'] = '余额退款失败';
				return $res;
			}
		}
		
		$data2 = array('refund_id'=>'','merchantid' => $activity['uid'], 'transid' => $records['transid'], 'createtime' => TIMESTAMP, 'status' => 0, 'type' => $type, 'activityid' => $records['activityid'], 'recordid' => $records['id'], 'payfee' => $records['price'], 'refundfee' => $money, 'refundername' => $records['realname'], 'refundermobile' => $records['mobile'], 'activityname' => $activity['title'], 'uniacid' => $_W['uniacid']);
		pdo_insert('fx_refund_record', $data2);
		
		if($records['paytype'] == 'balance'){ //余额支付
			load()->model('mc');
			$uid = mc_openid2uid($records['openid']);
			if(mc_credit_update($uid, 'credit2', $money, array($_W['uid'], '余额退款操作',MODULE_NAME, 0, 0, 2)));
			$refund = TRUE;
		}elseif($records['paytype'] == 'wechat'){ //微信支付
			$pay = new WeixinPay;
			$arr = array('transid'=>$records['transid'],'totalmoney'=>$records['price'],'refundmoney'=>$money);
			$data = $pay -> refund($arr);
			if($data['err_code'] == 'NOTENOUGH') {
				$arr['refund_account']=2;
				$data = $pay -> refund($arr);
			}
			if (empty($data)) {
				$data['err_code_des'] = '证书配置不正确';
				$data['err_code']     = 'ERROR';
			}
			if(!empty($data['refund_id']) && $data['return_code']=='SUCCESS' && $data['result_code']=='SUCCESS') $refund = TRUE;
		}

		if($refund){
			//主办方减帐
			if(!empty($records['merchantid'])){
				model_merchant::updateAmount(0-$money, $records['merchantid'],$records['id'],1,'订单退款');
				if($records['ishexiao'])
				model_merchant::updateNoSettlementMoney(0-$money, $records['merchantid']);//更新可结算金额
				pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$money,'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>5,'detail'=>'退款<br>订单号'.$records['orderno']));
			}
			pdo_update("fx_activity_records", array('status'=>7), array('id'=>$records['id']));
			pdo_update('fx_refund_record', array('status' => 1, 'refund_id' => $records['paytype']=='balance'?'余额退款':$data['refund_id']), array('recordid' => $records['id']));
			$url = app_url('records/records/detail',array('id'=>$id));
			$refund_reason=empty($refund_reason)?'报名取消':$refund_reason;
			//message::refund_success($records['orderno'], $records['openid'], $money, $url,$refund_reason);
			$res['status'] = true;					
			$res['message'] = '退款成功';		
			$records['time'] = date("Y-m-d H:i:s",time());
			file_put_contents(FX_DATA . "/refundSuccess.log", var_export($records, true).PHP_EOL, FILE_APPEND);			
		}else{
			pdo_update('fx_refund_record', array('status' => 0, 'refund_id' => $records['paytype']=='balance'?'余额退款':$data['err_code_des']), array('recordid' => $records['id']));
			$res['status'] = false;
			$res['message'] = $records['paytype']=='balance'?'余额退款失败':$data['err_code_des'];
			$res['error_code'] = $records['paytype']=='balance'?'余额退款失败':$data['err_code'];
			$records['time'] = date("Y-m-d H:i:s",time());
			file_put_contents(FX_DATA . "/refundFail.log", var_export($records, true).PHP_EOL, FILE_APPEND);			
		}
		return $res;
	}
	/** 
	* 获取多个退款订单
	* 
	* @access static
	* @name getNumRefundRecords 
	* @param $num  个数
	* @return array 
	*/  
	static function getNumRefundRecords($num){
		global $_W;
		if($num==0){
			return pdo_fetchall("select price,status,transid,pay_type,orderno,id from".tablename("fx_activity_records")."where uniacid={$_W['uniacid']} and status=6");
		}else{
			return pdo_fetchall("select price,status,transid,pay_type,orderno,id from".tablename("fx_activity_records")."where uniacid={$_W['uniacid']} and status=6 ORDER BY ptime DESC " . "LIMIT 0,". $num);
		}
	}
	
	/** 
	* 更改订单为取消
	* 
	* @access static
	* @name cancelDoNotPayOrder 
	* @param $orderinfo  订单信息
	* @return  
	*/  
	static function cancelDoNotPayOrder($orderinfo){
		global $_W;
		if($orderinfo['status'] != 0) return false; //不是待支付的订单
		$res = pdo_update('fx_activity_records',array('status'=>5),array('id'=>$orderinfo['id'],'status'=>0));
		return $res;
	}
	/** 
	* 处理报名生成时活动优惠 
	* 
	* @access static
	* @name getafterMarketing 
	* @param $pay_price  处理前 名额数量*价格（不包括运费） 
	* @param $num  名额数量
	* @param $activityid  活动ID 
	* @param $shouldFreight  应该支付的运费 
	* @param $deduct  是否抵扣 
	* @return array 
	*/  
	static function getafterMarketing($pay_price,$num,$activityid,$deduct,$yearcard){
		global $_W;
		$marketing = model_activity::getMarketing($activityid); //获取营销参数
		$m1 = $m2 = $m3 = $m4 = FALSE;
		$max = $p = $cardReduce = 0;
		$orderMarket=array();
		if(empty($marketing[2])){ //非VIP
			if($marketing[0]){ //折扣
				foreach($marketing[0] as $key => $value){
					if($num >= $value['meet']){
						$p = $value['meet']>$max?$value['give']:$p;
						$max = $value['meet']>$max?$value['meet']:$max;
						$m1 = TRUE;
					}
				}
				$orderMarket['discount'] = serialize(array($max,$p));
				$pay_price = $m1 ? sprintf("%.2f", $pay_price * $p * 0.1) : $pay_price;
			}elseif($marketing[1]){ //满减
				foreach($marketing[1] as $key => $value){
					if($num >= $value['meet']){
						$p = $value['meet']>$max?$value['give']:$p;
						$max = $value['meet']>$max?$value['meet']:$max;
						$m2 = TRUE;
					}
				}
				$max=sprintf("%.2f", $max);
				$p=sprintf("%.2f", $p);
				$orderMarket['enough'] = serialize(array($max,$p));
				$pay_price = $m2 ? $pay_price - $p : $pay_price;
			}
		
		}else{ //VIP级别
			$groupid = 0;
			foreach($marketing[2] as $key => $value){
				$group1 = pdo_get('mc_groups', array('uniacid' => $_W['uniacid'], 'groupid' => $value['groupid']), array('groupid', 'credit'));
				$group2 = pdo_get('mc_groups', array('uniacid' => $_W['uniacid'], 'groupid' => $_W['member']['groupid']), array('groupid', 'credit'));
				if ($_W['member']['groupid'] == $value['groupid']){
					if($value['discount']){
						$p = $value['discount'];
						$type = 1;
					}elseif($value['money']){
						$p = sprintf("%.2f", $value['money']);
						$type = 2;
					}
					$groupid = $value['groupid'];
					$m3 = TRUE;
				}
			}
			if ($type==1){
				$pay_price = sprintf("%.2f", $pay_price * $p * 0.1);
			}elseif ($type==2){
				$pay_price = $pay_price >= $p ? sprintf("%.2f", $pay_price - $p) : sprintf("%.2f", 0);
			}
			$orderMarket['vip'] = array('type' => $type, 'groupid' => $groupid);
		}
		
		if (!empty($yearcard)){//年卡打折
			$cardReduce = sprintf("%.2f", $pay_price * (1-$yearcard['percent'] * 0.1));
			$pay_price = sprintf("%.2f", $pay_price * $yearcard['percent'] * 0.1);
		}
		
		if($marketing[3]['deduct']){ //积分抵扣
			$m4 = TRUE;
			$credit1 = $_W['member']['credit1'];
			$money = $marketing[3]['money']; //1积分抵扣多少钱
			$singleMax=$marketing[3]['deduct']; // 单件最多抵扣
			if($marketing[3]['manydeduct']){ //累计抵扣
				$manydeduct=$num*$singleMax; //累计可以抵扣金额
				if($money*$credit1 >= $manydeduct) {//有足够积分
					$deductMoney = sprintf("%.2f",$manydeduct);
					$deductCredit = $manydeduct/$money;
				}else{
					$deductMoney = sprintf("%.2f",$money*$credit1);
					$deductCredit = $credit1;
				}
			}else{ //只抵扣一件商品
				if($money*$credit1 >= $singleMax) {//有足够积分
					$deductMoney = sprintf("%.2f",$singleMax);
					$deductCredit = $singleMax/$money;
				}else{
					$deductMoney = sprintf("%.2f",$money*$credit1);
					$deductCredit = $credit1;
				}
			}
			$resultCredit = $pay_price >= $deductMoney ? $deductCredit : $pay_price/$money;
			$resultMoney = $pay_price >= $deductMoney ? $deductMoney : $pay_price;
			if($deduct){
				$pay_price = $pay_price - $resultMoney;
			}
			$orderMarket['deduct'] = array($resultCredit, $resultMoney);
		}
		return array(
				'pay_price'=>$pay_price,
				'm1'=>$m1,
				'm2'=>$m2,
				'm3'=>$m3,
				'm4'=>$m4,
				'max'=>$max,
				'p'=>$p,
				'cardReduce'=>$cardReduce,
				'deductMoney'=>$deductMoney,
				'deductCredit'=>$deductCredit,
				'orderMarket'=>$orderMarket,				
		); 
	}

}