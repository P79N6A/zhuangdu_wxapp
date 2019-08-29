<?php
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$orderno = $_GPC['mid'];
$result = $_GPC['result'];
$pagetitle = $result =='success' ?'核销结果' :'确认核销';
$records = pdo_fetch("select * from".tablename('fx_activity_records')."where uniacid='{$_W['uniacid']}' and orderno = '{$orderno}'"); // 二维码是根据orderno生成的
$activity = model_activity::getSingleActivity($records['activityid']);
//$all_stores = pdo_fetchall("select id from" . tablename('fx_store') . "where uniacid='{$_W['uniacid']}' and status=1");
$ishexiao_member = FALSE;
$store = array();
$store_ids=array();
//if(!empty($goods['hexiao_id'])) $store_ids = $goods['hexiao_id']; 
if(empty($store_ids)) {

}
	
$con = '';
if(!empty($goods['merchantid'])){
	//$con .=  " and merchantid={$goods['merchantid']}";
}

if ($op=="display"){
	 //*判断是否是核销人员*/
	$hexiao_member = pdo_fetch("select * from".tablename('fx_saler')."where openid='{$_W['openid']}' and  uniacid='{$_W['uniacid']}' and status=1  {$con} ");
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
		if(!empty($hexiao_member['merchantid']) && !empty($goods['merchantid'])){
			if($hexiao_member['merchantid'] != $goods['merchantid']){
				//$ishexiao_member = FALSE;
			}
		}
	}
	
	//门店信息
	foreach($store as$key=>$value){
		//if($value) $stores[$key] =  pdo_fetch("select * from".tablename('fx_store')."where id ='{$value}' and uniacid='{$_W['uniacid']}'");
	}	
	include $this->template ('records/check');
}

if($_W['isajax'] && $op=="check"){
	$storeid = $_GPC['storeid'];
	if($records['ishexiao']==1){
		die(json_encode(array('errno'=>1,'message'=>'该报名已核销！')));
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
			$url = app_url('records/records/list'); // 报名成功通知
			message::hexiao_notice($records['openid'], $activity, $url);
			die(json_encode(array('errno'=>0,'message'=>'核销成功！')));
		}else{
			die(json_encode(array('errno'=>2,'message'=>'核销失败！')));
		}
	}
	
}