<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 

!defined('MODULE_ROOT') && define('MODULE_ROOT', IA_ROOT . '/addons/' . MODULE_NAME);

!defined('FX_CORE') && define('FX_CORE', MODULE_ROOT . '/core');
!defined('FX_APP')  && define('FX_APP', MODULE_ROOT . '/app');
!defined('FX_WEB')  && define('FX_WEB', MODULE_ROOT . '/web');
!defined('FX_DATA') && define('FX_DATA', MODULE_ROOT . '/data');

!defined('FX_PLUGIN_TEMPLATE') && define('FX_PLUGIN_TEMPLATE', '../../'.MODULE_PLUGIN_NAME . '/template');

$_W['siteroot'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? str_replace('http://', 'https://', $_W['siteroot']) : $_W['siteroot'];

!defined('FX_URL') && define('FX_URL', $_W['siteroot'] . 'addons/'. MODULE_NAME . '/');
!defined('FX_BASE') && define('FX_BASE', '../../../../addons/'.MODULE_NAME.'/app/resource/');