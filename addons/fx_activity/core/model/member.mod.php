<?php
/**
 * [woniu] Copyright (c) 2016/8/18 
 * 会员model
 */
function getInfo() {
	global $_W, $_GPC;
	$userinfo = array();
	$debug = TRUE;
	load()->model('mc');
	$userinfo = mc_oauth_userinfo();
	$uid = mc_openid2uid($userinfo['openid']);
	$member = mc_fetch($uid, array('nickname','avatar'));
	if(empty($member['nickname']) || empty($member['avatar'])){
		$result = mc_update($uid, array('nickname' => $userinfo['nickname'],'avatar' => $userinfo['avatar']));
	}
	if($debug == TRUE){
		
	}
	return $userinfo;
} 
//根据openid，获取会员信息
function getMember($openid = '') {
	load()->model('mc');
	$uid = mc_openid2uid($openid);
	$member = mc_fetch($uid);
	return $member;
}