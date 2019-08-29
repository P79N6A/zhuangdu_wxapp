<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * records.ctrl
 * 报名记录管理控制器
 */
defined('IN_IA') or exit('Access Denied');
$op         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid    = intval($_W['uniacid']);
$activityid = intval($_GPC['activityid']) ? intval($_GPC['activityid']) : 0;
$keyword    = $_GPC['keyword'];
$status     = $_GPC['status'];
$filesize   = intval($_W['_config']['output']['pagesize']) ? intval($_W['_config']['output']['pagesize']) : 200;//数据导出每个文件数据条数
$merchantid = MERCHANTID ? MERCHANTID : $_GPC['merchantid'];

if ($op == 'output' && $_W['isajax']){
	$condition = " uniacid = $uniacid";	
	if ($merchantid > -1) {//商家筛选
		$condition .= " AND merchantid=$merchantid";
	}
	if (!empty($activityid)) {
		$condition .= " AND activityid = $activityid";
		$activity = model_activity::getSingleActivity($activityid, '*');
		$merchantid = $merchantid > -1 ? $merchantid : $activity['merchantid'];
		$sysform  = unserialize($activity['form']);
		$sysform['realname']['show'] = $sysform['realname']['show']=='' || $sysform['realname']['show'] ? 1 :0;
		$sysform['mobile']['show'] = $sysform['mobile']['show']=='' || $sysform['mobile']['show'] ? 1 : 0;
		
		//$sys_form = array_slice($sysform, 2);
	}
	if (!empty($keyword)) {
		$condition .= " AND (INSTR(`realname`, '$keyword') or INSTR(`mobile`, '$keyword') or INSTR(`nickname`, '$keyword') or INSTR(`optionname`, '$keyword') or hexiaoma='$keyword' or transid='$keyword' or uniontid='$keyword' or orderno='$keyword')";
	}
	if ($status!='') {
		switch($status){
		case 0:$condition .= " AND status = 0";$title.='-待支付';break;
		case 1:$condition .= " AND (status = 1 || status = 2)";$title.='-待参与';break;
		case 2:$condition .= " AND (status = 1 || status = 2)";$title.='-待参与';break;
		case 3:$condition .= " AND status = 3";$title.='-已参与';break;
		case 5:$condition .= " AND status = 5";$title.='-已取消';break;
		case 6:$condition .= " AND status = 6";$title.='-待退款';break;
		case 7:$condition .= " AND status = 7";$title.='-已退款';break;
		default:;
		}
	}
	//设置表头部信息
	$title = $activityid ? $activity['title'] : "报名数据-ALL";
	$headers = array('订单编号', '姓名', '昵称', '性别', '电话', '名额', '支付费用', '签到次数', '状态', '核销员', '活动规格', '交易单号');
	if (!empty($activityid)){
		//自定义表单标题
		$activityForm = model_activity::getNumActivityForm($activityid);
		foreach ($activityForm[0] as $form) {
			if ($form['fieldstype']!='gender' && $form['displaytype']!=5 && $form['displaytype']!=6) $headers[] = $form['title'];
		}
	}
	$headers = array_merge($headers,array('核销码','报名时间', '留言信息'));
	$columnCount = count($headers); 
	
	$pindex = max(1, intval($_GPC['page']));
	$psize  = $filesize;//数据导出每个文件数据条数
	$list   = pdo_fetchall ("SELECT * FROM " . tablename ('fx_activity_records') . " WHERE $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition");
	$tpage  = (empty($psize) || $psize < 0) ? 1 : ceil($total / $psize);
	
	if ($total) {
		session_start();
		$temp_folder = $_SESSION['temp_folder'] = empty($_SESSION['temp_folder']) ? date('YmdHis') : $_SESSION['temp_folder'];
		$temp_path   = FX_DATA . "/files/temp_$uniacid/" . $temp_folder;
		if (!file_exists($temp_path)){
			load()->func('file');
			mkdirs($temp_path);//创建目录
		}
		fx_load() -> model('member');
		$sheetName = ($pindex - 1) * $psize + 1 . '至' . (count($list) < $psize ? ($pindex - 1) * $psize + count($list) : $pindex * $psize) .' 条';
		$objExcel = Excel::newPHPExcel($sheetName, $title, $columnCount, $headers);
		$data = array();
		foreach ($list as $k => $v) {
			$formdata_common = Util::getSingelData('*', 'fx_form_data_common', array('rid'=>$v['id']));
			$formdata_common = empty($formdata_common)?getMember($v['openid']):$formdata_common;
			$thisstatus = '已生效';
			if ($v['status'] == '0') $thisstatus = '待支付';
			if ($v['status'] == '1') $thisstatus = '待参与';
			if ($v['status'] == '2') $thisstatus = '待参与';
			if ($v['status'] == '3') $thisstatus = '已参与';
			if ($v['status'] == '5') $thisstatus = '已取消';
			if ($v['status'] == '6') $thisstatus = '待退款';
			if ($v['status'] == '7') $thisstatus = '已退款';
			$payprice = '免费活动';
			if ($v['price'] > 0) $payprice =  $v['payprice'] > 0 ? $v['payprice'] : '0.00';
			$time = date('Y-m-d H:i',strtotime($v['jointime']));
			$activity = pdo_get('fx_activity', array('id' => $v['activityid']), array('title'));
			
			$data[$k][] = $v['orderno'];
			$data[$k][] = $v['realname'];
			$data[$k][] = $v['nickname'];
			$data[$k][] = $v['gender'];
			$data[$k][] = $v['mobile'];
			$data[$k][] = $v['buynum'];
			$data[$k][] = $payprice;
			$data[$k][] = $v['signin'];
			$data[$k][] = $thisstatus;
			
			$hexiao = getMember($v['veropenid']);
			$data[$k][] = empty($hexiao['nickname']) ? '后台核销' : $hexiao['nickname'];
			$data[$k][] = !empty($v['optionname']) ? $v['optionname']:'';
			$data[$k][] = !empty($v['transid']) ? $v['transid']:'';
			if (!empty($activityid)){
				foreach ($activityForm[0] as $form) {
					if (!empty($form['fieldstype'])){
						//常用表单
						if ($form['fieldstype']!='gender'){
							switch($form['fieldstype']){
								case 'age':$data[$k][] = $formdata_common['age'].'岁';break;
								case 'birthyear':$data[$k][] = $formdata_common['birthyear'].'年'.$formdata_common['birthmonth'].'月'.$formdata_common['birthday'].'日';break;
								case 'resideprovince':$data[$k][] = $formdata_common['resideprovince'].$formdata_common['residecity'].$formdata_common['residedist'];break;
								default:$data[$k][] = $formdata_common[$form['fieldstype']];
							}
						}
					}elseif($form['displaytype']!=5 && $form['displaytype']!=6){
						//自定义表单
						$formdata = model_records::getSingleFormData($v['id'],$form['id']);
						$data[$k][] = $form['displaytype'] == 7 || $form['displaytype'] == 8 ? str_replace(',','-',$formdata['data']) : $formdata['data'];
					}
				}
			}
			$data[$k][] = $v['hexiaoma'];
			$data[$k][] = $time;
			$data[$k][] = $v['msg'];
			Excel::fillExcelRow($objExcel, $data[$k], $k);
		}
		$excelName = "data_" . $pindex;
		Excel::saveExcelFile($objExcel, $excelName, $temp_path);
	}
	
	if ($pindex < $tpage || $tpage == 0){
		die(json_encode(array('total' => $total, 'tpage' => $tpage)));
	}else{
		$_SESSION['temp_folder'] = '';
		die(json_encode(array("errno" => 0, 'title' => $title, 'temp_folder' => $temp_folder,'message' => '生成完毕')));
	}
}

if ($op == 'download') {
	$temp_folder = $_GPC['temp_folder'];
	$file_title  = $_GPC['file_title'];
	Excel::getZipFile($temp_folder, $file_title, FX_DATA . "/files/temp_$uniacid/" . $temp_folder);
}

if ($op == 'display') {
	fx_load() -> model('member');
	$pindex = max(1, intval($_GPC['page']));		
	$psize = 10;
		
	if (MERCHANTID) {
		fx_load() -> model('permissions');
		$_W['allow'] = allow_params();
		$merchant = model_merchant::getSingleMerchant(MERCHANTID, 'id,name');
	}else{
		$merchantsData = model_merchant::getNumMerchant(0,0,0,0);
		$merchants     = $merchantsData[0];
	}
	
	$condition = " uniacid = $uniacid";	
	if ($merchantid > -1) {//商家筛选
		$condition .= " AND merchantid=$merchantid";
	}
	if (!empty($activityid)) {
		$condition .= " AND activityid = $activityid";
		$activity = pdo_get('fx_activity', array('id' => $activityid), array('title','merchantid','endtime'));
		$merchantid = $merchantid > -1 ? $merchantid : $activity['merchantid'];
	}
	if (!empty($keyword)) {
		$condition .= " AND (INSTR(`realname`, '$keyword') or INSTR(`mobile`, '$keyword') or INSTR(`nickname`, '$keyword') or INSTR(`optionname`, '$keyword') or hexiaoma='$keyword' or transid='$keyword' or uniontid='$keyword' or orderno='$keyword')";
	}
	if (empty($starttime) || empty($endtime)) {//初始化时间
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time']) && !empty($_GPC['timetype'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		switch($_GPC['timetype']){
			case 1:$condition .= " and UNIX_TIMESTAMP(jointime)>" . $starttime . " and UNIX_TIMESTAMP(jointime)<" . $endtime;break;
			case 2:$condition .= " and UNIX_TIMESTAMP(paytime)>" . $starttime . " and UNIX_TIMESTAMP(paytime)<" . $endtime;break;
			case 3:$condition .= " and UNIX_TIMESTAMP(sendtime)>" . $starttime . " and UNIX_TIMESTAMP(sendtime)<" . $endtime;break;
			default:break;
		}
	}
	$totals = array();
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 0");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 1");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 2");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 3");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 5");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 6");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition AND status = 7");
	$total_output = $totals[0];
	if ($status!='') {
		switch($status){
		case 0:$condition .= " AND status = 0";$total_output = $totals[1];break;
		case 1:$condition .= " AND (status = 1 || status = 2)";$total_output = $totals[2];break;
		case 2:$condition .= " AND (status = 1 || status = 2)";$total_output = $totals[3];break;
		case 3:$condition .= " AND status = 3";$total_output = $totals[4];break;
		case 5:$condition .= " AND status = 5";$total_output = $totals[5];break;
		case 6:$condition .= " AND status = 6";$total_output = $totals[6];break;
		case 7:$condition .= " AND status = 7";$total_output = $totals[7];break;
		default:;
		}
	}
	
	$records = pdo_fetchall ("SELECT * FROM " . tablename ('fx_activity_records') . " WHERE $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $condition");
	$pager = pagination($total, $pindex, $psize);	
	include fx_template ('records');
} 

if ($op == 'status' && $_W['isajax']) {
	$id = $_GPC['id'];
	$price = $_GPC['price'];
	if (is_array($id)){
		foreach ($id as $k => $rid) {
			$rid = intval($rid);
			$result = pdo_update ('fx_activity_records', array('review' => 1), array ('id' => $rid));
		}
		die(json_encode(array("errno" => 0,'message' => '审核成功！')));
	}else{
		$records = model_records::getSingleRecords($id);
		$paytype = empty($records['transid']) && empty($records['paytype']) ? 'admin' :$records['paytype'];
		$status = $_GPC['status'];
		switch($_GPC['status']){
			case 0:$message='待支付';$detail='取消支付';$data = array('payprice' => '', 'status' => $status, 'ishexiao' =>0, 'operation' => 'admin');break;
			case 1:$message='待参与';$detail='支付成功';
				   $data = array('payprice' => $price, 'status' => $status, 'ishexiao' =>0, 'operation' => 'admin', 'paytype'=>$paytype, 'paytime'=>date('Y-m-d H:i:s',TIMESTAMP));break;
			case 2:$message='待参与';$detail='免费活动';$data = array('status' => $status, 'ishexiao' =>0, 'operation' => 'admin');break;
			case 3:$message='已参与';$detail='后台核销';
				   $data = array('payprice' => $price, 'status' => $status, 'ishexiao' =>1, 'operation' => 'admin', 'sendtime'=>date('Y-m-d H:i:s',TIMESTAMP));break;
			case 5:$message='已取消';$detail='取消报名';$data = array('status' => $status, 'ishexiao' =>0, 'operation' => 'admin');break;
			default:;
		}
		if ($records['status']!=$status){
			$result = pdo_update ('fx_activity_records', $data, array ('id' => $id));
			$detail = $detail . '<br>订单号：' . $records['orderno'];
			if ($status==3)
			{
				if(!empty($records['merchantid']) && !empty($records['price'])){
					if(empty($records['payprice']))//未支付直接转核销
						model_merchant::updateAmount($records['price'], $records['merchantid'],$records['id'],1,'核销入账');  //主办方进账
					if($records['paytype']=='wechat' || $records['paytype']=='alipay') {//只的微信、支付宝成功支付的才可更新结算金额
						model_merchant::updateNoSettlementMoney($records['price'], $records['merchantid']);//更新可结算金额
						$type = 2;
					}else{
						$type = 6;
					}
					pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>$type,'detail'=>$detail));
				}
				$activity = model_activity::getSingleActivity($records['activityid'], '*');
				$url = app_url('records/records/list'); // 核销通知
				message::hexiao_notice($records['openid'], $activity, $url);
			}else{
				if(!empty($records['merchantid']) && !empty($records['price'])){
					if ($status==0 && !empty($records['payprice'])){
						model_merchant::updateAmount(0-$records['price'], $records['merchantid'],$records['id'],1,'取消支付');  //主办方减账
						pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>0,'detail'=>$detail));
					}
					if ($status==1 && empty($records['payprice'])){
						model_merchant::updateAmount($records['price'], $records['merchantid'],$records['id'],1,'订单支付');  //主办方进账
						pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>1,'detail'=>$detail));
					}
					if($records['status']==3){//取消核销
						if($records['paytype']=='wechat' || $records['paytype']=='alipay')//只有微信、支付宝成功支付的才可更新结算金额
						model_merchant::updateNoSettlementMoney(0-$records['price'], $records['merchantid']);//更新可结算金额
						pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>3,'detail'=>$detail));
					}
				}
			}
		}
		$message = $result ? $message : '重复操作：状态不做修改';
		die(json_encode(array("errno" => $result ? 0 : 1,'message' => $message)));
	}
	exit;
}

if ($op == 'hexiao' && $_W['isajax']) {
	set_time_limit(0);
	ini_set('max_execution_time', 0);
	$condition = " uniacid = $uniacid AND activityid = $activityid and status in(1,2)";
	$records = pdo_fetchall ("SELECT * FROM " . tablename ('fx_activity_records') . " WHERE $condition");
	foreach ($records as $k => $value) {
		$data = array('payprice' => $value['price'], 'status' => 3, 'ishexiao' =>1, 'operation' => 'admin', 'sendtime'=>date('Y-m-d H:i:s',TIMESTAMP));
		$result = pdo_update ('fx_activity_records', $data, array ('id' => $value['id']));
		if(!empty($value['merchantid']) && !empty($value['price'])){
			if(empty($value['payprice']))//未支付直接转核销
				model_merchant::updateAmount($value['price'], $value['merchantid'],$value['id'],1,'核销入账');  //主办方进账
			if($value['paytype']=='wechat' || $value['paytype']=='alipay') {//只的微信、支付宝成功支付的才可更新结算金额
				model_merchant::updateNoSettlementMoney($value['price'], $value['merchantid']);//更新可结算金额
				$type = 2;
			}else{
				$type = 6;
			}
			pdo_insert("fx_merchant_money_record",array('merchantid'=>$value['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$value['price'],'recordsid'=>$value['id'],'createtime'=>TIMESTAMP,'type'=>$type,'detail'=>'后台核销'));
		}
		$activity = model_activity::getSingleActivity($activityid, '*');
		$url = app_url('records/records/list'); // 核销通知
		message::hexiao_notice($value['openid'], $activity, $url);
	}
	die(json_encode(array("errno" => 0, 'message'=>'核销完成')));
	exit;
}

if ($op == 'delete' && $_W['isajax']) {
	$id = $_GPC['id'];
	load()->func('file');
	if (!is_array($id)){
		$id = intval($id);
		$row = pdo_fetch("SELECT id,orderno,pic FROM " . tablename('fx_activity_records') . " WHERE id = $id");
		if (empty($row)) {
			message('抱歉，用户不存在或是已经被删除！', web_url('records/records/display'), 'error');
		}
		$qrcode = IA_ROOT.'/addons/'. MODULE_NAME . '/data/qrcode/' . $uniacid . '/' . $row['orderno'] . '.png';
		file_delete($row['pic']);
		file_delete($qrcode);
		pdo_delete('fx_activity_records', array('id' => $id));
		pdo_delete('core_paylog', array('tid' => $row['orderno']));
		pdo_delete('fx_form_data', array('recordid' => $id));
		pdo_delete('fx_form_data_common', array('rid' => $id));
		//message('删除成功！', referer(), 'success');系统提示
		die(json_encode(array("errno" => 0,'message'=>'删除成功')));
		exit;
	}else{
		if (empty($id)){
			echo json_encode(array('errno' => 1, 'message'=>'至少选择一条信息'));
			exit;
		}
		foreach ($id as $k => $bid) {
			$id = intval($bid);		
			$row = pdo_fetch("SELECT id,orderno,pic FROM " . tablename('fx_activity_records') . " WHERE id = $id");
			if (empty($row)) {
				echo json_encode(array('errno' => 1, 'message'=>'内容不存在或者已被删除'));
				exit;
			}
			$qrcode = IA_ROOT.'/addons/'. MODULE_NAME .'/data/qrcode/' . $uniacid . '/' . $row['orderno'] . '.png';
			file_delete($row['pic']);
			file_delete($qrcode);
			pdo_delete('fx_activity_records', array('id' => $id));
			pdo_delete('core_paylog', array('tid' => $row['orderno']));
			pdo_delete('fx_form_data', array('recordid' => $id));
			pdo_delete('fx_form_data_common', array('rid' => $id));
		}
		die(json_encode(array("errno" => 0, 'message'=>'删除成功')));
		exit;
	}
}

if ($op == 'review' && $_W['isajax']) {
	$id = $_GPC['id'];
	$review = $_GPC['review'];
	if (is_array($id)){
		foreach ($id as $k => $bid) {
			$rid = intval($bid);
			$result = pdo_update ('fx_activity_records', array('review' => $review), array ('id' => $rid));
			$records = model_records::getSingleRecords($rid);
			$activity = model_activity::getSingleActivity($records['activityid'], '*');
			$url = app_url('records/records/list'); // 审核通知
			message::join_review($records['openid'], $activity, $review, $url);
			if($activity['smsswitch'] && $review){//短信通知
				$smsparams=array(
					'product' => $_W['_config']['sname'],
					'item'    => $activity['title'],
					'name'    => $records['realname'],
					'timestr' => date('m月d日 H:i',strtotime($activity['starttime'])),
					'idcode'  => $records['hexiaoma']
				);
				$template_id = empty($activity['smsnotify']) ? $_W['_config']['sms_notify'] : $activity['smsnotify'];
				sendSMS($records['mobile'], $smsparams, $template_id, $_W['_config']['sms_type']);
			}
		}
		die(json_encode(array("errno" => 0,'message' => $review ? '审核成功！' : "取消审核成功！")));
	}else{
		$result = pdo_update ('fx_activity_records', array('review' => $review), array ('id' => $id));
		$records = model_records::getSingleRecords($id);
		$activity = model_activity::getSingleActivity($records['activityid'], '*');
		$url = app_url('records/records/list'); // 审核通知
		message::join_review($records['openid'], $activity, $review, $url);
		if($activity['smsswitch'] && $review){//短信通知
			$smsparams=array(
				'product' => $_W['_config']['sname'],
				'item'    => $activity['title'],
				'name'    => $records['realname'],
				'timestr' => date('m月d日 H:i',strtotime($activity['starttime'])),
				'idcode'  => $records['hexiaoma']
			);
			$template_id = empty($activity['smsnotify']) ? $_W['_config']['sms_notify'] : $activity['smsnotify'];
			sendSMS($records['mobile'], $smsparams, $template_id, $_W['_config']['sms_type']);
		}
		die(json_encode(array("errno" => $result ? 0 : 1,'message' => $review ? '审核成功！' : "取消审核成功！")));
	}
	exit;
}
if($op == 'refund' && $_W['isajax']){
	$id = $_GPC['id'];
	$records = model_records::getSingleRecords($id);
	$res = model_records::refundMoney($id,$records['price'],'',2);
	if($res['status']){
		$records['status'] = 7;
		$url = app_url('order/order/detail',array('type'=>'u', 'id'=>$id)); // 审核通知
		message::refund($records['openid'], $records, $url);
	}
	die(json_encode($res));
	exit;
}
if($op == 'activity' && $_W['isajax']){
	$where['@title'] = trim($_GPC['title']);
	if ($_GPC['merchantid'] > -1) {//商家筛选
		$where['merchantid'] = $_GPC['merchantid'];
	}
	$activityData = Util::getNumData('id, title, endtime', 'fx_activity', $where, 'displayorder DESC,endtime DESC,id DESC', 1, 0, 1);
	$activity = $activityData[0];
	$data['list'] = $activityData[0];
	$data['total'] = $activityData[2];
	die(json_encode($data));
	exit;
}