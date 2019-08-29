<?php 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2050.
// | Describe: project
// | 移动端活动发布
// +----------------------------------------------------------------------
defined('IN_IA') or exit('Access Denied');
if (empty($_W['fans']['nickname'])){
	$_W['fans'] = getInfo();
}
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$activityid = intval($_GPC['activityid']);
$pagetitle = '发起活动 - ' . $_W['_config']['sname'];
$redirect = 'javascript:wx.closeWindow();';
if (!ADMIN && !$_W['allow']['activity']['post'] && !$_W['_config']['proswitch']) fx_message ('无权限访问', $redirect, 'warning','点击确定返回微信');
if($op == 'display'){
	$pagetitle = '我的活动';
	include $this->template ('member/project_list');
	exit;
}

if ($op == 'post') {
	if (empty($_W['member']['mobile']) && $_W['_config']['bond']){
		header("Location:".app_url('member/bond/mobile',array('actype'=>'project')));
		exit;
	}
	if (MERCHANTID || ADMIN) $merchant = model_merchant::getSingleMerchant(MERCHANTID, '*');
	if (empty($merchant['logo']) || empty($merchant['name']) || empty($merchant['linkman_mobile']) || empty($merchant['linkman_name']) || empty($merchant['detail'])){
		fx_message ('亲！您还没有完善信息哦&#9786', app_url('member/merch/display',array('opp'=>'data')), 'warning', '点击确定去完善&#9997');
	}
	//读取分类
	$category = Util::getNumData('name as text,id as value','fx_category',array('parentid'=>0,'enabled'=>1,'redirect'=>''),'displayorder DESC, id DESC',0,0,0);
	foreach($category[0] as $key=> &$s){
		$children = pdo_fetchall("SELECT name as text,id as value FROM " . tablename('fx_category') . " WHERE uniacid = {$_W['uniacid']} and parentid={$s['value']} and enabled=1 ORDER BY displayorder DESC");
		$s['children'] = $children;
	}
	fx_load()->func('attachment');
	if (!empty($activityid)) {
		$activity = model_activity::getSingleActivity($activityid, '*');
		$form = unserialize($activity['form']);
		if (empty($activity)) fx_message ('抱歉，主题不存在或是已经删除！', '', 'error');
		
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
		} 
	}
	if($activity['hasoption']){
		//处理常规价格范围
		$activity['minprice'] = pdo_fetch("SELECT min(aprice) as aprice, min(marketprice) as marketprice FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$activityid);
		$activity['maxprice'] = pdo_fetch("SELECT max(aprice) as aprice, max(marketprice) as marketprice FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$activityid);
	}
	if ($_W['ispost']) {
		$data = $_GPC['activity'];
		if (empty($data['title'])) {
			message ('请输入活动主题名称');
		}
		//$data['hasoption'] = empty($data['hasoption'])?0:$data['hasoption'];
		$data['detail'] = htmlspecialchars_decode($data['detail']);
		$data['form'] = serialize($data['form']);
		$data['hasoption'] = empty($data['hasoption'])?0:$data['hasoption'];//开启规格
		//$data['falsename'] = str_replace(',','，',$data['falsename']);
		$aprice = $data['aprice'];//用来判断是否收费活动
		$otherdata = array (
			'uid' 	     => $_W['member']['uid'],
			'uniacid' 	 => $_W['uniacid'],
			'atlas' 	 => serialize($data['atlas']),
			'merchantid' => MERCHANTID,
			'hasstore'   => 1,
			'viewauth'   => 0,
			'review'     => $_W['_config']['proreview']==1 || ($_W['_config']['proreview']==2 && $aprice==0) ? 1 : 0
		);
		$data = array_merge($data,$otherdata);
		if (!empty($activityid)) {
			pdo_update ('fx_activity', $data, array (
					'id' => $activityid 
			));
		} else {
			pdo_insert ('fx_activity', $data);
			$activityid = pdo_insertid();
		}
		model_activity::UpdateForm($activityid,$_GPC);//更新表单
		if (!$_GPC['isSpecs']){
			model_activity::UpdateSpec($activityid,$_GPC);//更新规格
		}
		//$url = app_url('records/records/list'); //活动发起模板通知
		//message::join_success($_W['openid'], $activity, $recordid, $url);
		fx_message ('发布成功！', app_url ('member/project/display'), 'success','点击查看发起的活动');
	}
	include $this->template ('member/project');
}
//活动操作
if ($op == 'operation') {
	if ($_W['isajax']) {
		$id = $_GPC['id'];
		if ($_GPC['type'] == 'setShow'){//设置活动上下线
			$show = $_GPC['show'];
			$result = pdo_update ('fx_activity', array('show' => $show), array ('id' => $id));
			die(json_encode(array('message' => $result ? '设置成功' : '设置失败')));
		}elseif($_GPC['type'] == 'copy') {//复制一个新的活动
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
			die(json_encode(array('message' => $result ? '操作成功,正在为您返回活动列表！' : '操作失败！')));
		}
	}else{
		$activity  = model_activity::getSingleActivity($activityid, '*');
		$pagetitle = $activity['title'];
		
		$favonum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_favorite') . " WHERE $uniacid and favo=1 and activityid = $activityid");
		$joinnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity_records') . " WHERE $uniacid and activityid=$activityid and status<>5");	
	}
	include $this->template ('member/project_operation');
}
//读取活动列表
if($op =='ajax'){
	$pindex = max(1, intval($_GPC['page']));		
	//当前页码
	$psize = max(1, intval($_GPC['psize']));
	$condition = " uniacid = '{$_W['uniacid']}'";
	$condition.= " and merchantid=" . MERCHANTID;
	$field = "*";
	$activity = pdo_fetchall ("SELECT $field FROM " . tablename ('fx_activity') . " WHERE $condition ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE $condition");
	$merchant = model_merchant::getSingleMerchant(MERCHANTID, '*');
	foreach ($activity as &$s) {
		$s['switch'] = unserialize($s['switch']);
		$s['falsedata'] = unserialize($s['falsedata']);
		$s['falsedata']['num'] = intval($s['falsedata']['num']);
		$s['falsedata']['read'] = intval($s['falsedata']['read']);
		$s['falsedata']['share'] = intval($s['falsedata']['share']);
		$s['minaprice'] = 0;
		$condition = "activityid = {$s['id']} and status <> 5 and (status <> 0 or paytype = 'delivery')";
		$s['joinnum'] = pdo_fetchcolumn("SELECT SUM(buynum) FROM " . tablename('fx_activity_records') . " WHERE $condition");
		//读取规格名额
		if($s['hasoption']==1){
			$aprice['max'] = pdo_fetchcolumn("SELECT max(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$s['id']);
			$aprice['min'] = pdo_fetchcolumn("SELECT min(aprice) FROM " . tablename('fx_spec_option') . " WHERE aprice > 0 and activityid = " .$s['id']);
			$s['minaprice'] = !empty($aprice['min']) && $aprice['max'] > $aprice['min']?$aprice['min']:0;
			//读取规格总名额，总虚拟人数
			$opt['gnum'] = pdo_fetchcolumn("SELECT SUM(gnum) FROM " . tablename('fx_spec_option') . " WHERE activityid = " .$s['id']);
			$opt['nolimit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fx_spec_option') . " WHERE gnum = 0 and activityid = ".$s['id']);
			$opt['falsenum'] = pdo_fetchcolumn("SELECT SUM(falsenum) FROM " . tablename('fx_spec_option') . " WHERE activityid = ".$s['id']);
			if ($opt['nolimit']){
				$s['gnum'] = 0;
			}else{
				$s['gnum'] = $opt['gnum'];
			}
			$s['falsedata']['num'] = $opt['falsenum'] ? $opt['falsenum'] : 0;
		}
		$s['joinnum'] = !empty($s['joinnum'])?$s['joinnum']+$s['falsedata']['num']:0+$s['falsedata']['num'];
		//读取商户信息
		$s['merchant']  = $merchant;
		$s['merchant']['logo'] = tomedia($merchant['logo']);
		if ($s['hasstore']){//判断位置是否活动中定义
			$s['merchant']['storename'] = $s['addname'];
			$s['merchant']['address'] = $s['address'];
			$s['merchant']['lng'] = $s['lng'];
			$s['merchant']['lat'] = $s['lat'];
		}elseif (is_array(unserialize($s['storeids']))){//判断活动门店
				$stores = model_activity::getNumActivityStore(unserialize($s['storeids']));
				$s['merchant']['storename'] = $stores[0]['storename'];
		}
	}	
	//array_merge数组拼接
	$data['list'] = $activity;
	$data['total'] = $total;
	$data['tpage']=(empty($psize) || $psize < 0) ? 1 : ceil($total / $psize);
	die(json_encode($data));
	exit;
}