<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxstore',
	'name'    => '品牌馆(商家展示)',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title' => '入口', 'route' => 'setting'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
