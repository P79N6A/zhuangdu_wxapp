<?php
//in_array("fx_activity_plugin_babycard", $_W['current_module']['plugin_list'])
function plugin_setting($mod='') {
	global $_W;
	$uni_modules = uni_modules();
	
	$plugin = array();
	$plugin['card'] = $uni_modules['fx_activity_plugin_babycard'];
	
	return $plugin;
} 