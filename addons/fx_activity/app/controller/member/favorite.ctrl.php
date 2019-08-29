<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * favorite.ctrl
 * 收藏控制器
 */
defined('IN_IA') or exit('Access Denied');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = '我收藏的活动';

if($op =='display'){
	include $this->template ('member/favorite_list');
	exit;
}

if ($op == 'ajax') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
	$where = array(
		'openid' => $_W['openid'],
		'favo'   => 1
	);
	$order = "id DESC";
	$favorData = Util::getNumData('*', 'fx_activity_favorite', $where, $order, $pindex, $psize, 1);
	
	foreach ($favorData[0] as &$s) {
		$activity = model_activity::getSingleActivity($s['activityid'], '*');
		if (!empty($activity)){
			$s['title'] = $activity['title'];
			$s['merchantid'] = $activity['merchantid'];
			$s['starttime'] = $activity['starttime'];
			$s['endtime'] = $activity['endtime'];
			$s['joinstime'] = $activity['joinstime'];
			$s['joinetime'] = $activity['joinetime'];
			$s['freetitle'] = $activity['freetitle'];
			$s['thumb'] = $activity['thumb'];
			$s['intro'] = $activity['intro'];
			$s['falsedata']['num'] = $activity['falsedata']['num'];
			$s['switch']['joinnum'] = $activity['switch']['joinnum'];
			$s['aprice'] = $activity['aprice'];
			$s['minaprice'] = 0;
			//读取规格名额
			if($s['hasoption']==1){
				$aprice['max'] = pdo_fetchcolumn("SELECT max(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$s['activityid']);
				$aprice['min'] = pdo_fetchcolumn("SELECT min(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$s['activityid']);
				$s['minaprice'] = !empty($aprice['min']) && $aprice['max'] > $aprice['min']?$aprice['min']:0;
				//读取规格总名额，总虚拟人数
				$opt['gnum'] = pdo_fetchcolumn("SELECT SUM(gnum) FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$s['activityid']);
				$opt['nolimit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fx_spec_option') . " WHERE gnum = 0 and activityid = ".$s['activityid']);
				$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = ".$s['activityid']);
				if ($opt['nolimit']){
					$s['gnum'] = 0;
				}else{
					$s['gnum'] = $opt['gnum'];
				}
				$s['falsedata']['num'] = $opt['falsenum'] ? $opt['falsenum'] : 0;
			}
			$s['joinnum'] = model_records::getJoinNum($s['activityid']) + $s['falsedata']['num'];
			//读取商户信息
			if ($s['merchantid']){
				$merchant = model_merchant::getSingleMerchant($s['merchantid'], '*');
				$s['merchant']['name']  = $merchant['name'];
				$s['merchant']['logo'] = tomedia($merchant['logo']);
			}else{
				$s['merchant']['name']  = $_W['_config']['sname'];
				$s['merchant']['logo'] = tomedia($_W['_config']['slogo']);
			}
			$s['favoexists'] = 1;
		}else{
			$s['merchant']['name']  = $_W['_config']['sname'];
			$s['merchant']['logo'] = tomedia($_W['_config']['slogo']);
			$s['favoexists'] = 0;
		}
	}
	$data = array();
	$data['list'] = $favorData[0]?$favorData[0]:array();
	$data['total'] = $favorData[2];
	$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($favorData[2] / $psize);
	die(json_encode($data));
}

if($op =='favorite'){
	$favo = intval($_GPC['favorite']);
	$id = intval($_GPC['fid']);
	$activityid = intval($_GPC['activityid']);
	$where = array('id' => $id, 'uniacid' => $_W['uniacid']);
	$activity = model_activity::getSingleActivity($activityid, 'id');
	if (!empty($activity)){
		$result = pdo_update('fx_activity_favorite', array('favo' => $favo, 'uid'=>$_W['member']['uid']), $where);
	}else{
		$result = pdo_delete('fx_activity_favorite', $where);
	}
	if ($result){
		die(json_encode(array("result" => $result, "data" => $favo ? 0 : 1)));
	}else{
		die(json_encode(array("result" => $result, "data" => $favo)));
	}
	exit;
}