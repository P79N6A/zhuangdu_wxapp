<?php
	function fx_autoLoad($class_name){
		$file = FX_CORE . '/class/' . $class_name . '.class.php';	
		if(is_file($file)){
			require_once $file;
		}
		return false;
	}

	spl_autoload_register('fx_autoLoad');
?>