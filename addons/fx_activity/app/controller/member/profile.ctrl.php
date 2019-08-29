<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * home.ctrl
 * 主页控制器
 */
defined('IN_IA') or exit('Access Denied');

$op  = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$merchantid  = intval($_GPC['id']);
$muid  = intval($_GPC['muid']);
//主办方信息
if($op =='display'){
	$_W['merchant']['id']        = 0;
	$_W['merchant']['uid']       = 0;
	$_W['merchant']['name']      = $_W['_config']['sname'];
	$_W['merchant']['logo']      = tomedia($_W['_config']['slogo']);
	$_W['merchant']['thumb']     = tomedia($_W['_config']['merch_thumb']);
	$_W['merchant']['detail']    = $_W['_config']['detail'];
	$_W['merchant']['followno']  = $_W['_config']['followno'];
	
	
	$condition = "uniacid = '{$_W['uniacid']}' and merchantid=" . $merchantid;
	
	$total['activity'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition and `show`=1 and review=1 and viewauth=0");
	if ($merchantid){
		$merchant = model_merchant::getSingleMerchant($merchantid, '*');
		if (empty($merchant)){
			fx_message('主办方不存在', referer(), 'warning', '主办方可能已被删除');
		}
		$_W['merchant']['id']     = $merchantid;
		$_W['merchant']['uid']    = $muid;
		$_W['merchant']['name']   = $merchant['name'];
		$_W['merchant']['logo']   = tomedia($merchant['logo']);
		$_W['merchant']['thumb']  = empty($merchant['thumb']) ? tomedia($_W['_config']['merch_thumb']) : tomedia($merchant['thumb']);
		$_W['merchant']['detail'] = $merchant['detail'];
		$_W['merchant']['followno'] = $merchant['followno'];
	}
	//分享内容
	$_W['share']['title'] = str_replace('"','\"',str_replace("'","\'",htmlspecialchars_decode($_W['merchant']['name'])));
	$_W['share']['desc']  = str_replace('"','\"',str_replace("'","\'",htmlspecialchars_decode($_W['merchant']['detail'])));
	$_W['share']['desc']  = preg_replace("/\s/","",$_W['share']['desc']);
	$_W['share']['pic'] = $_W['merchant']['logo'];
	
	$_W['merchant']['detail'] = empty($_W['merchant']['detail']) ? 'Ta太懒了，什么也没有留下' : $_W['merchant']['detail'];
	$pagetitle = $_W['merchant']['name'];
	$_W['merchant']['thumb'] = empty($_W['merchant']['thumb']) ? FX_URL . 'app/resource/images/homepage_cover.jpg' : $_W['merchant']['thumb'];
	$total['fans'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_merchant_fans') . " WHERE $condition and follow=1");
	$total['fans'] = $_W['merchant']['followno'] ? $_W['merchant']['followno']+$total['fans']:$total['fans'];
	$fans = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_merchant_fans') . " WHERE $condition and follow=1 and uid='{$_W['member']['uid']}'");
	include $this->template ('member/profile');
	exit;
}
//关注的主办方
if($op =='list'){
	$pagetitle = '我关注的';
	if ($_W['isajax']) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
		$where['uid'] = $_W['member']['uid'];
		$where['follow'] = 1;
		$order = "id DESC";
		$fansData = Util::getNumData('*', 'fx_merchant_fans', $where, $order, $pindex, $psize, 1);
		foreach ($fansData[0] as &$s) {
			$condition = "uniacid = '{$_W['uniacid']}' and `show`=1 and review=1 and viewauth=0";
			if (!$s['merchantid']){
				$s['name'] = $_W['_config']['sname'];
				$s['logo'] = tomedia($_W['_config']['slogo']);
				$s['detail'] = !empty($_W['_config']['detail']) ? $_W['_config']['detail'] : 'Ta太懒了，什么也没有留下';
				$condition.= " and merchantid=" . $s['merchantid'];
			}else{
				if ($s['merchantid']==$s['muid']){ 
					$merchant = pdo_fetch("SELECT id FROM " . tablename ('fx_merchant') . " WHERE uid=" . $s['muid']);
				}else{
					$merchant = model_merchant::getSingleMerchant($s['merchantid'], '*');
				}
				if (!empty($merchant)){
					$condition.= " and merchantid=" . $merchant['id'];
				}else{
					$member = getMember($s['muid']);
					$condition.= " and merchantid=-1";
				}
				$s['name'] = empty($merchant) ? $member['nickname'] : $merchant['name'];
				$s['logo'] = empty($merchant) ? $member['avatar'] : tomedia($merchant['logo']);
				$s['detail'] = empty($merchant) ? 'Ta太懒了，什么也没有留下' : $merchant['detail'];
			}
			$s['activity'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition");
		}
		$data = array();
		$data['list'] = $fansData[0]?$fansData[0]:array();
		$data['total'] = $fansData[2];
		$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($fansData[2] / $psize);
		die(json_encode($data));
	}else{
		include $this->template ('member/profile_list');
	}
	exit;
}
//读取当前主办方活动
if($op =='activity' && $_W['isajax']){
	$pindex = max(1, intval($_GPC['page']));
	$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
	$where = array(
		'show' => 1,
		'review' =>1,
		'viewauth' => 0
	);

	$where['merchantid'] = $merchantid;

	if ($_GPC['type']==1) $where['recommend'] = 1;
	$order = "displayorder DESC,endtime DESC,id DESC";
	$activityData = Util::getNumData('*', 'fx_activity', $where, $order, $pindex, $psize, 1);
	//读取规格名额
	foreach ($activityData[0] as &$s) {
		$s['minaprice'] = 0;
		$s['falsedata'] = unserialize($s['falsedata']);
		$s['falsedata']['num'] = intval($s['falsedata']['num']);
		$s['falsedata']['read'] = intval($s['falsedata']['read']);
		$s['falsedata']['share'] = intval($s['falsedata']['share']);
		$condition = "activityid = {$s['id']} and status <> 5 and (status <> 0 or paytype = 'delivery')";
		$s['joinnum'] = pdo_fetchcolumn("SELECT SUM(buynum) FROM " . tablename('fx_activity_records') . " WHERE $condition");
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
		$s['joinnum'] = !empty($s['joinnum'])?$s['joinnum']+$s['falsedata']['num']:0+$s['falsedata']['num'];
		$s['switch']  = unserialize($s['switch']);
	}
	$data = array();
	$data['list'] = $activityData[0]?$activityData[0]:array();
	$data['total'] = $activityData[2];
	$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($activityData[2] / $psize);
	die(json_encode($data));
	exit;
}
//关注操作
if($op =='follow' && $_W['isajax']){
	$follow = intval($_GPC['follow']);
	$activityid = intval($_GPC['activityid']);
	$list = intval($_GPC['list']);
	if (!$_W['member']['uid']){
		$arr = array();
		if ($activityid) {
			$arr['activityid'] = $activityid;
		}else{
			$arr['id'] = $merchantid;
			$arr['muid'] = $muid;
		}
		$_SESSION['oauth_url'] = app_url('activity/'.$_GPC['type'].'/'.($list?'list':'display'), $arr);
		
		die(json_encode(array("result" => 2, "data" => '')));
	}
	$condition = "uniacid = '{$_W['uniacid']}' and merchantid = $merchantid and uid = '{$_W['member']['uid']}'";
	$fans = pdo_fetch("SELECT * FROM " . tablename ('fx_merchant_fans') . " WHERE $condition");
	if(!empty($fans)){
		$where = array('merchantid' => $merchantid, 'uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']);
		$result = pdo_update('fx_merchant_fans', array('follow' => $follow,'createtime' => TIMESTAMP,'muid' => $muid,'openid' => $_W['openid']), $where);
		if ($result){
			pdo_query("UPDATE ".tablename('fx_merchant')." SET follownum=follownum+" . ($follow ? $follow : -1) . " WHERE id = :id", array(':id' => $merchantid));
			die(json_encode(array("result" => $result, "data" => $follow ? 0 : 1)));
		}else{
			die(json_encode(array("result" => $result, "data" => $follow)));
		}
		exit;
	}else{
		if (!empty($_W['member']['uid'])){
			$result = pdo_insert ('fx_merchant_fans', array (
				'merchantid' => $merchantid,
				'muid'       => $muid,
				'uniacid'    => $_W['uniacid'],
				'uid'        => $_W['member']['uid'],
				'openid'     => $_W['openid'],
				'follow'     => 1,
				'createtime' => TIMESTAMP
			));
		}
		if ($result){
			pdo_query("UPDATE ".tablename('fx_merchant')." SET follownum=follownum+" . ($follow ? $follow : -1) . " WHERE id = :id", array(':id' => $merchantid));
			die(json_encode(array("result" => $result, "data" => 0)));
		}else{
			die(json_encode(array("result" => $result, "data" => 1)));
		}
		exit;
	}
}