<?php
/**
 * 模块小程序接口定义
 */

defined('IN_IA') or exit('Access Denied');
class Weisrc_DishModuleWxapp extends WeModuleWxapp{
	public function __construct()
	{
		global $_GPC;
		global $_W;
		global $openid;
		$this->iswxapp = true;
		$_W['account']['key'] = $_W['wxappkey'];
		$_W['openid'] = $_GPC['openid'];
		$_W['fans'] = mc_fansinfo($_W['openid']);
		$_W['member']['uid'] = $_W['fans']['uid'];
		//$_W['openid'] = 'o9NSQt-C6M33iau0Cz-cBpsGlBJI';
		//$_W['fans']['from_user'] = 'o9NSQt-C6M33iau0Cz-cBpsGlBJI';
		//$_W['member']['uid'] = '156819';
		$this->openid = $_W['openid'];
	}
	public function doPageindex() {
		//首页，读取门店列表
		global $_W,$_GPC;
		$result=pdo_getall('weisrc_dish_stores',array('weid'=>$_W['uniacid']),array('id','title','logo','level','address','sendingprice','dispatchprice','begintime','endtime','lat','lng'));
		if(!empty($result)){
			foreach($result as &$row){
				$row['logo']=tomedia($row['logo']);
				$row['lat']=sprintf('%.2f', $row['lat']);
				if(!$row['begintime'] || !$row['endtime']){
					$row['is_rest']=true;
				}else{
					$start=strtotime($row['begintime']);
					$end=strtotime($row['endtime']);
					if($start<TIMESTAMP && TIMESTAMP<$end){
						$row['is_rest']=true;
					}
				}
			}
		}
		return $this->result(0, '成功', $result);
	}
	public function doPageslide() {
		//首页，读取门店列表
		global $_W,$_GPC;
		$slide = pdo_fetchall("SELECT * FROM " . tablename('weisrc_dish_ad') . " WHERE uniacid = :uniacid AND position=1 AND status=1 AND :time > starttime AND :time < endtime  ORDER BY displayorder DESC,id DESC LIMIT 6", array(':uniacid' => $_W['uniacid'], ':time' => TIMESTAMP));
		return $this->result(0, '成功', $slide);
	}
	public function doPagelist() {
		//商品列表
		global $_W,$_GPC;
		$result=pdo_getall('weisrc_dish_category',array('storeid'=>$_GPC['storeid']));
		if($result){
			foreach($result as &$row){
				$row['dishes']=pdo_getall('weisrc_dish_goods',array('storeid'=>$_GPC['storeid'],'pcate'=>$row['id'],'status'=>1));
				foreach($row['dishes'] as &$d){
					$d['thumb']=tomedia($d['thumb']);
				}
			}
		}
		return $this->result(0, '成功', $result);
	}
	public function doPagedetail() {
		//商品详情
		global $_W,$_GPC;
		$good_id=intval($_GPC['good_id']);
		$result=pdo_get('weisrc_dish_goods',array('id'=>$good_id));
		$result['thumb']=tomedia($result['thumb']);
		return $this->result(0, '成功', $result);
	}
	public function doPagecartList() {
		//购物车
		global $_W,$_GPC;
		$result=pdo_getall('weisrc_dish_cart',array('weid'=>$_W['uniacid'],'from_user'=>$_W['openid']));
		if($result){
			foreach($result as &$row){
				$goods=pdo_get('weisrc_dish_goods',array('id'=>$row['goodsid']),array('title','thumb','marketprice'));
				$row['title']=$goods['title'];
				$row['thumb']=tomedia($goods['thumb']);
				$row['price']=$goods['marketprice'];
			}
		}
		return $this->result(0, '成功', $result);
	}
	public function doPageDelteCart() {
		//删除购物车商品
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		if($id){
			pdo_delete('weisrc_dish_cart',array('weid'=>$_W['uniacid'],'from_user'=>$_W['openid'],'id'=>$id));
		}
		return $this->result(0, '成功', array());
	}
	public function doPagesubmitOrder() {
		//提交订单
		global $_W,$_GPC;
		//print_r($_GPC['order']);exit;
		$data=json_decode(htmlspecialchars_decode($_GPC['order']),true);
		$order_goods=array();
		$total_price=0.00;
		$total_num=0;
		if(!is_array($data)){
			return $this->result(1, '参数错误');
		}
		
		foreach($data as $row){
			$goods=pdo_get('weisrc_dish_goods',array('id'=>intval($row['goodsid'])),array('id','marketprice'));
			$num=intval($row['nums']);
			$order_goods[]=array(
				'weid'=>$_W['uniacid'],
				'storeid'=>intval($_GPC['storeid']),
				'goodsid'=>$goods['id'],
				'price'=>$goods['marketprice'],
				'total'=>$num
			);
			$total_price+=$goods['marketprice']*$num;
			$total_num+=$num;
		}
		$order=array(
			'weid'=>$_W['uniacid'],
			'storeid'=>intval($_GPC['storeid']),
			'from_user'=>$_W['openid'],
			'totalnum'=>$total_num,
			'totalprice'=>$total_price,
			'paytype'=>2,
			'ordersn'=>date('ymdHis').random(5,true),
		);
		//print_r($order);exit;
		pdo_insert('weisrc_dish_order',$order);
		$order_id=pdo_insertid();
		foreach($order_goods as $o){
			$o['orderid']=$order_id;
			pdo_insert('weisrc_dish_order_goods',$o);
		}
		return $this->result(0, '成功', $order_id);
		
	}
	public function doPageorder() {
		global $_W,$_GPC;
		$orderid=intval($_GPC['orderid']);
		$order=pdo_get('weisrc_dish_order',array('id'=>$orderid),array('id','ordersn','totalprice'));
		//print_r($order);exit;
		$order_goods=pdo_getall('weisrc_dish_order_goods',array('orderid'=>$orderid),array('total','goodsid'));
		foreach($order_goods as &$row){
			$goods=pdo_get('weisrc_dish_goods',array('id'=>$row['goodsid']),array('title','thumb','marketprice'));
			$goods['thumb']=tomedia($goods['thumb']);
			$row=array_merge($row,$goods);
		}
		$order['goods']=$order_goods;
		return $this->result(0, '成功', $order);
	}
	public function doPagepay() {
		global $_W,$_GPC;
		$order=array(
			'username'=>trim($_GPC['username']),
			'tel'=>trim($_GPC['tel']),
			'remark'=>trim($_GPC['remark']),
			'address'=>trim($_GPC['address'])
		);
		$id=intval($_GPC['id']);
		pdo_update('weisrc_dish_order',$order,array('id'=>$id));
		$order=pdo_get('weisrc_dish_order',array('id'=>$id),array('id','totalprice'));
		$data=array(
			'title'=>'餐费',
			'fee'=>$order['totalprice'],
			'tid'=>$order['id']
		);
		$params['tid'] = $order['id'];
		$params['user'] = $_W['fans']['from_user'];
		$params['title'] = '餐费';
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		$params['fee'] = $order['totalprice'];
		$res = $this->wxpay($params, 14);
		if (!is_error($res)) {
				$wechat = array('success' => true, 'payinfo' => $res);
		}else {
				$wechat['payinfo'] = $res;
		}
		return $this->result(0, '成功', $wechat);
		
	}
	public function doPagepaysuccess() {
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		if($id){
			pdo_update('weisrc_dish_order',array('ispay'=>1),array('id'=>$id));
		}
		return $this->result(0, '成功',array());
	}
	public function doPageAddToCart() {
		//添加购物车
		global $_W,$_GPC;
		$goodsid=intval($_GPC['goodsid']);
		//$num=intval($_GPC['num']);
		$cart=pdo_get('weisrc_dish_cart',array('weid'=>$_W['uniacid'],'from_user'=>$_W['openid'],'goodsid'=>$goodsid),array('id','total'));
		if($cart){
			pdo_update('weisrc_dish_cart',array('total'=>$cart['total']+1),array('id'=>$cart['id']));
		}else{
			$insert=array(
				'weid'=>$_W['uniacid'],
				'from_user'=>$_W['openid'],
				'goodsid'=>$goodsid,
				'total'=>1
			);
			pdo_insert('weisrc_dish_cart',$insert);
		}
		return $this->result(0, '成功', array());
	}
	
	public function doPageorderList() {
		//我的订单
		global $_W,$_GPC;
		$result=pdo_getall('weisrc_dish_order',array('weid'=>$_W['uniacid'],'from_user'=>$_W['openid']),array('ispay','id','totalprice','createtime'));
		foreach($result as &$order){
			$order_goods=pdo_getall('weisrc_dish_order_goods',array('orderid'=>$order['id']),array('total','goodsid'));
			foreach($order_goods as &$row){
				$goods=pdo_get('weisrc_dish_goods',array('id'=>$row['goodsid']),array('title','thumb','marketprice','createtime'));
				$goods['thumb']=tomedia($goods['thumb']);
				$row=array_merge($row,$goods);
			}
			$order['goods']=$order_goods;
			$order['addtime']=date('20y/m/d H:i:s',$order['createtime']);
		}
		return $this->result(0, '成功', $result);
	}
	public function wxpay($params, $type = 0)
	{
			global $_W;
			$setting = uni_setting($_W['wx_uniacid']);
			$wxpay = $setting['payment']['wechat'];
			$openid = (empty($params['openid']) ? $_W['openid'] : $params['openid']);
			$package = array();
			$package['appid'] = $_W['wxappkey'];
			$package['mch_id'] = $wxpay['mchid'];
			$package['nonce_str'] = random(32);
			$package['body'] = $params['title'];
			$package['device_info'] = 'wdl_shopping';
			$package['attach'] = $_W['uniacid'] . ':' . $type;
			$package['out_trade_no'] = $params['tid'];
			$package['total_fee'] = $params['fee'] * 100;
			$package['spbill_create_ip'] = CLIENT_IP;

			if (!empty($params['goods_tag'])) {
				$package['goods_tag'] = $params['goods_tag'];
			}

			$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
			$package['trade_type'] = 'JSAPI';
			$package['openid'] = $openid;
			ksort($package, SORT_STRING);
			$string1 = '';

			foreach ($package as $key => $v) {
				if (empty($v)) {
					continue;
				}

				$string1 .= $key . '=' . $v . '&';
			}

			$string1 .= 'key=' . $wxpay['signkey'];
			$package['sign'] = strtoupper(md5($string1));
			$dat = array2xml($package);
			//file_put_contents(IA_ROOT.'/data/cs.txt',$string1.$_W['wx_uniacid']);
			load()->func('communication');
			$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);

			if (is_error($response)) {
				return error(-1, $response['message']);
			}

			$xml = @simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);

			if (strval($xml->return_code) == 'FAIL') {
				return error(-2, strval($xml->return_msg));
			}

			if (strval($xml->result_code) == 'FAIL') {
				return error(-3, strval($xml->err_code) . ': ' . strval($xml->err_code_des));
			}

			$prepayid = $xml->prepay_id;
			$wOpt['appId'] = $_W['wxappkey'];
			$wOpt['timeStamp'] = TIMESTAMP . '';
			$wOpt['nonceStr'] = random(32);
			$wOpt['package'] = 'prepay_id=' . $prepayid;
			$wOpt['signType'] = 'MD5';
			ksort($wOpt, SORT_STRING);
			$string = '';

			foreach ($wOpt as $key => $v) {
				$string .= $key . '=' . $v . '&';
			}

			$string .= 'key=' . $wxpay['signkey'];
			$wOpt['paySign'] = strtoupper(md5($string));
			unset($wOpt['appId']);
			return $wOpt;
	}

	public function isWeixinPay($out_trade_no, $money = 0)
		{
			global $_W;
			global $_GPC;
			$setting = uni_setting($_W['wx_uniacid']);
			$wxpay = $setting['payment']['wechat'];
			$url = 'https://api.mch.weixin.qq.com/pay/orderquery';
			$pars = array();
			$pars['appid'] = $_W['wxappkey'];
			$pars['mch_id'] = $wxpay['mchid'];
			$pars['nonce_str'] = random(32);
			$pars['out_trade_no'] = $out_trade_no;
			ksort($pars, SORT_STRING);
			$string1 = '';

			foreach ($pars as $k => $v) {
				$string1 .= $k . '=' . $v . '&';
			}

			$string1 .= 'key=' . $wxpay['signkey'];
			$pars['sign'] = strtoupper(md5($string1));
			$xml = array2xml($pars);
			load()->func('communication');
			$resp = ihttp_post($url, $xml);

			if (is_error($resp)) {
				return error(-2, $resp['message']);
			}

			if (empty($resp['content'])) {
				return error(-2, '网络错误');
			}

			$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
			$dom = new DOMDocument();

			if ($dom->loadXML($xml)) {
				$xpath = new DOMXPath($dom);
				$code = $xpath->evaluate('string(//xml/return_code)');
				$ret = $xpath->evaluate('string(//xml/result_code)');
				$trade_state = $xpath->evaluate('string(//xml/trade_state)');
				if ((strtolower($code) == 'success') && (strtolower($ret) == 'success') && (strtolower($trade_state) == 'success')) {
					$total_fee = intval($xpath->evaluate('string(//xml/total_fee)')) / 100;

					if ($total_fee != $money) {
						return error(-1, '金额出错');
					}

					return true;
				}

				if ($xpath->evaluate('string(//xml/return_msg)') == $xpath->evaluate('string(//xml/err_code_des)')) {
					$error = $xpath->evaluate('string(//xml/return_msg)');
				}
				else {
					$error = $xpath->evaluate('string(//xml/return_msg)') . ' | ' . $xpath->evaluate('string(//xml/err_code_des)');
				}

				return error(-2, $error);
			}

			return error(-1, '未知错误');
	}
}