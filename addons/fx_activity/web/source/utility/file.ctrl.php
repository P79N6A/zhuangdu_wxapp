<?php

/**

 * [WeEngine System] Copyright (c) 2014 WE7.CC

 * WeEngine is NOT a free software, it under the license terms, visited http://bbs.mhyma.com/ for more details.

 */

defined('IN_IA') or exit('Access Denied');

error_reporting(0);

global $_W;

load()->func('file');

if (!in_array($do, array('upload', 'fetch', 'networktolocal', 'image', 'module' ,'video', 'browser', 'delete', 'local'))) {

	exit('Access Denied');

}

$result = array(

	'error' => 1,

	'message' => '',

	'data' => ''

);



$type = $_COOKIE['__fileupload_type'];

$type = in_array($type, array('image','audio')) ? $type : 'image';

$option = array();

$option = array_elements(array('uploadtype', 'global', 'dest_dir'), $_POST);

$option['width'] = intval($option['width']);

$option['global'] = !empty($_COOKIE['__fileupload_global']);

if (!empty($option['global']) && empty($_W['isfounder'])) {

	$result['message'] = '没有向 global 文件夹上传文件的权限.';

	die(json_encode($result));

}



$dest_dir = $_COOKIE['__fileupload_dest_dir'];

if (preg_match('/^[a-zA-Z0-9_\/]{0,50}$/', $dest_dir, $out)) {

	$dest_dir = trim($dest_dir, '/');

	$pieces = explode('/', $dest_dir);

	if(count($pieces) > 3){

		$dest_dir = '';

	}

} else {

	$dest_dir = '';

}



$setting = $_W['setting']['upload'][$type];

$uniacid = intval($_W['uniacid']);



if (!empty($option['global'])) {

	$setting['folder'] = "{$type}s/global/";

	if (!empty($dest_dir)) {

		$setting['folder'] .= '/'.$dest_dir.'/';

	}

} else {

	$setting['folder'] = "{$type}s/{$uniacid}";

	if(empty($dest_dir)){

		$setting['folder'] .= '/'.date('Y/m/');

	} else {

		$setting['folder'] .= '/'.$dest_dir.'/';

	}

}





if ($do == 'fetch') {

	$url = trim($_GPC['url']);

	load()->func('communication');

	$resp = ihttp_get($url);

	if (is_error($resp)) {

		$result['message'] = '提取文件失败, 错误信息: '.$resp['message'];

		die(json_encode($result));

	}

	if (intval($resp['code']) != 200) {

		$result['message'] = '提取文件失败: 未找到该资源文件.';

		die(json_encode($result));

	}

	$ext = '';

	if ($type == 'image') {

		switch ($resp['headers']['Content-Type']){

			case 'application/x-jpg':

			case 'image/jpeg':

				$ext = 'jpg';

				break;

			case 'image/png':

				$ext = 'png';

				break;

			case 'image/gif':

				$ext = 'gif';

				break;

			default:

				$result['message'] = '提取资源失败, 资源文件类型错误.';

				die(json_encode($result));

				break;

		}

	} else {

		$result['message'] = '提取资源失败, 仅支持图片提取.';

		die(json_encode($result));

	}

	

	if (intval($resp['headers']['Content-Length']) > $setting['limit'] * 1024) {

		$result['message'] = '上传的媒体文件过大('.sizecount($size).' > '.sizecount($setting['limit'] * 1024);

		die(json_encode($result));

	}

	$originname = pathinfo($url, PATHINFO_BASENAME);

	$filename = file_random_name(ATTACHMENT_ROOT .'/'. $setting['folder'], $ext);

	$pathname = $setting['folder'] . $filename;

	$fullname = ATTACHMENT_ROOT . '/' . $pathname;

	if (file_put_contents($fullname, $resp['content']) == false) {

		$result['message'] = '提取失败.';

		die(json_encode($result));

	}

}





if ($do == 'upload') {

	$fileData = getFile();

	if (empty($fileData['name'])) {

		$result['message'] = '上传失败, 请选择要上传的文件！';

		die(json_encode($result));

	}

	if ($fileData['error'] != 0) {

		$result['message'] = '上传失败, 请重试.';

		die(json_encode($result));

	}

	$ext = pathinfo($fileData['name'], PATHINFO_EXTENSION);

	$ext = strtolower($ext);

	$size = intval($fileData['size']);

	$originname = $fileData['name'];

	$filename = file_random_name(ATTACHMENT_ROOT .'/'. $setting['folder'], $ext);

	$file = file_upload($fileData, $type, $setting['folder'] . $filename);

	if (is_error($file)) {

		$result['message'] = $file['message'];

		die(json_encode($result));

	}

	$pathname = $file['path'];

	$fullname = ATTACHMENT_ROOT . '/' . $pathname;

}



if ($do == 'fetch' || $do == 'upload') {

	if($type == 'image'){

		$thumb = empty($setting['thumb']) ? 0 : 1; 		$width = intval($setting['width']); 

		if(isset($option['thumb'])){

			$thumb = empty($option['thumb']) ? 0 : 1;

		}

		if (isset($option['width']) && !empty($option['width'])) {

			$width = intval($option['width']);

		}

		if ($thumb == 1 && $width > 0) {

			$thumbnail = file_image_thumb($fullname, '', $width);

			@unlink($fullname);

			if (is_error($thumbnail)) {

				$result['message'] = $thumbnail['message'];

				die(json_encode($result));

			} else {

				$filename = pathinfo($thumbnail, PATHINFO_BASENAME);

				$pathname = $thumbnail;

				$fullname = ATTACHMENT_ROOT .'/'.$pathname;

			}

		}

	}



	$info = array(

		'name' => $originname,

		'ext' => $ext,

		'filename' => $pathname,

		'attachment' => $pathname,

		'url' => tomedia($pathname),

		'is_image' => $type == 'image' ? 1 : 0,

		'filesize' => filesize($fullname),

	);

	if ($type == 'image') {

		$size = getimagesize($fullname);

		$info['width'] = $size[0];

		$info['height'] = $size[1];

	} else {

		$size = filesize($fullname);

		$info['size'] = sizecount($size);

	}

	if (!empty($_W['setting']['remote']['type'])) {

		$remotestatus = file_remote_upload($pathname);

		if (is_error($remotestatus)) {

			$result['message'] = '远程附件上传失败，请检查配置并重新上传';

			file_delete($pathname);

			die(json_encode($result));

		} else {

			file_delete($pathname);

			$info['url'] = tomedia($pathname);

		}

	}

	pdo_insert('core_attachment', array(

		'uniacid' => $uniacid,

		'uid' => 0,

		'filename' => $originname,

		'attachment' => $pathname,

		'type' => $type == 'image' ? 1 : 2,

		'createtime' => TIMESTAMP,

		'group_id' => intval($_GPC['group_id']),

		'merch_id'=>$_W['uid']

	));

	

	die(json_encode($info));

}



if ($do == 'networktolocal') {

	$url = $_GPC['url'];

	$type = $_GPC['type'];



	if (!in_array($type, array('image', 'video'))) {



		$type = 'image';



	}

	

	$path = file_remote_attach_fetch($url);

	

	if(is_error($path)) {

		iajax(1, $path['message']);

	}

	$filename = pathinfo($path,PATHINFO_FILENAME);

	$data = array('uniacid' => $uniacid, 'uid' => 0,

		'filename' => $filename,

		'attachment' => $path,

		'type' => $type == 'image' ? ATTACH_TYPE_IMAGE : ($type == 'audio'||$type == 'voice' ? ATTACH_TYPE_VOICE : ATTACH_TYPE_VEDIO),

		'createtime'=>TIMESTAMP,

		'group_id' => -1,

		'merch_id'=>$_W['uid']

	);

	pdo_insert('core_attachment', $data);

	$id = pdo_insertid();

	$data['id'] = $id;

	$data['url'] = tomedia($path);

	

	if (is_error($data)) {

		iajax(1, $data['message']);

		return;

	}



	iajax(0, $data);



}



if ($do == 'delete') {

	$id = intval($_GPC['id']);

	$media = pdo_get('core_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));

	if(empty($media)) {

		exit('文件不存在或已经删除');

	}

	if(empty($_W['isfounder']) && $_W['role'] != 'manager') {

		exit('您没有权限删除该文件');

	}

	load()->func('file');

	if (!empty($_W['setting']['remote']['type'])) {

		$status = file_remote_delete($media['attachment']);

	} else {

		$status = file_delete($media['attachment']);

	}

	if(is_error($status)) {

		exit($status['message']);

	}

	pdo_delete('core_attachment', array('uniacid' => $uniacid, 'id' => $id));

	exit('success');

}



if ($do == 'image') {

	$types = array('image', 'audio');

	$type = in_array($_GPC['type'], $types) ? $_GPC['type'] : 'image';

	$typeindex = array('image' => 1, 'audio' => 2);

	$groupid = intval($_GPC['groupid']);

	$condition = ' WHERE uniacid = :uniacid AND type = :type AND merch_id = :merch_id';

	$params = array(':uniacid' => $_W['uniacid'], ':type' => $typeindex[$type], ':merch_id'=>$_W['uid']);

	$year = intval($_GPC['year']);

	$month = intval($_GPC['month']);

	if($year > 0 || $month > 0) {

		if($month > 0 && !$year) {

			$year = date('Y');

			$starttime = strtotime("{$year}-{$month}-01");

			$endtime = strtotime("+1 month", $starttime);

		} elseif($year > 0 && !$month) {

			$starttime = strtotime("{$year}-01-01");

			$endtime = strtotime("+1 year", $starttime);

		} elseif($year > 0 && $month > 0) {

			$year = date('Y');

			$starttime = strtotime("{$year}-{$month}-01");

			$endtime = strtotime("+1 month", $starttime);

		}

		$condition .= ' AND createtime >= :starttime AND createtime <= :endtime';

		$params[':starttime'] = $starttime;

		$params[':endtime'] = $endtime;

	}

	if ($groupid == 0) {

		$condition .= ' AND group_id <= :groupid';

		$params[':groupid'] = 0;

	}

	if ($groupid > 0){

		$condition .= ' AND group_id = :groupid';

		$params[':groupid'] = $groupid;

	}	

	

	$page = intval($_GPC['page']);

	$page = max(1, $page);

	$page_size = $_GPC['pagesize'] ? intval($_GPC['pagesize']) : 32;



	$remote = $_W['setting']['remote'];



	$sql = 'SELECT * FROM '.tablename('core_attachment')." {$condition} ORDER BY id DESC LIMIT ".(($page-1)*$page_size).','.$page_size;

	$list = pdo_fetchall($sql, $params);



	foreach ($list as &$item) {

		$item['url'] = tomedia($item['attachment']);

		unset($item['uid']);

	}

	$total = pdo_fetchcolumn('SELECT count(*) FROM '.tablename('core_attachment') ." {$condition}", $params);

	

	

	$pager = pagination($total, $page, $page_size,'',$context = array('before' => 5, 'after' => 4, 'isajax' => $_W['isajax']));



	$result = array('items' => $list, 'pager' => $pager);



	iajax(0, $result);

}