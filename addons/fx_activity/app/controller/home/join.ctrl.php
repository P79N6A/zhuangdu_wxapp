<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * join.ctrl
 * 报名入口控制器
 */
defined('IN_IA') or exit('Access Denied');
if (empty($_W['fans']['nickname'])){
	$_W['fans'] = getInfo();
}
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle  = '报名信息填写';
$activityid = intval($_GPC['activityid']);
$activity   = model_activity::getSingleActivity($activityid, '*');
$optionid   = intval($_GPC['optionid'])?intval($_GPC['optionid']):0;
$buynum     = intval($_GPC['buynum']) ? intval($_GPC['buynum']) : 1;
$form       = unserialize($activity['form']);//系统表单

if ($_W['container']!='wechat'){
	fx_message('请用微信打开此连接报名', '', 'error', '点确定返回');
}
if ($activity['show']==0 || $activity['review']!=1){
	fx_message('当前活动没有开放报名', '', 'error', '点确定返回');
}
if ($_W['_config']['guanzhu_join']==2 && empty($_W['fans']['follow'])){
	fx_message('您还未关注,不能报名', app_url('activity/detail/display', array ('activityid' => $activityid)), 'warning');
}

$blacklist = pdo_get('fx_member_blacklist', array('uid' => $_W['member']['uid']), array('uid'));
if ($blacklist['uid']){
	fx_message('对不起您被主办方屏蔽了', app_url('activity/detail/display', array ('activityid' => $activityid)), 'error','请联系主办方解封');
}
if ($activity['costcredit'] && $activity['costcredit'] > $_W['member']['credit1']){
	fx_message('对不起您的积分不足', '', 'error', '参与当前活动需要 '.$activity['costcredit'].' 积分，分享当前活动可以赚取积分哦！');
}
if (!is_array($form['realname'])){//表单优化，数据兼容
	$sysform['realname']['title'] = $form['realname'];
	$sysform['realname']['need'] = 1;
	$sysform['realname']['show'] = $form['realnameshow'];
	
	$sysform['mobile']['title'] = $form['mobile'];
	$sysform['mobile']['need'] = 1;
	$sysform['mobile']['show'] = $form['mobileshow'];
	
	$sysform['gender']['title'] = $form['gender'];
	$sysform['gender']['need'] = $form['gendermust'];
	$sysform['gender']['show'] = $form['gendershow'];
	
	$sysform['birthyear']['title'] = $form['birthyear'];
	$sysform['birthyear']['need'] = $form['birthmust'];
	$sysform['birthyear']['show'] = $form['birthshow'];
	
	$sysform['resideprovince']['title'] = $form['resideprovince'];
	$sysform['resideprovince']['need'] = $form['residemust'];
	$sysform['resideprovince']['show'] = $form['resideshow'];
}else{
	$sysform = $form;
	$sysform['realname']['show'] = $form['realname']['show']=='' || $form['realname']['show']?1:0;
	$sysform['mobile']['show'] = $form['mobile']['show']=='' || $form['mobile']['show']?1:0;
	if (count($sysform)>2){
		$sysform['gender']['show'] = $form['gender']['show']=='' || $form['gender']['show']?1:0;
		$sysform['birthyear']['show'] = $form['birthyear']['show']=='' || $form['birthyear']['show']?1:0;
		$sysform['resideprovince']['show'] = $form['resideprovince']['show']=='' || $form['resideprovince']['show']?1:0;
	}
}
//验证名额，每次进入加载剩余名额数量、及规格
$condition = " activityid = $activityid and status <> 5 and (status <> 0 or paytype = 'delivery')";
$gnum = $activity['gnum'];

if ($activity['hasoption']){//规格选项
	$option = model_activity::getSingleActivityOption($optionid);
	$activity['aprice'] = $option['aprice'];
	$activity['marketprice'] = $option['marketprice'];
	$activity['falsedata']['num'] = $option['falsenum'] ? $option['falsenum'] : 0;
	$gnum = $option['gnum'];
}
$price = $activity['aprice'];
$pay_price = $activity['aprice'];
$joinnum = model_records::getJoinNum($activityid, $optionid) + $activity['falsedata']['num'];

if ($_W['plugin']['card']['config']['card_enable'] && $yearcard['buy']) {//年卡
	if ($activity['atype']==2){
		if (!$activity['prize']['cardper']['enable']){//年卡价格
			//$price = $activity['marketprice'];
			$vipdeduct = $activity['aprice'] - $activity['marketprice'];
			$pay_price = $activity['marketprice'];
		}else {//年卡打折
			$yc['percent'] = $activity['prize']['cardper']['percent'];
		}
	}
	if ($_W['plugin']['card']['config']['credit_double']) $credit_double = 1;
}

$dc = $_GPC['dc'] == 'yes' ? TRUE : FALSE; //是否积分抵扣
$pay_price = sprintf("%.2f", $pay_price * $buynum);
$afterMarketing = model_records::getafterMarketing($pay_price, $buynum, $activityid, $dc, $yc); //获取优惠后的价格等参数
$pay_price = $afterMarketing['pay_price'];  //营销后的价格
$vipdeduct = empty($vipdeduct) ? $afterMarketing['cardReduce'] : $vipdeduct; //高级vip优惠费用

if($_W['isajax'] && $op == 'getprice') {
	$params = array(
		'price' =>$price,
		'pay_price' =>$pay_price,
		'joinnum' =>$joinnum,
		'm1' =>$afterMarketing['m1'],
		'm2' =>$afterMarketing['m2'],
		'm3' =>$afterMarketing['m3'],
		'm4' =>$afterMarketing['m4'],
		'max'=>$afterMarketing['max'],
		'p'=>$afterMarketing['p'],
		'deductMoney'=>$afterMarketing['deductMoney'],
		'deductCredit'=>$afterMarketing['deductCredit'],
		'orderMarket'=>$afterMarketing['orderMarket'],
		'cardReduce'=>$afterMarketing['cardReduce'],
		'token'=>$_W['token']
	);
	
	die(json_encode(array('errno'=>0, 'params'=>$params)));
}

if($op =='display'){
	load()->model('mc');
	$marketing = model_activity::getMarketing($activityid);//优惠
	$profile   = mc_fetch($_W['member']['uid']);//读取会员信息
	if(empty($profile['email']) || (!empty($profile['email']) && substr($profile['email'], -6) == 'we7.cc' && strlen($profile['email']) == 39)) {
		$profile['email'] = '';
	}
	$activityForm = model_activity::getNumActivityForm($activityid,'app');//读取表单
	if(!$optionid && $activity['hasoption']){
		fx_message ('请选择活动类型', app_url('activity/detail/display', array ('activityid' => $activityid)), 'warning');
	}
	if($joinnum >= $gnum && $gnum>0) {
		fx_message ('很遗憾！名额已经满了', app_url('activity/detail/display', array ('activityid' => $activityid)), 'warning');
	}
	if ($_W['openid'] == 'o7CHdvlvTW37Kg51Hmycd0y9EmUk'){
		include $this->template ('join_1');
	}else{
		include $this->template ('join');	
	}
	exit;
}
if($_W['isajax'] && $op == 'join') {
	setcookie("sms_code", '');
	$data_member = $_GPC['member'];
	if (empty($data_member['realname']) && $systemform['realnameshow']) {
		fx_message ('请输入姓名','history.go(-1)','warning');
	}
	if (empty($data_member['mobile']) && $systemform['mobileshow']) {
		fx_message ('请输入手机','history.go(-1)','warning');
	}
	
	$_W['member'] = getMember($_W['openid']);
	if($dc == 'yes'){
		$deduct[0] = $afterMarketing['orderMarket']['deduct'][0];
		$deduct[1] = $afterMarketing['orderMarket']['deduct'][1];
		$orderMarket['deduct'] = $deduct;
	}
	
	if($credit_double) $orderMarket['credit_double'] = $credit_double;
	
	if ($pay_price > 0){
		$orderMarket['market_price'] =  sprintf("%.2f", $price * $buynum - $pay_price);
	}
	$data = array (
			'uid' => $_W['member']['uid'],
			'activityid' => $activityid,
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'buynum' => $buynum,
			'nickname' => $_W['member']['nickname'],
			'headimgurl' => $_W['member']['avatar'],
			'orderno' => createUniontid(),
			'realname' => $data_member['realname'],
			'mobile' => $data_member['mobile'],
			'gender' => $_GPC['gender']==0 ? '保密' : ($_GPC['gender']==1?'男':'女'),
			'pic' => $_GPC['pic'],
			'msg' => htmlspecialchars_decode($_GPC['msg']),
			'optionid' => $optionid?$optionid:0,
			'optionname' => $option['title'],
			'review' => $activity['switch']['joinreview']?0:1,
			'status' => 2,
			'merchantid' => $activity['merchantid']?$activity['merchantid']:0,
			'marketing' => serialize($orderMarket)
	);
	
	if ($pay_price > 0){//获取付费金额
		$data['price'] = $pay_price;
		$data['aprice'] = $price;
		$data['vipdeduct'] = $vipdeduct;
		$data['status'] = 0;
	}
	$condition = "activityid = $activityid and status <> 5 and openid='{$_W['openid']}'";
	$findUser = pdo_fetch("SELECT id FROM " . tablename ('fx_activity_records') . " WHERE $condition");
	if(!empty($findUser) && !$activity['switch']['rejoin']){
		fx_message ('不能重复报名！', $this->createMobileUrl ('activity/detail/display', array ('activityid' => $activityid)), 'warning');
	}else{
		if ($gnum>0){
			$joinnum = model_records::getJoinNum($activityid, $optionid) + $activity['falsedata']['num'];
			if($joinnum >= $gnum) {
				fx_message ('很遗憾！名额已经满了', app_url('activity/detail/display', array ('activityid' => $activityid)), 'warning');
			}elseif($joinnum + $buynum > $gnum){
				fx_message ("当前活动仅剩 " . ($gnum - $joinnum) . ' 个名额，点击确定重新填写', '' , 'warning');
			}
		}
		$result = pdo_insert('fx_activity_records', $data);
		if (!empty($result)){
			$recordid = pdo_insertid();
			//保存自定义表单信息
			$form_ids = $_GPC['form_id'];
			$len = count($form_ids);
			$form_items = array();
			for ($k = 0; $k < $len; $k++) {
				$form_items[$k] = is_array($_GPC["form_item_val_".$k]) ? implode(',', $_GPC["form_item_val_".$k]) : $_GPC["form_item_val_".$k];
				$form_id = $form_ids[$k];
				$a = array(
					"activityid" => $activityid,
					"recordid" => $recordid,
					"formid" => $form_id,
					"data" => $form_items[$k]
				);
				//表单数据
				pdo_insert("fx_form_data", $a);
			}
			//同步会员信息
			if (!empty($_GPC['gender']))
			{
				$data_member['gender'] = $_GPC['gender'];
			}
			if (!empty($_GPC['birth']))
			{
				$data_member['birthyear'] = $_GPC['birth']['year'];
				$data_member['birthmonth'] = $_GPC['birth']['month'];
				$data_member['birthday'] = $_GPC['birth']['day'];
			}
			if (!empty($_GPC['reside']))
			{
				$data_member['resideprovince'] = $_GPC['reside']['province'];
				$data_member['residecity'] = $_GPC['reside']['city'];
				$data_member['residedist'] = $_GPC['reside']['district'];
			}
			if ($_GPC['education']!='') $data_member['education'] = $_GPC['education'];
			if ($_GPC['constellation']!='') $data_member['constellation'] = $_GPC['constellation'];
			if ($_GPC['zodiac']!='') $data_member['zodiac'] = $_GPC['zodiac'];
			if ($_GPC['bloodtype']!='') $data_member['bloodtype'] = $_GPC['bloodtype'];
			
			if (empty($_W['member']['mobile'])){
				mc_update($_W['member']['uid'], $data_member);
			}
			//插入常用表单数据
			$data_member['rid'] = $recordid;
			$data_member['uniacid'] = $_W['uniacid'];
			pdo_insert('fx_form_data_common', $data_member);
			if ($pay_price > 0){//判断是否为付费活动
				die(json_encode(array('errno' => 0, 'type' => 1, 'recordid' => $recordid)));
				exit;
			}else{
				//积分变更
				fx_load()->model('credit');
				if ($_W['_config']['creditstatus'] == 1){
					if ($activity['prize']['credit'] > 0){
						$credit = $credit_double ? $activity['prize']['credit'] * 2 : $activity['prize']['credit'];
						$credit_result = credit_update_credit1($_W['member']['uid'], $credit, "参与活动奖励：增加" . $credit . '积分', $activity['merchantid']);
					}
					if($dc == 'yes'){ 
						credit_update_credit1($_W['member']['uid'], -1 * $deduct[0], "积分抵扣：减少" . $deduct[0] . '积分', $activity['merchantid']);
					}
					if($activity['costcredit']){
						credit_update_credit1($_W['member']['uid'], -1 * $activity['costcredit'], "参与活动消耗：减少" . $activity['costcredit'] . '积分', $activity['merchantid']);
					}
				}				
				
				//生成核销码、二维码
				//$listNum = pdo_fetchall("SELECT hexiaoma as num FROM " . tablename ('fx_activity_records') . " WHERE activityid = $activityid");
				//$ma['hexiaoma'] = createNumber($listNum);
				$ma['hexiaoma'] = $data['hexiaoma'] = createRandomNumber(8);
				pdo_update('fx_activity_records', $ma, array('id' => $recordid, 'uniacid' => $_W['uniacid']));
				createQrcode::creategroupQrcode($data['orderno']);
				if (!$activity['switch']['joinreview']) {//活动不开启审核发送通知
					//报名成功通知
					message::join_success($_W['openid'], $activity, $recordid, app_url('records/records/list'));
					if($activity['smsswitch']){//短信通知
						$smsparams=array(
							'product' => $_W['_config']['sname'],
							'item'    => $activity['title'],
							'name'    => $data['realname'],
							'timestr' => date('m月d日 H:i',strtotime($activity['starttime'])),
							'idcode'  => $data['hexiaoma']
						);
						$template_id = empty($activity['smsnotify']) ? $_W['_config']['sms_notify'] : $activity['smsnotify'];
						sendSMS($data['mobile'], $smsparams, $template_id, $_W['_config']['sms_type']);
					}
				}else{
					//审核通知
					message::join_review($_W['openid'], $activity, 2, app_url('records/records/list'));
				}
				if ($_W['_config']['mmsg']){//管理通知
					if ($activity['merchantid']) $merchant = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
					$openids = $activity['openids'];
					$openids = !empty($openids) ? $openids : unserialize($merchant['messageopenid']);
					$openids = !empty($openids) ? $openids : $_W['_config']['openids'];
					if (!empty($openids)){
						foreach($openids as $key=> $value){
							message::admin_notice($value, $activity, $recordid, '');
						}
					}
				}
								
				die(json_encode(array('errno' => 0, 'type' => 0, 'recordid' => $recordid)));
				exit;
			}
		}
	}
}

if($_W['isajax'] && $op =='validgnum'){//验证名额填写时的剩余名额；
	if ($gnum > 0){
		if($joinnum >= $gnum) {
			die(json_encode(array('errno'=>1,'message'=>"很遗憾！名额已经满了")));
		}elseif($joinnum + $buynum > $gnum){
			die(json_encode(array('errno'=>1,'message'=>"当前活动仅剩 " . ($gnum - $joinnum) . ' 个名额')));
		}
	}
	die(json_encode(array('errno'=>0)));
}