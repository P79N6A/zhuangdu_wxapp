<?php
	// +----------------------------------------------------------------------
	// | Copyright (c) 2015-2020.
	// +----------------------------------------------------------------------
	// | Describe: 主办方操作模型
	// +----------------------------------------------------------------------
	// | Author: woniu<305439701@qq.com>
	// +----------------------------------------------------------------------
class model_merchant{
		
	/** 
	* 获取指定主办方信息 
	* 
	* @access public
	* @name getSingleMerchant 
 	* @param $id      缓存标志 
 	* @param $select  查询参数 
 	* @param $where   查询条件 
	* @return array 
	*/  		
	static function getSingleMerchant($id,$select,$where=array()){
		global $_W;
		$id = intval($id);
		$where['id'] = $id;
		if ($id){
			$merchant  = Util::getSingelData($select, 'fx_merchant', $where);//读取主办方
			$merchant['logo']    = tomedia($merchant['logo']);
			$merchant['kefuimg'] = tomedia($merchant['kefuimg']);
			$mcert = Util::getSingelData('*', 'fx_merchant_mcert', array('mid' => $id));
			if (empty($mcert)){
				$merchant['iscert'] = 0;
			}elseif ($mcert['status']==1 && TIMESTAMP <= $mcert['endtime']){			
				$merchant['iscert'] = 1;
			}
		}else{
			$merchant['id']        = 0;
			$merchant['uid']       = 0;
			$merchant['name']      = $_W['_config']['sname'];
			$merchant['logo']      = tomedia($_W['_config']['slogo']);
			$merchant['kefuimg']   = tomedia($_W['_config']['kefu']['qrcode']);
			$merchant['detail']    = $_W['_config']['detail'];
			$merchant['linkman_name'] = $_W['_config']['linkman_name'];
			$merchant['linkman_mobile'] = $_W['_config']['mobile'];			
			$merchant['lng']       = $_W['_config']['lng'];
			$merchant['lat']       = $_W['_config']['lat'];
			$merchant['adinfo']    = $_W['_config']['adinfo'];
			$merchant['address']   = $_W['_config']['address'];
			$merchant['storename'] = $_W['_config']['storename'];
			$merchant['followno']  = $_W['_config']['followno'];
			$merchant['iscert']     = 1;
		}
		return $merchant;
	}
	/** 
 	* 获取所有主办方数据 
 	* 
 	* @access static
 	* @name getNumCategory 
 	* @return array 
 	*/  
	static function getNumMerchant($pindex=0, $psize=0, $ifpage=0,$mid=0){
        $where = array();
        if($mid){
            $where['id']=$mid;
        }
		return Util::getNumData('*', 'fx_merchant', $where,'id desc',$pindex,$psize,$ifpage);
	}
	/** 
	* 获取指定主办方金额变动记录 
	* 
	* @access public
	* @name 方法名称 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function  getMoneyRecord($merchantid,$pindex,$psize,$ifpage){
		return Util::getNumData('*', 'fx_merchant_money_record', array('merchantid'=>$merchantid), 'createtime desc', $pindex, $psize, $ifpage);
	}
	/** 
	* 更新主办方总金额 
	* 
	* @access static
	* @name updateAmount 
	* @param $money  更新金额（元） 
	* @param $merchantid  主办方ID 
	* @return array 
	*/  
	static function  updateAmount($money,$merchantid,$orderid,$type=1,$detail=''){
		global $_W;
		if(empty($merchantid)) return FALSE;
		$merchant = pdo_fetch("select amount from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid={$merchantid} ");
		if(empty($merchant))
			return pdo_insert("fx_merchant_account",array('no_money'=>0,'merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'uid'=>$_W['uid'],'amount'=>$money,'updatetime'=>TIMESTAMP));
		else
			return pdo_update("fx_merchant_account",array('amount'=>$merchant['amount']+$money),array('merchantid'=>$merchantid));
	}
	/** 
	* 更新指定主办方的未结算金额 
	* 
	* @access static
	* @name 方法名称 
	* @param $money  更新金额（元） 
	* @param $merchantid  主办方ID
	* @return array 
	*/  
	static function  updateNoSettlementMoney($money,$merchantid){
		global $_W;
		if(empty($merchantid)) return '主办方ID错误';
		$merchant = pdo_fetch("select id,no_money from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid={$merchantid} ");
		if(empty($merchant)){
			pdo_insert("fx_merchant_account",array('no_money'=>0,'merchantid'=>$merchantid,'uniacid'=>$_W['uniacid'],'uid'=>$_W['uid'],'amount'=>0,'updatetime'=>TIMESTAMP));
			$merchant = pdo_fetch("select no_money from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid={$merchantid} ");
		}
		
		$m = $merchant['no_money']+$money;
		if(pdo_update("fx_merchant_account",array('no_money'=>$m,'updatetime'=>TIMESTAMP),array('merchantid'=>$merchantid))){
			return TRUE;
		}else{
			return FALSE;
		}
		
	}

	/** 
	* 得到指定主办方的未结算金额
	* 
	* @access static
	* @name getNoSettlementMoney 
	* @param $merchantid  主办方ID 
	* @return array 
	*/  
	static function  getNoSettlementMoney($merchantid){
		global $_W;
		$merchant = pdo_fetch("select no_money from".tablename('fx_merchant_account')."where uniacid={$_W['uniacid']} and merchantid={$merchantid} ");
		return $merchant['no_money'];
	}
	/** 
	* 给主办方结算到微信钱包 
	* 
	* @access static
	* @name finance 
	* @param $openid  收款人OPENID 
	* @param $money   收款（分）
	* @param $desc    说明 
	* @return array 
	*/  
	static function finance($openid = '', $money = 0, $desc = '') {
		global $_W;
		$pay = new WeixinPay;
		
		load() -> func('communication');
		$setting = uni_setting($_W['uniacid'], array('payment'));
		if (empty($openid)) return error(-1, 'openid不能为空');
		if (!is_array($setting['payment'])) return error(1, '没有设定支付参数');
//		$wechat = $setting['payment']['wechat'];
//		$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid limit 1';
//		$row = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
		$pars = array();
		$pars['mch_appid'] = $_W['account']['key'];
		$pars['mchid'] = $setting['payment']['wechat']['mchid'];
		$pars['nonce_str'] = $pay->createNoncestr();
		$pars['partner_trade_no'] = time() . random(4, true);
		$pars['openid'] = $openid;
		$pars['check_name'] = 'NO_CHECK';
		$pars['amount'] = $money;
		$pars['desc'] = empty($desc) ? '主办方佣金提现' : $desc;
		$pars['spbill_create_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
		if(empty($pars['mch_appid']) || empty($pars['mchid'])){
			$rearr['err_code']='请先在微擎的功能选项-支付参数内设置微信商户号和秘钥';
			return $rearr;
		}
	//	ksort($pars, SORT_STRING);
	//	$string1 = '';
	//	foreach ($pars as $k => $v) {
	//		$string1 .= "{$k}={$v}&";
	//	}
	//	$string1 .= "key=" . $wechat['apikey'];
	//	$pars['sign'] = strtoupper(md5($string1));
	//	$xml = array2xml($pars);
		
	//	$path_cert = IA_ROOT . '/attachment/'.{MODULE_NAME}.'/cert/' . $_W['uniacid'] . '/apiclient_cert.pem';
	//	//证书路径
	//	$path_key = IA_ROOT . '/attachment/'.{MODULE_NAME}.'/cert/' . $_W['uniacid'] . '/apiclient_key.pem';
	//	
	//	//证书路径
	//	if (!file_exists($path_cert) || !file_exists($path_key)) {
	//		$path_cert = IA_ROOT . '/addons/'.{MODULE_NAME}.'/cert/' . $_W['uniacid'] . '/apiclient_cert.pem';
	//		$path_key = IA_ROOT . '/addons/'.{MODULE_NAME}.'/cert/' . $_W['uniacid'] . '/apiclient_key.pem';
	//	}
	//	$path_ca = IA_ROOT . '/attachment/'.{MODULE_NAME}.'/cert/' . $_W['uniacid'] . '/rootca.pem';
	//	$extras = array();
	//	$extras['CURLOPT_SSLCERT'] = $path_cert;
	//	$extras['CURLOPT_SSLKEY'] = $path_key;
	//	$extras['CURLOPT_CAINFO'] = $path_ca;
	//	$resp = ihttp_request($url, $xml, $extras);
		//new
		$pars['sign'] = $pay->getSign($pars);
		$xml = $pay->arrayToXml($pars);
		$resp = $pay->wxHttpsRequestPem($xml,$url);
		$rearr = $pay->xmlToArray($resp);
		return $rearr;
		//new
	
	}
		
}
