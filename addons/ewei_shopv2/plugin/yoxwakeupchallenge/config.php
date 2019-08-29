<?php
/**
 * 早起挑战
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxwakeupchallenge',
	'name'    => '早起挑战',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title' => '活动','route'   => 'index',),
			//array('title' => '活动', 'route' => 'activity'),
			array('title' => '活动编辑', 'route' => 'activity.edit'),
			array('title' => '设置', 'route' => 'activity.setting'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
