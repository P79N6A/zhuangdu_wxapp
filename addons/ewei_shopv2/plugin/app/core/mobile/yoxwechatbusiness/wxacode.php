<?php
/**
 * 微商等级
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
if( !defined("IN_IA") )
{
	exit( "Access Denied" );
}
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Wxacode_EweiShopV2Page extends AppMobilePage
{
	public function main()
	{
	    global $_GPC, $_W;
		$image = $this->getImage($_GPC['invite_level']);
        if ($image) {
            $result['status']=1;
            $result['data']['intval_qrcode']=$image;
        }else{
             $result['status']=0;
            $result['data']['errMsg']='小程序码生成错误';
        }

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));

	}
	private function getImage($invite_level)
	{
	    global $_GPC, $_W;
		$member = $this->member;
		set_time_limit(0);
		@ini_set('memory_limit', '256M');
		$path = IA_ROOT . '/attachment/ewei_shopv2/yoxwechatbusiness/invite_qrcode/' . $_W['uniacid'] . '/';
		if (!is_dir($path)) {
			load()->func('file');
			mkdirs($path);
		}

		$md5 = md5(json_encode(array('siteroot' => $_W['siteroot'], 'openid' => $member['openid'], 'nickname' => $member['nickname'])));
		$filename = $md5 .$invite_level. '.png';
		$filepath = $path . $filename;

		if (is_file($filepath)) {
			return $_W['attachurl_local'].'ewei_shopv2/yoxwechatbusiness/invite_qrcode/' . $_W['uniacid'] . '/'.$filename;
		}
		$avatar = $this->changeAvatar(file_get_contents($member['avatar']));
		$qrcode = $this->getQR($member,$invite_level);
        // var_dump($qrcode);die();
        //小程序码与头像进行拼接
        $bool=$this->makeOnePic($qrcode, $avatar,$filepath);
        if ($bool) {
            return $_W['attachurl_local'].'ewei_shopv2/yoxwechatbusiness/invite_qrcode/' . $_W['uniacid'] . '/'.$filename;
        }else{
            return false;
        }


	}

    private function  makeOnePic($qr_code, $logo,$filepath)  //二维码与头像组合
    {
        $qr_code = imagecreatefromstring($qr_code);  //生成的二维码底色为白色
         imagesavealpha($qr_code, true);  //这个设置一定要加上
        // $bg = imagecolorallocatealpha($qr_code, 255, 255, 255, 127);   //拾取一个完全透明的颜色,最后一个参数127为全透明
        // imagefill($qr_code, 0, 0, $bg);

        $icon = imagecreatefromstring($logo);  //生成中间圆形logo （微信头像获取到的logo的大小为132px 132px）

        $qr_width = imagesx($qr_code);  //二维码图片宽度
//        $qr_height = imagesy($qr_code);  //二维码图片高度
        $lg_width = imagesx($icon);  //logo图片宽度
        $lg_height = imagesy($icon);  //logo图片高度
        $qr_lg_width = $qr_width / 2.2;
        $scale = $lg_width / $qr_lg_width;
        $qr_lg_height = $lg_height / $scale;

        $start_width = ($qr_width - $lg_width) / 3+18;  //（获取logo的左上方的位置：( 外部的正方形-logo的宽 )
        imagecopyresampled($qr_code, $icon, $start_width, $start_width, 0, 0, $qr_lg_width, $qr_lg_height, $lg_width, $lg_height);
        $bool=imagepng($qr_code,$filepath); //保存
        imagedestroy($qr_code);
        imagedestroy($icon);
        return $bool;
    }

	private function getQR($member = array(),$invite_level)
	{
		if (empty($member)) {
			return '';
		}
		$image = p('app')->getCodeUnlimit(array('scene' => $member['uid'].'-'.$invite_level, 'page' => 'packageYoxwechatbusiness/pages/invited_success/invited_success','is_hyaline'=>true));
		return $image;
	}




    private function changeAvatar($avatar)
    {
       //处理用户头像为圆形icon
        $avatar = imagecreatefromstring($avatar);
        $w = imagesx($avatar);
        $h = imagesy($avatar);
        $w = min($w, $h);
        $h = $w;

        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);

        $r = $w / 2; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($avatar, $x, $y);

                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }

        ob_start();
        imagepng($img);
        imagedestroy($img);
        imagedestroy($avatar);
        $contents = ob_get_contents();
        ob_end_clean();  //清空缓存区

        return $contents;
    }






}
?>