<?php
define('IN_MOBILE', true);
fx_load()->model('member');
load()->func('communication');
$_W['page']['title'] = '消息提醒';
$_W['share'] = $_W['_config']['share'];
$_W['share']['title'] = str_replace('"','\"',str_replace("'","\'",htmlspecialchars_decode($_W['share']['title'])));
$_W['share']['desc'] = str_replace('"','\"',str_replace("'","\'",htmlspecialchars_decode($_W['share']['desc'])));
$uniacid = "uniacid = '{$_W['uniacid']}'";
$controller = $_GPC['do'];
$action = $_GPC['ac'];
if (empty($controller)) {
	$_GPC['do'] = $controller = 'home';
	$_GPC['ac'] = $action = 'index';
}else if (!empty($controller) && empty($action)) {
	$action = $controller;
}

if ($action!='auto_task'){//计划任务不执行此条件语句内代码
	if (intval($_W['account']['level']) < 4 && $_W['account']['oauth']['acid']==$_W['uniacid']) {
		fx_message ('获取用户信息权限不足！请借用认证服务号权限。');
	}
	if(!empty($_W['openid']) && empty($_W['member']['uid'])){
		$_W['member'] = mc_fetch($_W['openid']);
	}
	
	$uid = empty($_W['member']['uid']) ? 0 : $_W['member']['uid'];
	//判断当前用户是否主办方
	$mid = pdo_fetchcolumn("SELECT id FROM " . tablename ('fx_merchant') . " WHERE uid = $uid");
	$_W['_config']['openids'] = empty($_W['_config']['openids']) ? array() : $_W['_config']['openids'];
	define('ADMIN', in_array($_W['openid'], $_W['_config']['openids']) ? 1 : 0);
	define('MERCHANTID', $mid && !ADMIN ? $mid : 0);

	//读取主办方权限
	if (MERCHANTID) {
		fx_load() -> model('permissions');
		$_W['allow'] = allow_params();
		$_W['_config']['proreview'] = $_W['allow']['activity']['proreview'] ? 1 : $_W['_config']['proreview'];
	}
	//读取年卡
	if ($_W['plugin']['card']['config']['card_enable']){
		$yearcard = Util::getSingelData('*', 'fx_yearcard',array());
		$yearcard['buy'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fx_yearcard_record') . " WHERE status<>0 and buyuid = $uid");
	}
}
$_W['_config']['image']['ratio'] = $_W['_config']['image']['ratio']=='' ? "1/1" : $_W['_config']['image']['ratio'];//图片附件缩放比
$file = FX_APP . '/controller/'.$controller.'/'.$action.'.ctrl.php';
if (!file_exists($file)) {
	header("Location: index.php?i={$_W['uniacid']}&c=entry&do=home&ac=index&m=".MODULE_NAME);
	exit;
}
require $file;