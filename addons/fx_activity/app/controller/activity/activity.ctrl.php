<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * index.ctrl
 * 首页控制器
 */
defined('IN_IA') or exit('Access Denied');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = !empty($_W['_config']['sname']) ? $_W['_config']['sname'] : '活动首页';

if($op =='display'){
	$keyword = $_GPC['keyword'];
	/*顶部菜单栏*/
	$pid  = intval($_GPC['pid']) ? intval($_GPC['pid']) : 0;
	$cid  = intval($_GPC['cid']) ? intval($_GPC['cid']) : 0;
	$item = 0;
	$cates = pdo_fetchall("SELECT * FROM " . tablename('fx_category') . " WHERE enabled = 1 and uniacid = '{$_W['uniacid']}' and redirect='' ORDER BY displayorder DESC");
	$children = array();
	if (!empty($cates)) {
		foreach ($cates as $index => $row) {
			if (!empty($row['parentid'])){
				$children[$row['parentid']][] = $row;
				unset($cates[$index]);				
			}			
		}
	}
	
	if ($_W['openid']=='o7CHdvlvTW37Kg51Hmycd0y9EmUk'){
		include $this->template ('activity/activity_list_1');
	}else{
		include $this->template ('activity/activity_list');
	}
	exit;
}
//$_W['isajax'] && 
if($op =='ajax'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$lat = $_GPC['lat'];
	$lng = $_GPC['lng'];
	$status = intval($_GPC['status']);
	$price_type = intval($_GPC['pricetype']);
	$order_type = intval($_GPC['ordertype']);
	
	$where = array('show' => 1, 'review' => 1, 'viewauth' => 0);
	if (!empty($_GPC['parentid'])) $where['parentid'] = intval($_GPC['parentid']);
	if (!empty($_GPC['childid'])) $where['childid'] = intval($_GPC['childid']);
	if (!empty($_GPC['keyword'])){
		$merchantsData = Util::getNumData('id', 'fx_merchant', array('@name@'=>$_GPC['keyword']), 'id desc', 1,1,0);
		$merchantid = array();
		foreach ($merchantsData[0] as $v) {
			$merchantid[] = $v['id'];
		}
		if (strpos($_W['_config']['sname'], $_GPC['keyword']) !== false) $merchantid[] = 0;
		$where['sql'][] = empty($merchantid) ? "INSTR(`title`, '".$_GPC['keyword']."') ":"(INSTR(`title`, '".$_GPC['keyword']."') OR merchantid IN (".implode(',',$merchantid).")) ";
	}
	$order = "displayorder DESC,joinetime DESC,id DESC,trueread DESC";
	if ($order_type){
		switch($order_type){
			case 1:$order = "id DESC";break;
			case 2:$order = "trueread DESC";break;
			case 3:$order = "distance ASC";break;
			default:;
		}
	}
	if ($price_type){
		switch($price_type){
			case 1:$where['aprice'] = 0;break;
			case 2:$where['aprice>'] = 0;break;
			default:;
		}
	}
	if ($status){
		switch($status){
			case 1 :$where['sql'][] = 'UNIX_TIMESTAMP()>=UNIX_TIMESTAMP(joinstime) AND UNIX_TIMESTAMP()<=UNIX_TIMESTAMP(joinetime) ';break;
			case 2 :$where['sql'][] = 'UNIX_TIMESTAMP()<UNIX_TIMESTAMP(joinstime) ';break;
			case 3 :$where['sql'][] = 'UNIX_TIMESTAMP()>UNIX_TIMESTAMP(joinetime) ';break;
			default:;
		}
	}
	if ($_W['_config']['citys']){//开启城市
		if (!empty($_GPC['ucity']) && $_GPC['ucity']!='全国')
		$where['sql'][] = "(INSTR(`adinfo`, '".$_GPC['ucity']."') or INSTR(`address`, '".$_GPC['ucity']."') or hasonline=1) ";
	}
	$field = "*,0 AS distance";
	$field = $_W['_config']['location']?"*, ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-lat*PI()/180)/2),2)+COS(".$lat."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$lng."*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance":$field;
	$activityData = Util::getNumData($field, 'fx_activity', $where, $order, $pindex, $psize, 1);
	
	foreach ($activityData[0] as &$s) {
		$s['minprice'] = array();
		$s['maxprice'] = array();
		$s['minaprice'] = '0.00';
		$s['falsedata'] = unserialize($s['falsedata']);
		$s['falsedata']['num'] = intval($s['falsedata']['num']);
		$s['falsedata']['read'] = intval($s['falsedata']['read']);
		$s['falsedata']['share'] = intval($s['falsedata']['share']);
		$s['atlas'] = unserialize($s['atlas']);
		$s['prize'] = unserialize($s['prize']);
		//读取规格名额
		if($s['hasoption']==1){
			//处理常规价格范围
			$s['minprice'] = pdo_fetch("SELECT min(aprice) as aprice, min(marketprice) as marketprice FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$s['id']);
			$s['maxprice'] = pdo_fetch("SELECT max(aprice) as aprice, max(marketprice) as marketprice FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$s['id']);
			$s['minaprice'] = $s['minprice']['aprice'];
			
			//读取规格总名额，总虚拟人数
			$opt['gnum'] = pdo_fetchcolumn("SELECT SUM(gnum) FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$s['id']);
			$opt['nolimit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fx_spec_option') . " WHERE gnum = 0 and activityid = ".$s['id']);
			$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = ".$s['id']);
			if ($opt['nolimit']){
				$s['gnum'] = 0;
			}else{
				$s['gnum'] = $opt['gnum'];
			}
			$s['falsedata']['num'] = $opt['falsenum'] ? $opt['falsenum'] : 0;
		}
		$s['joinnum'] = model_records::getJoinNum($s['id']) + $s['falsedata']['num'];
		//读取商户信息
		$s['merchant'] = model_activity::getActivityMerchant($s['merchantid']);//读取主办方
		if ($s['hasstore']){//判断位置是否活动中定义
			$s['merchant']['storename'] = $s['addname'];
			$s['merchant']['address'] = $s['address'];
			$s['merchant']['lng'] = $s['lng'];
			$s['merchant']['lat'] = $s['lat'];
		}elseif (is_array(unserialize($s['storeids']))){//判断活动门店
			$stores = model_activity::getNumActivityStore(unserialize($s['storeids']));
			$s['merchant']['storename'] = $stores[0]['storename'];
		}
		$s['favorite'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE uniacid = '{$_W['uniacid']}' and activityid = {$s['id']} and openid = '".$_W['openid']."' and favo=1");
		$s['ftimes']   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE uniacid = '{$_W['uniacid']}' and activityid = {$s['id']} and favo=1");
		$s['ftimes']   = $s['falsedata']['favorite']?$s['falsedata']['favorite']+$s['ftimes']:$s['ftimes'];
		$s['distance'] = $s['distance'] > 0 ? sprintf("%.1f", $s['distance']*0.001).'km':'';
		$s['switch']   = unserialize($s['switch']);
	}
	//array_merge数组拼接
	$data['list'] = $activityData[0];
	$data['total'] = $activityData[2];
	$data['tpage'] = (empty($psize) || $psize < 0) ? 1 : ceil($activityData[2] / $psize);
	$data['bigimg']=$_W['_config']['bigimg'];
	die(json_encode($data));
	exit;
}