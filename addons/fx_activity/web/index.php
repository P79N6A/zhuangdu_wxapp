<?php
if (empty($_GPC)) {
	require_once '../../../framework/bootstrap.inc.php';
	if ($_GPC['c'] == 'utility') {
		require IA_ROOT . '/addons/fx_activity/web/merch.php';
		exit ;
	}
}

define('FX_MERCHANTID', '');
define('FX_ID', '');
define('MERCHANTID', false);
fx_load()->func('tpl');
fx_load()->func('template');
load()->func('communication');

$entries = module_entries(MODULE_NAME);
$op = $_GPC['op'] = $_GPC['op'] ? $_GPC['op'] : 'display';
$controller = $_GPC['do'];
$action = $_GPC['ac'] ? $_GPC['ac'] : $_GPC['state'];
$action = $_GPC['ac'] = empty($action) ? $controller : $action;

if (empty($controller) || empty($action)) $_GPC['do'] = $controller = 'index';
$file = FX_WEB . '/controller/' . $controller . '/' . $action . '.ctrl.php';
if (!file_exists($file)) {
	header("Location: index.php?c=home&a=welcome&do=ext&m=" . MODULE_NAME);
	exit;
}

require $file;

