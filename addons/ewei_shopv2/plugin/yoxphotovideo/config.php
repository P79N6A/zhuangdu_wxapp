<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxphotovideo',
	'name'    => '图文视频',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title' => '全部','route'   => 'index',),
			array('title' => '图文素材','route'   => 'article',),
			array('title' => '视频素材', 'route' => 'video'),
			//array('title' => '添加', 'route' => 'edit'),
			//array('title' => '评论管理', 'route' => 'comment'),
			array('title' => '入口', 'route' => 'setting'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
