<?php
/**
 * 颜值测试
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
	'id'      => 'yoxface',
	'name'    => '颜值测试',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title'   => '颜值测试','route'   => 'index',),
			array('title'   => '颜值测试建议','route'   => 'face_value_suggest_list',),
			array('title' => '入口', 'route' => 'setting'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
