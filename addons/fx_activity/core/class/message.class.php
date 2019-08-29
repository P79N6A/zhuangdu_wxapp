<?php
class message{
	static function joinpay_success($openid, $price, $title, $tel, $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_pay = $_W['_config']['m_pay'];
		if (empty($m_pay)){
			$msg = '';
			$msg .= "【报名提醒】尊敬的用户您好,您已成功报名.\n——".date('Y-m-d H:i:s',time())."\n";
			$msg .= "";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => "尊敬的用户您好,您已成功付款.",
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => "￥ " . $price,
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => $title,
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					'value' => date('Y年m月d日 H:i',TIMESTAMP),
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => '如有问题请致电'.$tel.'或直接在微信留言，我们将第一时间为您服务！',
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_pay, $postdata, $url);
		}
	}
	
	static function join_success($openid, $activity = array(), $recordid, $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_join = $_W['_config']['m_join'];
		$records = model_records::getSingleRecords($recordid);
		if (empty($records['aprice']))
		{
			$price = '免支付';	
		}else{
			$price = $records['paytype']=='delivery' ? "[线下支付]" : '￥'.$records['price'];
		}
		$optionname = empty($records['optionname'])?"无":$records['optionname'];
		$first = empty($activity['tmplmsg']['jointitle'])?"尊敬的客户您好,您已成功报名":$activity['tmplmsg']['jointitle'];
		$remark = empty($activity['tmplmsg']['joinremark'])?"":"\n\n".$activity['tmplmsg']['joinremark'];
		//读取活动地址
		if ($activity['hasonline']){//线上
			$address = '线上活动';
		}elseif ($activity['hasstore']){//内部设置
			$address = $activity['address'];
		}else{//门店
			if (!empty($activity['storeids'])){
				$stores = model_activity::getNumActivityStore($activity['storeids']);
				foreach ($stores as $key => $row) {
					$address = $row['address'];
					break;
				}
			}else{
				$merchant  = model_activity::getActivityMerchant($activity['merchantid']);//读取主办方
				$address  = $merchant['address'];
			}
		}
		
		if (empty($m_join)){
			$msg = '';
			$msg .= "【报名提醒】尊敬的用户您好,您已成功报名.\n——".date('Y-m-d H:i:s',time())."\n";
			$msg .= "";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => "$first.\n",
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $records['nickname'],
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => $activity['title'],
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					"value" => date('m月d日 H:i',strtotime($activity['starttime'])),
					"color" => "#4a5077"
				) ,
				"keyword4" => array(
					"value" => $records['buynum'] . "名",
					"color" => "#4a5077"
				) ,
				"keyword5" => array(
					"value" => $price,
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => "所选规格：".$optionname."\n活动地点：".$address.$remark,
				) ,
			);
			sendtplnotice($openid, $m_join, $postdata, $url);
		}
	}
	
	static function admin_notice($openid, $activity = array(), $recordid, $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_join = $_W['_config']['m_join'];
		$records = model_records::getSingleRecords($recordid);
		if (empty($records['aprice']))
		{
			$price = '免支付';	
		}else{
			$price = $records['paytype']=='delivery' ? "[线下支付]" : '￥'.$records['price'];
		}
		if (empty($m_join)){
			$msg = '';
			$msg .= "【报名提醒】\n".$activity['title']."\n\n用户昵称：".$records['nickname'].".\n报名时间：".date('Y-m-d H:i:s',time())."\n用户手机：".$records['mobile'];
			$msg .= "";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => "编号：".$records['orderno'] . "\n",
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $records['nickname'],
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => $activity['title'],
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					"value" => date('m月d日 H:i',strtotime($activity['starttime'])),
					"color" => "#4a5077"
				) ,
				"keyword4" => array(
					"value" => $records['buynum'] . "名",
					"color" => "#4a5077"
				) ,
				"keyword5" => array(
					"value" => $price,
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => "\n用户手机：".$records['mobile'],
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_join, $postdata, $url);
		}
	}
	
	static function hexiao_notice($openid, $activity = array(), $url) {
		global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_hexiao = $_W['_config']['m_hexiao'];
		if (empty($m_hexiao)){
			$msg = "【核销提醒】尊敬的用户您好,您的活动凭证已成功消费.\n\n活动名称：".$activity['title'].".\n消费时间：".date('Y年m月d日 H:i:s',time())."\n\n";
			$url = app_url('records/records/list'); // 报名成功通知
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => "尊敬的用户您好,您的活动凭证已成功消费.\n",
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $activity['title'],
				) ,
				"keyword2" => array(
					'value' => date('Y年m月d H:i:s',time()),
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => "",
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_hexiao, $postdata, $url);
		}
	}
	
	static function join_cancel($openid, $title, $starttime, $recordid, $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_cancle = $_W['_config']['m_cancle'];
	    $records = model_records::getSingleRecords($recordid);
		$price = ($records['status']==0 || $records['status']==5) && $records['aprice'] > 0 && empty($records['payprice']) ? '￥'.$records['price']."[未支付]" : '￥'.$records['price'];
		$price = empty($records['aprice']) ? '免费' : $price;
		if (empty($m_cancle)){
			$msg = '';
			$msg .= "【报名提醒】尊敬的用户您好,您已成功取消报名.\n——".date('Y-m-d H:i:s',time())."\n";
			$msg .= "";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => "您好，您已成功取消报名!",
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $_W['fans']['nickname'],
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => $title,
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					"value" => date('m月d日 H:i',strtotime($starttime)),
					"color" => "#4a5077"
				) ,
				"keyword4" => array(
					"value" => $price,
					"color" => "#4a5077"
				) ,
				"keyword5" => array(
					"value" => empty($records['payprice'])? "￥0" : "￥" . $records['payprice'],
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => "感谢您对\"".$_W['_config']['sname']."\"的支持，期待下次您的热情参与!",
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_cancle, $postdata, $url);
		}
	}
	
	static function join_review($openid, $activity = array(), $review, $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_review = $_W['_config']['m_review'];
		$timestr = date('m月d日 H:i',strtotime($activity['starttime']));
		$first = $review ? ($review==1 ? "恭喜您，您的活动报名已审核通过.\n" : "报名成功，已通知管理员审核.\n") : "很遗憾，您的活动报名审核未通过.\n";
		$remark = $review ? "\n点击详情查看更多详细信息" : "\n信息不符，官方已拒绝";
		//$url = $review ? $url : "";
		$result = $review ? ($review==1 ? "已通过" : "待审核") : "未通过";
		
		if (empty($m_review)){
			$msg = '';
			$msg .= "【审核结果】\n尊敬的用户您好,您的报名审核" . $result . ".\n\n活动名称：" . $activity['title'] . "\n活动时间：" . $timestr . "\n";
			$msg .= "";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => $first,
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $activity['title'],
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => $timestr,
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					"value" => $result,
					"color" => "#FF0000"
				) ,
				"remark" => array(
					"value" => $remark,
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_review, $postdata, $url);
		}
	}
	
	static function activity_review($openid, $activity = array(), $review, $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_review = $_W['_config']['m_review'];
		$timestr = date('m月d日 H:i',strtotime($activity['starttime']));
		$first = $review==1 ? "恭喜您，您发起的活动已审核通过.\n" : "很遗憾，您发起的活动审核未通过.\n";
		$remark = $review==1 ? "点击详情查看更多详细信息" : "请联系官方人员";
		//$url = $review ? $url : "";
		$result = $review==1 ? "已通过" : "未通过";
		if (empty($m_review)){
			$msg = '';
			$msg .= "【审核结果】\n尊敬的用户您好,您发起的活动审核" . $result . ".\n\n活动名称：" . $activity['title'] . "\n活动时间：" . $timestr . "\n";
			$msg .= "";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => $first,
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $activity['title'],
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => $timestr,
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					"value" => $result,
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => $remark,
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_review, $postdata, $url);
		}
	}
	
	static function admin_notice_cash($openid, $merchant = array(),$url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_cash = $_W['_config']['m_cash'];
		$first = "商户【".$merchant['name']."】提交了提现申请\n";
		$remark = "\n请及时处理！";
		//$url = $review ? $url : "";
		$result = $review==1 ? "已通过" : "未通过";
		if (empty($m_cash)){
			$msg = "商户【".$merchant['name']."】提交了提现申请\n\n";
			$msg .= "申请金额：￥".$merchant['money']."\n";
			$msg .= "提现订单：".$merchant['orderno']."\n";
			$msg .= "提现账户：".$merchant['account']."\n";
			$msg .= "申请时间：".date("Y-m-d H:i:s",$merchant['createtime'])."\n\n请及时处理！";
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => $first,
					"color" => "#4a5077"
				) ,
				"keyword1" => array(
					'value' => $merchant['name'],
					"color" => "#4a5077"
				) ,
				"keyword2" => array(
					'value' => "￥".$merchant['money'],
					"color" => "#4a5077"
				) ,
				"keyword3" => array(
					"value" => $merchant['orderno'],
					"color" => "#4a5077"
				) ,
				"keyword4" => array(
					"value" => $merchant['account'],
					"color" => "#4a5077"
				) ,
				"keyword5" => array(
					"value" => date("Y-m-d H:i:s",$merchant['createtime']),
					"color" => "#4a5077"
				) ,
				"remark" => array(
					"value" => $remark,
					"color" => "#4a5077"
				) ,
			);
			sendtplnotice($openid, $m_cash, $postdata, $url);
		}
	}
	
	static function send_msg($openid, $activity = array(), $params = array(), $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_status = $_W['_config']['m_status'];
		$merchant['name'] = $_W['_config']['sname'];
		//读取活动地址
		if ($activity['hasonline']){//线上
			$address = '线上活动';
		}elseif ($activity['hasstore']){//内部设置
			$address = $activity['address'];
		}else{//门店
			if (!empty($activity['storeids'])){
				$stores = model_activity::getNumActivityStore($activity['storeids']);
				foreach ($stores as $key => $row) {
					$address = $row['address'];
					break;
				}
			}else{
				$merchant  = model_activity::getActivityMerchant($activity['merchantid']);//读取主办方
				$address  = $merchant['address'];
			}
		}
		switch($params['status']){
			case 1:
				$first  = $merchant['name']."：发布了新活动，快去报名吧！";
				$remark = "点击“详情”查看最新活动信息";
				$date   = date('Y年m月d H:i',strtotime($activity['starttime']))."\n结束时间：".date('Y年m月d H:i',strtotime($activity['endtime']));
				break;
			case 2:
				$first  = "您参加的活动即将开始，请留意查看！";
				$remark = "点击“详情”查看您参加的活动信息";
				$date   = date('Y年m月d H:i',strtotime($activity['starttime']))."\n结束时间：".date('Y年m月d H:i',strtotime($activity['endtime']));
				break;
			case 3:
				$first  = "您参加的活动圆满结束了";
				$remark = "【".$merchant['name']."】在此感谢所有用户的热情参与！";
				$date   = date('Y年m月d H:i',strtotime($activity['starttime']))."\n结束时间：".date('Y年m月d H:i',strtotime($activity['endtime']));
				break;
			default:;
		}
		$first  = empty($params['first']) ? $first : $params['first'];
		$remark = empty($params['remark']) ? $remark : $params['remark'];
		if (empty($m_status)){
			$msg = "【活动状态】".$first.".\n\n活动名称：".$activity['title'].".\n活动时间：".date('Y年m月d H:i:s',strtotime($activity['starttime']))."\n\n".$remark;
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => $first,
				) ,
				"keynote1" => array(
					'value' => $activity['title'],
				) ,
				"keynote2" => array(
					'value' => $date,
				) ,
				"remark" => array(
					"value" => "活动地点：".$address."\n\n".$remark,
				) ,
			);
			sendtplnotice($openid, $m_status, $postdata, $url);
		}
	}
	
	static function mcert_review($openid, $params = array(), $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_mcert = $_W['_config']['m_mcert'];
		
		$first  = '尊敬的：'.$params['mname']."，您申请的主办方实名认证处理如下.";
		$remark = $params['status']==1 ? '认证资料已审核通过，请点击查看详情！' : '认证资料不符合要求，请仔细阅读认证相关说明！';
		$remark = empty($params['remark']) ? $remark : $params['remark'];
				
		if (empty($m_mcert)){
			$msg = $first . '\n认证类型：'.($params['type']==1 ? '个人认证' : '企业认证').'\n认证状态：'.($params['status']==1 ? '通过' : '被驳回').'\n审核时间：'.date('Y年m月d H:i:s', $params['createtime'])."\n\n".$remark;
			sendCustomNotice($openid,$msg,$url,'');
		}else{
			$postdata = array(
				"first" => array(
					"value" => $first."\n",
				) ,
				"keyword1" => array(
					'value' => $params['type']==1 ? '个人认证' : '企业认证',
				) ,
				"keyword2" => array(
					'value' => $params['name'],
				) ,
				"keyword3" => array(
					'value' => $params['status']==1 ? '通过' : '被驳回',
					"color" => $params['status']==1 ? "#008000" : "#ff0000"
				) ,
				"keyword4" => array(
					'value' => date('Y年m月d H:i', $params['createtime']),
				) ,
				"remark" => array(
					"value" => "\n\n".$remark,
				) ,
			);
			sendtplnotice($openid, $m_mcert, $postdata, $url);
		}
	}

	static function refund($openid, $order = array(), $url) {
	    global $_W;
		if ($_W['_config']['mmsg']=='0') return false;
	    $m_refund = $_W['_config']['m_refund'];
		
		$first  = $order['status']==6 ? "亲，您的订单已经申请退款." : "亲，订单已经完成退款，请注意查收.";
		$remark = '';
				
		if (!empty($m_refund)){
			$postdata = array(
				"first" => array(
					"value" => $first."\n",
				) ,
				"keyword1" => array(
					'value' => $order['orderno'],
				) ,
				"keyword2" => array(
					'value' => "￥ " . $order['price'],
				) ,
				"keyword3" => array(
					'value' => '微信零钱',
				) ,
				"keyword4" => array(
					'value' => $order['status']==6 ? '待处理，2-5个工作日' : '2-5个工作日',
					"color" => $order['status']==6 ? "#ff0000" : "#008000"
				) ,
				"remark" => array(
					"value" => $remark,
				) ,
			);
			sendtplnotice($openid, $m_refund, $postdata, $url);
		}
	}
}
?>
