<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxdiy',
	'name'    => 'diy模板素材',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title'   => '模板管理','route'   => 'index',),
		    array('title' => '入口', 'route' => 'setting'),
		    //array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
