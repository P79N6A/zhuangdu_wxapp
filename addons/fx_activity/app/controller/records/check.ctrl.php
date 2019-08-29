<?php
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$orderno = $_GPC['mid'];
$result = $_GPC['result'];
$pagetitle = $result =='success' ?'核销结果' :'确认核销';
$records = Util::getSingelData('*', 'fx_activity_records', array('orderno'=>$orderno));// 二维码是根据orderno生成的
if (!in_array($records['status'], array('1','2','3'))){
	fx_message ('此订单状态不可核销', '', 'warning','点击确定返回');
}
$activity = model_activity::getSingleActivity($records['activityid'], '*');
$ishexiao_member = FALSE;
$store = array();
$store_ids=array();
$_W['merchant']['id']        = 0;
$_W['merchant']['uid']       = 0;
$_W['merchant']['name']      = $_W['_config']['sname'];
$_W['merchant']['logo']      = $_W['_config']['slogo'];
$_W['merchant']['detail']    = $_W['_config']['detail'];
$_W['merchant']['lng']       = $_W['_config']['lng'];
$_W['merchant']['lat']       = $_W['_config']['lat'];
$_W['merchant']['mobile']    = $_W['_config']['mobile'];
$_W['merchant']['address']   = $_W['_config']['address'];
$_W['merchant']['storename'] = $_W['_config']['storename'];
$_W['merchant']['followno']  = $_W['_config']['followno'];
if ($activity['merchantid']){
	$merchant  = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
	$_W['merchant']['id']        = $merchant['id'];
	$_W['merchant']['uid']       = $merchant['uid'];
	$_W['merchant']['name']      = $merchant['name'];
	$_W['merchant']['logo']      = $merchant['logo'];
	$_W['merchant']['detail']    = $merchant['detail'];
	$_W['merchant']['lng']       = $merchant['lng'];
	$_W['merchant']['lat']       = $merchant['lat'];
	$_W['merchant']['mobile']    = $merchant['linkman_mobile'];
	$_W['merchant']['address']   = $merchant['address'];
	$_W['merchant']['storename'] = $merchant['storename'];
	$_W['merchant']['followno']  = $merchant['followno'];
}

if ($activity['hasstore']){//判断位置是否活动中定义
	$_W['merchant']['lng']       = $activity['lng'];
	$_W['merchant']['lat']       = $activity['lat'];
	$_W['merchant']['mobile']    = $activity['tel'];
	$_W['merchant']['address']   = $activity['address'];
	$_W['merchant']['storename'] = $activity['addname'];
}elseif (!empty($activity['storeids'])){//判断活动门店
	$stores = model_activity::getNumActivityStore($activity['storeids']);
	$store_ids = $activity['storeids'];
}
if(empty($store_ids)) {
	//$all_stores = pdo_fetchall("select id from" . tablename('fx_store') . "where uniacid='{$_W['uniacid']}' and status=1");
	//foreach($all_stores as $key=>$value){
		//$store_ids[] = $value['id'];
	//}
}
$con = '';
if(!empty($activity['merchantid'])){
	$con .=  " and merchantid={$activity['merchantid']}";
}else{
	$con .=  " and merchantid=0";
}

if ($op=="display"){
	 //*判断是否是核销人员*/
	if (!empty($_W['openid'])){
		$hexiao_member = pdo_fetch("select * from " . tablename('fx_saler')." where INSTR(`openid`, '{$_W['openid']}') and  uniacid='{$_W['uniacid']}' and status=1 {$con} ");
		if($hexiao_member){
			if($hexiao_member['storeid']==''){
				$store = $store_ids;
				$ishexiao_member = TRUE;
			}else{
				$hexiao_ids = explode(',', substr($hexiao_member['storeid'],0,strlen($hexiao_member['storeid'])-1)); //核销员属于门店的id
				foreach($hexiao_ids as$key=> $value){
					if(in_array($value,$store_ids)){
						$store[] = $value;
						$ishexiao_member = TRUE;
					}
				}
			}
	
			if(!empty($hexiao_member['merchantid']) && !empty($activity['merchantid'])){
				if($hexiao_member['merchantid'] != $activity['merchantid']){
					$ishexiao_member = FALSE;
				}
			}
		}else{
			$ishexiao_member = FALSE;
		}
	}else{
		$ishexiao_member = FALSE;
	}
	//门店信息
	foreach($store as$key=>$value){
		if($value) $stores[$key] =  pdo_fetch("select * from".tablename('fx_store')."where id ='{$value}' and uniacid='{$_W['uniacid']}'");
	}
	$cond = " uniacid = '{$_W['uniacid']}' and activityid = {$records['activityid']} and (status in(1,2,3) or (status=0 and paytype='delivery'))";
	$NO = pdo_fetchcolumn("SELECT rownum FROM (SELECT (@rownum:=@rownum+1) AS rownum, a.* FROM ".tablename('fx_activity_records')." a, (SELECT @rownum:= 0) r WHERE $cond ORDER BY a.`id` ASC) AS b  WHERE id={$records['id']}");
	include $this->template ('records/check');
}

if($_W['isajax'] && $op=="check"){
	$storeid = $_GPC['storeid'];
	if($records['ishexiao']==1){
		die(json_encode(array('errno'=>1,'message'=>'该报名已核销！')));
	}elseif($records['status'] > 2 || ($records['paytype'] != 'delivery' && $records['status'] == 0)){
		die(json_encode(array('errno'=>1,'message'=>'报名状态错误！')));
	}else{
		$data = array(
			'payprice' => $records['price'], 
			'status'=>3,
			'ishexiao'=>1,
			'veropenid' => $_W['openid'],
			'sendtime'=>date('Y-m-d H:i:s',TIMESTAMP)
		);
		$result = pdo_update('fx_activity_records', $data ,array('orderno'=>$orderno));
		if($result){
			if(!empty($records['merchantid']) && !empty($records['price'])){
				pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>2,'detail'=>'二维码核销<br>订单号：'.$records['orderno']));
				if($records['paytype']=='wechat' || $records['paytype']=='alipay')//只有微信、支付宝成功支付的才可更新结算金额
				model_merchant::updateNoSettlementMoney($records['price'], $records['merchantid']);//更新可结算金额
			}
			//积分奖励
			if ($_W['_config']['creditstatus'] == 1 && $activity['prize']['sign_credit'] > 0) {
				$credit = intval($activity['prize']['sign_credit']);//赠送积分额度
				$credit_type = $_W['_config']['credit_type']?$_W['_config']['credit_type']:1;
				fx_load()->model('credit');
				$result = credit_update_credit1($_W['member']['uid'], $credit, "核销获取" . $credit . '积分', $activity['merchantid']);
			}
			Util::deleteCache('records', $records['id']);
			$url = app_url('records/records/list'); // 报名成功通知
			message::hexiao_notice($records['openid'], $activity, $url);
			die(json_encode(array('errno'=>0,'message'=>'核销成功！')));
		}else{
			die(json_encode(array('errno'=>2,'message'=>'核销失败！')));
		}
	}
	
}