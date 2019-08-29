<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * home.ctrl
 * 个人中心首页控制器
 */
defined('IN_IA') or exit('Access Denied');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$opp = !empty($_GPC['opp']) ? $_GPC['opp'] : 'display';
$pagetitle = '主办方';

if($op =='display'){
	if (empty($_W['member']['mobile']) && $_W['_config']['bond']){
		header("Location:".app_url('member/bond/mobile',array('actype'=>'merch')));
		exit;
	}
	//首次发布如果不是主办方,自动添加主办方,默认不给后台登录权限
	if (!MERCHANTID && !ADMIN){
		load()->model('mc');
		$member = mc_fetch($_W['openid']);
		$merchdata = array (
			'uid' 	         => $member['uid'],
			'uniacid'        => $_W['uniacid'],
			'open'           => 1,
			'uname'          => $member['mobile'],
			'logo'           => $member['avatar'],
			'password'       => user_hash('888888', ''),
			'percent'        => $_W['_config']['percent'] ? $_W['_config']['percent'] : '0.6',
			'name'           => $member['nickname'],
			'openid'         => $_W['openid'],
			'messageopenid'  => serialize(array($_W['openid'])),
			'linkman_name'   => $member['realname'],
			'linkman_mobile' => $member['mobile'],
			'createtime'     => TIMESTAMP
		);
		pdo_insert('fx_merchant', $merchdata);
		$merchantid = pdo_insertid();
	}
	fx_load()->model('credit');
	$credits = credit_get_by_uid($_W['member']['uid'],1);
	$merchant = model_merchant::getSingleMerchant(MERCHANTID?MERCHANTID:$merchantid, '*');
	if (MERCHANTID){
		$mcert = Util::getSingelData('*', 'fx_merchant_mcert', array('mid' => MERCHANTID));
	}
	include $this->template ('member/merch');
	exit;
}
//收入管理
if($op =='income'){
	$pagetitle = '我的收入';
	$account =  pdo_fetch("SELECT * FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid=" . MERCHANTID);
	$w_money  = pdo_fetchcolumn("select SUM(money) from".tablename('fx_merchant_record')." where uniacid={$_W['uniacid']} and status<>3 and merchantid=" . MERCHANTID);
	$y_money  = pdo_fetchcolumn("select SUM(money) from".tablename('fx_merchant_record')." where uniacid={$_W['uniacid']} and status=3 and merchantid=" . MERCHANTID);
	$no_hexiao = pdo_fetchcolumn("select SUM(price) from".tablename('fx_activity_records')." where (paytype='wechat' or paytype='alipay') and status in(1) and merchantid=" . MERCHANTID);
	$merchant = model_merchant::getSingleMerchant(MERCHANTID, '*');
	$merchant['amount'] = $account['amount'];
	$merchant['w_money'] = $w_money;//待处理的提现金额
	$merchant['y_money'] = $y_money;//已处理的提现金额
	$merchant['no_hexiao'] = $no_hexiao;//待确认
	$merchant['no_money'] = $account['no_money'];//可提现金额
	$merchant['no_money_doing'] = $account['no_money_doing'];
	$merchant['minimum'] = empty($_W['_config']['merch_amount']) ? 1 : $_W['_config']['merch_amount'];//每笔提现金额最低值
	$merchant['maximum'] = empty($_W['_config']['merch_maximum']) ? 10000 : $_W['_config']['merch_maximum'];//每笔提现金额最大值
	include $this->template ('member/merch_income');
	exit;
}
//读取明细
if($op =='income_list'){
	$pagetitle = '明细';
	if ($_W['isajax']) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
		$where['merchantid'] = MERCHANTID;
		$moneyRecordData = Util::getNumData('id,money,createtime,type', 'fx_merchant_money_record', $where, 'createtime desc', $pindex, $psize, 1);
		$data = array();
		$data['list'] = $moneyRecordData[0]?$moneyRecordData[0]:array();
		$data['total'] = $moneyRecordData[2];
		$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($moneyRecordData[2] / $psize);
		die(json_encode($data));
	}
	include $this->template ('member/merch_income_list');
	exit;
}
//提现操作
if($op =='cash'){
	$pagetitle = '提现';
	$_W['_config']['cash_time'] = empty($_W['_config']['cash_time']) ? 1 : $_W['_config']['cash_time'];
	$merchant = model_merchant::getSingleMerchant(MERCHANTID, '*');
	if (!$merchant['iscert'] && $_W['_config']['certswitch']) fx_message('访问失败', app_url('member/merch/display'), 'warning','你还没有认证，不能进行提现操作！');
	$account =  pdo_fetch("SELECT * FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid=" . MERCHANTID);
	$merchant['amount'] = $account['amount'];
	$merchant['no_money'] = $account['no_money'];
	$merchant['no_money_doing'] = $account['no_money_doing'];
	if ($_W['isajax']) {
		/*先判断是否有正在申请中的结算申请*/
		if($account['no_money_doing']>0) die(json_encode(array("errno" => 1,'message' => '上一笔申请还未处理!')));	
		/*先判断是否有正在申请中的结算申请*/
		if(empty($merchant['openid'])) die(json_encode(array("errno" => 1,'message' => '您未绑定提现微信号!')));
		$money = $_GPC['money'];//提现金额（元）
		$no_money = model_merchant::getNoSettlementMoney(MERCHANTID); //未结算金额
		$minimum = empty($_W['_config']['merch_amount']) ? 1 : $_W['_config']['merch_amount'];//每笔提现金额最低值
		$maximum = empty($_W['_config']['merch_maximum']) ? 10000 : $_W['_config']['merch_maximum'];//每笔提现金额最大值
		if(is_numeric($money)){
			$commission = '0.00';//手续费
			$money = sprintf("%.2f", $money);
			if(!empty($merchant['percent'])){
				$commission = sprintf("%.2f", $money*$merchant['percent']*0.01);
				$get_money = sprintf("%.2f", $money-$commission);//实到金额
			}else{
				$get_money = $money;//实到金额
			}
			//if($money<$minimum) die(json_encode(array("errno" => 1,'message' => '单次提现金额不得小于'.$minimum.'元')));
			if($get_money<$minimum) die(json_encode(array("errno" => 1,'message' => '单次提现实到金额不得小于'.$minimum.'元')));
			if($get_money>$maximum) die(json_encode(array("errno" => 1,'message' => '单次提现金额不得大于'.$maximum.'元')));
			if($no_money<$money) die(json_encode(array("errno" => 1,'message' => '可提现金额不足')));
			
			$orderno = date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
			$time = TIMESTAMP;
			pdo_update('fx_merchant_account',array('no_money_doing'=>$money),array('merchantid'=>MERCHANTID));
			pdo_insert("fx_merchant_record",array('status'=>1,'updatetime'=>$time,'percent'=>$merchant['percent'],'get_money'=>$get_money,'merchantid'=>MERCHANTID,'money'=>$money,'commission'=>$commission,'uid'=>$_W['member']['uid'],'createtime'=>$time,'uniacid'=>$_W['uniacid'],'orderno'=>$orderno));
			if ($_W['_config']['mmsg']){//管理通知
				$openids = $_W['_config']['openids'];
				$data = array(
					'name'=>$merchant['name'],
					'money'=>$get_money,
					'orderno'=>$orderno,
					'account'=>'微信零钱【'.$_W['fans']['nickname']."】",
					'createtime'=>$time
				);
				if (!empty($openids)){
					foreach($openids as $key=> $value){
						message::admin_notice_cash($value, $data, '');
					}
				}

			}
			die(json_encode(array("errno" => 0,'message' => '提现成功')));
		}else{
			die(json_encode(array("errno" => 1,'message' => '提现金额输入错误')));
		}
	}
	include $this->template ('member/merch_cash');
	exit;
}
//提现记录
if($op =='cash_list'){
	//$status = intval($_GPC['status'])?intval($_GPC['status']):1;
	$pagetitle = '提现记录';
	if($_W['isajax']){
		$pindex = max(1, intval($_GPC['page']));
		$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
		$where['merchantid'] = MERCHANTID;
		//$where['status'] = $status;
		//if ($status!=3) $where['#status#'] = '(1,2)';
		$recordData = Util::getNumData('*', 'fx_merchant_record', $where, 'updatetime desc', $pindex, $psize, 1);
		$data = array();
		$data['list'] = $recordData[0]?$recordData[0]:array();
		$data['total'] = $recordData[2];
		$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($recordData[2] / $psize);
		die(json_encode($data));
	}
	include $this->template ('member/merch_cash_list');
	exit;
}
//待确认记录
if($op =='confirm'){
	//$status = intval($_GPC['status'])?intval($_GPC['status']):1;
	$pagetitle = '待确认记录';
	if($_W['isajax']){
		$pindex = max(1, intval($_GPC['page']));
		$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
		$where = array(
			'merchantid' => MERCHANTID,
			'paytype' => 'wechat',
			'status' => 1,
			'ishexiao' => 0
		);
		//if ($status!=3) $where['#status#'] = '(1,2)';
		$recordData = Util::getNumData('*', 'fx_activity_records', $where, 'jointime desc', $pindex, $psize, 1);
		$data = array();
		$data['list'] = $recordData[0]?$recordData[0]:array();
		$data['total'] = $recordData[2];
		$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($recordData[2] / $psize);
		die(json_encode($data));
	}
	include $this->template ('member/merch_confirm');
	exit;
}

//读取粉丝
if($op =='fans'){
	$pagetitle = '我的粉丝';
	if ($_W['isajax']) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = $_GPC['pagesize']?$_GPC['pagesize']:15;
		$where['merchantid'] = MERCHANTID;
		$where['follow'] = 1;
		$order = "createtime DESC,id DESC";
		$fansData = Util::getNumData('*', 'fx_merchant_fans', $where, $order, $pindex, $psize, 1);
		foreach ($fansData[0] as &$s) {
			$condition = "uid={$s['uid']} and merchantid=".MERCHANTID ;
			$merchant = pdo_fetch("SELECT * FROM " . tablename ('fx_merchant') . " WHERE uid=" . $s['uid']);
			$member = getMember($s['uid']);
			$s['merchantid'] = empty($merchant) ? $s['uid'] : $merchant['id'];
			$s['name'] = empty($merchant) ? $member['nickname'] : $merchant['name'];
			$s['logo'] = empty($merchant) ? $member['avatar'] : tomedia($merchant['logo']);
			$s['detail'] = empty($merchant) ? 'Ta太懒了，什么也没有留下' : $merchant['detail'];
			$s['activity'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition");
			$s['createtime'] = tranTime($s['createtime']);
		}
		$data = array();
		$data['list'] = $fansData[0]?$fansData[0]:array();
		$data['total'] = $fansData[2];
		$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($fansData[2] / $psize);
		die(json_encode($data));
	}else{
		include $this->template ('member/merch_fans');
	}
	exit;
}
if($op == 'help'){
	$pagetitle = '帮肋中心';
	$kefutel = explode(",", str_replace('，', ',', $_W['_config']['kefutel']));
	include $this->template ('member/help_list');
	exit;
}
if($op == 'hexiao'){
	$pagetitle = '核销管理';
	if ($_W['isajax']) {
		$type = trim($_GPC['type']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = $_GPC['pagesize'] ? $_GPC['pagesize'] : 15;
		$where = array();
		if (empty($_GPC['opp'])) {
			$where['merchantid'] = MERCHANTID;
			$order = "id DESC";
			$tableName = 'fx_' . $type;
			$listData = Util::getNumData('*', $tableName, $where, $order, $pindex, $psize, 1);
			if ($type == 'saler') {
				fx_load() -> model('member');
				foreach ($listData[0] as $key => $value) {
					$openids_arr = explode(',', $value['openid']);
					$storeid_arr = explode(',', $value['storeid']);
					$salername   = '';
					$storename   = '';
					//所属门店
					foreach ($storeid_arr as $k => $v) {
						if ($v) {
							$store = pdo_fetch("select * from" . tablename('fx_store') . "where id='{$v}'");
							$storename .= $store['storename'] . "/";
						}
					}
					$storename               = substr($storename, 0, strlen($storename) - 1);
					//所有核销员昵称
					foreach ($openids_arr as $k => $v) {
						if ($v) {
							$member = getMember($v);
							$salername .= $member['nickname'] . "/";
						}
					}
					$salername               = substr($salername, 0, strlen($salername) - 1);
					$listData[0][$key]['salername'] = $salername;
					$listData[0][$key]['storename'] = $storename;
					$listData[0][$key]['salernum'] = count($openids_arr)-1;
					//$list[$key]['merchant'] = pdo_fetch("SELECT * FROM " . tablename('fx_merchant') . " WHERE uniacid = '{$_W['uniacid']}' and id={$value['merchantid']}");
				}
			}
		}if ($_GPC['opp']=='search'){
			if ($type == 'store'){
				$order = "id DESC";
				$where['merchantid'] = MERCHANTID;
			}else{
				$order = "fanid DESC";
			}
			if (!empty($_GPC['keyword'])) {
				if ($type == 'saler')
					$where['@nickname@'] = trim($_GPC['keyword']);
				else
					$where['@storename@'] = trim($_GPC['keyword']);
			}
			
			$tableName = $type == 'store' ? 'fx_store' : 'mc_mapping_fans';
			$listData = Util::getNumData('*', $tableName, $where, $order, $pindex, $psize, 1);
			if ($type == 'saler') {
				load()->model('mc');
				foreach ($listData[0] as &$v) {
					$v['member'] = mc_fetch($v['uid'], array('realname', 'nickname', 'avatar'));
					$v['member']['avatar'] = tomedia($v['member']['avatar']);
				}
			}
		} elseif ($opp == 'post') {
			$id = $_GPC['id'];
			if ($_W['ispost']){
				if ($type == 'saler'){
					load()->model('mc');
					$storeid  = '';
					$storeids = $_GPC['storeids'];
					if ($storeids) {
						foreach ($storeids as $key => $value) {
							if ($value) {
								$storeid .= $value . ",";
							}
						}
					}
					$openid  = '';
					$openids = $_GPC['openids'];
					if ($openids) {
						foreach ($openids as $key => $value) {
							if ($value) {
								$openid .= $value . ",";
							}
						}
					}
					$data = array(
						'uniacid' => $_W['uniacid'],
						'openid'  => $openid,
						'storeid' => $storeid,
						'status'  => $_GPC['status'],
						'merchantid'=> MERCHANTID
					);
					
					$uid  = mc_openid2uid($data['openid']);
					$info = mc_fetch($uid, array('nickname','avatar'));
					//$info             = member_get_by_params(" openid = '{$data['openid']}'");
					$data['avatar']   = $info['avatar'];
					$data['nickname'] = $info['nickname'];
					if ($id) {
						$result = pdo_update('fx_saler', $data, array('id' => $id));
					} else {
						$result = pdo_insert('fx_saler', $data);
					}
				}else{
					$data = array(
						'uniacid' => $_W['uniacid'],
						'storename' => $_GPC['storename'],
						'address' => $_GPC['address'],
						'tel' => $_GPC['tel'],
						'lng' => $_GPC['map']['lng'],
						'lat' => $_GPC['map']['lat'],
						'adinfo' => $_GPC['map']['adinfo'],
						'status' => $_GPC['status'],
						'merchantid'=> MERCHANTID
					);
					$registerdate     = $_GPC['registerdate'];
					$data['storehours']= serialize($registerdate);
					if ($id) {
						$result = pdo_update('fx_store', $data, array(
							'id' => $id
						));
					} else {
						$result = pdo_insert('fx_store', $data);
					}
				}
				die(json_encode(array('errno' => $result ? 0 : 1)));
				exit;
			}
			if ($type == 'saler'){
				$saler = Util::getSingelData('*', 'fx_saler',array('id' => $id));
				$storeid_arr = explode(',', $saler['storeid']);
				$openid_arr = explode(',', $saler['openid']);
				$stores = $member = array();
				foreach ($storeid_arr as $k => $v) {
					if ($v) {
						$stores[$k] = Util::getSingelData('*', 'fx_store',array('id' => $v));
					}
				}
				foreach ($openid_arr as $k => $v) {
					load()->model('mc');
					if ($v) {				
						$member[$k] = mc_fetch($v, array('realname', 'nickname', 'avatar'));
						$member[$k]['openid'] = $v;
					}
				}
				$data = array('id' => $saler['id'], 'stores' => $stores, 'member' => $member, 'status' => $saler['status']);
				die(json_encode($data));
			}
			if ($type == 'store'){
				$store = Util::getSingelData('*', 'fx_store',array('id' => $id));
				$store['storehours'] = unserialize($store['storehours']);
				die(json_encode($store));
			}
		} elseif ($opp == 'delete') {
			$id = $_GPC['id'];
			$tableName = 'fx_' . $type;
			
			foreach ($id as $k => $bid) {
				pdo_delete($tableName, array('id' => $bid));
			}
			die(json_encode(array("errno" => 0, 'message'=>'删除成功')));
			exit;
		}
		
		$data = array();
		$data['list'] = $listData[0]?$listData[0]:array();
		$data['total'] = $listData[2];
		$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($listData[2] / $psize);
		
		die(json_encode($data));
	}
	include $this->template ('member/merch_hexiao');
}

//更新主办方信息
if ($op == 'update' && $_W['isajax']) {
	$type = $_GPC['type'];
	$merchant = model_merchant::getSingleMerchant(MERCHANTID, 'uname,logo');
	$settings = $_W['_config'];
	$data = array();
	switch($type){
		case 'logo'           : $data['logo'] = $settings['slogo'] = trim($_GPC['logo']);break;
		case 'name'           : $data['name'] = $settings['sname'] = trim($_GPC['name']);break;
		case 'linkman_mobile' : $data['linkman_mobile'] = $settings['mobile'] = trim($_GPC['linkman_mobile']);
			if (empty($merchant['uname']) && MERCHANTID){
				$data['uname'] = trim($_GPC['linkman_mobile']);
				fx_load()->model('merchant');
				if(merchant_check(array('uname' => $data['uname']))) message('当前手机号已被绑定！', referer(), 'error');
			}
		break;
		case 'linkman_name'   : $data['linkman_name'] = $settings['linkman_name'] = trim($_GPC['linkman_name']);break;
		case 'detail'         : $data['detail'] = $settings['detail'] = trim($_GPC['detail']);break;
		default:;
	}
	if (ADMIN){
		$pars = array('module' => MODULE_NAME, 'uniacid' => $_W['uniacid']);
		$sys = array();
		$sys['settings'] = iserializer($settings);
		$result = pdo_update('uni_account_modules', $sys, $pars);
		cache_build_module_info(MODULE_NAME);
	}else{
		if (!empty($data['logo'])){
			fx_load()->func('attachment');
			$id = attachment_id($merchant['logo']);
			load()->func('file');
			if ($_W['setting']['remote']['type']) {
				file_remote_delete($merchant['logo']);
			} else {
				file_delete($merchant['logo']);
			}
			if ($id) {
				pdo_delete('core_attachment', array('id' => $id));
			}
		}
		$where = array('id' => MERCHANTID, 'uniacid' => $_W['uniacid']);
		$result = pdo_update('fx_merchant', $data, $where);
	}
	if ($result) {
		message('更新资料成功！', referer(), 'success');
	}else{
		message('相同信息不做修改！', referer(), 'error');
	}
	exit;
}
if($op == 'mcert'){
	fx_load()->func('attachment');
	$mcert = Util::getSingelData('*', 'fx_merchant_mcert', array('mid' => MERCHANTID));
	if (!empty($mcert)){
		$mcert['detail'] = unserialize($mcert['detail']);
		$mcert['detail']['thumbid'][] = attachment_id($mcert['detail']['thumb'][0]);
		$mcert['detail']['thumbid'][] = attachment_id($mcert['detail']['thumb'][1]);
	}
	
	if ($_W['ispost']){
		$data = $_GPC['mcert'];
		$data['detail'] = serialize($data['detail']);
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $_W['openid'];
		$data['mid'] = MERCHANTID;
		$data['status'] = 0;
		$data['createtime'] = TIMESTAMP;
		$data['endtime'] = strtotime("+1 year", TIMESTAMP);
				
		if (!empty($mcert)) {
			$result = pdo_update('fx_merchant_mcert', $data, array('mid' => MERCHANTID));
		} else {
			$result = pdo_insert('fx_merchant_mcert', $data);
		}
		
		die(json_encode(array('errno' => $result ? 0 : 1)));
		exit;
	}
	die(json_encode($mcert));
	exit;
}