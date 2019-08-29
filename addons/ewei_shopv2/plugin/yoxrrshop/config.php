<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxrrshop',
	'name'    => '人人店',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title'   => '店铺管理','route'   => 'index',),
			array('title'   => '首页banner','route'   => 'banner',),
		    array('title' => '产品', 'route' => 'setting'),
		    array('title' => '预约', 'route' => 'appointment'),
		    array('title' => '预约需求', 'route' => 'needs'),

			)
		)
	);

?>
