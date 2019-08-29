<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxarticlevideo',
	'name'    => '海报|视频 (展示)',
	'v3'      => true,
	'menu'    => array(
	    'title'     => '海报|视频 ',
		'plugincom' => 1,
		'items'     => array(
			array('title' => '分类','route'   => 'index.cate_list',),
			array('title' => '全部','route'   => 'index',),
			array('title' => '海报管理','route'   => 'article',),
			array('title' => '视频管理', 'route' => 'video'),
			array('title' => '添加', 'route' => 'edit'),
			//array('title' => '评论管理', 'route' => 'comment'),
			array('title' => '入口', 'route' => 'setting'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
