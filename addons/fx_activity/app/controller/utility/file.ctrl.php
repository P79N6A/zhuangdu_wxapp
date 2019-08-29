<?php 

/**

 * [WeEngine System] Copyright (c) 2014 WE7.CC

 * WeEngine is NOT a free software, it under the license terms, visited http://bbs.mhyma.com/ for more details.

 */

global $_W,$_GPC;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'upload';

if($op == 'upload'){

	load()->func('file');

	$type = in_array($_GPC['type'], array('image','audio')) ? $_GPC['type'] : 'image';

	if($type == 'image'){

		$fdata = getFile();

		$result = array(

			'jsonrpc' => '2.0',

			'id' => 'id',

			'error' => array('code' => 1, 'message'=>''),

		);

		if ($fdata['error'] != 0) {

			$result['error']['message'] = '非法文件，请重试！';

			die(json_encode($result));

		}

		if (!file_is_image($_GPC['name'])) {

			$result['message'] = '选择一个图片, 请重试.';

			die(json_encode($result));

		}

		$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');

		$ext = pathinfo($_GPC['name'], PATHINFO_EXTENSION);

		$ext = strtolower($ext);

		$extType = pathinfo($fdata['type'], PATHINFO_BASENAME);

		$extType = strtolower($extType);

		

		$setting = $_W['setting']['upload'][$type];

		if (!empty($setting)) {

			$allowExt = array('bmp', 'ico');

			$allowExt = array_merge($setting['extentions'], $allowExt);

		}

		if (!in_array(strtolower($ext), $allowExt) || in_array(strtolower($ext), $harmtype)) {

			$result['error']['message'] = '不允许上传此类文件';

			die(json_encode($result));

		}

		if (!in_array(strtolower($extType), $allowExt) || in_array(strtolower($extType), $harmtype)) {

			$result['error']['message'] = '不允许上传此类文件';

			die(json_encode($result));

		}

		if (!empty($limit) && $limit * 1024 < filesize($fdata['tmp_name'])) {

			$result['error']['message'] = "上传的文件超过大小限制，请上传小于 {$limit}k 的文件";

			die(json_encode($result));

		}

		if (pathinfo($fdata['type'],PATHINFO_DIRNAME)=='image'){

			//验证通过，上传Blob类型图片

			$uniacid = intval($_W['uniacid']);

			$path = "{$type}s/{$uniacid}/" . date('Y/m/');

			mkdirs(ATTACHMENT_ROOT . $path);//创建目录

			$filename = file_random_name(ATTACHMENT_ROOT . $path, $ext);

			$pathname = $path . $filename;

			$fullname = ATTACHMENT_ROOT . $pathname;

			if (file_put_contents($fullname, file_get_contents($fdata['tmp_name']))){

				$info = array(

					'name' => $_GPC['name'],

					'filename' => $filename,

					'attachment' => $pathname,

					'url' => tomedia($pathname),

					'is_image' => 1,

					'filesize' => filesize($fullname),

				);

				setting_load('remote');

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

					'filename' => $_GPC['name'],

					'attachment' => $pathname,

					'type' => $type == 'image' ? 1 : 2,

					'createtime' => TIMESTAMP,

					'group_id' => -1,

					'merch_id'=> MERCHANTID

				));

				$info['id'] = pdo_insertid();

				$info['token'] = $_W['token'];

				die(json_encode($info));

			}

		}else{

			$result['error']['message'] = '不允许上传此类文件';

			die(json_encode($result));

		}

		//$id = intval($_GPC['attachid']);

		//pdo_update('core_attachment', array('uniacid' => $_W['uniacid'],'uid' => $_W['member']['uid']), array('id' => $id));

		//exit;

	}

}

if($op == 'update'){

	$uniacid = intval($_W['uniacid']);

	$id = intval($_GPC['id']);

	pdo_update('core_attachment', array('uniacid' => $uniacid, 'uid' => $_W['member']['uid']), array('id' => $id));

}

if($op == 'delete' && $_W['isajax']){

	$result = array('error' => 1, 'message' => '');

	$id = intval($_GPC['id']);

	if (!empty($id)) {

		$attachment = pdo_get('core_attachment', array('id' => $id), array('attachment', 'uniacid', 'uid'));

		if (!empty($attachment)) {

			if ($attachment['uniacid'] != $_W['uniacid'] || empty($_W['openid']) || (!empty($_W['fans']) && $attachment['uid'] != $_W['fans']['from_user']) || (!empty($_W['member']) && $attachment['uid'] != $_W['member']['uid'])) {

				//return message(error(1, '无权删除！'), '', 'ajax');

			}

			load()->func('file');

			if ($_W['setting']['remote']['type']) {

				$result['error'] = file_remote_delete($attachment['attachment']);

			} else {

				$result['error'] = file_delete($attachment['attachment']);

			}

			if (!is_error($result['error'])) {

				pdo_delete('core_attachment', array('id' => $id));

			}

			if (!is_error($result)) {

				$result['error'] = 0;

				$result['message'] = '删除成功';

			} else {

				$result['error'] = 1;

				$result['message'] = '删除失败';

			}

		} else {

			$result['error']   = 1;

			$result['message'] = '不存在或已删除';

		}

	}

	die(json_encode($result));

}