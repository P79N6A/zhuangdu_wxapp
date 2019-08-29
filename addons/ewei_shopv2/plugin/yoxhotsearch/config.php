<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxhotsearch',
	'name'    => '热搜',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title'   => '热搜管理','route'   => 'index',),
			array('title' => '添加热搜', 'route' => 'edit'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
