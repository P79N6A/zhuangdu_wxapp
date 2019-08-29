<?php
/**
 * 颜值PK模块定义
 *
 * @author 悟空源码社区
 * @url http://www.5kym.cn/
 */
defined('IN_IA') or exit('Access Denied');

class Lshd_yanzhiModule extends WeModule {
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		header("Content-Security-Policy: upgrade-insecure-requests");
		define('THEME_URL', MODULE_URL . 'template/static/');
		if (checksubmit()) {
			$this->saveSettings($_GPC);
			message('保存成功!', 'refresh', 'success');
		}
		include $this->template('setting');
	}
}