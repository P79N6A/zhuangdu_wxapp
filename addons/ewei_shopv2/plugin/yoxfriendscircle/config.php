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
		    array('title' => '设置', 'route' => 'setting'),
			array('title'   => '"朋友圈"管理','route'   => 'index',),
			array('title' => '"朋友圈"编辑', 'route' => 'edit'),
			array('title' => '评论管理', 'route' => 'comment'),
		    array('title' => '关注', 'route' => 'follow'),
		    array('title' => '添加关注', 'route' => 'follow.edit'),
		    array('title' => '态度管理', 'route' => 'attitude'),
		    array('title' => '入口', 'route' => 'setting'),
		    //array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
