<?php
global $_W,$_GPC;
define('IN_SYS', true);
define("MODULE_NAME", 'fx_activity');
require_once '../../../framework/bootstrap.inc.php';
require_once IA_ROOT . '/addons/'.MODULE_NAME.'/web/common/bootstrap.sys.inc.php';
require_once IA_ROOT . '/addons/'.MODULE_NAME.'/core/common/defines.php';
require_once FX_CORE . '/class/loader.class.php';
$autoload =  FX_CORE . '/class/autoload.php';
if(file_exists($autoload)) require $autoload;
load()->web('template');
fx_load()->web('common');
fx_load()->func('template');
fx_load()->func('global');
fx_load()->func('tpl');
load()->func('communication');
session_start();
if($_SESSION['role']!='merchant'){
	require MODULE_ROOT . '/web/source/user/login.ctrl.php';
}else{
	if($_GPC['do']=='user' && $_GPC['ac']=='logout'){
		require MODULE_ROOT . '/web/source/user/logout.ctrl.php';
	}elseif($_GPC['c']=='utility'){
		$_W['uid'] = $_SESSION['role_id'];
		require MODULE_ROOT . '/web/source/utility/file.ctrl.php';
	}else{
		define('UNIACID', $_GPC['__uniacid']);
		define('FX_ID', " and id = '{$_SESSION['role_id']}' ");
		define('FX_MERCHANTID', " and merchantid = '{$_SESSION['role_id']}' ");
		define('MERCHANTID', $_SESSION['role_id']);
		$_W['uniacid'] = $_GPC['__uniacid'];
		$controller = $_GPC['do'];
		$action = $_GPC['ac'];
		$op = $_GPC['op'];
		if(empty($controller) || empty($action)) {
			$_GPC['do'] = $controller = 'application';
			$_GPC['ac'] = $action = 'merchant';
			$_GPC['op'] = $op = 'setting';
		}
		$modules = uni_modules();
		$_W['current_module'] = $modules[MODULE_NAME];
		$_W['_config'] = $_W['current_module']['config'];
		$_W['setting']['copyright']['blogo'] = empty($_W['_config']['merch_logo'])?$_W['setting']['copyright']['blogo']:$_W['_config']['merch_logo'];
		
		$flag = allow($controller, $action, $op, $_SESSION['role_id']);
		if(($controller!='application' && $controller!='map' && $controller!='member' && $action!='option' && $action!='url2qr' && $op!='output' && $op!='download') || ($controller=='application' && $action!='merchant' && $op!='selectstore')){
			if(!$flag){
				message("权限不足，如有需要请联系系统管理员",referer(),'error');
			}
		}
		//$top_menus = get_top_menus();
		$file = FX_WEB . '/controller/'.$controller.'/'.$action.'.ctrl.php';
		require $file;
	}
}