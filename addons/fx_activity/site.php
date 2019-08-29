<?php
/**
 */
defined('IN_IA') or exit('Access Denied');
define("MODULE_NAME", "fx_activity");
require IA_ROOT . '/addons/' . MODULE_NAME . '/core/common/defines.php';
require FX_CORE . '/class/loader.class.php';
$autoload = FX_CORE . '/class/autoload.php';
if(file_exists($autoload)) require $autoload;
fx_load()->func('global');
fx_load()->model('plugin');
load()->func('tpl');
class Fx_activityModuleSite extends WeModuleSite {
	function __call($name, $arguments) {
		global $_W,$_GPC;
		$_W['_config'] = $this->module['config'];
		$_W['plugin'] = plugin_setting();
		$isWeb = stripos($name, 'doWeb') === 0;
		$isMobile = stripos($name, 'doMobile') === 0;
		if($isWeb || $isMobile) {
			if($isWeb) {
				$dir = FX_WEB;
				$controller = strtolower(substr($name, 5));
			}
			if($isMobile) {
				$dir = FX_APP;
				$controller = strtolower(substr($name, 8));
			}
			$file = $dir . '/index.php';
			if(file_exists($file)) {
				require $file;
				exit;
			}
		}
	}
	
	/*结果返回*/
	public function payResult($params) {
		global $_W;
		$_W['page']['title'] = '支付结果';
		$_W['uniacid'] = $_W['acid'] = $params['uniacid'];
		load()->model('module');
		$this->module = module_fetch(MODULE_NAME);
		$_W['_config'] = $this->module['config'];
		payResult::payNotify($params);
	}
}