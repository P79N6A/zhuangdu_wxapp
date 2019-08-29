<?php
/**
 * [woniu] Copyright (c) 2016/8/18
 * login.ctrl
 * 收藏控制器
 */
defined('IN_IA') or exit('Access Denied');
if (empty($_W['fans']['nickname'])){
	$_W['fans'] = getInfo();
}
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = '用户登录';
$actype = $_GPC['actype'];
$username = trim($_GPC['username']);
$password = trim($_GPC['password']);
if($op =='mobile'){
	$pagetitle = $actype=='project'?'手机验证':'手机绑定';
	$mode = trim($_GPC['mode']);
	if ($_W['ispost']){
		load()->model('mc');
		$sql = 'SELECT `uid` FROM ' . tablename('mc_members') . ' WHERE `uniacid`=:uniacid';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$code = trim($_GPC['code']);
		if (empty($username)) {
			fx_message('手机不能为空', '', 'error', '点击确定返回');
		}
		if(preg_match(REGULAR_MOBILE, $username)) {
			$type = 'mobile';
			$sql .= ' AND `mobile`=:mobile';
			$pars[':mobile'] = $username;
		} else {
			fx_message('手机号不合法', referer(), 'error', '点击确定重新输入');
		}
		
		if(!empty($_W['openid'])) {
			$fan = mc_fansinfo($_W['openid']);
			if (!empty($fan)) {
				$map_fans = $fan['tag'];
			}
			if (empty($map_fans) && isset($_SESSION['userinfo'])) {
				$map_fans = unserialize(base64_decode($_SESSION['userinfo']));
			}
		}
		if(!empty($map_fans)) {
			$data['nickname'] = $map_fans['nickname'];
			$data['gender'] = $map_fans['sex'];
			$data['residecity'] = $map_fans['city'] ? $map_fans['city'] . '市' : '';
			$data['resideprovince'] = $map_fans['province'] ? $map_fans['province'] . '省' : '';
			$data['nationality'] = $map_fans['country'];
			$data['avatar'] = rtrim($map_fans['headimgurl'], '0') . 132;
		}
		if (!empty($_W['member']['uid'])){
			$user['uid'] = $_W['member']['uid'];
			$result = mc_update($user['uid'], array('mobile' => $username));
		}
		if($result) {
			if ($actype=='project'){
				fx_message('手机绑定成功', app_url('member/project/post'), 'success', '点击返回返回');
			}elseif ($actype=='merch'){
				fx_message('手机绑定成功', app_url('member/merch/display'), 'success', '点击确定返回');
			}else{
				fx_message('手机绑定成功', app_url('member/home/display'), 'success', '点击确定返回个人中心');
			}
		}else{
			fx_message('绑定失败', '', 'error', '点击返回');
		}
		exit;
	}
}
include $this->template ('member/bond');