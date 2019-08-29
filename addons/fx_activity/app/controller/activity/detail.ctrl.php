<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * detail.ctrl
 * 活动详情控制器
 */
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = '活动详情';
$activityid = intval($_GPC['activityid']);

if($op =='display'){
	$activity  = model_activity::getSingleActivity($activityid, '*');
	if (empty($activity))fx_message('访问失败', app_url('home/index/display'), 'warning','活动可能没有上线，或者不存在');
	$pagetitle = !empty($activity['pagetitle'])?$activity['pagetitle']:$pagetitle;
	$marketing = model_activity::getMarketing($activityid);//优惠
	$merchant  = model_activity::getActivityMerchant($activity['merchantid']);//读取主办方
	
	$_W['share']['title'] = $activity['sharetitle'] != '' ? $activity['sharetitle'] : $activity['title'];
	$_W['share']['title'] = str_replace('"','\"',str_replace("'","\'",htmlspecialchars_decode($_W['share']['title'])));
	$_W['share']['desc']  = $activity['sharedesc'] != '' ? $activity['sharedesc'] : $activity['intro'];
	$_W['share']['desc']  = str_replace('"','\"',str_replace("'","\'",htmlspecialchars_decode($_W['share']['desc'])));
	$_W['share']['desc']  = preg_replace("/\s/","",$_W['share']['desc']);
	$_W['share']['pic']   = $activity['sharepic'] != '' ? $activity['sharepic'] : $activity['thumb'];
	
	$activity['falsedata']['nickname'] = explode('，',$activity['falsedata']['nickname']);
	$activity['unitstr'] = empty($activity['unitstr'])?'名额':$activity['unitstr'];
	if ($activity['kefu']['switch']){
		$_W['_config']['kefu']['type'] = $activity['kefu']['type'];
		$_W['_config']['kefu']['url']  = $activity['kefu']['url'];
		$merchant['kefuimg'] = tomedia($activity['kefu']['qrcode']);
	}
	
	$activity['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE uniacid = '{$_W['uniacid']}' and `show`=1 ");
	$condition = " activityid = $activityid and status <> 5 and (status <> 0 or paytype = 'delivery')";
	$records = pdo_fetchall ("SELECT * FROM " . tablename ('fx_activity_records') . " WHERE $condition ORDER BY id DESC limit 30");
	//$gender['women'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition and gender='女'");
	//$gender['man'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition and gender='男'");
	//读取规格名额与价格
	if($activity['hasoption']){
		//处理常规价格范围
		$activity['minprice'] = pdo_fetch("SELECT min(aprice) as aprice, min(marketprice) as marketprice FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$activityid);
		$activity['maxprice'] = pdo_fetch("SELECT max(aprice) as aprice, max(marketprice) as marketprice FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$activityid);
		$activity['marketprice'] = $activity['minprice']['marketprice'];
		
		$specsData = model_activity::getNumActivitySpec($activityid,'app'); // 规格
		$options = $specsData[2];
		$specs = $specsData[0];
		
		$usednum = model_records::getJoinNum($activityid,  $options[0]['id']);
		$optionid  = count($specs) > 1 ? 0 : $options[0]['id'];
		$optaprice = $options[0]['aprice'];
		if ($_W['plugin']['card']['config']['card_enable'] && $yearcard['buy'] && $activity['atype']==2 && !$activity['prize']['cardper']['enable'])
		$optaprice = $options[0]['marketprice'];
		$forgnum  = $options[0]['gnum'] > $usednum+$options[0]['falsenum'] ? $options[0]['gnum'] - $usednum - $options[0]['falsenum'] : 0;
		$optgnum  = $options[0]['gnum'];
		
		foreach ($options as &$s) {
			$s['usednum'] = model_records::getJoinNum($activityid, $s['id']);
		}
		//读取规格总名额，总虚拟人数
		$opt['gnum'] = pdo_fetchcolumn("SELECT SUM(gnum) FROM " . tablename('fx_spec_option') . " WHERE activityid = $activityid");
		$opt['nolimit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fx_spec_option') . " WHERE gnum = 0 and activityid = $activityid");
		$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = $activityid");
		if ($opt['nolimit']){
			$activity['gnum'] = 0;
		}else{
			$activity['gnum'] = $opt['gnum'];
		}
		$activity['falsedata']['num'] = $opt['falsenum'] ? $opt['falsenum'] : 0;
	}
	$joinnum = model_records::getJoinNum($activityid) + $activity['falsedata']['num'];
	$uniacid = "uniacid = '{$_W['uniacid']}'";
	//统计报名、收藏、关注数量
	$fansnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_merchant_fans') . " WHERE $uniacid and merchantid='{$merchant['id']}' and follow=1");
	$fansnum = $merchant['followno'] ? $merchant['followno']+$fansnum:$fansnum;
	$favonum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE $uniacid and favo=1 and activityid = $activityid");
	$favonum = $activity['falsedata']['favorite'] ? $favonum+$activity['falsedata']['favorite']:$favonum;
	//判断是否报名、收藏、关注
	$join = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $uniacid and activityid=$activityid and status<>5 and openid='{$_W['openid']}'");
	$favo = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE $uniacid and activityid=$activityid and favo=1 and openid='{$_W['openid']}'");
	$fans = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_merchant_fans') . " WHERE $uniacid and follow=1 and merchantid={$merchant['id']} and uid='{$_W['member']['uid']}'");
	if ($_W['openid']=='o7CHdvlvTW37Kg51Hmycd0y9EmUk'){
		include $this->template ('activity/detail_1');
	}else{
		include $this->template ('activity/detail');
	}
	exit;
}

if ($_W['isajax'] && $op == 'share') {
	$id = intval($_GPC['id']);
	$activity = model_activity::getSingleActivity($id, '*');
	pdo_query("UPDATE ".tablename('fx_activity')." SET trueshare=trueshare+1 WHERE id = :id", array(':id' => $id));
	if (TIMESTAMP > strtotime($activity['joinetime']) || TIMESTAMP > strtotime($activity['endtime'])){//报名结束或者活动结束，不奖励积分;
		die(json_encode(array("result" => 3, "data" => '')));
	}elseif ($_W['_config']['creditstatus'] && $activity['prize']['share_times']>0){
		$credit = intval($activity['prize']['share_credit']);//赠送积分额度
		$creditoff = intval($activity['prize']['creditoff']);//扣除积分额度
		$share_times = intval($activity['prize']['share_times']);//每天分享获取奖励次数
		$credit_type = $_W['_config']['credit_type']?$_W['_config']['credit_type']:1;

		$log['nums'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_credits_record') . ' WHERE to_days(FROM_UNIXTIME(createtime))=to_days(now()) AND uniacid=:uniacid AND uid=:uid AND module=:module AND store_id=:store_id AND clerk_type=:clerk_type', array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid'],':module'=>MODULE_NAME,':store_id'=>$activity['merchantid'],':clerk_type'=>4));
		if ($share_times > $log['nums']) {
			fx_load()->model('credit');
			if ($_W['plugin']['card']['config']['card_enable'] && $yearcard['buy']) {//年卡
				if ($_W['plugin']['card']['config']['credit_double']) {
					$credit = $credit * 2;
					$cardRemark = '，'.$yearcard['name'].'积分奖励翻倍';
				}
			}
			$result = credit_update_credit1($_W['member']['uid'], $credit, "分享获取：" . $credit . '积分', $activity['merchantid'], 4);
			$result ? die(json_encode(array("result" => 1, "data" => '恭喜您获取 ' . $credit . '积分' . $cardRemark))) : die(json_encode(array("result" => 0, "data" => '失败')));
		}else{
			die(json_encode(array("result" => 2, "data" => '您当天分享积分已送完，请明日再领取')));
		}
	}else{
		die(json_encode(array("result" => 3, "data" => '')));
	}
	exit;
}

if($_W['isajax'] && $op == 'favorite'){
	$favo = intval($_GPC['favorite']);
	$activityid = intval($_GPC['activityid']);
	if (!$_W['member']['uid']){
		$arr = array();
		if ($activityid) $arr['activityid'] = $activityid;
		$_SESSION['oauth_url'] = app_url('activity/' . $_GPC['type'] . '/display', $arr);
		die(json_encode(array("result" => 2, "data" => '')));
	}
	$condition = "uniacid = '{$_W['uniacid']}' and activityid = $activityid and (openid = '{$_W['openid']}' or uid='{$_W['member']['uid']}')";
	$favorite = pdo_fetch("SELECT * FROM " . tablename ('fx_activity_favorite') . " WHERE $condition");
	if(!empty($favorite)){
		$where = array('activityid' => $activityid, 'uniacid' => $_W['uniacid'], 'openid' => $_W['openid']);
		$result = pdo_update('fx_activity_favorite', array('favo' => $favo, 'uid'=>$_W['member']['uid']), $where);
		if ($result){
			die(json_encode(array("result" => $result, "data" => $favo ? 0 : 1)));
		}else{
			die(json_encode(array("result" => $result, "data" => $favo)));
		}
		exit;
	}else{
		if (!empty($_W['openid'])){
			$result = pdo_insert('fx_activity_favorite', array (
				'activityid' => $activityid,
				'uniacid'    => $_W['uniacid'],
				'uid'        => $_W['member']['uid'],
				'openid'     => $_W['openid'],
				'favo'       => 1
			));
		}
		if ($result){
			die(json_encode(array("result" => $result, "data" => 0)));
		}else{
			die(json_encode(array("result" => $result, "data" => 1)));
		}
		exit;
	}
}
if($_W['isajax'] && $op == 'read') {
	$id = intval($_GPC['id']);
	pdo_query("UPDATE ".tablename('fx_activity')." SET trueread=trueread+1 WHERE id = :id", array(':id' => $id));
	exit;
}
if($_W['isajax'] && $op == 'getstore') {
	$lat = $_GPC['lat'];
	$lng = $_GPC['lng'];
	$activity  = model_activity::getSingleActivity($activityid, '*');
	if (!$activity['hasstore']){//判断活动门店
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$order = $_W['_config']['location'] ? "distance ASC":'id DESC';
		$where = array();
		$where['merchantid'] = $activity['merchantid'];
		if (!empty($activity['storeids'])) $where['#id#'] = '('.implode(',',$activity['storeids']).')';
		$field = "*,0 AS distance";
		$field = $_W['_config']['location']?"*, ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-lat*PI()/180)/2),2)+COS(".$lat."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$lng."*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance":$field;
		$storesData = Util::getNumData($field, 'fx_store', $where, $order, 1, $psize, 1);
		if (empty($storesData[0])){
			$merchant  = model_activity::getActivityMerchant($activity['merchantid']);//读取主办方
			$stores[0]['lng']       = $merchant['lng'];
			$stores[0]['lat']       = $merchant['lat'];
			$stores[0]['tel']       = $merchant['tel'];
			$stores[0]['address']   = $merchant['address'];
			$stores[0]['storename'] = $merchant['storename'];
			$stores[0]['distance']  = $_W['_config']['location']?getDistance_map($lat, $lng, $merchant['lat'], $merchant['lng']):0;
			$storesData[0] = $stores;
			$storesData[2] = 1;
		}
		$data['list'] = $storesData[0];
		$data['total'] = $storesData[2];
		$data['tpage'] = (empty($psize) || $psize < 0) ? 1 : ceil($storesData[2] / $psize);
		die(json_encode($data));
	}else{
		$stores[0]['lng']       = $activity['lng'];
		$stores[0]['lat']       = $activity['lat'];
		$stores[0]['tel']       = $activity['tel'];
		$stores[0]['address']   = $activity['address'];
		$stores[0]['storename'] = $activity['addname'];
		$stores[0]['distance']  = $_W['_config']['location']?getDistance_map($lat, $lng, $activity['lat'], $activity['lng']):0;
		$data['list'] = $stores;
		$data['total'] = 1;
		$data['tpage'] = 1;
	}
	die(json_encode($data));
}