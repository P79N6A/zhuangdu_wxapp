<?php 
	// 
	//  <project>
	//  移动端活动发布，图片上传，评价评论，讨论详情，讨论评价
	//  Created by Administrator on 2016-08-31.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	
global $_W,$_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($op == 'upload'){
	load()->func('file');
	$type = in_array($_GPC['type'], array('image','audio')) ? $_GPC['type'] : 'image';
	$fileType = $_FILES['wangEditorMobileFile']['type'];
	$fileContent = file_get_contents($_FILES['wangEditorMobileFile']['tmp_name']);
	$dataURL = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
	//$show_pic_scal=show_pic_scal(230, 230, $fileContent);
	//resize($dataURL,$show_pic_scal[0],$show_pic_scal[1]); 
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $dataURL, $result)){
		$img_type = $result[2];
		$uniacid = intval($_W['uniacid']);
		$path = "{$type}s/{$uniacid}/" . date('Y/m/');
		mkdirs(ATTACHMENT_ROOT . $path);//创建目录
		$filename = file_random_name(ATTACHMENT_ROOT . $path, $img_type);
		$result['path'] = $path . $filename;
		if (file_put_contents(ATTACHMENT_ROOT . $result['path'], base64_decode(str_replace($result[1], '', $dataURL)))){
			pdo_insert('core_attachment', array(
				'uniacid' => $uniacid,
				'uid' => $_W['member']['uid'],
				'filename' => $_FILES['wangEditorMobileFile']['name'],
				'attachment' => $result['path'],
				'type' => $type == 'image' ? 1 : 2,
				'createtime' => TIMESTAMP,
			));
			$info['id'] = pdo_insertid();
			die(tomedia($result['path']));
		}

	}
}

if($op == 'delete' && $_W['isajax']){
	$result = array('error' => 1, 'message' => '');
	$id = intval($_GPC['attachid']);
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

function show_pic_scal($width, $height, $picpath)
{
    $imginfo = GetImageSize($picpath);
    $imgw = $imginfo[0];
    $imgh = $imginfo[1];
    $ra = number_format($imgw / $imgh, 1);
    //宽高比
    $ra2 = number_format($imgh / $imgw, 1);
    //高宽比
    if ($imgw > $width or $imgh > $height) {
        if ($imgw > $imgh) {
            $newWidth = $width;
            $newHeight = round($newWidth / $ra);
        } elseif ($imgw < $imgh) {
            $newHeight = $height;
            $newWidth = round($newHeight / $ra2);
        } else {
            $newWidth = $width;
            $newHeight = round($newWidth / $ra);
        }
    } else {
        $newHeight = $imgh;
        $newWidth = $imgw;
    }
    $newsize[0] = $newWidth;
    $newsize[1] = $newHeight;
    return $newsize;
}

function getImageInfo($src)
{
    return getimagesize($src);
}
/**  
* 创建图片，返回资源类型  
* @param string $src 图片路径  
* @return resource $im 返回资源类型  
**/
function create($src)
{
    $info = getImageInfo($src);
    switch ($info[2]) {
        case 1:
            $im = imagecreatefromgif($src);
            break;
        case 2:
            $im = imagecreatefromjpeg($src);
            break;
        case 3:
            $im = imagecreatefrompng($src);
            break;
    }
    return $im;
}
/**  
* 缩略图主函数  
* @param string $src 图片路径  
* @param int $w 缩略图宽度  
* @param int $h 缩略图高度  
* @return mixed 返回缩略图路径  
**/

function resize($src, $w, $h)
{
    $temp = pathinfo($src);
    $name = $temp["basename"];
    //文件名
    $dir = $temp["dirname"];
    //文件所在的文件夹
    $extension = $temp["extension"];
    //文件扩展名
    $savepath = "{$dir}/{$name}";
    //缩略图保存路径,新的文件名为*.thumb.jpg
    //获取图片的基本信息
    $info = getImageInfo($src);
    $width = $info[0];
    //获取图片宽度
    $height = $info[1];
    //获取图片高度
    $per1 = round($width / $height, 2);
    //计算原图长宽比
    $per2 = round($w / $h, 2);
    //计算缩略图长宽比
    //计算缩放比例
    if ($per1 > $per2 || $per1 == $per2) {
        //原图长宽比大于或者等于缩略图长宽比，则按照宽度优先
        $per = $w / $width;
    }
    if ($per1 < $per2) {
        //原图长宽比小于缩略图长宽比，则按照高度优先
        $per = $h / $height;
    }
    $temp_w = intval($width * $per);
    //计算原图缩放后的宽度
    $temp_h = intval($height * $per);
    //计算原图缩放后的高度
    $temp_img = imagecreatetruecolor($temp_w, $temp_h);
    //创建画布
    $im = create($src);
    imagecopyresampled($temp_img, $im, 0, 0, 0, 0, $temp_w, $temp_h, $width, $height);
    if ($per1 > $per2) {
        imagejpeg($temp_img, $savepath, 100);
        imagedestroy($im);
        return addBg($savepath, $w, $h, "w");
        //宽度优先，在缩放之后高度不足的情况下补上背景
    }
    if ($per1 == $per2) {
        imagejpeg($temp_img, $savepath, 100);
        imagedestroy($im);
        return $savepath;
        //等比缩放
    }
    if ($per1 < $per2) {
        imagejpeg($temp_img, $savepath, 100);
        imagedestroy($im);
        return addBg($savepath, $w, $h, "h");
        //高度优先，在缩放之后宽度不足的情况下补上背景
    }
}
/**  
* 添加背景  
* @param string $src 图片路径  
* @param int $w 背景图像宽度  
* @param int $h 背景图像高度  
* @param String $first 决定图像最终位置的，w 宽度优先 h 高度优先 wh:等比  
* @return 返回加上背景的图片  
* 
**/
function addBg($src, $w, $h, $fisrt = "w")
{
    $bg = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($bg, 255, 255, 255);
    imagefill($bg, 0, 0, $white);
    //填充背景      //获取目标图片信息
    $info = getImageInfo($src);
    $width = $info[0];
    //目标图片宽度
    $height = $info[1];
    //目标图片高度
    $img = create($src);
    if ($fisrt == "wh") {
        //等比缩放
        return $src;
    } else {
        if ($fisrt == "w") {
            $x = 0;
            $y = ($h - $height) / 2;
            //垂直居中
        }
        if ($fisrt == "h") {
            $x = ($w - $width) / 2;
            //水平居中
            $y = 0;
        }
        imagecopymerge($bg, $img, $x, $y, 0, 0, $width, $height, 100);
        imagejpeg($bg, $src, 100);
        imagedestroy($bg);
        imagedestroy($img);
        return $src;
    }
}