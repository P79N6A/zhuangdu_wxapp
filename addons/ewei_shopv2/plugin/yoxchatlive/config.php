<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'yoxchatlive',
	'name'    => '培训课',
	'v3'      => true,
	'menu'    => array(
	    'title'     => '培训课 ',
		'plugincom' => 1,
		'items'     => array(
		    array('title' => '微商培训课', 'route' => 'index'),
			)
		)
	);

?>
