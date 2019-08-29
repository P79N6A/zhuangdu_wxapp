<?php
/**
 * 证书
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
class Certificate_EweiShopV2Page extends AppMobilePage
{
    //列表
    public function main()
    {
        $this->certificate();
    }
    public function certificate(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $user = $yoxwechatbusiness->user_info($_W['uniacid'],$_W['member']['uid']);
        $user['up_level_invitationcode']=pdo_fetchcolumn("select invitationcode from ".tablename('ewei_shop_yoxwechatbusiness_user')." where uid=".$user['up_level_invite_uid']." and uniacid=".$_W['uniacid']);
        $user['add_time']=date('Y-m-d',$user['add_time']);
        $user['end_time']=date('Y-m-d',strtotime("{$user['add_time']}+1year"));
        $this->check_data($user);
        $this->getimage($user);

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
    // public function certificate_edit(){
    //     global $_GPC, $_W;
    //     $level_id = $_GPC['id'];
    //     $yoxwechatbusiness=p("yoxwechatbusiness");
    //     $result = $yoxwechatbusiness->page_certificate_edit();

    //     ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
    //     $_GPC['isdebug']&& print_r($result);
    // }

    public function check_data($member){

        if (!$member['realname']) {
            echo json_encode(array('status'=>0,'message' =>'请到个人中心填写真实姓名'));die();
        }
        if (!$member['weixin']) {
            echo json_encode(array('status'=>0,'message' =>'请到个人中心填写微信号'));die();
        }
        if (!$member['idcard']) {
            echo json_encode(array('status'=>0,'message' =>'请到个人中心填写身份证号'));die();
        }

    }

    public function getimage($member)
    {
        // echo "<pre>";
        // print_r($member);die();
        global $_W,$_GPC;
        set_time_limit(0);
        @ini_set('memory_limit', '256M');
        $path = IA_ROOT . '/attachment/ewei_shopv2/certificate/' . $_W['uniacid'] . '/';
        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }

        $md5 = md5(json_encode(array('siteroot' => $_W['siteroot'], 'openid' => $member['openid'], 'nickname' => $member['nickname'])));
        $filename = $md5 .$member['level']. '.png';
        $filepath = $path . $filename;

        if (is_file($filepath)) {
            echo json_encode(array('status'=>1,'certificate' => $this->getImgUrl($filename)));die();
        }

        $bg=pdo_fetchcolumn("select value from ".tablename('ewei_shop_yoxwechatbusiness_setting')." where name='CERTIFICATE' and uniacid=".$_W['uniacid']);

        $bgimg = $this->createImage($_W['attachurl'] . unserialize($bg));
        $bgsize = array(imagesx($bgimg), imagesy($bgimg));
        $target = imagecreatetruecolor($bgsize[0], $bgsize[1]);
        imagecopy($target, $bgimg, 0, 0, 0, 0, $bgsize[0], $bgsize[1]);
        imagedestroy($bgimg);

        $data['color']='#ffffff';
        $data['size']='small';
        $data['left']='780';
        $data['top']='180';
        $target = $this->mergeText($target,$data, $member['invitationcode']);
        unset($data);

        $data['color']='#ffffff';
        $data['size']='small';
        $data['left']='780';
        $data['top']='199';
        $target = $this->mergeText($target,$data, $member['up_level_invitationcode']);
        unset($data);



        $data['color']='#ffffff';
        $data['size']='medium';
        $data['left']='320';
        $data['top']='250';
        $target = $this->mergeText($target,$data, $member['realname']);
        unset($data);


         $data['color']='#ffffff';
        $data['size']='medium';
        $data['left']='670';
        $data['top']='250';
        $target = $this->mergeText($target,$data, $member['weixin']);
        unset($data);

         $data['color']='#ffffff';
        $data['size']='medium';
        $data['left']='680';
        $data['top']='318';
        $target = $this->mergeText($target,$data, $member['level_name']);
        unset($data);
        $data['color']='#ffffff';
        $data['size']='medium';
        $data['left']='300';
        $data['top']='390';
        $target = $this->mergeText($target,$data, substr_replace($member['idcard'], '********', 6,8));
        unset($data);


        $data['color']='#ffffff';
        $data['size']='medium';
        $data['left']='310';
        $data['top']='470';
        $target = $this->mergeText($target,$data, $member['add_time']);
        unset($data);

        $data['color']='#ffffff';
        $data['size']='medium';
        $data['left']='670';
        $data['top']='470';
        $target = $this->mergeText($target,$data, $member['end_time']);
        unset($data);



        imagepng($target, $filepath);
        imagedestroy($target);

        echo json_encode(array('status'=>1,'certificate' => $this->getImgUrl($filename)));die();
    }

    /**
     * 获取图片路径
     * @param $filename
     * @return string
     */
    private function getImgUrl($filename)
    {
        global $_W;
        return $_W['attachurl_local'].'ewei_shopv2/certificate/' . $_W['uniacid'] . '/'.$filename;
    }

    /**
     * 获取背景图尺寸
     * @param string $imgurl
     * @return array
     */
    private function getImgSize($imgurl = '')
    {
        $size = array(640, 1008);
        if (!empty($imgurl) && function_exists('getimagesize')) {
            $imgsize = getimagesize($imgurl);

            if (is_array($imgsize)) {
                $size = $imgsize;
            }
        }

        return $size;
    }

    /**
     * 创建图片
     * @param $imgurl
     * @return resource|string
     */
    private function createImage($imgurl)
    {
        if (empty($imgurl)) {
            return '';
        }

        load()->func('communication');
        $resp = ihttp_request($imgurl);

        if (isset($resp['errno'])) {
            $urlArr = explode(':', $imgurl);

            if ($urlArr[0] == 'https') {
                $imgurl = 'http:' . $urlArr[1];
                $resp = ihttp_request($imgurl);
            }
        }

        if ($resp['code'] == 200 && !empty($resp['content'])) {
            return imagecreatefromstring($resp['content']);
        }

        $i = 0;

        while ($i < 3) {
            $resp = ihttp_request($imgurl);
            if ($resp['code'] == 200 && !empty($resp['content'])) {
                return imagecreatefromstring($resp['content']);
            }

            ++$i;
        }

        return '';
    }

    /**
     * 合并图片
     * @param $target
     * @param $data
     * @param $imgurl
     */
    private function mergeImage($target = false, $data = array(), $imgurl = '', $local = false)
    {
        if (empty($data) || empty($imgurl)) {
            return $target;
        }

        if (!$local) {
            $image = $this->createImage($imgurl);
        }
        else {
            $image = imagecreatefromstring($imgurl);
        }

        $sizes = $sizes_default = array('width' => imagesx($image), 'height' => imagesy($image));

        if ($data['type'] == 'avatar') {
            switch ($data['size']) {
            case 'big':
                $sizes = array('width' => 230, 'height' => 230);
                break;

            case 'medium':
                $sizes = array('width' => 120, 'height' => 120);
                break;

            case 'small':
                $sizes = array('width' => 90, 'height' => 90);
                break;
            }
        }
        else {
            if ($data['type'] == 'qrcode') {
                switch ($data['size']) {
                case 'good_img':
                    $sizes = array('width' => 1200, 'height' => 1200);
                    break;
                case 'big':
                    $sizes = array('width' => 350, 'height' => 350);
                    break;

                case 'medium':
                    $sizes = array('width' => 200, 'height' => 200);
                    break;

                case 'small':
                    $sizes = array('width' => 160, 'height' => 160);
                    break;
                }
            }
        }

        if ($data['style'] == 'radius' || $data['style'] == 'circle') {
            $image = $this->imageZoom($image, 4);
            $image = $this->imageRadius($image, $data['style'] == 'circle');
            $sizes_default = array('width' => $sizes_default['width'] * 4, 'height' => $sizes_default['height'] * 4);
        }

        imagecopyresampled($target, $image, intval($data['left']) * 2, intval($data['top']) * 2, 0, 0, $sizes['width'], $sizes['height'], $sizes_default['width'], $sizes_default['height']);
        imagedestroy($image);
        return $target;
    }

    /**
     * 图片缩放
     * @param bool $image
     * @param int $zoom
     * @return resource
     */
    private function imageZoom($image = false, $zoom = 2)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $target = imagecreatetruecolor($width * $zoom, $height * $zoom);
        imagecopyresampled($target, $image, 0, 0, 0, 0, $width * $zoom, $height * $zoom, $width, $height);
        imagedestroy($image);
        return $target;
    }

    /**
     * 合并文字
     * @param $target
     * @param $data
     * @param $text
     */
    private function mergeText($target = false, $data = array(), $text = '')
    {
        if (empty($data) || empty($text)) {
            return $target;
        }

        $font = IA_ROOT . '/addons/ewei_shopv2/static/fonts/msyh.ttf';

        if (!is_file($font)) {
            return $target;
        }

        $colors = $this->hex2rgb($data['color']);
        $color = imagecolorallocate($target, $colors['red'], $colors['green'], $colors['blue']);

        switch ($data['size']) {
        case 'big':
            $fontsize = 120;
            break;
        case 'little_big':
            $fontsize = 55;
            break;
        case 'medium':
            $fontsize = 40;
            break;

        case 'small':
            $fontsize = 20;
            break;
        }

        mb_internal_encoding("UTF-8"); // 设置编码
        if ($data['size']=='little_big') {
            $width=1200;
        }else{
            $width=700;
        }
        $content = "";
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        for ($i=0;$i<mb_strlen($text);$i++) {
            $letter[] = mb_substr($text, $i, 1);
        }

        foreach ($letter as $l) {

            $teststr = $content." ".$l;
            // $fontBox = imagettfbbox($fontSize, 0, $font, $teststr);
            $textbox = imagettfbbox($fontsize, 0, $font, $teststr);
            // $textbox = imagettfbbox($fontsize, 1, $font, $teststr);
            // $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($textbox[2] > $width) && ($content !== "")) {
                $content .= "\n";
                $yes=1;
            }
            $content .= $l;
        }
        // if ($yes==1) {
        //     echo $content;die();
        //     # code...
        // }

        $textwidth = $textbox[4] - $textbox[6];
        $left = intval($data['left']) * 2;

        if ($data['align'] == 'center') {
            $left = imagesx($target) / 2 - $textwidth / 2;
        }

        imagettftext($target, $fontsize, 0, $left, intval($data['top']) * 2 + $fontsize * 1.5, $color, $font, $content);
        return $target;
    }

    /**
     * hex转rgb
     * @param $colour
     * @return array|bool
     */
    private function hex2rgb($colour)
    {
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
        }

        if (strlen($colour) == 6) {
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        }
        else if (strlen($colour) == 3) {
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        }
        else {
            return false;
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }


}
?>