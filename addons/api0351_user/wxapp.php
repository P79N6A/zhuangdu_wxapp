<?php
/**
 * 个人名片模块小程序接口定义
 *
 * @author 悟空源码社区
 * @url http://www.5kym.cn/
 */
defined('IN_IA') or exit('Access Denied');

use Qiniu\Auth;

class Api0351_userModuleWxapp extends WeModuleWxapp {
	
	
	const TABLE_A = 'api_user_gz';
	const TABLE_B = 'api_user_more';
	const TABLE_C = 'api_user_ms';
	const TABLE_D = 'api_user_opinion';
	const TABLE_E = 'api_user_class';
	const TABLE_F = 'api_user_pay';
	const TABLE_G = 'api_user_collection';
	const TABLE_H = 'api_user_formid';
	const TABLE_I = 'api_user_shop';
	const TABLE_J = 'api_user_message';
	const TABLE_K = 'api_user_trade';
	const TABLE_L = 'api_user_banner';
	const TABLE_M = 'api_user_trade';
	const TABLE_N = 'api_user_news';
	const TABLE_O = 'api_user_reward';
	const TABLE_P = 'api_user_reward_log';
	const TABLE_Q = 'api_user_cdr';
	const TABLE_R = 'api_user_moto';
	const TABLE_S = 'api_user_config';
	const TABLE_T = 'api_user_group';
	const TABLE_U = 'api_user_group_img';
	const TABLE_V = 'api_user_group_news';
	const TABLE_W = 'api_user_auth';
	const TABLE_X = 'api_user_auth_list';
	
	private $uid; 
	private $AppId; 
	private $gpc;
	private $w;
	private $setConfig;
	public function __construct() {
		global $_W;
		global $_GPC;
		$this->gpc = $_GPC;
		$this->w = $_W;
		$this->uniacid = $_W['uniacid'];
		$this->uid = $_W['openid'];
		$this->AppId = $_W['account']['key'];
		$this->setConfig = pdo_fetchall("SELECT * FROM ".tablename('api_user_config'));
	}
	
	public function doPageUserData(){
		global $_GPC, $_W;
		$getData = pdo_get(self::TABLE_B, array('openid' => $_GPC['openid'],'uniacid'   => $_W['uniacid']));
		if (empty($getData)) {
			$data = array(
				'openid' => $_GPC['openid'],
				'indes' => 0,
				'display' => 1,
				'uniacid'   => $_W['uniacid']
			);
			$data = pdo_insert(self::TABLE_B, $data);
			if (!empty($data)) {
				$message = '数据添加成功';
				$errno = 0;
				return $this->result($errno, $message, $data);
			}	
		}
	}
	
	public function doPagePostGz(){
		global $_GPC, $_W;
		$cid = $_GPC['cid'];
		$uniacid = $_W['uniacid'];
		if($cid == 1){
			$openid = $_GPC['openid'];
			$uid = $_GPC['uid'];
			if($uid == 0){
				exit();
			}
			$sql = "SELECT * FROM ".tablename('api_user_gz')." WHERE openid='$openid' AND uid='$uid' AND uniacid='$uniacid'";
			$dataMsn = pdo_fetchall($sql);
			$total = count($dataMsn);
			if($total > 0){
				$dataGz = array(
					'time' => $_W['timestamp']
				);
				$data = pdo_update(self::TABLE_A, $dataGz, array('openid' => $_GPC['openid'],'uid' => $_GPC['uid'],'uniacid'   => $_W['uniacid']));
				if (!empty($data)) {
					$message = '更新成功';
					$errno = 0;
					return $this->result($errno, $message, $data);
				}				
			}else{
				if($_GPC['avatar'] != '.._.._image/nouser.png' || $_GPC['avatar'] != 0 || $_GPC['avatar'] != 'undefined' || $_GPC['avatar'] != '' || $_GPC['openid'] != '' ){
					$dataGz = array(
						'avatar' => $_GPC['avatar'],
						'openid' => $_GPC['openid'],
						'uid' => $_GPC['uid'],
						'uniacid'   => $_W['uniacid'],
						'time' => $_W['timestamp']
					);
					$data = pdo_insert(self::TABLE_A, $dataGz);
					if (!empty($data)) {
						$message = '关注成功';
						$errno = 0;
						return $this->result($errno, $message, $data);
					}	
				}

			}
			
		}else if($cid == 2){
			$data = pdo_getall(self::TABLE_A,array('uid' => $_GPC['uid'],'uniacid'   => $_W['uniacid']),array() , '' , 'time DESC', array(1,8));
			foreach($data as $key=>$value){
				if($data[$key]['avatar'] == 'undefined' || $data[$key]['avatar'] == ''){
					$data[$key]['avatar'] = '../../image/nouser.png';
				}
	    	}
			$dataNum = pdo_getall(self::TABLE_A,array('uid' => $_GPC['uid'],'uniacid'   => $_W['uniacid']),array() , '' , '', array());
			$message = $dataNum;
			$errno = 0;
			return $this->result($errno, $message, $data);		
		}
		
	}
	
	public function doPageGetUser() {
		global $_GPC, $_W;
		$mod = $_GPC['mod'];
		if($mod == 1){
			$data = pdo_get(self::TABLE_B, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
			if(!$data || !$_GPC['openid']){
				$data['userMber'] = false;
				$newUserData = array(
					'openid' => $_GPC['openid'],
					'avatarUrl' => $_GPC['avatarUrl'],
					'nickName' => $_GPC['nickName'],
					'uniacid'   => $_W['uniacid'],
				);
				pdo_insert(self::TABLE_B, $newUserData);
				$data['userUid'] = pdo_insertid();
			}else{
				$data['userMber'] = true;	
			}

			if($data['display'] == 0){
				$data['mobile'] = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $data['mobile']);
			}
		}else if($mod == 2){
			$data = pdo_get(self::TABLE_B, array('id' => $_GPC['uid'],'uniacid' => $_W['uniacid']));
			if($data['display'] == 0){
				$data['me'] = pdo_get('api_user_collection', array('uid' => $_GPC['uid'], 'tid' => $_GPC['tid'], 'uniacid' => $_W['uniacid']));
				$data['wo'] = pdo_get('api_user_collection', array('uid' => $_GPC['tid'], 'tid' => $_GPC['uid'], 'uniacid' => $_W['uniacid']));
				if($data['me'] == false || $data['wo'] == false){
					$data['mobile'] = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $data['mobile']);
				}
			}
		}
		
		$avatarUrl = explode("/",$this->cut_str($data['avatarUrl'],'/',0));
		if($avatarUrl[0] == 'images'){
			$data['avatarUrl'] = $data['avatarUrl'];
		}
		
		if($data['video']){
			$data['video'] = $_W['attachurl'].$data['video'];
		}
		
		$day = $_W['current_module']['config']['cycle'];
		$d = date("Y-m-d", $data['paytime']);
		$data['paytime'] = date("Y-m-d", strtotime("{$d} +{$day} day"));
		$data['overtime'] = date("Y-m-d", $data['overtime']);
		$errno = 0;
		$message = '名片数据请求成功';
		return $this->result($errno, $message, $data);
    }
	
	public function doPagePostAvatar() {
		global $_GPC, $_W;
		$errno = 0;
		$message = '图片信息';
		$data = array(
			'avatarUrl' =>  $_GPC['avatarUrl'],
		);
		$result = pdo_update(self::TABLE_B, $data, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		if(!empty($result)){
			return $this->result($errno, $message, $result);
		}
    }
	
	
	public function doPageAddUser() {
		global $_GPC, $_W;
		
		$addData = pdo_get(self::TABLE_B, array('openid' => $_GPC['openid'],'uniacid'   => $_W['uniacid']), array('openid'));
		if(!empty($addData)){
			$ed_data = array(
				'cid' => $_GPC['sid'],
				'indes' => $_GPC['index'],
				'mobile' => $_GPC['mobile'],
				'nickname' => $_GPC['nickname'],
				'user_zc' => $_GPC['user_zc'],
				'user_weixin' => $_GPC['user_weixin'],
				'user_gs'   => $_GPC['user_gs'],
				'longitude'   => $_GPC['longitude'],
				'latitude'   => $_GPC['latitude'],
				'uniacid'   => $_W['uniacid'],
				'uptime' => $_W['timestamp'],
			);
			$data = pdo_update(self::TABLE_B, $ed_data, array('openid' => $_GPC['openid'],'uniacid'   => $_W['uniacid']));
			$message = '更新成功';
			$errno = 0;
			return $this->result($errno, $message, $data);
		}else{
			$ad_data = array(
				'cid' => $_GPC['sid'],
				'indes' => $_GPC['index'],
				'openid' => $_GPC['openid'],
				'avatarUrl' => $_GPC['avatarUrl'],
				'mobile' => $_GPC['mobile'],
				'nickname' => $_GPC['nickname'],
				'user_zc' => $_GPC['user_zc'],
				'user_gs'   => $_GPC['user_gs'],
				'user_weixin' => $_GPC['user_weixin'],
				'longitude'   => $_GPC['longitude'],
				'latitude'   => $_GPC['latitude'],
				'uniacid'   => $_W['uniacid'],
				'uptime' => $_W['timestamp'],
			);
			$data = pdo_insert(self::TABLE_B, $ad_data);
			if (!empty($data)) {
				$message = '提交成功';
				$errno = 0;
				return $this->result($errno, $message, $data);
			}
		}
    }
	
	
	public function doPageUserUp() {
		global $_GPC, $_W;
		$ed_data = array(
			'signature' => $_GPC['signature'],
		);
		$data = pdo_update(self::TABLE_B, $ed_data, array('openid' => $_GPC['openid'],'uniacid'   => $_W['uniacid']));
		$message = '更新成功';
		$errno = 0;
		return $this->result($errno, $message, $data);
		
    }
	
	
	public function doPageAddMsn(){
		global $_GPC, $_W;
		$data = array(
			'uid' => $_GPC['uid'],
			'fromid' => $_GPC['fromid'],
			'op' => $_GPC['op'],
			'openid' => $_GPC['openid'],
			'avatar' => $_GPC['avatar'],
			'nickname' => $_GPC['nickname'],
			'content' => $_GPC['content'],
			'uniacid'   => $_W['uniacid'],
			'addtime' => $_W['timestamp'],
		);
		$dataMsn = pdo_insert(self::TABLE_C, $data);
		if (!empty($dataMsn)) {
			//==========================================//
			$msStart = $_W['current_module']['config']['msStart'];
			if($msStart == 1){
				$account_api = WeAccount::create();
				$access_token = $account_api->getAccessToken();


				$touser = $_GPC['op'];
				$template_id = $_W['current_module']['config']['bok'];
				$findFid = pdo_getall(self::TABLE_H, array('openid' => $touser,'display' => 0), array() , '' , '' , array(1));
				$form_id = $findFid[0]['formid'];
				$keyword1 = date('Y/m/d H:i:s',time());
				$keyword2 = '您有新的问答消息，进入个人中心问答管理查看';
				$page = 'api0351_user/pages/user/user';
				/**************************/
				$this->SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$page);
				pdo_update(self::TABLE_H, array('display' => 1), array('formid' => $findFid[0]['formid']));
			}
			//==========================================//
			$message = '发布成功';
			$errno = 0;
			return $this->result($errno, $message, $data);
		}
	}
	
	public function doPageGetMsn(){
		global $_GPC, $_W;
		/*******************************/
		$uid = $_GPC['uid'];
		$dataMsn = pdo_getall(self::TABLE_C,array('uid' => $_GPC['uid'],'display' => $_GPC['display'],'uniacid' => $_W['uniacid']),array() , '' , 'mid DESC', array(1,10));
		/*******************************/	
		foreach($dataMsn as $key=>$value){
			$dataMsn[$key]['addtime'] = date("m-d H:i", $value['addtime']);
	    }
		$errno = 0;
		$message = '留言信息请求成功';
		return $this->result($errno, $message, $dataMsn);
	}
	
	public function doPageEdGetMsn(){
		global $_GPC, $_W;
		/*******************************/
		$op = $_GPC['op'];
		$uniacid = $_W['uniacid'];
		$sql = "SELECT * FROM ".tablename('api_user_ms')." WHERE op='$op' AND uniacid='$uniacid'";
		$dataMsn = pdo_fetchall($sql);
		$total = count($dataMsn);
		$pageindex = max($_GPC['page'],1);
		$pagesize = 5;
		$p = ($pageindex-1)*$pagesize;
		$sql.=" ORDER BY mid DESC LIMIT ".$p.",".$pagesize;
		$dataMsn = pdo_fetchall($sql);
		/*******************************/	
		foreach($dataMsn as $key=>$value){
			$dataMsn[$key]['addtime'] = date("m-d H:i", $value['addtime']);
	    }
		$errno = 0;
		$message = $total;
		$data = $dataMsn;
		return $this->result($errno, $total, $data);
	}
	
	public function doPageReplyMsn(){
		
		global $_GPC, $_W;
		$data = array(
			'r_content' => $_GPC['r_content'],
			'display' => 1,
			'rtime' => $_W['timestamp'],
		);
		$result = pdo_update(self::TABLE_C, $data, array('mid' => $_GPC['mid'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '信息回复成功';
		$data = $result;
		if (!empty($result)) {
			return $this->result($errno, $message, $data);
		}
		
	}
	
	public function doPageDelMsn(){
		global $_GPC, $_W;
		$result = $temp= pdo_delete(self::TABLE_C, array('mid' => $_GPC['mid'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '删除成功';
		$data = $result;
		if (!empty($result)) {
			return $this->result($errno, $message, $data);
		}
		
	}
	
	public function doPagePsOpinion(){
		global $_GPC, $_W;
		$ad_data = array(
			'openid'   => $_GPC['openid'],
			'nickname'   => $_GPC['nickName'],
			'avatar'   => $_GPC['avatarUrl'],
			'content'   => $_GPC['content'],
			'uniacid' => $_W['uniacid'],
			'time' => $_W['timestamp'],
		);
		$data = pdo_insert(self::TABLE_D, $ad_data);
		if (!empty($data)) {
			$message = '提交成功';
			$errno = 0;
			return $this->result($errno, $message, $data);
		}
		
	}
	
	
	public function doPageGetMobile() {
		global $_GPC, $_W;
		$account_api = WeAccount::create();
		$oauth = $account_api->getOauthInfo($_W['code']);
		require 'common/wx.inc.php';
		$getMobileWx = new WXBizDataCrypt($this->AppId, $oauth['session_key']);
		$errCode = $getMobileWx->decryptData($_GPC['encryptedData'], $_GPC['iv'], $data);
		
		if ($errCode == 0) {
			$errno = 0;
			$message = '获取成功';
			return $this->result($errno, $message, json_decode($data));
		} else {
			$errno = 1;
			$message = '获取失败，点击重试';
			return $this->result($errno, $message, $errCode);
		}
	}
	
	 
	public function doPageReClass() {
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_E,array('uniacid' => $_W['uniacid'],'recomme' => 1),array() , '' , 'sort ASC');
		foreach($data as $key=>$value){
			$data[$key]['icon'] = $_W['attachurl'].$value['icon'];
		}
		$errno = 0;
		$message = '推荐分类请求';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageGetClass() {
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_E,array('uniacid' => $_W['uniacid']),array() , '' , 'sort ASC');
				foreach($data as $key=>$value){
			$data[$key]['icon'] = $_W['attachurl'].$value['icon'];
		}
		$errno = 0;
		$message = '分类请求成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageFooter() {
		global $_GPC, $_W;
		$data = $_W['current_module']['config'];
		$errno = 0;
		$message = '底部信息请求成功';
		return $this->result($errno, $message, $data);
	}

	public function doPageOpenid(){
		global $_GPC, $_W;
		$message = 'Openid';
		$errno = 0;
		return $this->result($errno, $message, $this->uid);
	}
	

	public function get($key, $default = null) {
		return isset($this->gpc[$key]) ? $this->gpc[$key] : $default;
	}
	
	public function doPagePay() {
		global $_GPC, $_W;
		$shopNum = $_GPC['shopNum'];
		$orderid = $this->get('orderid', null);
		if (!$this->hasOrder($orderid)) {
			$this->result(1, '非用户订单');
		}
		if($shopNum == 1){
			$fee = $_GPC['price'];
			$title = '贸易街租金';
			$titleInt = 1;
		}else if($shopNum == 2){
			$fee = $_GPC['price'];
			$title = '礼品打赏';
			$titleInt = 2;
		}else{ 
			$fee = $_W['current_module']['config']['wxpay'];
			$title = '开设名片功能';
			$titleInt = 0;
		}
		$_W['openid'] = $_GPC['openid'];
		$order = array(
			'tid' => $orderid,
			'fee' => floatval($fee), 
			'title' => $title,
		);

        $pay_params = $this->pay($order);
        if (is_error($pay_params)) {
			$this->result($pay_params['errno'], $pay_params['message']);
       	}
		$uniacid = $_W['uniacid'];
		$data = array(
			'title' => $titleInt,
			'openid' => $_GPC['openid'],
			'username' => $_GPC['username'],
			'fee' => $fee,
			'status' => 0,
			'pay_time' => $_W['timestamp'],
			'uniacid' => $_W['uniacid'],
			'orderid' => $orderid
		);
		pdo_insert(self::TABLE_F, $data);
		// 
		if($shopNum == 2){
			$data = array(
				'sid' => $_GPC['sid'],
				'img' => $_GPC['img'],
				'title' => $_GPC['stitle'],
				'openid' => $_GPC['openid'],
				'username' => $_GPC['username'],
				'hotnum' => $_GPC['hotnum'],
				'price' => $fee,
				'status' => 0,
				'sopenid' => $_GPC['sopenid'],
				'srtime' => $_W['timestamp'],
				'uniacid' => $_W['uniacid'],
				'orderid' => $orderid
			);
			pdo_insert(self::TABLE_P, $data);
		}
		//
		if($shopNum != 1 && $shopNum != 2){
			$day = $_W['current_module']['config']['cycle'];
			$overtime = strtotime("+{$day} day");
			pdo_update(self::TABLE_B, array('paytime' => $_W['timestamp'] , 'overtime' => $overtime), array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		}
        return $this->result(0, '', $pay_params);
	}

	private function hasOrder($orderid) {
		return true;
	}
	
	public function payResult($params) {
		global $_GPC, $_W;
		$orderid = $params['tid'];
		$paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => 'api0351_user', 'tid' => $orderid));
		pdo_update(self::TABLE_B, array('user_vip' => 1), array('openid' => $paylog['openid'],'uniacid' => $_W['uniacid']));
		$data = array(
			'status' => $paylog['status'],
			'pay_time' => time(),
			'uniontid' => $paylog['uniontid'],
		);
		pdo_update(self::TABLE_F, $data, array('openid' => $paylog['openid'],'orderid' => $orderid,'uniacid' => $_W['uniacid']));
		//
		pdo_update(self::TABLE_M, array('pay' => 1), array('openid' => $paylog['openid'],'uniacid' => $_W['uniacid']));
		// 
		pdo_update(self::TABLE_P, array('status' => 1), array('orderid' => $paylog['tid'],'uniacid' => $_W['uniacid']));
	}
	
	public function doPagewxDashang(){
		global $_GPC, $_W;
		/**************************/
		$findUser = pdo_get('api_user_more', array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		$account_api = WeAccount::create();
		$access_token = $account_api->getAccessToken();
		$touser = $_GPC['sopenid'];
		$template_id = $this->setConfig[35]['value'];
		$findFid = pdo_getall('api_user_formid', array('openid' => $touser,'display' => 0), array() , '' , '' , array(1));
		$form_id = $findFid[0]['formid'];
		$keyword1 = '￥'.$_GPC['price'].' 元';
		$keyword2 = date('Y/m/d H:i:s',time());
		$keyword3 = $findUser['nickName'].'打赏了您，为您增加了名片热度，提升了排行！快来看看吧~';
		$page = 'api0351_user/pages/index/index';
		$this->SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$keyword3,$page,3);
		$data = pdo_update(self::TABLE_H, array('display' => 1), array('formid' => $findFid[0]['formid']));		
		/**************************/
		$errno = 0;
		$message = '打赏成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageEditDis(){
		global $_GPC, $_W;
		$data = array(
			'display' => $_GPC['display'],
		);
		$result = pdo_update(self::TABLE_B, $data, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '更新成功';
		$data = $result;
		if (!empty($result)) {
			return $this->result($errno, $message, $data);
		}
		
	}
	
	public function doPageGetData(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$examine = $_W['current_module']['config']['examine'];
		if($examine == 1){
			if($_GPC['xid'] == 0 || $_GPC['xid'] == 1){
				$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime != 0 AND examine=1 AND uniacid='$uniacid'";
			}else if($_GPC['xid'] == 3){
				$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uniacid='$uniacid' AND examine=1 AND heat!=0";
			}else{
				$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime != 0 AND examine=1 AND uniacid='$uniacid'";
			}
		}else{

			if($_GPC['xid'] == 0 || $_GPC['xid'] == 1){
				$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime != 0 AND uniacid='$uniacid'";
			}else if($_GPC['xid'] == 3){
				$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uniacid='$uniacid' AND heat!=0";
			}else{
				$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime != 0 AND uniacid='$uniacid'";
			}
		}
		
		/*******************************/
		//$dataMsn = pdo_fetchall($sql);
		$pageindex = max($_GPC['page'],1);
		$pagesize = 10;
		$p = ($pageindex-1)*$pagesize;
		if($_GPC['xid'] == 0 || $_GPC['xid'] == 1){
			$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
		}else if($_GPC['xid'] == 3){
			$sql.=" ORDER BY heat DESC LIMIT ".$p.",".$pagesize;
		}else{
			$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
		}
		
		$dataMsn = pdo_fetchall($sql);
		$total = count($dataMsn);
		/*******************************/	
		foreach($dataMsn as $key=>$value){
			$value['avatarUrl'] = explode("/",$this->cut_str($value['avatarUrl'],'/',0));
			if($value['avatarUrl'][0] == 'images'){
				$dataMsn[$key]['avatarUrl'] = $dataMsn[$key]['avatarUrl'];
			}
	    }
		$errno = 0;
		$message = $total;
		return $this->result($errno, $total, $dataMsn);
	}
	
	public function cut_str($str,$sign,$number){
			$array=explode($sign, $str);
			$length=count($array);
			if($number<0){
				$new_array=array_reverse($array);
				$abs_number=abs($number);
				if($abs_number>$length){
					return 'error';
				}else{
					return $new_array[$abs_number-1];
				}
			}else{
				if($number>=$length){
					return 'error';
				}else{
					return $array[$number];
				}
			}

		
	}
	
	public function doPageTesting(){
		global $_GPC, $_W;
		$data = pdo_get(self::TABLE_G, array('uid' => $_GPC['uid'], 'openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));	
		$errno = 0;
		$message = '查询收藏';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageCollection(){

		global $_GPC, $_W;
		$findData = pdo_get(self::TABLE_G, array('tid' => $_GPC['tid'], 'uid' => $_GPC['uid'], 'openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));			

		if (empty($findData)) {

			if($_GPC['openid'] != 'undefined'){
				
				if($_GPC['search'] == 1){
					$data = 1;
				}else{
					$msStart = $_W['current_module']['config']['msStart'];
					if($msStart == 1){
						$account_api = WeAccount::create();
						$access_token = $account_api->getAccessToken();
						$findUid = pdo_get(self::TABLE_B, array('id' => $_GPC['uid'],'uniacid' => $_W['uniacid']));
						$touser = $findUid['openid'];
						$template_id = $_W['current_module']['config']['sc'];
						$findFid = pdo_getall(self::TABLE_H, array('openid' => $touser,'display' => 0,'uniacid' => $_W['uniacid']), array() , '' , '' , array(1));
						$form_id = $findFid[0]['formid'];
						$keyword1 = date('Y/m/d H:i:s',time());
						$keyword2 = '您的名片被人收藏了，点击查看对方名片。';
						$page = 'api0351_user/pages/index/index?uid='.$_GPC['tid'];
						$this->SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$page);
						pdo_update(self::TABLE_H, array('display' => 1), array('formid' => $findFid[0]['formid'],'uniacid' => $_W['uniacid']));
					}
					
					$data = pdo_insert(self::TABLE_G, array('tid' => $_GPC['tid'], 'uid' => $_GPC['uid'], 'openid' => $_GPC['openid'], 'otime' => $_W['timestamp'], 'uniacid' => $_W['uniacid']));	
				}
			}
			$errno = 0;
			$message = '收藏成功';
		}else{
			$data = 0;
			$errno = 0;
			$message = '重复收藏';
		}
		return $this->result($errno, $message, $data);
		
	}
	
	public function doPageGetColl(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$sql = "SELECT * FROM ".tablename('api_user_collection')." AS u left join ".tablename('api_user_more')." a on u.uid = a.id WHERE u.openid = '$_GPC[openid]' AND u.uniacid = '$uniacid'";
		
		$total = pdo_fetch("SELECT COUNT(*) AS count FROM".tablename('api_user_collection')." WHERE openid = '$_GPC[openid]' AND uniacid = '$uniacid'");
		$pageindex = max($_GPC['page'],1);
		$pagesize = 10;
		$pager = pagination($total['count'],$pageindex,$pagesize);
		$p = ($pageindex-1)*$pagesize;
		$sql.=" ORDER BY u.oid DESC LIMIT ".$p.",".$pagesize;
		$data = pdo_fetchall($sql);
		
		foreach($data as $key=>$value){
			$value['avatarUrl'] = explode("/",$this->cut_str($value['avatarUrl'],'/',0));
			if($value['avatarUrl'][0] == 'images'){
				$data[$key]['avatarUrl'] = $data[$key]['avatarUrl'];
			}
	    }
		$errno = 0;
		$message = '请求成功';
		return $this->result($errno, $total['count'], $data);
	}
	
	public function doPageCodePic() {  
		global $_GPC, $_W;
		$paths = MODULE_ROOT.'/share/';  
		$uid = $_GPC['uid'];  
		$account_api = WeAccount::create();
		$response = $account_api->getCodeLimit('api0351_user/pages/index/index?uid='.$uid, 430, array(
			'auto_color' => false,
			'line_color' => array(
				'r' => '#ABABAB',
				'g' => '#ABABAC',
				'b' => '#ABABAD',
			),
		));
		$findData = pdo_get(self::TABLE_B, array('id' => $uid,'uniacid' => $_W['uniacid']));
		$filename = $uid.'.png';  
		if($findData['shareurl'] == 0){
			file_put_contents($paths.$filename, $response);
			$aupdata = array(
				'shareurl' => $_W['siteroot'].'addons/api0351_user/share/'.$uid.'.png'
			);
			pdo_update(self::TABLE_B, $aupdata, array('id' => $uid,'uniacid' => $_W['uniacid']));
		}
		sleep(2);
		$findDate = pdo_get(self::TABLE_B, array('id' => $uid,'uniacid' => $_W['uniacid']));
		if($findDate['shareurl'] != NULL){
			$data = $findDate;
		}else{
			$data = 1;
		}
		$errno = 0;
		$message = '请求成功';
		return $this->result($errno, $message, $data);
			
    }  
	
	public function SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$keyword3,$page, $num){
		$url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$access_token;
		if($num == 2){
			$value = array(
				"keyword1"=>array(
					"value"=>$keyword1,
					"color"=>"#333333"
				),
				"keyword2"=>array(
					"value"=>$keyword2,
					"color"=>"#333333"
				)
			);
		}else if($num == 3){
			$value = array(
				"keyword1"=>array(
					"value"=>$keyword1,
					"color"=>"#333333"
				),
				"keyword2"=>array(
					"value"=>$keyword2,
					"color"=>"#333333"
				),
				"keyword3"=>array(
					"value"=>$keyword3,
					"color"=>"#333333"
				)
			);	
		}
		$dataMsn = array();
		$dataMsn['touser']=$touser;
		$dataMsn['template_id']=$template_id;
		$dataMsn['page']=$page;  
		$dataMsn['form_id']=$form_id;
		$dataMsn['data']=$value;  
		$dataMsn['color']='';                        
		$dataMsn['emphasis_keyword']='';   
		$result = $this->https_curl_json($url,$dataMsn,'json');
		return $result;
		
    }
	
	
	public function https_curl_json($url,$data,$type){
        if($type=='json'){
			//json $_POST=json_decode(file_get_contents('php://input'), TRUE);
		  	$headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
          	$data=json_encode($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $output;
    }
	
	public function doPageformId(){
		global $_GPC, $_W;
		if($_GPC['openid']){
			$data = array(
				'formid' => $_GPC['formid'],
				'openid' => $_GPC['openid'],
				'display' => 0,
				'uniacid' => $_W['uniacid'],
				'time' => $_W['timestamp'],
			);
			$data = pdo_insert(self::TABLE_H, $data);
		}
		$errno = 0;
		$message = 'formId-OK';
		return $this->result($errno, $message, $data);
		
	}
	
	public function doPageShopAdd(){
		global $_GPC, $_W;
		$data = array(
			'openid' => $_GPC['openid'],
			'photo' => $_GPC['photo'],
			'title' => $_GPC['title'],
			'content' => $_GPC['content'],
			'uniacid' => $_W['uniacid'],
			'time' => $_W['timestamp'],
		);
		$data = pdo_insert(self::TABLE_I, $data);
		$errno = 0;
		$message = '商品添加成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageShopEdit(){
		global $_GPC, $_W;
		$data = array(
			'title' => $_GPC['title'],
			'content' => $_GPC['content'],
		);
		$data = pdo_update(self::TABLE_I, $data, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = $_GPC['id'];
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageGetShop(){
		
		global $_GPC, $_W;
		$openid = $_GPC['openid'];
		$id = $_GPC['id'];
		$lmit = $_GPC['lmit'];
		$uniacid = $_W['uniacid'];
		$k = $_GPC['k'];
		if($k == 1){
			$sql = "SELECT *,a.id as oid FROM ".tablename('api_user_shop')." a left join ".tablename('api_user_more')." b on a.openid = b.openid WHERE a.uniacid = '$_W[uniacid]' ORDER BY a.time DESC LIMIT 20";
			$dataMsn = pdo_fetchall($sql);
			foreach($dataMsn as $key=>$value){
				$dataMsn[$key]['time'] = date("m-d H:i", $value['time']);
			}
		}else if($k == 2){
			$dataMsn = pdo_getall(self::TABLE_I,array(
					'openid' => $_GPC['openid'],
					'uniacid' => $_W['uniacid']
			),array() , '' , 'id DESC', array(6));
		}else{
			if($lmit == 4){
				$dataMsn = pdo_getall(self::TABLE_I,array(
					'openid' => $_GPC['openid'],
					'uniacid' => $_W['uniacid']
				),array() , '' , 'id DESC', array(6));
			}else{
				if($id){
					$sql = "SELECT * FROM ".tablename('api_user_shop')." WHERE id='$id' AND uniacid = '$uniacid'";
				}else{
					$sql = "SELECT * FROM ".tablename('api_user_shop')." WHERE openid='$openid' AND uniacid = '$uniacid'";
				}
				$dataMsn = pdo_fetchall($sql);
				$total = count($dataMsn);
				$pageindex = max($_GPC['page'],1);
				$pagesize = 8;
				$p = ($pageindex-1)*$pagesize;
				$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
				$dataMsn = pdo_fetchall($sql);
			}
		}
		$data = $dataMsn;
		$errno = 0;
		$message = $total;
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageDelShop(){
		global $_GPC, $_W;
		$result = pdo_delete(self::TABLE_I, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '删除成功';
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageMessage(){
		global $_GPC, $_W;
		$data = array(
			'op' => $_GPC['op'],
			'openid' => $_GPC['openid'],
			'nickname' => $_GPC['nickname'],
			'avatar' => $_GPC['avatar'],
			'display' => 0,
			'content' => $_GPC['content'],
			'uniacid' => $_W['uniacid'],
			'addtime' => $_W['timestamp'],
		);
		$data = pdo_insert(self::TABLE_J, $data);
		/**************************/
		$findUser = pdo_get('api_user_more', array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		$account_api = WeAccount::create();
		$access_token = $account_api->getAccessToken();
		$touser = $_GPC['op'];
		$template_id = $this->setConfig[33]['value'];
		$findFid = pdo_getall('api_user_formid', array('openid' => $touser,'display' => 0), array() , '' , '' , array(1));
		$form_id = $findFid[0]['formid'];
		$keyword1 = $findUser['nickName'];
		$keyword2 = date('Y/m/d H:i:s',time());
		$keyword3 = '您有1封新的未读私信，请到个人中心私信管理处查收。';
		$page = 'api0351_user/pages/user/user';
		$this->SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$keyword3,$page,3);
		pdo_update(self::TABLE_H, array('display' => 1), array('formid' => $findFid[0]['formid']));		
		/**************************/
		$errno = 0;
		$message = '发送成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageDelMessage(){
		global $_GPC, $_W;
		$data = pdo_delete(self::TABLE_J, array('mid' => $_GPC['mid'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '删除成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageGetMesage(){
		global $_GPC, $_W;
		if($_GPC['k'] == 3){
			$data = pdo_getall(self::TABLE_J,array('uniacid' => $_W['uniacid'],'op' => $_GPC['openid'],'display' => 0),array() , '' , 'mid DESC', array());
		}else{
			$data = pdo_getall(self::TABLE_J,array('uniacid' => $_W['uniacid'],'op' => $_GPC['openid'],'display' => $_GPC['display']),array() , '' , 'mid DESC', array());
		}
		foreach($data as $key=>$value){
			$data[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
	    }
		$errno = 0;
		$message = '私信请求成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageEdMesage(){
		global $_GPC, $_W;
		$data = array(
			'display' =>  $_GPC['display'],
		);
		$data = pdo_update(self::TABLE_J, $data, array('mid' => $_GPC['mid'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '读取成功';
		return $this->result($errno, $message, $data);
	}
	
	
	public function doPageShop(){
		global $_GPC, $_W;
		
		$serachData = pdo_get(self::TABLE_K, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		if(empty($serachData)){
			$data = array(
				'dtitle' => $_GPC['dtitle'],
				'dnumber' => $_GPC['dnumber'],
				'x' => $_GPC['x'],
				'y' => $_GPC['y'],
				'openid' => $_GPC['openid'],
				'avatar' => $_GPC['avatar'],
				'nickname' => $_GPC['nickname'],
				'seid' => $_GPC['seid'],
				'dmoney' => $_GPC['dmoney'],
				'dcontent' => $_GPC['dcontent'],
				'uniacid' => $_W['uniacid'],
				'display' => 0,
				'ctime' => $_W['timestamp'],
			);
			$data = pdo_insert(self::TABLE_K, $data);	
		}else{
			if($_GPC['dmoney'] == 0){
				$dmoney = $_GPC['dnumber'] * $_W['current_module']['config']['shoPay'];
			}else{

				$dmoney = $_GPC['dnumber'];
			}
			$data = array(
				'dtitle' => $_GPC['dtitle'],
				'dnumber' => $_GPC['dnumber'],
				'x' => $_GPC['x'],
				'y' => $_GPC['y'],
				'seid' => $_GPC['seid'],
				'dmoney' => $dmoney,
				'dcontent' => $_GPC['dcontent'],
				'display' => 0,
			);
			pdo_update(self::TABLE_K, $data, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		}
		$errno = 0;
		$message = '提交成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageShopNum(){
		global $_GPC, $_W;
		$data = pdo_fetchall("SELECT SUM(dnumber) as num FROM ".tablename('api_user_trade')." WHERE uniacid = $_W[uniacid] AND display=1");
		$errno = 0;
		$message = '求和成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageShopOnline(){
		global $_GPC, $_W;
		$data = pdo_get(self::TABLE_M, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		$data['sptime'] = date('Y-m-d H:i:s',$data['sptime']);
		$data['dqtime'] = date('Y-m-d',$data['dqtime']);
		$data['ctime'] = date('Y-m-d H:i:s',$data['ctime']);
		$errno = 0;
		$message = '请求状态';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageGetTong(){
		global $_GPC, $_W;
		
		$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
		$todayPay = pdo_fetchall("SELECT * FROM ".tablename('api_user_trade')." WHERE ctime >= $today_start AND ctime <= $today_end AND display=1 AND uniacid=$_W[uniacid]");
		$todayPay = count($todayPay);
			
		$data = pdo_fetchall("SELECT * FROM ".tablename('api_user_trade')." WHERE uniacid = $_W[uniacid] AND display=1");
		$errno = 0;
		$message = $todayPay;
		return $this->result($errno, $message, $data);
	}
	
	public function doPageBanner(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_L,array('uniacid' => $_W['uniacid']),array() , '' , 'sort ASC', array(1,5));
		foreach($data as $key=>$value){
			$data[$key]['icon'] = $_W['attachurl'].$value['icon'];
		}
		$message = '幻灯数据请求成功';
		$errno = 0;
		return $this->result($errno, $message, $data);		
	}
	
	public function doPageGetUserShop(){
		global $_GPC, $_W;
		$data = pdo_get(self::TABLE_M, array('openid' => $_GPC['openid'],'display' => 1,'uniacid' => $_W['uniacid']));
		$message = '是否有店铺';
		$errno = 0;
		return $this->result($errno, $message, $data);		
	}
		
	public function doPageShopList(){
		global $_GPC, $_W;
		if($_GPC['s'] == 0){
			$data = pdo_getall(self::TABLE_M,array('uniacid' => $_W['uniacid'],'display' => 1),array() , '' , 'did DESC', array(1,10));
			foreach($data as $key=>$value){
				$data[$key]['ctime'] = date('Y-m-d H:i:s',$value['ctime']);
			}
		}else if($_GPC['s'] == 1){
			$data = pdo_getall(self::TABLE_M,array('uniacid' => $_W['uniacid'],'display' => 1),array() , '' , 'dnumber DESC', array(1,4));
			foreach($data as $key=>$value){
				$data[$key]['ctime'] = date('Y-m-d H:i:s',$value['ctime']);
			}
		}else if($_GPC['s'] == 2){
			$data = pdo_get(self::TABLE_M, array('did' => $_GPC['did'],'uniacid' => $_W['uniacid']));
			$data['ctime'] = date('m-d',$data['ctime']);
		}

		$message = '店铺数据请求成功';
		$errno = 0;
		return $this->result($errno, $message, $data);		
	}
	
	public function doPageDelDisplay(){
		global $_GPC, $_W;
		$data = pdo_delete(self::TABLE_M, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		$errno = 0;
		$message = '删除成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPagePostReport(){
		global $_GPC, $_W;
		$data = array(
			'jid' => $_GPC['jid'],
			'op' => $_GPC['op'],
			'openid' => $_GPC['openid'],
			'nickname' => $_GPC['nickname'],
			'avatar' => $_GPC['avatar'],
			'content' => $_GPC['content'],
			'uniacid'   => $_W['uniacid'],
			'dis'   => $_GPC['dis'],
			'time' => $_W['timestamp'],
		);
		$data = pdo_insert(self::TABLE_D, $data);
		pdo_update(self::TABLE_B, array('report +=' => 1), array('openid' => $_GPC['op'],'uniacid' => $_W['uniacid']));
		$message = '举报成功';
		$errno = 0;
		return $this->result($errno, $message, $data);		
	}
	
	public function doPageGetNews(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_N,array('uniacid' => $_W['uniacid']),array() , '' , 'nid DESC', array(1,5));
		foreach($data as $key=>$value){
			$data[$key]['addtime'] = date('m-d',$value['addtime']);
	    }
		$message = '商报请求成功';
		$errno = 0;
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageGetViewNew(){
		global $_GPC, $_W;
		$data = pdo_get(self::TABLE_N, array('nid' => $_GPC['id'],'uniacid' => $_W['uniacid']));
		$data['addtime'] = date('m-d',$data['addtime']);
		$message = '商报内容';
		$errno = 0;
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageGetReward(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_O,array('uniacid' => $_W['uniacid']),array() , '' , 'rid DESC', array(10));
		foreach($data as $key=>$value){
			$data[$key]['photo'] = $_W['attachurl'].$value['photo'];
		}
		$message = '礼品信息';
		$errno = 0;
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageAddShang(){
		global $_GPC, $_W;
		$data = pdo_update(self::TABLE_B, array('heat +=' => $_GPC['hotnum']), array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		$message = '热度增加';
		$errno = 0;
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageGetShang(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_P,array('uniacid' => $_W['uniacid'],'sopenid' => $_GPC['openid'],'status' => 1),array() , '' , 'lid DESC', array());
		foreach($data as $key=>$value){
			$data[$key]['srtime'] = date('Y-m-d H:i',$value['srtime']);
		}
		$message = '礼品信息';
		$errno = 0;
		return $this->result($errno, $message, $data);	
	}
	
	public function doPageCdrsb(){
		global $_GPC, $_W;
		// 图片base64编码
		$data   = file_get_contents($_GPC['image']);
		$base64 = base64_encode($data);
		
		// 设置请求数据
		$appkey = $_W['current_module']['config']['tappkey'];    // appkey
		$params = array(
			'app_id'     => $_W['current_module']['config']['tappid'],   // app_id
			'image'      => $base64,
			'time_stamp' => strval(time()),
			'nonce_str'  => strval(rand()),
			'sign'       => '',
		);
		$params['sign'] = $this->getReqSign($params, $appkey);
		// 执行API调用
		$url = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_bcocr';
		$data = $this->doHttpPost($url, $params);
		$message = '识别成功';
		$errno = 0;
		return $this->result($errno, $message, $data);	
	}
	
	
	public function doPageAddCdr() {
		global $_GPC, $_W;
		$data = array(
			'imgurl' => $_GPC['imgurl'],
			'openid' => $_GPC['openid'],
			'xingming' => $_GPC['xingming'],
			'gongsi' => $_GPC['gongsi'],
			'zhiwei' => $_GPC['zhiwei'],
			'dianhua' => $_GPC['dianhua'],
			'shouji' => $_GPC['shouji'],
			'youxiang' => $_GPC['youxiang'],
			'weixin'   => $_GPC['weixin'],
			'uniacid'   => $_W['uniacid'],
			'time' => $_W['timestamp'],
		);
		$data = pdo_insert(self::TABLE_Q, $data);
		$errno = 0;
		$message = '新增成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageGetCdr() {
		global $_GPC, $_W;
		//$data = pdo_getall(self::TABLE_Q,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']),array() , '' , 'rid DESC', array());
		$uniacid = $_W['uniacid'];
		$sql = "SELECT * FROM ".tablename('api_user_cdr')." WHERE openid = '$_GPC[openid]' AND uniacid = '$uniacid'";
		$total = pdo_fetch("SELECT COUNT(*) AS count FROM".tablename('api_user_cdr')." WHERE openid = '$_GPC[openid]' AND uniacid = '$uniacid'");
		$pageindex = max($_GPC['page'],1);
		$pagesize = 10;
		$pager = pagination($total['count'],$pageindex,$pagesize);
		$p = ($pageindex-1)*$pagesize;
		$sql.=" ORDER BY rid DESC LIMIT ".$p.",".$pagesize;
		$data = pdo_fetchall($sql);
		
		$errno = 0;
		$message = '查询成功';
		return $this->result($errno, $total['count'], $data);
	}
	
	public function doPageGetViewCdr() {
		global $_GPC, $_W;
		$mod = $_GPC['mod'];
		if($mod == 1){
			$data = pdo_get(self::TABLE_Q,array('uniacid' => $_W['uniacid'],'rid' => $_GPC['uid']));
		}else if($mod == 2){
			$data = pdo_get(self::TABLE_Q,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid'],'rid' => $_GPC['uid']));
		}
		$errno = 0;
		$message = '查询成功';
		return $this->result($errno, $message, $data);
	}
	
	// doHttpPost ：执行POST请求，并取回响应结果
    public function doHttpPost($url, $params){
        $curl = curl_init();
        $response = false;
        do
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            $head = array(
                'Content-Type: application/x-www-form-urlencoded'
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
            $body = http_build_query($params);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_NOBODY, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
        } while (0);
        curl_close($curl);
        return $response;
    }
	
	
	public function getReqSign($params, $appkey){
        if (!$params['nonce_str']){
            $params['nonce_str'] = uniqid("{$params['app_id']}_");
        }
        if (!$params['time_stamp']){
            $params['time_stamp'] = time();
        }
        ksort($params);
        $str = '';
        foreach ($params as $key => $value){
            if ($value !== ''){
                $str .= $key . '=' . urlencode($value) . '&';
            }
        }
        $str .= 'app_key=' . $appkey;
        $sign = strtoupper(md5($str));
        return $sign;
    }
	
	public function doPageDelCarlist(){
		global $_GPC, $_W;
		$m = $_GPC['mum'];
		if($m == 1){
			$data = pdo_delete(self::TABLE_G, array('oid' => $_GPC['oid'],'openid' => $_GPC['openid']));
		}else if($m == 2){
			$data = pdo_delete(self::TABLE_Q, array('rid' => $_GPC['rid'],'openid' => $_GPC['openid']));
		}
		$errno = 0;
		$message = '移除成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageKewords(){
		global $_GPC, $_W;
		
		
		$examine = $_W['current_module']['config']['examine'];
		if($examine == 1){
			if($_GPC['searchid'] == 0){
				$condition .= " WHERE signature LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND examine=1 AND uniacid=".$_W['uniacid'];
			}else if($_GPC['searchid'] == 1){
				$condition .= " WHERE nickName LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND examine=1 AND uniacid=".$_W['uniacid'];
			}else if($_GPC['searchid'] == 2){
				$condition .= " WHERE user_gs LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND examine=1 AND uniacid=".$_W['uniacid'];
			}
		}else{
			if($_GPC['searchid'] == 0){
				$condition .= " WHERE signature LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND uniacid=".$_W['uniacid'];
			}else if($_GPC['searchid'] == 1){
				$condition .= " WHERE nickName LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND uniacid=".$_W['uniacid'];
			}else if($_GPC['searchid'] == 2){
				$condition .= " WHERE user_gs LIKE '%".$_GPC['keyword']."%' AND uptime!=0 AND uniacid=".$_W['uniacid'];
			}
		}
		

		
		$sql = "SELECT * FROM ".tablename('api_user_more').$condition;
		$total = pdo_fetch("SELECT COUNT(*) AS count FROM".tablename('api_user_more').$condition);
		$pageindex = max($_GPC['page'],1);
		$pagesize = 10;
		$pager = pagination($total['count'],$pageindex,$pagesize);
		$p = ($pageindex-1)*$pagesize;
		$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
		$data = pdo_fetchall($sql);
		
		foreach($data as $key=>$value){
			$value['avatarUrl'] = explode("/",$this->cut_str($value['avatarUrl'],'/',0));
			if($value['avatarUrl'][0] == 'images'){
				$data[$key]['avatarUrl'] = $data[$key]['avatarUrl'];
			}
		}
		
		$errno = 0;
		$message = '检索成功';
		return $this->result($errno, $total['count'], $data);
	}
	
	public function doPageGetDataClas(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$examine = $_W['current_module']['config']['examine'];
		
		$find = pdo_get(self::TABLE_E, array('id' => $_GPC['cid'],'uniacid' => $_W['uniacid']));
		if($examine == 1){
			$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime != 0 AND indes=".$find['sort']." AND examine = 1 AND uniacid='$uniacid'";
			$total = pdo_fetch("SELECT COUNT(*) AS count FROM".tablename('api_user_more')." WHERE uptime != 0 AND examine = 1 AND indes=".$find['sort']." AND uniacid='$uniacid'");
		}else{
			$sql = "SELECT * FROM ".tablename('api_user_more')." WHERE uptime != 0 AND indes=".$find['sort']." AND uniacid='$uniacid'";
			$total = pdo_fetch("SELECT COUNT(*) AS count FROM".tablename('api_user_more')." WHERE uptime != 0 AND indes=".$find['sort']." AND uniacid='$uniacid'");
		}
		$pageindex = max($_GPC['page'],1);
		$pagesize = 20;
		$p = ($pageindex-1)*$pagesize;
		$sql.=" ORDER BY id DESC LIMIT ".$p.",".$pagesize;
		$dataMsn = pdo_fetchall($sql);
		/*******************************/	

		foreach($dataMsn as $key=>$value){
			$value['avatarUrl'] = explode("/",$this->cut_str($value['avatarUrl'],'/',0));
			if($value['avatarUrl'][0] == 'images'){
				$dataMsn[$key]['avatarUrl'] = $dataMsn[$key]['avatarUrl'];
			}
		}
		
		$errno = 0;
		$message = $total['count'];
		return $this->result($errno, $message, $dataMsn);
	}
	
	public function doPageUploadWx(){
		global $_GPC, $_W;
		//--------------------------------------------------
		$data = array(
			'openid' => $_GPC['openid'],
			'imgUrl' => $_GPC['imgUrl'],
			'uniacid'   => $_W['uniacid'],
			'time' => $_W['timestamp'],
		);
		pdo_insert(self::TABLE_R, $data);
		//--------------------------------------------------
		$data = array("Success"=>true);
		echo json_encode($data);
	}
	
	public function doPageGetImg(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_R,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']),array() , '' , 'id ASC', array());
		$errno = 0;
		$message = '图像请求';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageDelImg(){
		global $_GPC, $_W;
		$data = pdo_delete(self::TABLE_R, array('id' => $_GPC['id'],'openid' => $_GPC['openid']));
		$errno = 0;
		$message = '删除成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageConfig(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_S,array(),array() , '' , 'num ASC', array());
		foreach($data as $key=>$value){
			if(strpos($data[$key]['value'],'images') !== false){
				$data[$key]['value'] = $_W['attachurl'].$value['value'];
			}
		}
		$errno = 0;
		$message = '配置请求成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageBackGro() {
		global $_GPC, $_W;
		$errno = 0;
		$message = '设置成功';
		$data = array(
			'backgro' =>  $_GPC['backgro'],
		);
		$result = pdo_update(self::TABLE_B, $data, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
		if(!empty($result)){
			return $this->result($errno, $message, $result);
		}
    }
	
	public function doPageGetBack() {
		global $_GPC, $_W;
		$data = pdo_get(self::TABLE_B,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']));
		$errno = 0;
		$message = '背景加载';
		return $this->result($errno, $message, $data);
    }
	
	
	    /**
     * 登录
     */
    public function doPageLogin(){
		global $_GPC, $_W;
        $code = $_GPC["code"];
        $rawData = htmlspecialchars_decode($_GPC["rawData"]);
        if (!$code || !$rawData) {
			return $this->result(1, '用户授权参数错误', 0);
        }
        $userInfo = (array)json_decode($rawData, true);

			
        $params = [
            'appid'      => $_W['account']['oauth']['key'],
            'secret'     => $_W['account']['oauth']['secret'],
            'js_code'    => $code,
            'grant_type' => 'authorization_code'
        ];
        $result = ihttp_request("https://api.weixin.qq.com/sns/jscode2session", $params);

		$json = (array)json_decode($result['content'], true);
		
		$data = [
			'openid'        => $json['openid'],
			'wxInfo'      => $userInfo,
			'session_key'  => $json['session_key'],
		];
				
		if (isset($json['openid'])) {
			$errno = 0;
			$message = '登录成功';
			return $this->result($errno, $message, $data);
		}else{
			$errno = 0;
			$message = '登录失败';
			return $this->result($errno, $message, $data);

		}
		
    }
	
	public function doPageGroup(){
		global $_GPC, $_W;
		$errno = 0;	
		if($_GPC['mod'] == 'add'){
			$data = array(
				'openid' => $_GPC['openid'],
				'title' => $_GPC['title'],
				'photo' => $_GPC['photo'],
				'checkedstart' => $_GPC['checkedstart'],
				'uniacid' => $_W['uniacid'],
				'time' => $_W['timestamp'],
			);
			$data = pdo_insert(self::TABLE_T, $data);
			$message = '新增成功';
		}else if($_GPC['mod'] == 'load'){
			$data = pdo_getall(self::TABLE_T,array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']),array() , '' , 'id DESC', array());
			foreach($data as $key=>$value){
				$data[$key]['time'] = date("m-d H:i", $value['time']);
			}
			$message = '加载成功';
		}else if($_GPC['mod'] == 'groupView'){
			$data = pdo_get(self::TABLE_T,array('uniacid' => $_W['uniacid'],'id' => $_GPC['id']));
			$message = '请求成功';
		}else if($_GPC['mod'] == 'userAdd'){
			
			$finOpenid = pdo_get(self::TABLE_T,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid'],'id' => $_GPC['groupId']));
			if($finOpenid){
				$data = 4;
				$message = '群主不能加入自己的群';
			}else{
				$finData = pdo_get(self::TABLE_T,array('uniacid' => $_W['uniacid'],'id' => $_GPC['groupId']));
				if($finData){
					if($finData['userid'] == NULL){
						$userid['b'] = 0;
					}else{
						$userid['b'] = $finData['userid'];
					}
					$userid['c'] = $_GPC['userid'];
					$UseData = array( 
						'userid' => implode(",",$userid),
					);
					$dataFind = explode(",",$finData['userid']);
					if (in_array($_GPC['userid'], $dataFind)) {
						$data = 0;
						$message = '数据已存在';
					}else{
						$data = pdo_update(self::TABLE_T, $UseData, array('id' => $_GPC['groupId'],'uniacid' => $_W['uniacid']));
						$message = '更新成功';
					}

					$findGroup = pdo_get(self::TABLE_B, array('uniacid' => $_W['uniacid'], 'openid' => $_GPC['openid']));
					if($findGroup['groupid'] == NULL){
						$groupData = array( 
							'groupid' => $_GPC['groupId'],
						);
						pdo_update(self::TABLE_B, $groupData, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
					}else{
						$group['a'] = $findGroup['groupid'];
						$group['b'] = $_GPC['groupId'];
						$groupData = array( 
							'groupid' => implode(",",$group),
						);
						$groupFind = explode(",",$findGroup['groupid']);
						if (!in_array($_GPC['groupId'], $groupFind)) {
							pdo_update(self::TABLE_B, $groupData, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
						}
					}
					
				}else{
					$data = 0;
					$message = '加入失败';
				}
			}

		}else if($_GPC['mod'] == 'getUser'){
			$finData = pdo_get(self::TABLE_T,array('uniacid' => $_W['uniacid'],'id' => $_GPC['groupId']));
			$userData = $finData['userid'];
			if($userData){
				$sql = "SELECT * FROM ".tablename('api_user_more')."  WHERE id in ($userData)";
				$data = pdo_fetchall($sql);
			}else{
				$data = 0;
			}
			$message = '请求成功';
		}else if($_GPC['mod'] == 'delUser'){
			
			$finData = pdo_get(self::TABLE_T,array('uniacid' => $_W['uniacid'],'id' => $_GPC['groupId']));
			if($finData){
				$dataFind = explode(",",$finData['userid']);
				$uid = $_GPC['userId'];
				foreach($dataFind as $key=>$v){
					if($uid == $v) unset($dataFind[$key]);
				}
				$UseData = array( 
					'userid' => implode(",",$dataFind),
				);
				$data = pdo_update(self::TABLE_T, $UseData, array('id' => $_GPC['groupId'],'uniacid' => $_W['uniacid']));
				$message = '剔除成功';
				// 清除对应UID的组信息
				$FindGroup = pdo_get(self::TABLE_B,array('uniacid' => $_W['uniacid'], 'id' => $_GPC['userId']));
				$GroupMroe = explode(",",$FindGroup['groupid']);
				$gid = $_GPC['groupId'];
				foreach($GroupMroe as $key=>$v){
					if($gid == $v) unset($GroupMroe[$key]);
				}
				$UsData = array( 
					'groupid' => implode(",",$GroupMroe),
				);
				pdo_update(self::TABLE_B, $UsData, array('id' => $_GPC['userId'],'uniacid' => $_W['uniacid']));
				
			}else{
				$data = 0;
				$message = '没有数据';
			}
		}else if($_GPC['mod'] == 'addContent'){
			$UseData = array( 
				'content' => $_GPC['content'],
			);
			$data = pdo_update(self::TABLE_T, $UseData, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			$message = '内容更新成功';
		}else if($_GPC['mod'] == 'UserGroup'){
			$userFind = pdo_get(self::TABLE_B,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']));
			$userGroupId = $userFind['groupid'];
			if($userGroupId){
				$sql = "SELECT * FROM ".tablename('api_user_group')."  WHERE id in ($userGroupId)";
				$data = pdo_fetchall($sql);
				foreach($data as $key=>$value){
					$data[$key]['time'] = date("m-d H:i", $value['time']);
				}
			}else{
				$data = 0;
			}
			$message = '我加入的';
		}else if($_GPC['mod'] == 'quitGroup'){
			// 修改自身的群ID
			$FindGroup = pdo_get(self::TABLE_B,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['op']));
			if($FindGroup){
				$GroupMroe = explode(",",$FindGroup['groupid']);
				$uid = $_GPC['id'];
				foreach($GroupMroe as $key=>$v){
					if($uid == $v) unset($GroupMroe[$key]);
				}
				$UsData = array( 
					'groupid' => implode(",",$GroupMroe),
				);
				$data = pdo_update(self::TABLE_B, $UsData, array('openid' => $_GPC['op'],'uniacid' => $_W['uniacid']));
				
				// 修改当前群所含的UID
				$finData = pdo_get(self::TABLE_T,array('uniacid' => $_W['uniacid'],'id' => $_GPC['id']));
				$dataFind = explode(",",$finData['userid']);
				$gid = $_GPC['gid'];
				foreach($dataFind as $key=>$v){
					if($gid == $v) unset($dataFind[$key]);
				}
				$UseData = array( 
					'userid' => implode(",",$dataFind),
				);
				pdo_update(self::TABLE_T, $UseData, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
			
			}else{
				$data = 0;	
			}
			$message = '退出成功';
		}else if($_GPC['mod'] == 'delGroup'){
			$data = pdo_delete(self::TABLE_T, array('id' => $_GPC['id'])); 
			$message = '删除成功';	
		}else if($_GPC['mod'] == 'delView'){
			$data = pdo_delete(self::TABLE_V, array('nid' => $_GPC['id'])); 
			$message = '删除成功';	
		}
		return $this->result($errno, $message, $data);
	}
	
	
	public function doPageGroupPic() {
		global $_GPC, $_W;
		$errno = 0;
		$message = '图片信息';
		$data = array(
			'photo' =>  $_GPC['photo'],
		);
		$result = pdo_update(self::TABLE_T, $data, array('id' => $_GPC['id'],'uniacid' => $_W['uniacid']));
		if(!empty($result)){
			return $this->result($errno, $message, $result);
		}
    }
	
	public function doPageAddView(){
		global $_GPC, $_W;
		$data = array(
			'gid' => $_GPC['gid'],
			'avatarUrl' => $_GPC['avatarUrl'],
			'nickName' => $_GPC['nickName'],
			'openid' => $_GPC['openid'],
			'content' => $_GPC['content'],
			'uniacid' => $_W['uniacid'],
			'random' => $_GPC['random'],
			'addtime' => $_W['timestamp'],
		);
		$data = pdo_insert(self::TABLE_V, $data);
		$message = '新增成功';
		$errno = 0;
		return $this->result($errno, $message, $data);
	}
	
	
	public function doPageGetView(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_V,array('gid' => $_GPC['gid'],'uniacid' => $_W['uniacid']),array() , '' , 'nid DESC', array());
		foreach($data as $key=>$value){
			$data[$key]['addtime'] = date("m-d H:i", $value['addtime']);
			$data[$key]['imgMore'] = pdo_getall(self::TABLE_U,array('random' => $data[$key]['random'],'uniacid' => $_W['uniacid']),array() , '' , 'id DESC', array());	
		}
		$errno = 0;
		$message = '加载成功';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageUploadHt(){
		global $_GPC, $_W;
		//--------------------------------------------------
		$data = array(
			'gid' => $_GPC['gid'],
			'openid' => $_GPC['openid'],
			'random' => $_GPC['random'],
			'imgUrl' => $_GPC['imgUrl'],
			'uniacid'   => $_W['uniacid'],
			'time' => $_W['timestamp'],
		);
		pdo_insert(self::TABLE_U, $data);
		//--------------------------------------------------
		$data = array("Success"=>true);
		echo json_encode($data);
	}
	
	public function doPageEditCard(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '修改成功';
		$data = array(
			'xingming' =>  $_GPC['xingming'],
			'weixin' =>  $_GPC['weixin'],
			'youxiang' =>  $_GPC['youxiang'],
			'shouji' =>  $_GPC['shouji'],
			'dianhua' =>  $_GPC['dianhua'],
			'zhiwei' =>  $_GPC['zhiwei'],
			'gongsi' =>  $_GPC['gongsi'],
		);
		$result = pdo_update(self::TABLE_Q, $data, array('rid' => $_GPC['id'],'uniacid' => $_W['uniacid']));
		if(!empty($result)){
			return $this->result($errno, $message, $result);
		}
	}
	
	
	public function doPageAuthUpload(){
		global $_GPC, $_W;
		//--------------------------------------------------
		$data = array(
			'openid' => $_GPC['openid'],
			'imgUrl' => $_GPC['imgUrl'],
			'uniacid'   => $_W['uniacid'],
			'imgid' => $_GPC['imgid'],
			'time' => $_W['timestamp'],
		);
		pdo_insert(self::TABLE_W, $data);
		//--------------------------------------------------
		$data = array("Success"=>true);
		echo json_encode($data);
	}
	
	public function doPageGetAuthImg(){
		global $_GPC, $_W;
		$data = pdo_getall(self::TABLE_W,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']),array() , '' , 'id ASC', array());
		$errno = 0;
		$message = '图像请求';
		return $this->result($errno, $message, $data);
	}
	
	public function doPageDelAuthImg(){
		global $_GPC, $_W;
		$data = pdo_delete(self::TABLE_W, array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid'],'imgid' => $_GPC['imgid']));
		$errno = 0;
		$message = '删除成功';
		return $this->result($errno, $message, $data);
	}
	
	
	public function doPageAuthData(){
		global $_GPC, $_W;
		if($_GPC['mod'] == 0){
			$data = pdo_get(self::TABLE_X,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']));
			$message = '查询成功';
			$errno = 0;
			return $this->result($errno, $message, $data);
		}else if($_GPC['mod'] == 1){
			
			$findata = pdo_get(self::TABLE_X,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']));
			if(!$findata){
				$ad_data = array(
					'openid'   => $_GPC['openid'],
					'nickName'   => $_GPC['nickName'],
					'avatarUrl'   => $_GPC['avatarUrl'],
					'username'   => $_GPC['username'],
					'userid'   => $_GPC['userid'],
					'uniacid' => $_W['uniacid'],
					'autime' => $_W['timestamp'],
				);
				$data = pdo_insert(self::TABLE_X, $ad_data);
				pdo_update('api_user_more', array('auth' => 1), array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
				if (!empty($data)) {
					$message = '提交成功';
					$errno = 0;
					return $this->result($errno, $message, $data);
				}	
			}else{
				$errno = 0;
				$message = '修改成功';
				$data = array(
					'nickName'   => $_GPC['nickName'],
					'avatarUrl'   => $_GPC['avatarUrl'],
					'username'   => $_GPC['username'],
					'userid'   => $_GPC['userid'],
				);
				$result = pdo_update(self::TABLE_X, $data, array('openid' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
				if(!empty($result)){
					return $this->result($errno, $message, $result);
				}
			}

		}

		
	}
	
	public function doPageAuthFind(){
		global $_GPC, $_W;
		$data = pdo_get('api_user_more',array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']));
		$message = '查询认证状态';
		$errno = 0;
		return $this->result($errno, $message, $data);
	}
	
	
	public function doPageVisitor(){
		global $_GPC, $_W;
		if($_GPC['mod'] == 0){
			$finData = pdo_get('api_user_visitor',array('uniacid' => $_W['uniacid'],'opa' => $_GPC['opa'],'opb' => $_GPC['opb']));
			if(!empty($finData)){
				$data = array(
					'ftime' => $_W['timestamp'],
				);
				pdo_update('api_user_visitor', $data, array('opa' => $_GPC['opa'],'opb' => $_GPC['opb'],'uniacid' => $_W['uniacid']));
				$message = '更新访客成功';
			}else{
				$data = array(
					'opa'   => $_GPC['opa'],
					'opb'   => $_GPC['opb'],
					'nickName'   => $_GPC['nickName'],
					'avatarUrl'   => $_GPC['avatarUrl'],
					'uniacid' => $_W['uniacid'],
					'ftime' => $_W['timestamp'],
				);
				pdo_insert('api_user_visitor', $data);
				$message = '写入访客信息';
				/**************************/
				$findUser = pdo_get('api_user_more', array('openid' => $_GPC['opb'],'uniacid' => $_W['uniacid']));
				$account_api = WeAccount::create();
				$access_token = $account_api->getAccessToken();
				$touser = $_GPC['opa'];
				$template_id = $this->setConfig[34]['value'];
				$findFid = pdo_getall('api_user_formid', array('openid' => $touser,'display' => 0), array() , '' , '' , array(1));
				$form_id = $findFid[0]['formid'];
				$keyword1 = $findUser['nickName'];
				$keyword2 = date('Y/m/d H:i:s',time());
				$keyword3 = '有一位新的访客访问了您的电子名片，快来看看是谁吧！';
				$page = 'api0351_user/pages/index/index';
				$this->SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$keyword3,$page,3);
				pdo_update(self::TABLE_H, array('display' => 1), array('formid' => $findFid[0]['formid']));		
				/**************************/
			}
			$errno = 0;
			return $this->result($errno, $message, $finData);
		}else if($_GPC['mod'] == 1){
			
			$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
			$querya = load()->object('query');
			$querya->from('api_user_visitor')->where(array('ftime >=' => $today_start,'ftime <='=>$today_end,'uniacid' => $_W[uniacid],'opa' => $_GPC['openid']))->page(20)->getall();
			$t['todayPay'] = $querya->getLastQueryTotal();
			
			$yesterday_start=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
			$yesterday_end=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
			$queryb = load()->object('query');
			$queryb->from('api_user_visitor')->where(array('ftime >=' => $yesterday_start, 'ftime <=' => $yesterday_end, 'uniacid' => $_W[uniacid],'opa' => $_GPC['openid']))->page(20)->getall();
			$t['yesterdayPay'] = $queryb->getLastQueryTotal();
				
			$data = pdo_getall('api_user_visitor',array('uniacid' => $_W['uniacid'],'opa' => $_GPC['openid']),array() , '' , 'ftime DESC', array());
			foreach($data as $key=>$value){
				$datas = pdo_getall('api_user_more',array('uniacid' => $_W['uniacid'],'openid' => $value['opb']));
				foreach ($datas as $k => $v) {
					$v['opa'] = $value['opa'];
					$v['ydis'] = $value['ydis'];
					$v['ftime'] = date("m-d H:i", $value['ftime']);
					$arrs[] = $v;
				}
				//$data[$key]['ftime'] = date("m-d H:i", $value['ftime']);
			}
			$errno = 0;
			$message = '谁访问了我';
			return $this->result($errno, $t, $arrs);
		}else if($_GPC['mod'] == 2){	
			$data = pdo_getall('api_user_visitor',array('uniacid' => $_W['uniacid'],'opb' => $_GPC['openid']),array() , '' , 'ftime DESC', array());
			foreach ($data as $key=>$value) {
				$datas = pdo_getall('api_user_more',array('uniacid' => $_W['uniacid'],'openid' => $value['opa']));
				foreach ($datas as $k => $v) {
					$v['ftime'] = date("m-d H:i", $value['ftime']);
					$arrs[] = $v;
				}
			}
			$errno = 0;
			$message = '我访问了谁';
			return $this->result($errno, $message, $arrs);
		}

	}
	
	public function doPageSendi(){
		global $_GPC, $_W;
		$msStart = $_W['current_module']['config']['msStart'];
		if($msStart == 1){
			$account_api = WeAccount::create();
			$access_token = $account_api->getAccessToken();
			$touser = $_GPC['openid'];
			$template_id = $_W['current_module']['config']['bok'];
			$findFid = pdo_getall(self::TABLE_H, array('openid' => $touser,'display' => 0), array() , '' , '' , array(1));
			$form_id = $findFid[0]['formid'];
			$keyword1 = date('Y/m/d H:i:s',time());
			$keyword2 = '您的好友有了自己的电子名片，您也创建一个吧！';
			$page = 'api0351_user/pages/index/index';
			$this->SendMsg($access_token,$touser,$template_id,$form_id,$keyword1,$keyword2,$page);
			pdo_update(self::TABLE_H, array('display' => 1), array('formid' => $findFid[0]['formid'],'uniacid' => $_W['uniacid']));
		}
		pdo_update('api_user_visitor', array('ydis' => 1), array('opa' => $_GPC['opa'],'opb' => $_GPC['openid'],'uniacid' => $_W['uniacid']));
	}
	
	public function doPageVideoadd(){
		global $_GPC, $_W;
		if($_GPC['mod'] == 0){
			$errno = 0;
			$message = '修改成功';
			$data = pdo_update('api_user_more', array('video' => $_GPC['videoUrl']), array('uniacid' => $_W['uniacid'],'openid' => $_GPC['openid']));
			return $this->result($errno, $message, $data);
		}else if ($_GPC['mod'] == 1){
			if (!empty($_FILES)) {
				$errno = 0;
				$message = '图片信息';
				load()->func('file');
				$imginfo = file_upload($_FILES['files'], 'video');
				return $this->result($errno, $message, $imginfo);
			}
		}
	}

		

	public function doPageDelPay(){
		global $_GPC, $_W;
		//var_dump($_W['current_module']['config']['zuNum']);
		//exit;
		if($_W['current_module']['config']['userVip'] == 1){
			// 清除尚未支付的残余订单
			pdo_delete(self::TABLE_F, array('status' => 0));
			// 清除没有填写信息资料的用户，数据库残留空用户
			pdo_delete(self::TABLE_B, array('cid' => 0, 'indes' => 0, 'uptime' => 0, 'user_vip' => 0));
			// 删除无效的模板消息ID
			pdo_delete(self::TABLE_H, array('display' => 1,'formid' => 'the formId is a mock one'), 'OR');
			pdo_delete(self::TABLE_H, array('openid' => 'undefined')); 
			// 删除超7天的消息模板ID
			$date = strtotime('-7 days');
			pdo_delete('api_user_formid', array('time <' => $date)); 
			// 删除过期VIP
			$today = $_W['timestamp'];
			$data = array(
				'user_vip' => 0,
			);
			pdo_update(self::TABLE_B, $data, array('overtime <=' => $today));
			// 删除无效的收藏信息
			pdo_delete(self::TABLE_G, array('openid' => 'undefined','uid' => 0), 'OR'); 
			// 删除无效的关注信息
			pdo_delete(self::TABLE_A, array('avatar' => '','avatar' => 'undefined','openid' => ''), 'OR');
			// 删除无效的留言信息
			pdo_delete(self::TABLE_C, array('openid' => 0)); 
			// 删除过期店铺信息
			$today = $_W['timestamp'];
			//$data = array(
			//	'display' => 0,
			//);
			//pdo_update(self::TABLE_M, $data, array('dqtime <=' => $today));
			pdo_delete(self::TABLE_M, array('dqtime <=' => $today,'pay' => 1));
			// 删除未成功的打赏记录
			pdo_delete(self::TABLE_P, array('status' => 0)); 
		}
	}

}