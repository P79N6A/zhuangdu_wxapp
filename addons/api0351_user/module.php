<?php
/**
 * 共享名片模块定义
 *
 * @author 悟空源码社区
 * @url http://www.5kym.cn/
 */
defined('IN_IA') or exit('Access Denied');

class Api0351_userModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		if($_W['ispost']) {
			$setting = $_GPC['data'];
			$flag = $this->saveSettings($setting);
            if ($flag) {
                message("信息保存成功", referer(), "success");
            } else {
                message("信息保存失败", referer(), "error");
            }
		}else{
			$sql = 'SELECT settings FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
			$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'api0351_user'));
			$setting = $this->settings = iunserializer($settings);	
			include $this->template('setting');
		}
		
	}


}