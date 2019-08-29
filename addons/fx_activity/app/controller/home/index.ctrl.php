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
	$where = array('enabled'=>1);
	$cateData = Util::getNumData('*', 'fx_category', $where, 'displayorder DESC', 1,1,0);
	$children = array();
	if (!empty($cateData[0])) {
		foreach ($cateData[0] as $index => $row) {
			if (!empty($row['parentid'])){
				$children[$row['parentid']][] = $row;
				unset($cateData[0][$index]);				
			}			
		}
	}
	//读取幻灯片
	$advlist = pdo_fetchall("SELECT * FROM " . tablename('fx_adv') . " WHERE uniacid = '{$_W['uniacid']}' and enabled=1 ORDER BY displayorder DESC");
	if ($_W['openid']=='o7CHdvlvTW37Kg51Hmycd0y9EmUk'){
		include $this->template ('index_1');
	}else{
		include $this->template ('index');
	}
	exit;
}

if($_W['isajax'] && $op =='hot'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = intval($_GPC['pagesize']) ? intval($_GPC['pagesize']) : 15;
	$order = "displayorder DESC,joinetime DESC,id DESC,trueread DESC";
	$where = array('show' => 1, 'review' => 1, 'viewauth' => 0, 'recommend' => 1);
	if ($_W['_config']['citys']){//开启城市
		if (!empty($_GPC['ucity']) && $_GPC['ucity']!='全国')
		$where['sql'][] = "(INSTR(`adinfo`, '".$_GPC['ucity']."') or INSTR(`address`, '".$_GPC['ucity']."') or hasonline=1) ";
	}
	$field = "*";
	$activityData = Util::getNumData($field, 'fx_activity', $where, $order, $pindex, $psize, 1);
	//读取规格名额
	foreach ($activityData[0] as &$s) {
		$s['minaprice'] = 0;
		$s['falsedata'] = unserialize($s['falsedata']);
		$s['falsedata']['num'] = intval($s['falsedata']['num']);
		$s['falsedata']['read'] = intval($s['falsedata']['read']);
		$s['falsedata']['share'] = intval($s['falsedata']['share']);
		if($s['hasoption']==1){
			$aprice['max'] = pdo_fetchcolumn("SELECT max(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$s['id']);
			$aprice['min'] = pdo_fetchcolumn("SELECT min(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$s['id']);
			$s['minaprice'] = !empty($aprice['min']) && $aprice['max'] > $aprice['min']?$aprice['min']:0;
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
		$s['favorite'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE openid = '".$_W['openid']."' and activityid = ".$s['id']);
		$s['ftimes'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE activityid = ".$s['id']);
		$s['switch'] = unserialize($s['switch']);
	}
	//array_merge数组拼接
	$data['list'] = $activityData[0];
	$data['total'] = $activityData[2];
	$data['tpage'] = (empty($psize) || $psize < 0) ? 1 : ceil($activityData[2] / $psize);
	die(json_encode($data));
	exit;
}


if($_W['isajax'] && $op == 'getparams'){
	$activityid = intval($_GPC['activityid']);
	$activity = model_activity::getSingleActivity($activityid, '*');
	//读取规格名额
	if($activity['hasoption']){
		$aprice['max'] = pdo_fetchcolumn("SELECT max(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$activity['id']);
		$aprice['min'] = pdo_fetchcolumn("SELECT min(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$activity['id']);
		//读取规格总名额，总虚拟人数
		$opt['gnum'] = pdo_fetchcolumn("SELECT SUM(gnum) FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$activity['id']);
		$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = ".$activity['id']);
		$activity['gnum'] = $opt['gnum'] ? $opt['gnum'] : $activity['gnum'];
		$activity['falsedata']['num'] = $opt['falsenum'] ? $opt['falsenum'] : 0;
	}
	$aprice['min'] = !empty($aprice['min']) && $aprice['max'] >= $aprice['min']?$aprice['min']:0;
	$params = array(
		"joinnum" => model_records::getJoinNum($activityid) + $activity['falsedata']['num'],
		"gnum"  => $activity['gnum'],
		"aprice"  => $activity['aprice'],
		"minaprice"  => $aprice['min']
	);
	die(json_encode($params));
	exit;
}