<?php
/**
 * [奔跑的蜗牛] Copyright (c) 2016
 * 全局常用方法
 */
defined('IN_IA') or exit('Access Denied');
function fx_message($msg, $redirect = '', $type = '', $desc='') {
	global $_W;
	if($redirect == 'refresh') {
		$redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
	} elseif (!empty($redirect) && strexists($redirect, 'javascript')) {
		$redirect = $redirect;
	} elseif (!empty($redirect) && !strexists($redirect, 'http://')) {
		$urls = parse_url($redirect);
		$redirect = $_W['siteroot'] . 'app/index.php?' . $urls['query'];
	}
	if($redirect == '') {
		$type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql')) ? $type : 'info';
	} else {
		$type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql')) ? $type : 'success';
	}
	if($_W['isajax'] || $type == 'ajax') {
		$vars = array();
		$vars['message'] = $msg;
		$vars['redirect'] = $redirect;
		$vars['type'] = $type;
		$vars['desc'] = $desc;
		exit(json_encode($vars));
	}
	if (empty($msg) && !empty($redirect)) {
		header('location: '.$redirect);
	}
	$label = $type;
	if($type == 'error') {
		$label = 'danger';
	}
	if($type == 'ajax' || $type == 'sql') {
		$label = 'warning';
	}
	if (defined('IN_API')) {
		exit($msg);
	}
	fx_load()->func('template');
	include fx_template('common/message', TEMPLATE_INCLUDEPATH);
	exit();
}

//货币格式
function currency_format($currency, $decimals = 2) {
	$currency = floatval($currency);
	if (empty($currency)) {
		return '0.00';
	}
	$currency = number_format($currency, $decimals);
	$currency = str_replace(',', '', $currency);
	return $currency;
}

/*模板消息*/
function sendtplnotice($touser, $template_id, $postdata, $url = '') {
	global $_W;
	$account_api = WeAccount::create();
	$result = $account_api->sendTplNotice($touser, $template_id, $postdata, $url);
	return $result;
}
//触发消息
function sendCustomNotice($openid, $msg, $url = '') {
	global $_W;
	$account_api = WeAccount::create();
	$content = "";
	if (is_array($msg)) {
		foreach ($msg as $key => $value) {
			if (!empty($value['title'])) {
				$content .= $value['title'] . ":" . $value['value'] . "\n";
			} else {
				$content .= $value['value'] . "\n";
				if ($key == 0) {
					$content .= "\n";
				}
			}
		}
	} else {
		$content = $msg;
	}
	if (!empty($url)) {
		$content .= "<a href='{$url}'>点击查看详情</a>";
	}
	return $account_api -> sendCustomNotice(array("touser" => $openid, "msgtype" => "text", "text" => array('content' => urlencode($content))));
}

//sms短信发送
function sendSMS($mobile, $smsParam, $template_id, $type=false) {
	global $_W, $_GPC;
	$appkey = $_W['_config']['sms_appkey'];
	$secret = $_W['_config']['sms_appsecret'];
	$signNam = $_W['_config']['sms_signname'];
	if (!$type){
		require_once FX_CORE . '/library/alidayu/TopSdk.php';
		date_default_timezone_set('Asia/Shanghai'); 
		$c = new TopClient;
		$c ->appkey = $appkey;
		$c ->secretKey = $secret;
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		$req ->setExtend("");//可不填写
		$req ->setSmsType("normal");
		$req ->setSmsFreeSignName($signNam);//短信签名，传入的短信签名必须是审核通过的签名
		$req ->setSmsParam(json_encode($smsParam));//这里是发送内容的相关参数变量
		$req ->setRecNum("$mobile");//接收短信的手机号
		$req ->setSmsTemplateCode($template_id);//这里是短信模板ID
		$resp = $c ->execute($req);
	}else{
		require_once FX_CORE . '/library/alidayu/apilite/SignatureHelper.php';
		//use Aliyun\DySDKLite\SignatureHelper;
		$accessKeyId = $appkey;
		$accessKeySecret = $secret;
		$params["PhoneNumbers"] = "$mobile";
		$params["SignName"] = "$signNam";
		$params["TemplateCode"] = "$template_id";
		$params['TemplateParam'] = $smsParam;
		$params['OutId'] = "12345";
		// fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
		//$params['SmsUpExtendCode'] = "1234567";
		if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
			$params["TemplateParam"] = json_encode($params["TemplateParam"]);
		}
		$helper = new Aliyun\DySDKLite\SignatureHelper;
		try {
			$content = $helper->request($accessKeyId, $accessKeySecret, "dysmsapi.aliyuncs.com",
				array_merge($params, array(
					"RegionId" => "cn-hangzhou",
					"Action" => "SendSms",
					"Version" => "2017-05-25",
				))
			);
		} catch (Exception $e) {
			$content =  $e-> getMessage();
		}
		$resp = $content;
	}
	return $resp;
}
//地图位置坐标
function tpl_form_field_position($field, $value = array()) {
	$s = '';
	if(!defined('TPL_INIT_COORDINATE')) {
		$s .= '<script type="text/javascript">
				function showCoordinate(elm) {
					var val = {};
					val.lng = parseFloat($(elm).parent().prev().prev().find(":text").val());
					val.lat = parseFloat($(elm).parent().prev().find(":text").val());
					val.adinfo = parseFloat($(elm).prev().find(":hidden").val());
					util.maps(val, function(r){
						$(elm).parent().prev().prev().find(":text").val(r.lng);
						$(elm).parent().prev().find(":text").val(r.lat);
						$(elm).parent().find(":hidden").val(r.adinfo);
						$("#address").val(r.label);
					},"'. web_url('map/map/tencent').'");
				}

			</script>';
		define('TPL_INIT_COORDINATE', true);
	}
	$s .= '
		<div class="row row-fix">
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="' . $field . '[lng]" value="'.$value['lng'].'" placeholder="地理经度"  class="form-control" />
			</div>
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="' . $field . '[lat]" value="'.$value['lat'].'" placeholder="地理纬度"  class="form-control" />
			</div>
			<div class="col-xs-4 col-sm-4">
				<input type="hidden" name="' . $field . '[adinfo]" value="'.$value['adinfo'].'"/>
				<button onclick="showCoordinate(this);" class="btn btn-default" type="button">选择坐标</button>
			</div>
		</div>';
	return $s;
}
//生成商户订单号
function createUniontid(){
	$randNum = substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
	$uniontid = date('YmdHis').$randNum;
	return $uniontid;
}
//生成随机码
function createRandomNumber($number){
	$str = '';
	$chars = '0123456789';
	for ($i = 0; $i < $number; $i++) {
		$str .= $chars[mt_rand(0, strlen($chars) - 1)];
	}
	return $str;
}
//生成指定范围随机数串
function createNumber($banNum = array()){
	//这里设置0-9999数组
	$arrNum = range (0000,9999);
	//遍历并删除$arrNum中已出现的号码$banNum
	foreach($banNum as $row)
	{  
		unset($arrNum[$row['num']]); 
	}
	//从剩余中随机取出一个,已占用的不会再出现
	$num = $arrNum[array_rand($arrNum,1)];
	$num = sprintf("%04d", $num);//不足4位补0
	return $num;
}

//页面跳转
function app_url($segment, $params = array(),$mod = MODULE_NAME) {
	global $_W;
	$_W['uniacid'] = empty($_W['oauth']['uniacid'])?$_W['uniacid']:$_W['oauth']['uniacid'];
	list($do, $ac, $op) = explode('/', $segment);
	$url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=' . $mod . '&';
	if (!empty($do)) {
		$url .= "do={$do}&";
	}
	if (!empty($ac)) {
		$url .= "ac={$ac}&";
	}
	if (!empty($op)) {
		$url .= "op={$op}";
	}
	if (!empty($params)) {
		$queryString = http_build_query($params, '', '&');
		$url .= '&' . $queryString;
	}
	return $url;
}
function web_url($segment, $params = array()) {
	global $_W;
	list($do, $ac, $op) = explode('/', $segment);
	if(MERCHANTID || $params['role']=='merchant'){
		$url = $_W['siteroot'] . 'addons/'.MODULE_NAME.'/web/merch.php?';
	}else{
		$url = $_W['siteroot'] . 'web/index.php?c=site&a=entry&m=' . MODULE_NAME . '&';
	}
	if (!empty($do)) {
		$url .= "do={$do}&";
	}
	if (!empty($ac)) {
		$url .= "ac={$ac}&";
	}
	if (!empty($op)) {
		$url .= "op={$op}";
	}
	if (!empty($params)) {
		$queryString = http_build_query($params, '', '&');
		$url .= '&' . $queryString;
	}
	return $url;
}
//主办方操作权限
function allow($do, $ac, $op, $merchantid) {
	global $_W;
	$role_op = pdo_get('fx_user_node', array('do' => $do, 'ac' => $ac, 'op' => $op));
	$role = pdo_fetch("select * from" . tablename('fx_user_role') . "where uniacid={$_W['uniacid']} and merchantid={$merchantid}");
	$nodes = unserialize($role['nodes']);
	if (!empty($role_op['id']) && !empty($nodes)) {
		if (in_array($role_op['id'], $nodes)) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}

}

//时间转换函数
function tranTime($time) {
	if ($time!=NULL && $time!=''){
		$rtime = date("m月d日 H:i", $time);
		$rtime2 = date("Y年m月d日 H:i", $time);
		$htime = date("H:i", $time);
		$time = time() - $time;
		if ($time < 60) {
			$str = '刚刚';
		} elseif ($time < 60 * 60) {
			$min = floor($time / 60);
			$str = $min . ' 分钟前';
		} elseif ($time < 60 * 60 * 24) {
			$h = floor($time / (60 * 60));
			$str = $h . '小时前 ' . $htime;
		} elseif ($time < 60 * 60 * 24 * 3) {
			$d = floor($time / (60 * 60 * 24));
			if ($d == 1) $str = '昨天 ' . $htime;
			else $str = '前天 ' . $htime;
		} elseif ($time < 60 * 60 * 24 * 7) {
			$d = floor($time / (60 * 60 * 24));
			$str = $d . ' 天前 ' . $htime;
		} elseif ($time < 60 * 60 * 24 * 30) {
			$str = $rtime;
		} else {
			$str = $rtime2;
		}
	}else{
		$str = "无";
	}
	return $str;
}

/** 
* 短网址生成
* 
* @name short_url 
* @param $url_long  长连接
* @param $appkey  新浪appkey
* @return array 
*/ 
function short_url($url_long){
	$appkey = "2155982343";
	$response = ihttp_get("https://api.weibo.com/2/short_url/shorten.json?source=" . $appkey . "&url_long=" . $url_long);
	if (is_error($response)) {
		$short_url = $url_long;
		$res = error(-1, "读取新浪接口失败, 错误: {$response['message']}");
		$res['url_short'] = $url_long;
		return $res;
	}
	$result = @json_decode($response['content'], true);
	if (empty($result)) {
		$res = error(-1, "接口调用失败, 元数据: {$response['meta']}");
		$res['url_short'] = $url_long;
		return $res;
	} elseif (!empty($result['error_code'])) {
		$res = error(-1, "接口调用失败, 错误代码: {$result['error_code']}, 错误信息: {$result['error']}");
		$res['url_short'] = $url_long;
		return $res;
	}
	$res = error(0, "成功");
	$res['url_short'] = $result['urls'][0]['url_short'];
	return $res['url_short'];
}
/* 附近 */
/**
 * 计算当前商家位置是否在范围内
 * @param 当前位置经度 $lat_a
 * @param 计算经度 $lng_a
 * @param 当前位置维度 $lat_b
 * @param 计算纬度 $lng_b
 * @author bieanju
 * @return number 距离  */
function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b) {
	//R是地球半径（米）
	$R = 6366000;
	$pk = doubleval(180 / 3.14169);
	$a1 = doubleval($lat_a / $pk);
	$a2 = doubleval($lng_a / $pk);
	$b1 = doubleval($lat_b / $pk);
	$b2 = doubleval($lng_b / $pk);
	$t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
	$t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
	$t3 = doubleval(sin($a1) * sin($b1));
	$tt = doubleval(acos($t1 + $t2 + $t3));
	return round($R * $tt);
}
/** 
* 格式化html
* 
* @name $htmlstr
* @name $htmlstr
* @name $res
*/ 
function html_format($htmlstr, $arr = array('img'=>1)){
	$allow = "<h1> <h2> <h3> <ul> <ol> <li> <p> <b> <strong> <span> <a>";
	$allow.= $arr['img'] ? " <img>" : "";
	$res = preg_replace("/(\s)class=.+?['|\"]/i", "", htmlspecialchars_decode($htmlstr));
	$res = preg_replace("/(\s)id=.+?['|\"]/i", "", $res);
	$res = preg_replace("/&#39;/i", chr(39), $res);
	
	$res = preg_replace_callback("/(style=.+?['|\"]>)/is", function($matched){
    	return preg_replace("/\">/is", ';">', $matched[0]);
	}, $res);
	$res = preg_replace_callback("/(style=.+?['|\"]>)/is", function($matched){
    	return preg_replace("/'>/is", ";'>", $matched[0]);
	}, $res);
	
	$res = preg_replace("/;;'>/i", ";'>", $res);
	$res = preg_replace("/;;\">/i", ";\">", $res);
	$res = preg_replace("/ul(\s)style=.+?['|\"]/i", "ul", $res);
	$res = preg_replace("/ol(\s)style=.+?['|\"]/i", "ol", $res);
	$res = preg_replace('/font:[^;]+?;/i', "", $res);
	$res = preg_replace('/font-family:[^;]+?;/i', "", $res);
	$res = preg_replace('/font-size-adjust:[^;]+?;/i', "", $res);
	$res = preg_replace('/font-stretch:[^;]+?;/i', "", $res);
	$res = preg_replace('/font-style:[^;]+?;/i', "", $res);
	$res = preg_replace('/font-variant:[^;]+?;/i', "", $res);
	$res = preg_replace('/font-weight:[^;]+?;/i', "", $res);
	
	$res = preg_replace('/line-height:[^;]+?;/i', "", $res);
	$res = preg_replace('/vertical-align:[^;]+?;/i', "", $res);
	$res = preg_replace('/margin.*?:[^;]+?;/i', "", $res);
	$res = preg_replace('/padding.*?:[^;]+?;/i', "", $res);
	$res = preg_replace('/background.*?:[^;]+?;/i', "", $res);
	$res = preg_replace('/border.*?:[^;]+?;/i', "", $res);
	$res = preg_replace('/display:[^;]+?;/i', "", $res);
	$res = preg_replace('/float:[^;]+?;/i', "", $res);
	
	$res = preg_replace_callback("/(style=.+?['|\"])/is", function($matched){
    	return preg_replace('/[\s\n]+/is', '', $matched[0]);
	}, $res);
	$res = preg_replace_callback("/<span[\s]style=.+?['|\"]/is", function($matched){
    	return preg_replace('/text-align:[^;]+?;/is', '', $matched[0]);
	}, $res);
	
	$res = preg_replace("/(\s)style=['|\"];['|\"]/i", "", $res);
	$res = preg_replace("/(\s)style=['|\"]['|\"]/i", "", $res);	
	$res = strip_tags($res, $allow);
	return $res;
}
function getFile(){
	$f = $_FILES['file'];
	return $f;
}
?>