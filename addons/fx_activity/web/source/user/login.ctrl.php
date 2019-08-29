<?php
defined('IN_IA') or exit('Access Denied');
define('IN_GW', true);
global $_GPC;
if(checksubmit()) {
	_login($_GPC['referer']);
}
include fx_template('user/login');

function _login($forward = '') {
	global $_GPC, $_W;
	$username = trim($_GPC['username']);
	$password = trim($_GPC['password']);
    $verify = trim($_GPC['verify']);
    if(empty($username)) {
		$_W['merch_error'] = 1;
        message('请输入账户名！', '', 'error');
    }
    if(empty($password)) {
		$_W['merch_error'] = 1;
        message('请输入密码！', '', 'error');
    }
    if(empty($verify)) {
        //message('请输入验证码！', '', 'error');
    }
    //$result = checkcaptcha($verify);
    if (empty($result)) {
       // message('验证码错误，请重新输入！', '', 'error');
    }
    $record = __user_single($username, $password);
	if (!empty($record)) {
		$cookie = array();
		$cookie['uid'] = $record['uid'];
		$cookie['fx_mall']['merch_id'] = $record['id'];
		$cookie['fx_mall']['uniacid'] = $record['uniacid'];
		$session = base64_encode(json_encode($cookie));
		isetcookie('___shop_session___', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
        isetcookie('__uniacid', $record['uniacid'], 7 * 86400);
        //isetcookie('merchantid', $record['id'], 7 * 86400);
		
		session_start();
		$_SESSION['role'] = 'merchant';
		$_SESSION['role_id'] = $record['id'];
		$_SESSION['role_name'] = $record['name'];
		$_SESSION['role_logo'] = $record['logo'];
		
		$_W['uniacid'] = $record['uniacid'];
		$modules = uni_modules();
		$_W['current_module'] = $modules[MODULE_NAME];
		$_W['_config'] = $_W['current_module']['config'];
		$_W['setting']['copyright']['blogo'] = empty($_W['_config']['merch_logo']) ? $_W['setting']['copyright']['blogo'] : $_W['_config']['merch_logo'];
		
		message("欢迎回来，{$record['uname']}！", web_url('application/merchant/setting',array('role'=>'merchant')), 'success');
	} else {
		$_W['merch_error'] = 1;
		message('登录失败，请检查您的账号和密码，或者您的商户没有被授权！', '', 'error');
	}
}

function __user_single($username, $password) {
    global $_W;
    $sql = "SELECT * FROM ".tablename('fx_merchant')." WHERE uname=:uname and open=:open";
    $params = array(
        ':uname' => $username,
		 ':open' => 1
    );
    $record = pdo_fetch($sql, $params);
    if (empty($record)) {
        return false;
    }
	$password = user_hash($password, $record['salt']);
    if ($password != $record['password']) {
        return false;
    }
    return $record;
}
