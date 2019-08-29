<?php
/**
 * 个人名片模块微站定义
 *
 * @author 悟空源码社区
 * @url http://www.5kym.cn/
 */
defined('IN_IA') or exit('Access Denied');

class Api0351_userModuleSite extends WeModuleSite {
	
	
	const TABLE_A = 'api_user_class';
	const TABLE_B = 'api_user_opinion';
	const TABLE_C = 'api_user_ms';
	const TABLE_D = 'api_user_more';
	const TABLE_F = 'api_user_pay';
	const TABLE_G = 'api_user_shop';
	const TABLE_H = 'api_user_trade';
	const TABLE_I = 'api_user_banner';
	const TABLE_J = 'api_user_message';
	const TABLE_K = 'api_user_news';
	const TABLE_L = 'api_user_reward';
	const TABLE_M = 'api_user_config';

	public $settings;
	public function __construct() {
		global $_W;
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'api0351_user'));
		$this->settings = iunserializer($settings);
	}
	
	public function doWebSetconfig(){
		global $_W, $_GPC;
		
		if($_GPC['action'] == NULL || $_GPC['action'] == 'news'){
			if (checksubmit('submit')) {
				$data = $_GPC['data'];
				foreach ($data as $key => $v){
					pdo_update(self::TABLE_M, array('value' => $v),array('key' => $key));
				}
				message('设置成功',$this->createWebUrl('Setconfig'),'success');
			}else{
				$setConfig = pdo_fetchall("SELECT * FROM ".tablename('api_user_config'));
				include $this->template('setconfig');
			}
		}else if($_GPC['action'] == 'wxtemp'){
						if (checksubmit('submit')) {
				$data = $_GPC['data'];
				foreach ($data as $key => $v){
					pdo_update(self::TABLE_M, array('value' => $v),array('key' => $key));
				}
				message('设置成功',$this->createWebUrl('Setconfig'),'success');
			}else{
				$setConfig = pdo_fetchall("SELECT * FROM ".tablename('api_user_config'));
				include $this->template('setconfig_wx');
			}
		}
		
		
	}

	public function doWebCardr() {
		global $_GPC, $_W;
		load()->func('tpl');
		$examine = $_W['current_module']['config']['examine'];
		if($_GPC['action'] == NULL || $_GPC['action'] == 'news'){
			
			if($_W['current_module']['config']['payStart'] == 1){
				$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
				$querya = load()->object('query');
				$querya->from('api_user_more')->where(array('uptime >=' => $today_start,'uptime <='=>$today_end,'uniacid' => $_W[uniacid]))->page(20)->getall();
				$todayPay = $querya->getLastQueryTotal();
				
				$yesterday_start=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
				$yesterday_end=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
				$queryb = load()->object('query');
				$queryb->from('api_user_more')->where(array('uptime >=' => $yesterday_start, 'uptime <=' => $yesterday_end, 'uniacid' => $_W[uniacid]))->page(20)->getall();
				$yesterdayPay = $queryb->getLastQueryTotal();
	
			}else{
				$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
				$querya = load()->object('query');
				$querya->from('api_user_more')->where(array('uptime >=' => $today_start,'uptime <='=>$today_end,'uniacid' => $_W[uniacid],'user_vip' => 1))->page(20)->getall();
				$todayPay = $querya->getLastQueryTotal();
				
				$yesterday_start=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
				$yesterday_end=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
				$queryb = load()->object('query');
				$queryb->from('api_user_more')->where(array('uptime >=' => $yesterday_start, 'uptime <=' => $yesterday_end, 'uniacid' => $_W[uniacid],'user_vip' => 1))->page(20)->getall();
				$yesterdayPay = $queryb->getLastQueryTotal();
				
			}

			$queryc = load()->object('query');
			$queryc->from('api_user_more')->where(array('uptime !=' => 0,'uniacid'=>$_W[uniacid]))->page(20)->getall();
			$okPay = $queryc->getLastQueryTotal();
			
			
			$examine = $_W['current_module']['config']['examine'];
			if($examine == 1){
				if(!empty($_GPC['keyword'])) {
					$condition .= " WHERE nickName LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND examine=1 AND uniacid=".$_W['uniacid'];
				}else{
					$condition .= " WHERE uptime!=0 AND examine=1 AND uniacid=".$_W['uniacid'];
				}
			}else{
				if(!empty($_GPC['keyword'])) {
					$condition .= " WHERE nickName LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND uniacid=".$_W['uniacid'];
				}else{
					$condition .= " WHERE uptime!=0 AND uniacid=".$_W['uniacid'];
				}
			}
			/*******************************/

			$sql = "SELECT * FROM ".tablename('api_user_more').$condition;
			$total = pdo_fetch("SELECT COUNT(*) AS count FROM".tablename('api_user_more').$condition);
			//var_dump($total);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total['count'],$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			/*******************************/	
			include $this->template('cardr');
			
		}else if($_GPC['action'] == 'verify'){	
			$data = array(
				'examine' => $_GPC['examine'],
			);
			$result = pdo_update('api_user_more', $data, array('id' => $_GPC['id'], 'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('审核成功',$this->createWebUrl('Cardr'),'success');
			}
			
		}else if($_GPC['action'] == 'examine'){
			
			/*******************************/
			$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime!=0 AND examine=0 AND uniacid=".$_W['uniacid'];
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('examine');
			
		}else if($_GPC['action'] == 'today'){
			
			/*******************************/
			$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime=0 AND uniacid=".$_W['uniacid'];
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			/*******************************/	
			include $this->template('today');
			
		}else if($_GPC['action'] == 'add'){
			if (checksubmit('submit')) {
				
				if($_GPC['cid'] > 0){
					$data = array(
						'indes' => $_GPC['cid'],
						'avatarUrl' => $_W['attachurl'].$_GPC['avatarUrl'],
						'nickName' => $_GPC['nickName'],
						'user_zc' => $_GPC['user_zc'],
						'user_weixin' => $_GPC['user_weixin'],
						'mobile' => $_GPC['mobile'],
						'user_gs' => $_GPC['user_gs'],
						'signature' => $_GPC['signature'],
						'uniacid' => $_W['uniacid'],
						'uptime' => $_W['timestamp']
					);
					$result = pdo_insert(self::TABLE_D, $data);
					if (!empty($result)) {
						message('新增成功',$this->createWebUrl('Cardr'),'success');
					}
				}else{
					message('请先选择分类',$this->createWebUrl('Cardr'),'error');
				}
				
			}else{
				/*******************************/
				$okClass = pdo_fetchall("SELECT *  FROM ".tablename('api_user_class')." WHERE uniacid=$_W[uniacid]");
				/*******************************/	
				include $this->template('cardr_add');
			}
		}else if($_GPC['action'] == 'edit'){

			$avatar = explode('/', $_GPC['avatarUrl']);
			if(in_array("https:", $avatar)){
				$avatarUrl = $_GPC['avatarUrl'];
			}else{
				$avatarUrl = $_W['attachurl'].$_GPC['avatarUrl'];
			}
			
			if (checksubmit('submit')) {
				if($_GPC['cid'] > 0){
					$data = array(
						'indes' => $_GPC['cid'],
						'avatarUrl' => $avatarUrl,
						'nickName' => $_GPC['nickName'],
						'heat' => $_GPC['heat'],
						'user_zc' => $_GPC['user_zc'],
						'mobile' => $_GPC['mobile'],
						'user_gs' => $_GPC['user_gs'],
						'signature' => $_GPC['signature'],
						'user_vip' => $_GPC['user_vip'],
						'overtime' => strtotime($_GPC['overtime'])
					);
					$result = pdo_update(self::TABLE_D, $data, array('id' => $_GPC['id'], 'uniacid' => $_W['uniacid']));
					if (!empty($result)) {
						message('修改成功',$this->createWebUrl('Cardr'),'success');
					}else{
						message('好像没什么变动呀',$this->createWebUrl('Cardr'),'error');
					}
				}else{
					message('请先选择分类',$this->createWebUrl('Cardr'),'error');
				}
			}else{
				if($_GPC['id']){
					$ed_ac = pdo_get(self::TABLE_D, array(
						'id'   => $_GPC['id'],
						'uniacid' => $_W['uniacid']
					));	
				}else if($_GPC['op']){
					$ed_ac = pdo_get(self::TABLE_D, array(
						'openid'   => $_GPC['op'],
						'uniacid' => $_W['uniacid']
					));
				}
				/*******************************/
				$okClass = pdo_fetchall("SELECT *  FROM ".tablename('api_user_class')." WHERE uniacid=$_W[uniacid]");
				/*******************************/	
				include $this->template('cardr_edit');
			}
		}else if($_GPC['action'] == 'display'){
			$data = array(
				'display' => $_GPC['display'],
			);
			$result = pdo_update(self::TABLE_D, $data, array('id' => $_GPC['id'], 'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('设置成功',$this->createWebUrl('Cardr'),'success');
			}
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_D, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Cardr'),'success');
			}
		}
	}
	public function doWebMessage() {
		global $_GPC, $_W;
		if($_GPC['action'] == NULL){
			/*******************************/
			$sql = "SELECT * FROM ".tablename('api_user_ms')." WHERE  uniacid = $_W[uniacid]";
			$message = pdo_fetchall($sql);
			$total = count($message);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY mid DESC LIMIT ".$p.",".$pagesize;
			$message = pdo_fetchall($sql);
			/*******************************/	
			include $this->template('message');
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_C, array('mid' => $_GPC['mid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Message'),'success');
			}
		}
	}
	public function doWebFeedback() {
		global $_GPC, $_W;
		if($_GPC['action'] == NULL || $_GPC['action'] == 'noid'){
			/*******************************/
			//$sql = "SELECT * FROM ".tablename('api_user_opinion')." WHERE  uniacid = $_W[uniacid]";
			$sql = "SELECT *,a.id as oid FROM ".tablename('api_user_opinion')." a left join ".tablename('api_user_more')." b on a.openid = b.openid WHERE a.uniacid = '$_W[uniacid]' AND a.jid = 0";
			$fek = pdo_fetchall($sql);
			$total = count($fek);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY a.time DESC LIMIT ".$p.",".$pagesize;
			$fek = pdo_fetchall($sql);
			//var_dump($fek);
			/*******************************/	
			include $this->template('feedback');
		}else if($_GPC['action'] == 'youid'){
			/*******************************/
			$sql = "SELECT *,a.id as oid FROM ".tablename('api_user_opinion')." a left join ".tablename('api_user_more')." b on a.op = b.openid WHERE a.uniacid = '$_W[uniacid]' AND a.jid = 1";
			$fek = pdo_fetchall($sql);
			$total = count($fek);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY a.time DESC LIMIT ".$p.",".$pagesize;
			$fek = pdo_fetchall($sql);
			//var_dump($fek);
			/*******************************/	
			include $this->template('feedback_y');
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_B, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Feedback'),'success');
			}
			
		}else if($_GPC['action'] == 'dis'){
			$data = array(
				'dis' => 1,
			);
			$result = pdo_update(self::TABLE_B, $data, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('操作成功',$this->createWebUrl('Feedback'),'success');
			}else{
				message('好像没什么变动呀',$this->createWebUrl('Feedback'),'error');
			}	
		}
	}
	
	public function doWebIndustry() {
		global $_GPC, $_W;
		load()->func('tpl');
		if($_GPC['action'] == NULL){
			/*******************************/
			$sql = "SELECT * FROM ".tablename('api_user_class')." WHERE display=1 AND uniacid = $_W[uniacid] ORDER BY sort ASC";
			$clas = pdo_fetchall($sql);
			$total = count($clas);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" LIMIT ".$p.",".$pagesize;
			$clas = pdo_fetchall($sql);
			/*******************************/	
			include $this->template('industry');
		}else if($_GPC['action'] == 'add'){
			if (checksubmit('submit')) {
				$data = array(
					'icon' => $_GPC['icon'],
					'sort' => $_GPC['sort'],
					'csname' => $_GPC['csname'],
					'content' => $_GPC['content'],
					'time' => $_W['timestamp'],
					'uniacid' => $_W['uniacid'],
					'display' => 1,
				);
				$result = pdo_insert(self::TABLE_A, $data);
				if (!empty($result)) {
					message('新增成功',$this->createWebUrl('Industry'),'success');
				}
			}
			include $this->template('industry_add');
			
		}else if($_GPC['action'] == 'recm'){
			$data = array(
				'recomme' => $_GPC['recomme'],
			);
			$result = pdo_update(self::TABLE_A, $data, array('id' => $_GPC['id'], 'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('设置成功',$this->createWebUrl('Industry'),'success');
			}	
		}else if($_GPC['action'] == 'edit'){
			if (checksubmit('submit')) {
				$data = array(
					'sort' => $_GPC['sort'],
					'csname' => $_GPC['csname'],
					'icon' => $_GPC['icon'],
					'content' => $_GPC['content'],
					'display' => 1,
				);
				$result = pdo_update(self::TABLE_A, $data, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
				if (!empty($result)) {
					message('修改成功',$this->createWebUrl('Industry'),'success');
				}else{
					message('好像没什么变动呀',$this->createWebUrl('Industry'),'error');
				}
			}else{
				$ed_ac = pdo_get(self::TABLE_A, array(
					'id'   => $_GPC['id'],
					'uniacid' => $_W['uniacid']
				));
				include $this->template('industry_edit');
			}
			
		}
	}
	
	public function doWebWxpay () {
		global $_GPC, $_W;
		if($_GPC['action'] == NULL){
			// =====================================
			$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
			$todayPay = pdo_fetchall("SELECT SUM(fee) as num FROM ".tablename('api_user_pay')." WHERE pay_time >= $today_start AND pay_time <= $today_end AND status=1 AND uniacid = $_W[uniacid]");
			$yesterday_start=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
			$yesterday_end=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
			$yesterdayPay = pdo_fetchall("SELECT SUM(fee) as num FROM ".tablename('api_user_pay')." WHERE pay_time >= $yesterday_start AND pay_time <= $yesterday_end AND status=1 AND uniacid = $_W[uniacid]");
			$okPay = pdo_fetchall("SELECT SUM(fee) as num FROM ".tablename('api_user_pay')." WHERE status=1 AND uniacid = $_W[uniacid]");
			// =====================================
			$sql = "SELECT * FROM ".tablename('api_user_pay')." AS u left join ".tablename('api_user_more')." a on u.openid = a.openid WHERE u.uniacid = $_W[uniacid]";
			$total = pdo_fetch("SELECT COUNT(*) AS count FROM ".tablename('api_user_pay')." AS u left join ".tablename('api_user_more')." a on u.openid = a.openid WHERE u.uniacid = $_W[uniacid]");
			
			//$cardr = pdo_fetchall($sql);
			//$total = count($cardr);
			
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total['count'],$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY plid DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('wxpay');
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_F, array('plid' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Wxpay'),'success');
			}
			
		}else if($_GPC['action'] == 'edit'){
			$data = array(
				'status' => 1,
			);
			$result = pdo_update(self::TABLE_F, $data, array('plid' => $_GPC['id'], 'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('设置成功',$this->createWebUrl('Wxpay'),'success');
			}	
		}
		
	}
	
	public function doWebTimes(){
		global $_GPC, $_W;
		include $this->template('times');
	}
	
	public function doWebShop(){
		global $_GPC, $_W;
		if($_GPC['action'] == NULL || $_GPC['action'] == 'news'){
			
			$sql = "SELECT * FROM ".tablename('api_user_trade')." WHERE uniacid = $_W[uniacid] AND display=1";
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY did DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('shop_news');
		}else if($_GPC['action'] == 'applys'){
			$sql = "SELECT * FROM ".tablename('api_user_trade')." WHERE uniacid = $_W[uniacid] AND display!=1";
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 15;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY did DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('shop_apply');
			
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_H, array('did' => $_GPC['did'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Shop'),'success');
			}
		}else if($_GPC['action'] == 'display'){
			$zuNum = $_W['current_module']['config']['zuNum'];
			if($zuNum == 1){
				$day = 1;
			}else if($zuNum == 2){
				$day = 30;
			}else if($zuNum == 3){
				$day = 365;
			}
			$dqtime = strtotime("+{$day} day");
			$data = array(
				'display' => $_GPC['display'],
				'noliyou' => 0,
				'sptime' => $_W['timestamp'],
				'dqtime' => $dqtime,
			);
			$result = pdo_update(self::TABLE_H, $data, array('did' => $_GPC['did'], 'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('操作成功',$this->createWebUrl('Shop'),'success');
			}
		}else if($_GPC['action'] == 'dispno'){
			$ed_ac = pdo_get(self::TABLE_H, array('did' => $_GPC['did'],'uniacid' => $_W['uniacid']));
			return json_encode($ed_ac);
		}else if($_GPC['action'] == 'displas'){
			$data = array(
				'display' => $_GPC['display'],
				'noliyou' => $_GPC['noliyou'],
			);
			$result = pdo_update(self::TABLE_H, $data, array('did' => $_GPC['did'], 'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('操作成功',$this->createWebUrl('Shop'),'success');
			}
		}else if($_GPC['action'] == 'look'){	
			if (checksubmit('submit')) {
				$data = array(
					'seid' => $_GPC['seid'],
					'dtitle' => $_GPC['dtitle'],
					'dqtime' => strtotime($_GPC['dqtime']),
					'dnumber' => $_GPC['dnumber'],
					'dmoney' => $_GPC['dmoney'],
					'dcontent' => $_GPC['dcontent'],
				);
				$result = pdo_update(self::TABLE_H, $data, array('did' => $_GPC['did'],'uniacid' => $_W['uniacid']));
				if (!empty($result)) {
					message('修改成功',$this->createWebUrl('Shop'),'success');
				}else{
					message('好像没什么变动呀',$this->createWebUrl('Shop'),'error');
				}
			}else{
				$ed_ac = pdo_get(self::TABLE_H, array(
					'did'   => $_GPC['did'],
					'uniacid' => $_W['uniacid']
				));
				include $this->template('shop_edit');
			}		
		}
	}
	
	public function doWebCommodity(){
		global $_GPC, $_W;
		if($_GPC['action'] == NULL){
			$sql = "SELECT * FROM ".tablename('api_user_shop')." WHERE uniacid = $_W[uniacid]";
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('commodity');
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_G, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Commodity'),'success');
			}
		}
	}

	
	public function  doWebBanner(){
		global $_GPC, $_W;
		if($_GPC['action'] == NULL){
			$sql = "SELECT * FROM ".tablename('api_user_banner')." WHERE uniacid = $_W[uniacid]";
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY bid DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('banner');
		}else if($_GPC['action'] == 'add'){	
			if (checksubmit('submit')) {
				$data = array(
					'icon' => $_GPC['icon'],
					'sort' => $_GPC['sort'],
					'title' => $_GPC['title'],
					'url' => $_GPC['banner_url'],
					'btime' => $_W['timestamp'],
					'appstatr' => $_GPC['appstatr'],
					'appid' => $_GPC['appid'],
					'uniacid' => $_W['uniacid'],
				);
				$result = pdo_insert(self::TABLE_I, $data);
				if (!empty($result)) {
					message('新增成功',$this->createWebUrl('Banner'),'success');
				}
			}
			include $this->template('banner_add');
		}else if($_GPC['action'] == 'edit'){	
			if (checksubmit('submit')) {
				$data = array(
					'sort' => $_GPC['sort'],
					'title' => $_GPC['title'],
					'url' => $_GPC['banner_url'],
					'icon' => $_GPC['icon'],
					'appstatr' => $_GPC['appstatr'],
					'appid' => $_GPC['appid'],
				);
				$result = pdo_update(self::TABLE_I, $data, array('bid' => $_GPC['bid'],'uniacid' => $_W['uniacid']));
				if (!empty($result)) {
					message('修改成功',$this->createWebUrl('Banner'),'success');
				}else{
					message('好像没什么变动呀',$this->createWebUrl('Banner'),'error');
				}
			}else{
				$ed_ac = pdo_get(self::TABLE_I, array(
					'bid'   => $_GPC['bid'],
					'uniacid' => $_W['uniacid']
				));
				include $this->template('banner_edit');
			}
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_I, array('bid' => $_GPC['bid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Banner'),'success');
			}
		}
	}
	
	public function  doWebNews(){
		global $_GPC, $_W;
		if($_GPC['action'] == NULL  || $_GPC['action'] == 'search'){
			
			if(!empty($_GPC['keyword'])) {
            	$condition .= " WHERE title LIKE '%".$_GPC['keyword']."%' AND uniacid=".$_W['uniacid'];
            }else{
				$condition .= " WHERE uniacid=".$_W['uniacid'];
			}
			$sql = "SELECT * FROM ".tablename('api_user_news').$condition;
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY nid DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('news');
		}else if($_GPC['action'] == 'add'){	
			$content = htmlspecialchars_decode($_GPC['content']);
			if (checksubmit('submit')) {
				$data = array(
					'title' => $_GPC['title'],
					'content' => $content,
					'addtime' => $_W['timestamp'],
					'uniacid' => $_W['uniacid'],
				);
				$result = pdo_insert(self::TABLE_K, $data);
				if (!empty($result)) {
					message('发布成功',$this->createWebUrl('News'),'success');
				}
			}
			include $this->template('news_add');
		}else if($_GPC['action'] == 'edit'){	
			$content = htmlspecialchars_decode($_GPC['content']);
			if (checksubmit('submit')) {
				$data = array(
					'title' => $_GPC['title'],
					'content' => $content,
				);
				$result = pdo_update(self::TABLE_K, $data, array('nid' => $_GPC['nid'],'uniacid' => $_W['uniacid']));
				if (!empty($result)) {
					message('修改成功',$this->createWebUrl('News'),'success');
				}else{
					message('好像没什么变动呀',$this->createWebUrl('News'),'error');
				}
			}else{
				$ed_ac = pdo_get(self::TABLE_K, array(
					'nid'   => $_GPC['nid'],
					'uniacid' => $_W['uniacid']
				));
				include $this->template('news_edit');
			}
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_K, array('nid' => $_GPC['nid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('News'),'success');
			}
		}
	}
	
	public function doWebReward(){
		global $_GPC, $_W;
		if($_GPC['action'] == NULL){
			$sql = "SELECT * FROM ".tablename('api_user_reward')." WHERE uniacid = $_W[uniacid]";
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY rid DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('reward');
		}else if($_GPC['action'] == 'add'){	
			if (checksubmit('submit')) {
				$data = array(
					'title' => $_GPC['title'],
					'content' => $_GPC['content'],
					'hotnum' => $_GPC['hotnum'],
					'price' => $_GPC['price'],
					'photo' => $_GPC['photo'],
					'rtime' => $_W['timestamp'],
					'uniacid' => $_W['uniacid'],
				);
				$result = pdo_insert(self::TABLE_L, $data);
				if (!empty($result)) {
					message('提交成功',$this->createWebUrl('Reward'),'success');
				}
			}
			include $this->template('reward_add');
		}else if($_GPC['action'] == 'edit'){
			if (checksubmit('submit')) {
				$data = array(
					'title' => $_GPC['title'],
					'content' => $_GPC['content'],
					'hotnum' => $_GPC['hotnum'],
					'price' => $_GPC['price'],
					'photo' => $_GPC['photo'],
				);
				$result = pdo_update(self::TABLE_L, $data, array('rid' => $_GPC['rid'],'uniacid' => $_W['uniacid']));
				if (!empty($result)) {
					message('修改成功',$this->createWebUrl('Reward'),'success');
				}else{
					message('好像没什么变动呀',$this->createWebUrl('Reward'),'error');
				}
			}else{
				$ed_ac = pdo_get(self::TABLE_L, array(
					'rid'   => $_GPC['rid'],
					'uniacid' => $_W['uniacid']
				));
				include $this->template('reward_edit');
			}
				
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_L, array('rid' => $_GPC['rid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Reward'),'success');
			}
		}
		
	}
	
	public function doWebPrivate(){
		global $_GPC, $_W;
		if($_GPC['action'] == NULL){
			$sql = "SELECT * FROM ".tablename('api_user_message')." WHERE uniacid = $_W[uniacid]";
			$cardr = pdo_fetchall($sql);
			$total = count($cardr);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY mid DESC LIMIT ".$p.",".$pagesize;
			$cardr = pdo_fetchall($sql);
			include $this->template('private');
		}else if($_GPC['action'] == 'del'){
			$result = pdo_delete(self::TABLE_J, array('mid' => $_GPC['mid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Private'),'success');
			}
		}
	}
	
	public function doWebWxconfig(){
		global $_W, $_GPC;
		if (checksubmit('submit')) {
			$data = $_GPC['data'];
			foreach ($data as $key => $v){
				pdo_update(self::TABLE_M, array('value' => $v),array('key' => $key));
			}
			message('设置成功',$this->createWebUrl('wxconfig'),'success');
		}else{
			$setConfig = pdo_fetchall("SELECT * FROM ".tablename('api_user_config'));
			include $this->template('wxconfig');
		}
	}
	
	
	public function doWebWxexne(){
		global $_W, $_GPC;
		if (checksubmit('submit')) {
			$data = $_GPC['data'];
			foreach ($data as $key => $v){
				pdo_update(self::TABLE_M, array('value' => $v),array('key' => $key));
			}
			message('设置成功',$this->createWebUrl('wxexne'),'success');
		}else{
			$setConfig = pdo_fetchall("SELECT * FROM ".tablename('api_user_config'));
			include $this->template('wxexne');
		}
	}
	
	public function doWebAdconfig(){
		global $_W, $_GPC;
		if (checksubmit('submit')) {
			$data = $_GPC['data'];
			foreach ($data as $key => $v){
				pdo_update(self::TABLE_M, array('value' => $v),array('key' => $key));
			}
			message('设置成功',$this->createWebUrl('adconfig'),'success');
		}else{
			$setConfig = pdo_fetchall("SELECT * FROM ".tablename('api_user_config'));
			include $this->template('adconfig');
		}
		
	}
	
	public function doWebWxauth(){
		
		global $_GPC, $_W;
		if($_GPC['action'] == NULL || $_GPC['action'] == 'noid'){
			/*******************************/
			//$sql = "SELECT * FROM ".tablename('api_user_auth_list')." WHERE uniacid = '$_W[uniacid]'";
			$sql = "SELECT *,a.display as dsp FROM ".tablename('api_user_auth_list')." a left join ".tablename('api_user_more')." b on a.openid = b.openid WHERE a.uniacid = '$_W[uniacid]' AND a.display = 0";
			$fek = pdo_fetchall($sql);
			$total = count($fek);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY autime DESC LIMIT ".$p.",".$pagesize;
			$fek = pdo_fetchall($sql);
			/*******************************/	
			include $this->template('wxauth');
		}else if($_GPC['action'] == 'youid'){
			/*******************************/
			$sql = "SELECT *,a.display as dsp FROM ".tablename('api_user_auth_list')." a left join ".tablename('api_user_more')." b on a.openid = b.openid WHERE a.uniacid = '$_W[uniacid]' AND a.display = 1";
			$fek = pdo_fetchall($sql);
			$total = count($fek);
			$pageindex = max($_GPC['page'],1);
			$pagesize = 10;
			$pager = pagination($total,$pageindex,$pagesize);
			$p = ($pageindex-1)*$pagesize;
			$sql.=" ORDER BY autime DESC LIMIT ".$p.",".$pagesize;
			$fek = pdo_fetchall($sql);
			/*******************************/	
			include $this->template('wxauth_x');
		}else if($_GPC['action'] == 'del'){
			pdo_update('api_user_more', array('auth' => 0), array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
			$result = pdo_delete('api_user_auth_list', array('auid' => $_GPC['auid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('Wxauth'),'success');
			}
			
		}else if($_GPC['action'] == 'look'){
			$ed_ac = pdo_get('api_user_auth_list', array('auid'   => $_GPC['auid'],'uniacid' => $_W['uniacid']));
			$img_ac = pdo_getall('api_user_auth', array('openid'   => $ed_ac['openid'],'uniacid' => $_W['uniacid']));
			include $this->template('Wxauth_c');
		}else if($_GPC['action'] == 'dis'){
			$data = array(
				'display' => 1,
			);
			$result = pdo_update('api_user_auth_list', $data, array('auid' => $_GPC['auid'],'uniacid' => $_W['uniacid']));
			pdo_update('api_user_more', array('auth' => 2), array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
			if (!empty($result)) {
				message('操作成功',$this->createWebUrl('Wxauth'),'success');
			}else{
				message('好像没什么变动呀',$this->createWebUrl('Wxauth'),'error');
			}	
		}

	}
	


}