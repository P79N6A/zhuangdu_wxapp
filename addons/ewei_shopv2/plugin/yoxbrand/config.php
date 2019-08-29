<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxfriendscircle',
	'name'    => '精选朋友圈',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title'   => '品牌','route'   => 'index.pp_list',),
			array('title' => '工厂', 'route' => 'index.gc_list'),
		    array('title' => '加盟', 'route' => 'index.jm_list'),
		    array('title' => '品牌馆编辑', 'route' => 'index.brand_edit'),
		    array('title' => '分类编辑', 'route' => 'index.brand_cate_edit'),
		    array('title' => '入口', 'route' => 'setting'),
		    //array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
