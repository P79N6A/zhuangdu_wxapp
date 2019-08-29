<?php
defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC; 
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$merchantid = MERCHANTID ? MERCHANTID : $_GPC['merchantid'];
if (empty($_GPC['op']) && pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE uniacid = '{$_W['uniacid']}'") == 0) { 	 	
	$op = 'post';
}
//读取主办方操作权限
if (MERCHANTID) {
	fx_load() -> model('permissions');
	$_W['allow'] = allow_params();
	$_W['_config']['proreview'] = $_W['allow']['activity']['proreview']?1:$_W['_config']['proreview'];
	$merchant = model_merchant::getSingleMerchant(MERCHANTID, 'id,name');
}else{
	$merchantsData = model_merchant::getNumMerchant(0,0,0,0);
	$merchants     = $merchantsData[0];
}
if ($op == 'display') {
	if(checksubmit()){//活动排序
		$displayorder=$_GPC['displayorder'];
		$ids = $_GPC['ids'];
		for($i=0;$i<count($ids);$i++){
			pdo_update("fx_activity",array('displayorder'=>$displayorder[$i]),array('id'=>$ids[$i]));
		}
		message('活动排序保存成功', web_url('activity/activity/display',array('status'=>1)), 'success');
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = " uniacid = '{$_W['uniacid']}'";
	if (!empty($_GPC['keyword'])) {
		if(!empty($_GPC['keywordtype'])){
			switch($_GPC['keywordtype']){
				case 1: $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";break;
				case 2: $condition .= " AND id='{$_GPC['keyword']}'";break;
				case 3: $m = pdo_fetch("select id from".tablename('fx_merchant')."where INSTR(`name`, '{$_GPC['keyword']}') and uniacid={$_W['uniacid']}");
						if($m['id']) {
							$condition .= " AND merchantid='{$m['id']}'";
						}elseif(strpos($_W['_config']['sname'], $_GPC['keyword']) !== false) {
							$condition .= " AND merchantid=0";
						}else {
							$condition .= " AND merchantid=-1";
						}
						break;
				default:break;
			}
		}else{
			$condition .= " AND INSTR(`title`, '{$_GPC['keyword']}')";
		}
	}

	if ($merchantid > -1) {//商家筛选
		$condition .= " AND merchantid=$merchantid";
	}
	$totals = array();
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition AND UNIX_TIMESTAMP()<UNIX_TIMESTAMP(endtime)");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition AND UNIX_TIMESTAMP()>UNIX_TIMESTAMP(endtime)");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition AND review=0");
	$totals[] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition AND review=2");
	$status = intval($_GPC['status']);
	if ($status) {
		switch($status){
		case 1:$condition .= " AND UNIX_TIMESTAMP()<UNIX_TIMESTAMP(endtime)";break;
		case 2:$condition .= " AND UNIX_TIMESTAMP()>UNIX_TIMESTAMP(endtime)";break;
		case 3:$condition .= " AND review=0";$total_output = $totals[4];break;
		case 4:$condition .= " AND review=2";$total_output = $totals[5];break;
		default:;
		}
	}
	$activity = pdo_fetchall ("SELECT * FROM " . tablename ('fx_activity') . " WHERE $condition ORDER BY displayorder DESC,review=1 DESC,review=0 DESC,joinetime DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	//读取规格名额
	foreach ($activity as &$s) {
		if($s['hasoption']==1){
			//读取规格总名额，总虚拟人数
			$opt['gnum'] = pdo_fetchcolumn("SELECT SUM(gnum) FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$s['id']);
			$opt['nolimit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fx_spec_option') . " WHERE gnum = 0 and activityid = ".$s['id']);
			$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = ".$s['id']);
			if ($opt['nolimit']){
				$s['gnum'] = 0;
			}else{
				$s['gnum'] = $opt['gnum'];
			}
		}
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition");
	//记录总数
	$pager = pagination($total, $pindex, $psize);
	include fx_template ('activity');
	exit;
}

if ($op == 'post') {
	fx_load() -> model('member');
	$activityid = intval($_GPC['activityid']);
	$mcgroups   = mc_groups();
	$category   = model_category::getNumCategory();
	
	if (!empty($activityid)) {
		$activity = model_activity::getSingleActivity($activityid, '*');
		if (empty($activity)) message ('抱歉，主题不存在或是已经删除！', '', 'error');
		
		$form = unserialize($activity['form']);
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
		if (!empty($activity)){
			$activityForm = model_activity::getNumActivityForm($activityid);//活动表单
			$activitySpec = model_activity::getNumActivitySpec($activityid);//活动规格
			$marketing    = model_activity::getMarketing($activityid);//优惠
			$stores       = model_activity::getNumActivityStore($activity['storeids']);//店铺
		} 
	}
	
	if (checksubmit('submit')) {
		$discount = $_GPC['marketing']; //折扣
		$data = $_GPC['activity'];
		$data['storeids']  = serialize($_GPC['storeids']);//核销门店ID
		$data['hasoption'] = empty($data['hasoption'])?0:$data['hasoption'];//开启规格
		$data['hasstore']  = empty($data['hasstore'])?0:$data['hasstore'];//自定义地址
		$data['hasonline'] = empty($data['hasonline'])?0:$data['hasonline'];//线上活动
		$data['detail']    = htmlspecialchars_decode($data['detail']);
		$data['form']      = serialize($data['form']);
		$data['openids']   = serialize($data['openids']);
		$data['tmplmsg']   = serialize($data['tmplmsg']);
		$data['kefu']      = serialize($data['kefu']);
		$data['switch']    = serialize($data['switch']);
		$data['info']      = html_format($data['info'], array('img'=>0));
		
		//虚拟信息
		$data['falsedata']['name'] = str_replace(',','，',$data['falsedata']['name']);
		$data['falsedata']['head'] = $_GPC['head'];
		$data['falsedata'] = serialize($data['falsedata']);
		
		//协议字数处理
		$data['agreement'] = html_format($data['agreement']);
		$bytes = strlen(html_format($data['agreement']))-65000;
		if ($bytes > 0){
			message('协议内容已超出存储范围：约超' . round($bytes/3) . "个汉字");
		}
		
		//用来判断是否收费活动
		$aprice = $data['aprice'];
		if (!empty($data['hasoption'])){
			$option_idss = $_GPC['option_ids'];
			for ($k = 0; $k < count($option_idss); $k++) {
				$ids = $option_idss[$k];
				if ($_GPC['option_aprice_' . $ids][0] > 0){
					$aprice =  $_GPC['option_aprice_' . $ids][0];
					break;
				}
			}
		}
		
		//其它设置项
		$prize = $_GPC['prize'];
		if ($_W['plugin']['card']['config']['card_enable']){
			if ($prize['cardper']['enable']){
				$prize['cardper']['percent'] = !empty($prize['cardper']['percent'])>0 ? $prize['cardper']['percent'] : (empty($_W['plugin']['card']['config']['percent']) ? '8.8' : $_W['plugin']['card']['config']['percent']);
			}else{
				$prize['cardper']['percent']='';
			}
		}
		$otherdata = array (
			'uniacid' 	 => $_W['uniacid'],
			'starttime'  => $_GPC['activityTime']['start'],
			'endtime' 	 => $_GPC['activityTime']['end'],
			'joinstime'  => $_GPC['joinTime']['start'],
			'joinetime'  => $_GPC['joinTime']['end'],
			'atlas' 	 => serialize($_GPC['atlas']),
			'prize' 	 => serialize($prize),
			'review'     => (!MERCHANTID || $_W['_config']['proreview']==1 || ($_W['_config']['proreview']==2 && $aprice==0)) ? 1 : 0
		);
		//读取位置
		if (!empty($_GPC['storeids']) && !$data['hasstore']){//判断活动门店
			$stores = model_activity::getNumActivityStore($_GPC['storeids']);
			$data['tel']     = $stores[0]['tel'];
			$data['lng']     = $stores[0]['lng'];
			$data['lat']     = $stores[0]['lat'];
			$data['adinfo']  = $stores[0]['adinfo'];
			$data['address'] = $stores[0]['address'];
			$data['addname'] = $stores[0]['storename'];
		}elseif (!$data['hasstore']){//无设置默认商家
			$merchant  = model_activity::getActivityMerchant($data['merchantid']);//读取主办方
			$data['tel']     = $merchant['tel'];
			$data['lng']     = $merchant['lng'];
			$data['lat']     = $merchant['lat'];
			$data['adinfo']  = $merchant['adinfo'];
			$data['address'] = $merchant['address'];
			$data['addname'] = $merchant['storename'];
		}
		
		$data = array_merge($data,$otherdata);
		if (!empty($activityid)) {
			pdo_update ('fx_activity', $data, array ('id' => $activityid));
			//if ($data['thumb']!=$_GPC['thumb_old']){
				//load()->func('file');
				//file_delete($_GPC['thumb_old']);
			//}
		} else {
			pdo_insert ('fx_activity', $data);
			$activityid = pdo_insertid();
		}
		model_activity::UpdateForm($activityid,$_GPC);//更新表单
		model_activity::UpdateSpec($activityid,$_GPC);//更新规格
		$discount = $_GPC['discount']; //折扣
		$enough = $_GPC['enough']; //满减
		$deduction = $_GPC['deduction'];//抵扣
		$mcgroup = $_GPC['mcgroup']; //会员优惠
		model_activity::updateMarketing($discount,$enough,$deduction,$mcgroup,$activityid);//更新优惠
		message ('更新成功！', web_url('activity/activity/display'), 'success');
	}
	include fx_template ('activity');
	exit;
}

if ($op == 'delete') {
	$activityid = intval($_GPC['activityid']);
	$activity = model_activity::getSingleActivity($activityid, '*');
	if (empty($activity)) {
		message ('抱歉，主题不存在或是已经被删除！');
	}
	$activityForm = model_activity::getNumActivityForm($activityid);//活动表单
	$activitySpec = model_activity::getNumActivitySpec($activityid);//活动规格
	foreach ($activityForm[0] as $form) {
		pdo_delete ('fx_form_item', array ('formid' => $form['id']));
	}
	pdo_delete ('fx_form', array ('activityid' => $activityid));
	foreach ($activitySpec[0] as $spec) {
		pdo_delete ('fx_spec_item', array ('specid' => $spec['id']));
	}
	pdo_delete ('fx_spec_option', array ('activityid' => $activityid));
	pdo_delete ('fx_spec', array ('activityid' => $activityid));
	load()->func('file');
	//file_delete($activity['thumb']);
	$qrcode = IA_ROOT . '/addons/'. MODULE_NAME .'/data/qrcode/' . $_W['uniacid'] . '/merchant/' . 'ver_qrcode_' . $activity['midkey'] . '_' . $activityid . '.png';
	file_delete($qrcode);
	pdo_delete ('fx_activity', array ('id' => $activityid));
	die(json_encode(array("errno" => 0,'message'=>'删除成功')));
	exit;
}

if ($op == 'show' && $_W['isajax']) {
	$id = $_GPC['id'];
	$show = $_GPC['show'];
	$result = pdo_update ('fx_activity', array('show' => $show), array ('id' => $id));
	die(json_encode(array('message' => $result ? '设置成功' : '设置失败')));
}

if ($op == 'recommend' && $_W['isajax']) {
	$id = $_GPC['id'];
	$data = $_GPC['data'];
	$result = pdo_update ('fx_activity', array('recommend' => $data), array ('id' => $id));
	die(json_encode(array('result'=>$result,'data' => $data?0:1)));
}

if ($op == 'falsemember') {
	$con     = "A.uniacid='{$_W['uniacid']}' and A.uid = B.uid ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		//$con .= " and (A.nickname LIKE '%{$keyword}%' or B.openid LIKE '%{$keyword}%') ";
	}
	$total =pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('mc_members')." WHERE uniacid = '{$_W['uniacid']}' ");
	$randnum = rand(0,$total-$keyword);
	//$sql="select * from ".$BIAOTOU."slides where hide=0 order by wz asc limit ".$randnum .",4";
	echo $count;
	$field  = "A.nickname, avatar, B.openid";
	$inner  = tablename ('mc_members') . "A , " . tablename ('mc_mapping_fans') . "B ";
	$ds = pdo_fetchall("select ".$field." from" . $inner . "where $con limit ".$randnum .",$$keyword");
	include fx_template('activity/false_member');
	exit;
}


if ($op == 'copy' && $_W['isajax']) {
	$id = $_GPC['id'];
	$field = "uid,uniacid,concat('请编辑！',title),pagetitle,aprice,sharetitle,sharepic,sharedesc,tel,intro,detail,starttime,endtime,joinstime,joinetime,thumb,atlas,gnum,lng,lat,adinfo,addname,address,prize,form,displayorder,limitnum,hasoption,0,smsnotify,smsswitch,parentid,childid,recommend,viewauth,review,openids,tmplmsg,merchantid,storeids,hasstore,atype,agreement,info,falsedata,hasonline,unitstr,switch";
	$fieldto = "uid,uniacid,title,pagetitle,aprice,sharetitle,sharepic,sharedesc,tel,intro,detail,starttime,endtime,joinstime,joinetime,thumb,atlas,gnum,lng,lat,adinfo,addname,address,prize,form,displayorder,limitnum,hasoption,`show`,smsnotify,smsswitch,parentid,childid,recommend,viewauth,review,openids,tmplmsg,merchantid,storeids,hasstore,atype,agreement,info,falsedata,hasonline,unitstr,switch";
	$result = pdo_query("insert into " . tablename('fx_activity') . "($fieldto) select $field from " . tablename ('fx_activity') . " where id= $id;");
	$insertid = pdo_insertid();
	if ($insertid){
		$activityForm = model_activity::getNumActivityForm($id);//活动表单
		$activitySpec = model_activity::getNumActivitySpec($id);//活动规格
		foreach ($activityForm[0] as $form) {//复制自定义表单
			pdo_query("insert into " . tablename('fx_form') . "(uniacid,title,displaytype,content,activityid,displayorder,essential,fieldstype) select uniacid,title,displaytype,content,$insertid,displayorder,essential,fieldstype from " . tablename ('fx_form') . " where id = ".$form['id'].";");
			$tihsid = pdo_insertid();
			pdo_query("insert into " . tablename('fx_form_item') . "(uniacid,formid,title,`show`,displayorder) select uniacid,$tihsid,title,`show`,displayorder from " . tablename ('fx_form_item') . " where formid= ".$form['id'].";");
		}
		
		foreach ($activitySpec[0] as $spec) {//复制规格
			pdo_query("insert into " . tablename('fx_spec') . "(uniacid,title,content,activityid,displayorder,essential) select uniacid,title,content,$insertid,displayorder,essential from " . tablename ('fx_spec') . " where id = ".$spec['id'].";");
			$tihsid = pdo_insertid();
			pdo_query("insert into " . tablename('fx_spec_item') . "(uniacid,specid,title,`show`,displayorder) select uniacid,$tihsid,title,`show`,displayorder from " . tablename ('fx_spec_item') . " where specid= ".$spec['id'].";");
		}
	}
	die(json_encode(array('message' => $result ? '复制成功,正在刷新当前页面' : '复制失败')));
}

if ($op == 'review' && $_W['isajax']) {
	$id = $_GPC['id'];
	$review = $_GPC['review'];
	if (IMS_VERSION=='0.8'){//兼容1.0,mc_uid2openid()
		fx_load()->model('mc');
	}else{
		load()->model('mc');
	}
	$url = app_url('activity/detail/display',array('activityid'=>$id));
	if (is_array($id)){
		foreach ($id as $k => $aid) {
			$aid = intval($aid);
			if ($aid == 0)
			continue;			
			$result = pdo_update ('fx_activity', array('review' => $review), array ('id' => $aid));
			if ($result){// 审核通知
				$activity = model_activity::getSingleActivity($aid, '*');
				$merchant = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
				$openids  = unserialize($merchant['messageopenid']);
				$openids  = !empty($openids) ? $openids : $_W['_config']['openids'];
				if (!empty($openids)){
					foreach($openids as $key=> $value){
						message::activity_review($value, $activity, $review, $url);
					}
				}
			}
		}
		die(json_encode(array("errno" => 0,'message' => $review==1?'审核成功！':"取消审核成功！")));
	}else{
		$result = pdo_update ('fx_activity', array('review' => $review), array ('id' => $id));
		if ($result){// 审核通知
			$activity = model_activity::getSingleActivity($id, '*');
			$merchant = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
			$openids  = unserialize($merchant['messageopenid']);
			$openids  = !empty($openids) ? $openids : $_W['_config']['openids'];
			if (!empty($openids)){
				foreach($openids as $key=> $value){
					message::activity_review($value, $activity, $review, $url);
				}
			}
		}
		die(json_encode(array("errno" => $result ? 0 : 1,'message' => $review==1?'审核成功！':"取消审核成功！")));
	}
	exit;
}

if ($op == 'sendmsg') {
	set_time_limit(0);
	$uniacid = $_W['uniacid'];
	$activityid  = intval($_GPC['activityid']);
	$messge_type = intval($_GPC['messge_type']);
	$fans_group  = intval($_GPC['fans_group']);
	$messge_url  = trim($_GPC['messge_url']);
	
	$params = array(
		"status" => intval($_GPC['status']),
		"first"  => $_GPC['messge_title'],
		"remark" => $_GPC['messge_remark']
	);
	$activity = model_activity::getSingleActivity($activityid, '*');
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 1;//每页条数
	switch($fans_group){
		case 1:
			$fans = pdo_fetchall ("SELECT uid,openid FROM " . tablename ('mc_mapping_fans') . " WHERE uniacid=$uniacid and follow=1 LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid=$uniacid and follow=1");
			break;
		case 2:
			$fans = pdo_fetchall ("SELECT uid,openid FROM " . tablename ('fx_merchant_fans') . " WHERE uniacid=$uniacid and follow=1 and uid<>0 and merchantid=" . $activity['merchantid'] . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_merchant_fans') . " WHERE uniacid=$uniacid and follow=1 and uid<>0 and merchantid=" . $activity['merchantid']);
			break;
		case 3:
			$fans = pdo_fetchall ("SELECT uid,openid FROM " . tablename ('fx_activity_records') . " WHERE uniacid=$uniacid and status in (1,2,3) and activityid=" . $activityid . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE  uniacid=$uniacid and status in (1,2,3) and activityid=" . $activityid);
			break;
		default:;
	}
	$tpage  = (empty($psize) || $psize < 0) ? 1 : ceil($total / $psize);
	
	
	fx_load()->model('mc');
	if ($messge_type==1){
		$url = empty($messge_url) ? app_url('activity/detail/display',array('activityid'=>$activityid)) : $messge_url;
		foreach($fans as $key=> $v){
			if (mc_fans_follow($v['uid']) && $v['openid']!='')
			message::send_msg($v['openid'], $activity, $params, $url);
		}
	}else{
		fx_load()->model('member');
		$merchant['name'] = $_W['_config']['sname'];
		$address = '';
		if (!$activity['hasstore']){//判断活动门店
			if (!empty($activity['storeids'])){
				$stores = model_activity::getNumActivityStore($activity['storeids']);
			}else{
				if ($activity['merchantid']) {
					$merchant = model_merchant::getSingleMerchant($activity['merchantid'], '*');//读取主办方
					$address  = $merchant['address'];
				}else{
					$address  = $_W['_config']['address'];
				}
			}
			if (!empty($stores)){
				foreach ($stores as $key => $row) {
					$address .= $row['address']."\n";
				}	
			}
		}else{
			$address = $activity['address'];
		}
		switch($params['status']){
			case 1:
				$first  = "(".$merchant['name'].")发布了：".$activity['title'];
				$remark = "机会不容错过";
				$urlstr = "(戳此查看：t.cn/RlhV8dD)";
				break;
			case 2:
				$first  = "您参加的 ".$activity['title']." 即将开始";
				$remark = "活动信息详情";
				$urlstr = "(戳此查看：t.cn/RlhV8dD)";
				break;
			case 3:
				$first  = "您参加的 ".$activity['title']." 圆满结束了";
				$remark = "(".$merchant['name'].")在此感谢所有用户的热情参与！活动信息详情";
				$urlstr = "(戳此查看：t.cn/RlhV8dD)";
				break;
			default:;
		}
		$date   = date('Y年m月d H:i',strtotime($activity['starttime']))." 至 ".date('Y年m月d H:i',strtotime($activity['endtime']));
		$first  = empty($params['first']) ? $first : $params['first'];
		$remark = empty($params['remark']) ? $remark : $params['remark'];
		foreach($fans as $key=> $v){
			$member = getMember($v['openid']);
			$smsparams=array(
				'item'    => $first,
				'timestr' => ",".$date.$remark,
				'webstr'  => $webstr
			);
			sendSMS($member['mobile'],$smsparams,"SMS_60390004");
		}
	}
	die(json_encode(array("errno" => 0, 'message' => '发送完毕', 'total' => $total, 'tpage' => $tpage)));

	exit;
}