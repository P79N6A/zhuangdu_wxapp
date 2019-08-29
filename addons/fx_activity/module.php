<?php
/**
 * 活动报名模块定义
 *
 * @author 奔跑的蜗牛
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define("MODULE_NAME", "fx_activity");
define('MERCHANTID', false);
require IA_ROOT . '/addons/' . MODULE_NAME . '/core/common/defines.php';
require FX_CORE . '/class/loader.class.php';
fx_load()->func('global');
fx_load()->func('template');
load()->func('tpl');
class Fx_activityModule extends WeModule {
	
	//public function welcomeDisplay()
	//{
		//header('location: ' . web_url('store/setting'));
		//exit();
	//}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			load()->func('file');
	        $r = mkdirs(IA_ROOT . '/attachment/fx_activity/cert/'. $_W['uniacid']);
			if(!empty($_GPC['cert'])) {
	            $ret = file_put_contents(IA_ROOT . '/attachment/fx_activity/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
	            $r = $r && $ret;
	        }
	        if(!empty($_GPC['key'])) {
	            $ret = file_put_contents(IA_ROOT . '/attachment/fx_activity/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
	            $r = $r && $ret;
	        }
			if(!empty($_GPC['rootca'])) {
	            $ret = file_put_contents(IA_ROOT . '/attachment/fx_activity/cert/'.$_W['uniacid'].'/rootca.pem', trim($_GPC['rootca']));
	            $r = $r && $ret;
	        }
			if(!$r) {
	            message('证书保存失败, 请保证该目录可写');
	        }
			$cfg                  = $_GPC['module'];
			$cfg['percent']       = $cfg['percent'] >= 0.6 ? $cfg['percent'] : '';
			$cfg['merch_amount']  = $cfg['merch_amount'] >= 1 ? $cfg['merch_amount'] : '';
			$cfg['joinagreement'] = html_format($cfg['joinagreement']);
			$cfg['proagreement']  = html_format($cfg['proagreement']);
			//echo $cfg['joinagreement'];
			$bytes = strlen(serialize($cfg))-65000;
			if ($bytes > 0){
				message('协议内容已超出存储范围：约超' . round($bytes/3) . "个汉字");
			}
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		fx_load() -> model('member');
        include fx_template('setting');
	}

}