<?php
class createQrcode {
	static function creategroupQrcode($mid = 0, $folder = '') {
		global $_W, $_GPC;
		$path = IA_ROOT . "/addons/". MODULE_NAME ."/data/qrcode/" . $_W['uniacid'] . "/";
		if (!empty($folder)) {
			$path .= $folder . "/";
			$folder = $folder . "/";
		}
		if (!is_dir($path)) {
			load() -> func('file');
			mkdirs($path);
		}
		$url = app_url('records/check/display', array('mid' => $mid));
		$file = $mid . '.png';
		$qrcode_file = $path . $file;
		if (!is_file($qrcode_file)) {
			require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			QRcode :: png($url, $qrcode_file, QR_ECLEVEL_L, 5);
		} 
		return $_W['siteroot'] . 'addons/'. MODULE_NAME .'/data/qrcode/' . $_W['uniacid'] . '/' . $folder . $file;
	
	}
	
	static public function createverQrcode($url, $mid = 0 , $pid = 0, $folder = '') {
		global $_W, $_GPC;
		$path = IA_ROOT . "/addons/". MODULE_NAME ."/data/qrcode/" . $_W['uniacid'] . "/";
		if (!empty($folder)) {
			$path .= $folder . "/";
			$folder = $folder . "/";
		} 
		if (!is_dir($path)) {
			load() -> func('file');
			mkdirs($path);
		} 
		$file = 'ver_qrcode_' . $mid . '_' . $pid . '.png';
		$qrcode_file = $path . $file;
		if (!is_file($qrcode_file)) {
			require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			QRcode :: png($url, $qrcode_file, QR_ECLEVEL_L, 5);
		} 
		return $_W['siteroot'] . 'addons/'. MODULE_NAME .'/data/qrcode/' . $_W['uniacid'] . '/' . $folder . $file;
	} 
} 
