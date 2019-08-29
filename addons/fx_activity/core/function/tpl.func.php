<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 */
defined('IN_IA') or exit('Access Denied');
function _calc_current_frames2(&$frames) {
	global $_W,$_GPC;
	if(!empty($frames) && is_array($frames)) {
		foreach($frames as &$frame) {
			foreach($frame['items'] as &$fr) {
				if(count($fr['actions']) == 2){
					if($fr['actions']['1'] == $_GPC[$fr['actions']['0']]){
						$fr['active'] = 'active';
					}
				}elseif(count($fr['actions']) == 4){
					if($fr['actions']['1'] == $_GPC[$fr['actions']['0']] && $fr['actions']['3'] == $_GPC[$fr['actions']['2']]){
						$fr['active'] = 'active';
					}
				}else{
					$query = parse_url($fr['url'], PHP_URL_QUERY);
					parse_str($query, $urls);
					if(defined('ACTIVE_FRAME_URL')) {
						$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
						parse_str($query, $get);						
					} else {
						$get = $_GET;
					}
					if(!empty($_GPC['a'])) {
						$get['a'] = $_GPC['a'];
					}
					if(!empty($_GPC['c'])) {
						$get['c'] = $_GPC['c'];
					}
					if(!empty($_GPC['do'])) {
						$get['do'] = $_GPC['do'];
					}
					if(!empty($_GPC['ac'])) {
						$get['ac'] = $_GPC['ac'];
					}
					if(!empty($_GPC['status'])) {
						$get['status'] = $_GPC['status'];
					}
					if(!empty($_GPC['recordsType'])) {
						$get['recordsType'] = $_GPC['recordsType'];
					}
					if(!empty($_GPC['g_type'])) {
						$get['g_type'] = $_GPC['g_type'];
					}
					if(!empty($_GPC['is_hexiao'])) {
						$get['is_hexiao'] = $_GPC['is_hexiao'];
					}
					if(!empty($_GPC['op'])) {
						$get['op'] = $_GPC['op'];
					}
					if(!empty($_GPC['m'])) {
						$get['m'] = $_GPC['m'];
					}
					$diff = array_diff_assoc($urls, $get);
					if(empty($diff)) {
						$fr['active'] = 'active';
					}else{
						$fr['active'] = '';
					}
				}
			}
		}
	}
}

function getapplicationFrames(){
	global $_W,$_GPC;
	$frames = array();
	if(!FX_ID || ($_GPC['ac'] != 'merchant' && FX_ID)){
		$frames['application']['title'] = '<i class="fa fa-cubes"></i>&nbsp;&nbsp; 管理应用';
		$frames['application']['items']['list']['url'] = web_url('application/plugins/display');
		$frames['application']['items']['list']['title'] = '&nbsp;&nbsp; 应用插件';
		$frames['application']['items']['list']['actions'] = array();
		$frames['application']['items']['list']['active'] = '';
		$frames['application']['items']['list']['append']['url'] = web_url('application/plugins/display');
		$frames['application']['items']['list']['append']['title'] = '<i class="fa fa-plus"></i>';
	}
	if(FX_ID){
		$frames['merchant']['title'] = '<i class="fa fa-gear"></i>&nbsp;&nbsp; 我的商铺';
		$frames['merchant']['items'] = array();
		$frames['merchant']['items']['setting']['url'] = web_url('application/merchant/setting');
		$frames['merchant']['items']['setting']['title'] = '&nbsp;&nbsp; 商铺设置';
		$frames['merchant']['items']['setting']['actions'] = array('op','setting');
		$frames['merchant']['items']['setting']['active'] = '';
		
		$frames['merchant']['items']['data_record']['url'] = web_url('application/merchant/data_record');
		$frames['merchant']['items']['data_record']['title'] = '&nbsp;&nbsp; 收入管理';
		$frames['merchant']['items']['data_record']['actions'] = array();
		$frames['merchant']['items']['data_record']['active'] = '';
	}
	if($_GPC['ac'] == 'plugins'){
		$frames['merchant']['title'] = '<i class="fa fa-gear"></i>&nbsp;&nbsp; 模块扩展';
		$frames['merchant']['items'] = array();
		if(!MERCHANTID){
		$frames['merchant']['items']['merchantlist']['url'] = web_url('application/merchant/display');
		$frames['merchant']['items']['merchantlist']['title'] = '&nbsp;&nbsp; 主办单位';
		$frames['merchant']['items']['merchantlist']['actions'] = array();
		$frames['merchant']['items']['merchantlist']['active'] = '';
		
		$frames['merchant']['items']['hexiao']['url'] = web_url('application/hexiao/entry');
		$frames['merchant']['items']['hexiao']['title'] = '&nbsp;&nbsp; 核销管理';
		$frames['merchant']['items']['hexiao']['actions'] = array();
		$frames['merchant']['items']['hexiao']['active'] = '';
		}else{
		$frames['merchant']['items']['hexiao']['url'] = web_url('application/hexiao/store');
		$frames['merchant']['items']['hexiao']['title'] = '&nbsp;&nbsp; 核销管理';
		$frames['merchant']['items']['hexiao']['actions'] = array();
		$frames['merchant']['items']['hexiao']['active'] = '';
		}
	}
	if($_GPC['ac'] == 'hexiao'){
		$frames['merchant']['title'] = '<i class="fa fa-gear"></i>&nbsp;&nbsp; 核销管理';
		$frames['merchant']['items'] = array();
		if(!MERCHANTID){
		$frames['merchant']['items']['entry']['url'] = web_url('application/hexiao/entry');
		$frames['merchant']['items']['entry']['title'] = '&nbsp;&nbsp; 核销设置';
		$frames['merchant']['items']['entry']['actions'] = array();
		$frames['merchant']['items']['entry']['active'] = '';
		}
		$frames['merchant']['items']['store']['url'] = web_url('application/hexiao/store');
		$frames['merchant']['items']['store']['title'] = '&nbsp;&nbsp; 场地设置';
		$frames['merchant']['items']['store']['actions'] = array();
		$frames['merchant']['items']['store']['active'] = '';
		
		$frames['merchant']['items']['saler']['url'] = web_url('application/hexiao/saler');
		$frames['merchant']['items']['saler']['title'] = '&nbsp;&nbsp; 核销员设置';
		$frames['merchant']['items']['saler']['actions'] = array();
		$frames['merchant']['items']['saler']['active'] = '';
	}
	if($_GPC['ac'] == 'merchant' && !MERCHANTID){
		$frames['merchant']['title'] = '<i class="fa fa-archive"></i>&nbsp;&nbsp; 主办单位';
		$frames['merchant']['items'] = array();
		$frames['merchant']['items']['merchantcenter']['url'] = web_url('application/merchant/display');
		$frames['merchant']['items']['merchantcenter']['title'] = '&nbsp;&nbsp; 主办方列表';
		$frames['merchant']['items']['merchantcenter']['actions'] = array();
		$frames['merchant']['items']['merchantcenter']['active'] = '';
		
		$frames['merchant']['items']['merchantcenteredit']['url'] = web_url('application/merchant/edit');
		$frames['merchant']['items']['merchantcenteredit']['title'] = '&nbsp;&nbsp; 添加主办方';
		$frames['merchant']['items']['merchantcenteredit']['actions'] = array('op','edit');
		$frames['merchant']['items']['merchantcenteredit']['active'] = '';
	}
	if($_GPC['ac'] == 'merchant'){
		$frames['merchantApply']['title'] = '<i class="fa fa-archive"></i>&nbsp;&nbsp; 提现管理';
		$frames['merchantApply']['items'] = array();
		$frames['merchantApply']['items']['cashConfirm']['url'] = web_url('application/merchant/merchantApply',array('status' => '1'));
		$frames['merchantApply']['items']['cashConfirm']['title'] = '&nbsp;&nbsp; 待确认';
		$frames['merchantApply']['items']['cashConfirm']['actions'] = array();
		$frames['merchantApply']['items']['cashConfirm']['active'] = '';
		
		$frames['merchantApply']['items']['cashPay']['url'] = web_url('application/merchant/merchantApply',array('status' => '2'));
		$frames['merchantApply']['items']['cashPay']['title'] = '&nbsp;&nbsp; 待打款';
		$frames['merchantApply']['items']['cashPay']['actions'] = array();
		$frames['merchantApply']['items']['cashPay']['active'] = '';
		
		$frames['merchantApply']['items']['cashFinish']['url'] = web_url('application/merchant/merchantApply',array('status' => '3'));
		$frames['merchantApply']['items']['cashFinish']['title'] = '&nbsp;&nbsp; 已打款';
		$frames['merchantApply']['items']['cashFinish']['actions'] = array();
		$frames['merchantApply']['items']['cashFinish']['active'] = '';
	}
	if($_GPC['ac'] == 'merchant' && !MERCHANTID){
		$frames['page']['title'] = '<i class="fa fa-inbox"></i>&nbsp;&nbsp; 入口设置';
		$frames['page']['items'] = array();		
		$frames['page']['items']['index']['url'] = web_url('application/merchant/cover');
		$frames['page']['items']['index']['title'] = '&nbsp;&nbsp; 主办方后台入口';
		$frames['page']['items']['index']['actions'] = array();
		$frames['page']['items']['index']['active'] = '';
	}
	return $frames;
}

function getactivityFrames(){
	global $_W,$_GPC;
	$frames = array();
	$frames['activity']['title'] = '<i class="fa fa-gift"></i>&nbsp;&nbsp; 活动管理';
	$frames['activity']['items'] = array();
	$frames['activity']['items']['display']['url'] = web_url('activity/activity/display');
	$frames['activity']['items']['display']['title'] = '&nbsp;&nbsp; 活动列表';
	$frames['activity']['items']['display']['actions'] = array();
	$frames['activity']['items']['display']['active'] = '';
	
	$frames['activity']['items']['post']['url'] = web_url('activity/activity/post');
	$frames['activity']['items']['post']['title'] = '&nbsp;&nbsp; 发布活动';
	$frames['activity']['items']['post']['actions'] = array();
	$frames['activity']['items']['post']['active'] = '';
	if(!FX_ID){
		$frames['other']['title'] = '<i class="fa fa-bookmark"></i>&nbsp;&nbsp; 其他管理';
		$frames['other']['items'] = array();
		$frames['other']['items']['category']['url'] = web_url('activity/category/display');
		$frames['other']['items']['category']['title'] = '&nbsp;&nbsp; 活动分类';
		$frames['other']['items']['category']['actions'] = array('ac','category');
		$frames['other']['items']['category']['active'] = '';
		
		$frames['other']['items']['adv']['url'] = web_url('store/adv/display');
		$frames['other']['items']['adv']['title'] = '&nbsp;&nbsp; 首页轮播';
		$frames['other']['items']['adv']['actions'] = array();
		$frames['other']['items']['adv']['active'] = '';
	}
	
	return $frames;
}

function getstoreFrames(){
	global $_W,$_GPC;
	$frames = array();
	$frames['activity']['title'] = '<i class="fa fa-gift"></i>&nbsp;&nbsp; 活动管理';
	$frames['activity']['items'] = array();
	$frames['activity']['items']['display']['url'] = web_url('activity/activity/display');
	$frames['activity']['items']['display']['title'] = '&nbsp;&nbsp; 活动列表';
	$frames['activity']['items']['display']['actions'] = array();
	$frames['activity']['items']['display']['active'] = '';
	
	$frames['activity']['items']['post']['url'] = web_url('activity/activity/post');
	$frames['activity']['items']['post']['title'] = '&nbsp;&nbsp; 发布活动';
	$frames['activity']['items']['post']['actions'] = array();
	$frames['activity']['items']['post']['active'] = '';
	if(!FX_ID){
		$frames['other']['title'] = '<i class="fa fa-bookmark"></i>&nbsp;&nbsp; 其他管理';
		$frames['other']['items'] = array();
		$frames['other']['items']['category']['url'] = web_url('activity/category/display');
		$frames['other']['items']['category']['title'] = '&nbsp;&nbsp; 活动分类';
		$frames['other']['items']['category']['actions'] = array('ac','category');
		$frames['other']['items']['category']['active'] = '';
		
		$frames['other']['items']['adv']['url'] = web_url('store/adv/display');
		$frames['other']['items']['adv']['title'] = '&nbsp;&nbsp; 首页轮播';
		$frames['other']['items']['adv']['actions'] = array('ac','adv');
		$frames['other']['items']['adv']['active'] = '';
	}
	
	return $frames;
}


function getrecordsFrames(){
	global $_W,$_GPC;
	$frames = array();
	$frames['records']['title'] = '<i class="fa fa-list"></i>&nbsp;&nbsp; 报名管理';
	$frames['records']['items'] = array();
	
	$frames['records']['items']['display1']['url'] = web_url('records/records/display');
	$frames['records']['items']['display1']['title'] = '&nbsp;&nbsp; 报名记录';
	$frames['records']['items']['display1']['actions'] = array();
	$frames['records']['items']['display1']['active'] = '';
	
	return $frames;
}

function getmemberFrames(){
	global $_W,$_GPC;
	$frames = array();
	$frames['member']['title'] = '<i class="fa fa-user"></i>&nbsp;&nbsp; 会员管理';
	$frames['member']['items'] = array();
	
	$frames['member']['items']['display']['url'] = web_url('member/member/display');
	$frames['member']['items']['display']['title'] = '&nbsp;&nbsp; 会员列表';
	$frames['member']['items']['display']['actions'] = array();
	$frames['member']['items']['display']['active'] = '';
	
	return $frames;
}