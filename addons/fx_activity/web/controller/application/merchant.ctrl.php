<?php
$op = !empty($op) ? $op : 'display';
$id = $merchantid = MERCHANTID ? MERCHANTID : intval($_GPC['id']);

if($op == 'updateData'){
	$merchant = model_merchant::getSingleMerchant($merchantid, '*');
	$account =  pdo_fetch("SELECT amount,no_money,no_money_doing FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$merchantid}");
	if(!empty($merchant['percent']))
	$account['no_money'] = $account['no_money'];
	$records = pdo_fetchall("SELECT * FROM ".tablename('fx_merchant_record')." WHERE uniacid = {$_W['uniacid']} and merchantid={$merchantid}");
	$rm = $delivery = 0;
	foreach($records as $key => $val){
		$rm += $val['money'];
	}
	$orders = pdo_fetchall('select price,status,paytype from'.tablename('fx_activity_records').'where merchantid='.$merchantid.' and status in(1,3,4,6)');
	$km = 0;
	$zm = 0;
	foreach($orders as $ke => $va){
		if($va['pay_type']==4)  $delivery += $va['price'];
		if(in_array($va['status'], array(3,4)))
			$km += $va['price'];
		$zm += $va['price'];
		
	}
	
	$am = $km - $rm;
	$am = $am>0?$am:0;
	if($_GPC['submit']){
		$no_money = $_GPC['no_money'];
		$amount   = $_GPC['amount'];
		$data = array();
		if(!empty($no_money) && is_numeric($no_money)){
			$data['no_money'] = sprintf("%.2f", $no_money);			
		}
		if(!empty($amount) && is_numeric($amount)){
			$data['amount'] = sprintf("%.2f", $amount);			
		}
		if(!empty($data))
			$result = pdo_update('fx_merchant_account', $data, array('merchantid'=>$merchantid));
		if($result)
		message('成功', referer(), 'success');
		message('更新失败,金额输入错误', referer(), 'error');
	}
	include fx_template('application/merchant/updateData');
}

if($op == 'edit' || $op == 'setting') { //主办方编辑
	if(!empty($merchantid)){
		fx_load() -> model('member');
		$merchant = model_merchant::getSingleMerchant($merchantid, '*');
		$member = getMember($merchant['uid']);
		$saler = getMember($merchant['openid']);
		$messagesaler =  unserialize($merchant['messageopenid']);
		if(empty($merchant)) message('主办方信息不存在', web_url('application/merchant', array('op'=>'display')), 'success');
		$merchant['tag'] = unserialize($merchant['tag']);
		//wl_debug($merchant);
	}
	
	if (checksubmit()) {
		$data = $_GPC['merchant']; // 获取打包值
		$data_img = $_GPC['data_img'];
		$data_tag = $_GPC['data_tag'];
		$data_status = $_GPC['data_status'];
		$len = count($data_img);
		$data['tag'] = array();
		for ($k = 0; $k < $len; $k++) {
			$data['tag'][$k]['data_img'] = $data_img[$k];
			$data['tag'][$k]['data_tag'] = $data_tag[$k];
			$data['tag'][$k]['data_status'] = $data_status[$k];
		}
		$data['tag'] = serialize($data['tag']);
		$data['detail'] = htmlspecialchars_decode($data['detail']);
		$data['openid'] = $_GPC['openid']; 
		$data['messageopenid'] = serialize($_GPC['openids']);
		$data['allsalenum'] = intval($data['salenum'])  + $data['falsenum'];
		
		if(empty($merchant)){
			$data['uniacid'] = $_W['uniacid'];
			$data['createtime'] = TIMESTAMP;
			  
			if($data['open']==1){
				load()->model('user');
				fx_load()->model('merchant');
				if(!preg_match(REGULAR_USERNAME, $data['uname'])) {
					message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
				}
				if(merchant_check(array('uname' => $data['uname']))) {
					message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
				}else{
					$tpwd = trim($_GPC['tpwd']);
					$data['password'] = trim($data['password']);
					if(empty($data['password']) || empty($tpwd)){
						message('密码不能为空！');exit;
					}
					if($data['password']!=$tpwd){
						message('两次密码输入不一致！');exit;
					}
					if(istrlen($data['password']) < 8) {
						message('必须输入密码，且密码长度不得低于8位。');exit;
					}
				}
			}
			$data['password'] = user_hash($data['password'], '');
			$ret = pdo_insert('fx_merchant', $data);
		} else {
			$user = pdo_fetch("select * from".tablename("users")."where uid=:uid",array(':uid'=>$merchant['uid']));
			$opwd = trim($_GPC['opwd']);
			$npwd = trim($_GPC['npwd']);
			$tpwd = trim($_GPC['tpwd']);
			if($data['open']==2){
				//$ret = pdo_update('users', array('status'=>1), array('uid'=>$merchant['uid']));
			}else{
				if(empty($npwd)|| empty($tpwd)){
				}else{
					if($npwd!=$tpwd){
						message('两次密码输入不一致！');exit;
					}
					if(istrlen($npwd) < 8) {
						message('必须输入密码，且密码长度不得低于8位。');exit;
					}
					$data['password'] = user_hash($npwd, '');
				}
				
			}
			$ret = pdo_update('fx_merchant', $data, array('id'=>$merchantid));
			//$ret = pdo_update('users', array('password'=> $npwd,'status'=>2), array('uid'=>$merchant['uid']));
		}
		if (!MERCHANTID)
		message('主办方信息保存成功', web_url('application/merchant/display', array('id'=>$merchantid)), 'success');
		else
		message('主办方信息保存成功', web_url('application/merchant/setting'), 'success');
	}
	
	include fx_template('application/merchant/merchant');
}
if($op == 'delete') { //删除主办方
	$merchant = model_merchant::getSingleMerchant($merchantid, 'uid');
	if(empty($merchantid)) message('主办方不存在');
	if(pdo_delete('fx_merchant', array('id'=>$merchantid, 'uniacid'=>$_W['uniacid']))){
		//pdo_delete('users', array('uid'=>$merchant['uid']));
		message('删除主办方成功.', web_url('application/merchant'), 'success');
	} else {
		message('删除主办方失败.');
	}
}
if($op == 'display'){ //可结算主办方列表
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$where=array();
	if($_GPC['keyword']) $where['id^uname^name^linkman_name^linkman_mobile'] = $_GPC['keyword'];
	$status = intval($_GPC['status']);
	if ($status) {
		switch($status){
		case 1:
			$where['sql'][] = '(select count(1) as num from '.tablename ('fx_merchant_mcert') . ' B where '.tablename ('fx_merchant').'.id = B.mid) = 0 ';
			break;
		case 2:
			$where['sql'][] = '(select count(1) as num from '.tablename ('fx_merchant_mcert') . ' B where '.tablename ('fx_merchant').'.id = B.mid AND B.status=1) > 0 ';
			break;
		case 3:
			$where['sql'][] = '(select count(1) as num from '.tablename ('fx_merchant_mcert') . ' B where '.tablename ('fx_merchant').'.id = B.mid AND B.status=0) > 0 ';
			break;
		case 4:
			$where['sql'][] = '(select count(1) as num from '.tablename ('fx_merchant_mcert') . ' B where '.tablename ('fx_merchant').'.id = B.mid AND B.status=2) > 0 ';
			break;
		default:;
		}
	}	
	$merchantsData = Util::getNumData('*', 'fx_merchant', $where, 'id desc', $pindex,$psize,1);
	$merchants = $merchantsData[0];
	$pager = $merchantsData[1];
	
	$inner  = tablename ('fx_merchant') . " A, " . tablename ('fx_merchant_mcert') . " B";
	$totals = array();
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename ('fx_merchant') . ' WHERE uniacid = :uniacid', array(':uniacid'=>$_W['uniacid']));
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename ('fx_merchant') . ' A WHERE (select count(1) as num from '.tablename ('fx_merchant_mcert') . ' B where A.id = B.mid) = 0 AND uniacid = :uniacid', array(':uniacid'=>$_W['uniacid']));
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . $inner . ' WHERE A.id = B.mid AND status=1 AND A.uniacid = :uniacid', array(':uniacid'=>$_W['uniacid']));
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . $inner . ' WHERE A.id = B.mid AND status=0 AND A.uniacid = :uniacid', array(':uniacid'=>$_W['uniacid']));
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . $inner . ' WHERE A.id = B.mid AND status=2 AND A.uniacid = :uniacid', array(':uniacid'=>$_W['uniacid']));
	$status = intval($_GPC['status']);
	foreach($merchants as$key=>$value){
		$account =  pdo_fetch("SELECT amount,no_money FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$value['id']}");
		$merchants[$key]['amount'] = $account['amount'];
		$merchants[$key]['no_money'] = $account['no_money'];
		$delivery = 0;
		$delivery = pdo_fetchcolumn("select SUM(price) from".tablename('fx_activity_records')." where (paytype='delivery' or paytype='admin') and status in(3) and merchantid={$value['id']}");
		$merchants[$key]['delivery'] = $delivery;
		$merchants[$key]['mcert'] = Util::getSingelData('*', 'fx_merchant_mcert', array('mid' => $value['id']));
		
	}
	include fx_template('application/merchant/merchant');
}
if($op == 'data_record'){ //金额变动记录
	$merchant = model_merchant::getSingleMerchant($merchantid, 'logo,name,openid,percent');
	$account =  pdo_fetch("SELECT * FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$merchantid}");
	$delivery = pdo_fetchcolumn("select SUM(price) from".tablename('fx_activity_records')." where (paytype='delivery' or paytype='admin') and status in(3) and merchantid={$merchantid}");
	$merchant['amount'] = $account['amount'];
	$merchant['delivery'] = $delivery;
	$merchant['no_money'] = $account['no_money'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$moneyRecordData = model_merchant::getMoneyRecord($merchantid, $pindex, $psize, 1);
	$moneyRecord = $moneyRecordData[0];
	$pager = $moneyRecordData[1];
	foreach($moneyRecord as &$item){
		if($item['recordsid'])$item['records'] = model_records::getSingleRecords($item['recordsid'], '*');
	}
	include fx_template('application/merchant/account');
}
if($op == 'account') { //结算操作
	$_GPC['accountType'] = $_GPC['accountType']?$_GPC['accountType']:'weixin';
	$merchant = model_merchant::getSingleMerchant($merchantid, '*');
	$account =  pdo_fetch("SELECT * FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$merchantid}");
	$delivery = pdo_fetchcolumn("select SUM(price) from".tablename('fx_activity_records')." where (paytype='delivery' or paytype='admin') and status in(3) and merchantid={$merchantid}");
	$merchant['amount'] = $account['amount'];
	$merchant['delivery'] = $delivery;
	$merchant['no_money'] = $account['no_money'];
	$merchant['no_money_doing'] = $account['no_money_doing'];
	if (checksubmit('submit') && $_GPC['accountType']=='weixin') {
		/*先判断是否有正在申请中的结算申请*/
		if($account['no_money_doing']>0) message('上一笔申请未处理完成，不可重复操作！', referer(), 'error');		
		/*先判断是否有正在申请中的结算申请*/
		if(empty($merchant['openid'])) message('未绑定提现微信号！', referer(), 'error');
		$money = $_GPC['money'];//提现金额（元）
		$no_money = model_merchant::getNoSettlementMoney($merchantid); //未结算金额
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
			if($get_money<$minimum) message('输入金额不得小于'.$minimum.'元', referer(), 'error');
			if($get_money>$maximum) message('单次提现金额不得大于'.$maximum.'元', referer(), 'error');
			if($no_money<$money) message('您没有足够的可结算金额！', referer(), 'error');
			
			$orderno = date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
			$time = TIMESTAMP;
			if(MERCHANTID){//主办方提现
				pdo_update('fx_merchant_account',array('no_money_doing'=>$money),array('merchantid'=>$merchantid));
				pdo_insert("fx_merchant_record",array('status'=>1,'updatetime'=>$time,'percent'=>$merchant['percent'],'get_money'=>$get_money,'merchantid'=>$merchantid,'money'=>$money,'commission'=>$commission,'uid'=>$_W['uid'],'createtime'=>$time,'uniacid'=>$_W['uniacid'],'orderno'=>$orderno));
			}else{
				$result = model_merchant::finance($merchant['openid'], $get_money * 100, '主办方提现');  //结算操作
				if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS'){
					message('微信钱包提现失败: ' .$result['err_code']."|" .$result['err_code_des'], '', 'error'); // 结算失败
				}else{ //结算成功
					pdo_insert("fx_merchant_money_record",array('merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'money'=>0-$get_money,'recordsid'=>'','createtime'=>$time,'type'=>4,'detail'=>$orderno));
					if($commission>0)
					pdo_insert("fx_merchant_money_record",array('merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'money'=>0-$commission,'recordsid'=>'','createtime'=>$time,'type'=>7,'detail'=>$orderno));
					$res = model_merchant::updateNoSettlementMoney(0-$money, $merchantid);
					if($res){
						pdo_update('fx_merchant_account',array('no_money_doing'=>0),array('merchantid'=>$merchantid));
						pdo_insert("fx_merchant_record",array('type'=>1,'status'=>3,'updatetime'=>$time,'percent'=>$merchant['percent'],'get_money'=>$get_money,'merchantid'=>$merchantid,'money'=>$money,'commission'=>$commission,'uid'=>$_W['uid'],'createtime'=>$time,'uniacid'=>$_W['uniacid'],'orderno'=>$orderno));
					}else{
						message('结算成功，更新结算金额失败！', '', 'error'); // 结算失败
					}
				}
			}
		}else{
			message('结算金额输入错误！', referer(), 'error');
		}
		message('操作成功！', referer(), 'success');
	}
	if (checksubmit('submit') && $_GPC['accountType']=='f2f') {
		/*先判断是否有正在申请中的结算申请*/
		if($account['no_money_doing']>0) message('上一笔申请未处理完成，不可重复操作！', referer(), 'error');	
		$money = $_GPC['money'];//提现金额（元）
		$no_money = model_merchant::getNoSettlementMoney($merchantid); //未结算金额
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
			if($get_money<$minimum) message('输入金额不得小于'.$minimum.'元', referer(), 'error');
			if($get_money>$maximum) message('单次提现金额不得大于'.$maximum.'元', referer(), 'error');
			if($no_money<$money) message('您没有足够的可结算金额！', referer(), 'error');
			
			$orderno = date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
			$time = TIMESTAMP;
			//结算成功
			pdo_insert("fx_merchant_money_record",array('merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'money'=>0-$get_money,'recordsid'=>'','createtime'=>$time,'type'=>4,'detail'=>$orderno));
			if($commission>0)
			pdo_insert("fx_merchant_money_record",array('merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'money'=>0-$commission,'recordsid'=>'','createtime'=>$time,'type'=>7,'detail'=>$orderno));
			$res = model_merchant::updateNoSettlementMoney(0-$money, $merchantid);
			if($res){
				pdo_update('fx_merchant_account',array('no_money_doing'=>0),array('merchantid'=>$merchantid));
				pdo_insert("fx_merchant_record",array('type'=>2,'status'=>3,'updatetime'=>$time,'percent'=>$merchant['percent'],'get_money'=>$get_money,'merchantid'=>$merchantid,'money'=>$money,'commission'=>$commission,'uid'=>$_W['uid'],'createtime'=>$time,'uniacid'=>$_W['uniacid'],'orderno'=>$orderno));
			}else{
				message('结算成功，更新结算金额失败！', referer(), 'success');
			}
				
		}else{
			message('结算金额输入错误！', referer(), 'error');
		}
		message('操作成功！', referer(), 'success');
	}
	include fx_template('application/merchant/account');
}
if($op == 'record') { //结算记录
	$merchant = model_merchant::getSingleMerchant($merchantid, 'logo,name,openid,percent');
	$account =  pdo_fetch("SELECT * FROM ".tablename('fx_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$merchantid}");
	$delivery = pdo_fetchcolumn("select SUM(price) from".tablename('fx_activity_records')." where (paytype='delivery' or paytype='admin') and status in(3) and merchantid={$merchantid}");
	$merchant['amount'] = $account['amount'];
	$merchant['delivery'] = $delivery;
	$merchant['no_money'] = $account['no_money'];
	$merchant['no_money_doing'] = $account['no_money_doing'];
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$where=array();
	if(MERCHANTID)
	$where['merchantid'] = MERCHANTID;
	$merchantsData = Util::getNumData('*', 'fx_merchant_record', $where, 'id desc', $pindex,$psize,1);
	$list = $merchantsData[0];
	$pager = $merchantsData[1];
	include fx_template('application/merchant/account');
}
if($op == 'permissions'){ //权限
	$nodes = pdo_fetchall("select * from".tablename('fx_user_node')." where status=2 and do_id=0");
	foreach($nodes as $key=>&$value){
		$value['children'] = pdo_fetchall("select * from".tablename('fx_user_node')." where status=2 and do_id={$value['id']} and ac_id=0");
		foreach($value['children'] as $k=>&$v){
			$v['children'] = pdo_fetchall("select * from".tablename('fx_user_node')." where status=2 and do_id={$value['id']} and ac_id={$v['id']}");
		}
	}
	$role = pdo_fetch("select * from".tablename('fx_user_role')."where uniacid={$_W['uniacid']} and merchantid={$merchantid}");
	if (checksubmit('submit')) {
		$nodes = $_GPC['node_ids'];
		$nodes=serialize($nodes);
		$data = array(
			'merchantid'=>$merchantid,
			'nodes'=>$nodes,
			'uniacid'=>$_W['uniacid']
		);
		if($role){
			pdo_update('fx_user_role',$data,array('merchantid'=>$merchantid));
		}else{
			pdo_insert('fx_user_role',$data);
		}
		message('保存成功！', referer(), 'success');
	}
	if($role){
		$role['node_ids'] = unserialize($role['nodes']);
	}
	$role['node_ids'] = !empty($role['node_ids']) ? $role['node_ids'] : array();
	include fx_template('application/merchant/permissions');
}
if($op == 'merchantAccount'){//商户中心（主办方登陆可见）
	if(!MERCHANTID) message('非法入口!');
	header('Location:'.web_url('application/merchant/data_record',array('id'=>MERCHANTID)));
}
if($op == 'merchantApply'){ //提现处理
	$status = $_GPC['status']?$_GPC['status']:1;
	if($_GPC['id']){
		$record = pdo_fetch("select * from".tablename('fx_merchant_record')."where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
		$merchant = model_merchant::getSingleMerchant($record['merchantid'], '*');
		$orderno = $record['orderno'];
		$time = TIMESTAMP;
		if($_GPC['type']==1){
			pdo_update('fx_merchant_record',array('status'=>2,'updatetime'=>$time),array('id'=>$_GPC['id']));
			message('已确认待打款！', web_url('application/merchant/merchantApply',array('status'=>2)), 'success');
		}elseif($_GPC['type']==2){//打款到微信钱包
			if($record['status']==3) message('当前提现已完成，无需重复处理！', referer(), 'error');
			if(empty($merchant['openid'])) message('您未绑定提现微信号！', referer(), 'error');			
			$money = $record['money'];//实扣金额（元）
			$get_money = $record['get_money'];//实到金额（元）
			$commission = sprintf("%.2f",$record['commission']);//手续费
			$no_money  = model_merchant::getNoSettlementMoney($record['merchantid']); //未结算金额
			$minimum = empty($_W['_config']['merch_amount']) ? 1 : $_W['_config']['merch_amount'];//每笔提现金额最低值
			$maximum = empty($_W['_config']['merch_maximum']) ? 10000 : $_W['_config']['merch_maximum'];//每笔提现金额最大值
			
			if($get_money<$minimum) message('输入金额不得小于'.$minimum.'元', referer(), 'error');
			if($get_money>$maximum) message('单次提现金额不得大于'.$maximum.'元', referer(), 'error');
			if($no_money<$money) message('您没有足够的可结算金额！', referer(), 'error');
			$result = model_merchant::finance($merchant['openid'], $get_money * 100, '主办方提现');  //结算操作
			if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS'){
				message('微信钱包提现失败: ' .$result['err_code']."|" .$result['err_code_des'], '', 'error'); // 结算失败
			}else{
				pdo_update('fx_merchant_account',array('no_money_doing'=>0),array('merchantid'=>$record['merchantid']));
				pdo_insert("fx_merchant_money_record",array('merchantid'=>$record['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$get_money,'recordsid'=>'','createtime'=>$time,'type'=>4,'detail'=>$orderno));
				if($commission>0)
				pdo_insert("fx_merchant_money_record",array('merchantid'=>$record['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$commission,'recordsid'=>'','createtime'=>$time,'type'=>7,'detail'=>$orderno));
				$res = model_merchant::updateNoSettlementMoney(0-$money, $record['merchantid']);
				if($res){
					pdo_update('fx_merchant_record',array('status'=>3,'updatetime'=>$time,'type'=>1),array('id'=>$_GPC['id']));
				}else{
					message('打款成功，更新结算金额失败！', referer(), 'success');
				}
					
			} 
			message('打款成功！', web_url('application/merchant/merchantApply',array('status'=>3)), 'success');
		}elseif($_GPC['type']==3){ //手动处理，不打款
			if($record['status']==3) message('当前提现已完成，无需重复处理！', referer(), 'error');
			$money = $record['money'];//实扣金额（元）
			$get_money = $record['get_money'];//实到金额（元）
			$commission = sprintf("%.2f",$record['commission']);//手续费
			$no_money  = model_merchant::getNoSettlementMoney($record['merchantid']); //未结算金额
			$minimum = empty($_W['_config']['merch_amount']) ? 1 : $_W['_config']['merch_amount'];//每笔提现金额最低值
			$maximum = empty($_W['_config']['merch_maximum']) ? 10000 : $_W['_config']['merch_maximum'];//每笔提现金额最大值
			
			if($get_money<$minimum) message('输入金额不得小于'.$minimum.'元', referer(), 'error');
			if($get_money>$maximum) message('单次提现金额不得大于'.$maximum.'元', referer(), 'error');
			if($no_money<$money) message('您没有足够的可结算金额！', referer(), 'error');
			pdo_update('fx_merchant_account',array('no_money_doing'=>0),array('merchantid'=>$record['merchantid']));
			pdo_insert("fx_merchant_money_record",array('merchantid'=>$record['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$get_money,'recordsid'=>'','createtime'=>$time,'type'=>4,'detail'=>$orderno));
			if($commission>0)
			pdo_insert("fx_merchant_money_record",array('merchantid'=>$record['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$commission,'recordsid'=>'','createtime'=>$time,'type'=>7,'detail'=>$orderno));
			$res = model_merchant::updateNoSettlementMoney(0-$money, $record['merchantid']);
			if($res){//更新结算后主办方可结算余额
				pdo_update('fx_merchant_record',array('status'=>3,'updatetime'=>$time,'type'=>2),array('id'=>$_GPC['id']));
			}else{
				message('结算成功，更新结算金额失败！', referer(), 'success');
			}
			message('手动处理成功！', web_url('application/merchant/merchantApply',array('status'=>3)), 'success');
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$where=array();
	if(MERCHANTID)
	$where['merchantid'] = MERCHANTID;
	$where['status'] = $status;
	$merchantsData = Util::getNumData('*', 'fx_merchant_record', $where, 'id desc', $pindex,$psize,1);
	$list = $merchantsData[0];
	$pager = $merchantsData[1];
	foreach($list as$key=>&$value){
		$value['merchant'] = model_merchant::getSingleMerchant($value['merchantid'], 'thumb,name,openid,percent');
	}
	include fx_template('application/merchant/cashConfirm');
}
if($op == 'tag'){
	include fx_template('application/merchant/imgandtag');
}
if($op == 'cover'){ //主办方入口
	require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
	$cover = $_GPC['type']?$_GPC['type']:'web';
	$url = app_url('goods/list/merchant',array('id'=>MERCHANTID)) ;
	QRcode :: png($_GPC['url'], false, QR_ECLEVEL_H, 4);
	include fx_template('application/merchant/cover');
}
if($op == 'search'){ //选择通知管理员
	$con = $con2  = "uniacid='{$_W['uniacid']}' ";
	$keyword = $_GPC['keyword'];
	$type = $_GPC['t'];
	$con     = "A.uniacid='{$_W['uniacid']}' and A.uid = B.uid ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and (A.nickname LIKE '%{$keyword}%' or B.openid LIKE '%{$keyword}%' or A.uid LIKE '%{$keyword}%') ";
	}
	$field  = "A.*, avatar, B.openid";
	$inner  = tablename ('mc_members') . "A , " . tablename ('mc_mapping_fans') . "B ";
	$ds = pdo_fetchall("select ".$field." from" . $inner . "where $con");
	
	include fx_template('application/merchant/select_merchanter');
	exit;
}
if($op == 'mcert'){
	$merchant = model_merchant::getSingleMerchant($_GPC['mid'], '*');
	$mcert = Util::getSingelData('*', 'fx_merchant_mcert', array('mid' => $_GPC['mid']));
	if (!empty($mcert)) $mcert['detail'] = unserialize($mcert['detail']);
	if ($_W['ispost']){
		if ($_GPC['status']){
			$data['status'] = $_GPC['status'];
			$data['createtime'] = TIMESTAMP;
			$data['endtime'] = strtotime("+1 year", TIMESTAMP);
			$result = pdo_update('fx_merchant_mcert', $data, array('mid' => $_GPC['mid']));
			fx_load()->model('mc');
			if (mc_fans_follow($mcert['openid'])){
				$params = array(
					'type' => $mcert['type'],
					'name' => $mcert['detail']['name'],
					'mname' => $merchant['name'],
					'status' => $_GPC['status'],
					'createtime' => $mcert['createtime'],
					'remark' => $_GPC['messge_remark'],
				);
				$url = app_url('member/merch/display');
				message::mcert_review($mcert['openid'], $params, $url);
			}
		}
		die(json_encode(array('errno' => $result ? 0 : 1)));
		exit;
	}
	include fx_template('application/merchant/mcertData');
}
?>