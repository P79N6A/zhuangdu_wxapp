<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxwechatbusiness',
	'name'    => '微商',
	'v3'      => true,
	'menu'    => array(
	    'title'     => '微商',
		'plugincom' => 1,
		'items'     => array(
		    array('title' => '基本配置','route'   => 'setting',),
		    array('title' => '微商用户','route'   => 'index',),
		    array('title' => '用户升级','route'   => 'upgrade',),
		    array('title' => '等级管理','route'   => 'level',),
		    array('title' => '银行卡管理','route'   => 'bank_card',),
		    array('title' => '证书管理','route'   => 'certificate',),
		    array('title' => '微商库存','route'=> 'user_stock',),
		    array('title' => '佣金管理','route'   => 'commission',),
		    array('title' => '转单','route'   => 'order',),
		    array('title' => '成分社', 'route' => 'ingredients'),
		    array('title' => '微商培训课', 'route' => 'course'),
		    array('title' => '好友管理', 'route' => 'frient'),
		    array('title' => '电子合同', 'route' => 'electronic_contract'),
		    array('title' => '导入物流码', 'route' => 'securitycode'),
		    array('title' => '投诉建议', 'route' => 'suggestion'),
		    array('title' => '入口', 'route' => 'setting'),
			//array('title' => '安装', 'route' => 'install'),
			)
		)
	);

?>
