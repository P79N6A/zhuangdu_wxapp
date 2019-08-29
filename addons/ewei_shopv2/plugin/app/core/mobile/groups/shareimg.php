<?php
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Shareimg_EweiShopV2Page extends AppMobilePage
{
    public function main()
    {
        global $_W,$_GPC;
        $this->getimage();
    }
    /**
     * 获取图片
     */
    public function getimage()
    {
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            app_error(AppError::$ParamsError);
        }
        $good=pdo_get('ewei_shop_groups_goods',array('id'=>$id));
        $member = $this->member;
        if (empty($member)) {
            app_error(AppError::$UserLoginFail);
        }

        set_time_limit(0);
        @ini_set('memory_limit', '256M');
        $path = IA_ROOT . '/attachment/ewei_shopv2/groups/invite_qrcode/' . $_W['uniacid'] . '/';
        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }

        $md5 = md5(json_encode(array('siteroot' => $_W['siteroot'], 'openid' => $member['openid'], 'nickname' => $member['nickname'])));
        $filename = $md5 .$good['id']. '.png';
        $filepath = $path . $filename;

        if (is_file($filepath)) {
            echo json_encode(array('poster' => $this->getImgUrl($filename)));die();
        }
        $bgimg = $this->createImage($_W['siteroot'] . "attachment/ewei_shopv2/groups/share_bg.jpg");
        $bgsize = array(imagesx($bgimg), imagesy($bgimg));
        $target = imagecreatetruecolor($bgsize[0], $bgsize[1]);
        imagecopy($target, $bgimg, 0, 0, 0, 0, $bgsize[0], $bgsize[1]);
        imagedestroy($bgimg);
        //小程序码
        $qrcode = $this->getQR($id);
        $data['type']='qrcode';
        $data['size']='big';
        $data['left']='525';
        $data['top']='980';
        $target = $this->mergeImage($target, $data, $qrcode, true);
        unset($data);
        //海报主图
        $good_img = file_get_contents($_W['siteroot'] . "attachment/".$good['poster']);
        $data['type']='qrcode';
        $data['size']='good_img';
        $data['left']='98';
        $data['top']='340';
        $target = $this->mergeImage($target, $data, $good_img, true);
        unset($data);
        //分享者头像
        $data['type']='avatar';
        $data['style']='circle';
        $data['size']='big';
        $data['left']='100';
        $data['top']='180';
        $avatar = preg_replace('/\\/0$/i', '/96', $member['avatar']);
        $target = $this->mergeImage($target, $data, $avatar);
        unset($data);
        //商品名字
        $data['color']='black';
        $data['size']='small';
        $data['left']='98';
        $data['top']='1150';
        $target = $this->mergeText($target,$data, $good['title']);
        unset($data);
        //价格符号
        $data['color']='#fd2d6b';
        $data['size']='medium';
        $data['left']='98';
        $data['top']='1000';
        $target = $this->mergeText($target,$data, '￥');
        unset($data);
        //价格
        $data['color']='#fd2d6b';
        $data['size']='big';
        $data['left']='150';
        $data['top']='960';
        $target = $this->mergeText($target,$data, $good['groupsprice']);
        //昵称
        $data['color']='black';
        $data['size']='little_big';
        $data['left']='300';
        $data['top']='185';
        $target = $this->mergeText($target,$data,'咔咔团—'.$member['nickname']);

        $data['color']='black';
        $data['size']='little_big';
        $data['left']='300';
        $data['top']='240';
        $target = $this->mergeText($target,$data,'推荐给你一个好物');

        imagepng($target, $filepath);
        imagedestroy($target);

        echo json_encode(array('poster' => $this->getImgUrl($filename)));die();
    }

    /**
     * 获取图片路径
     * @param $filename
     * @return string
     */
    private function getImgUrl($filename)
    {
        global $_W;
        return $_W['attachurl_local'].'ewei_shopv2/groups/invite_qrcode/' . $_W['uniacid'] . '/'.$filename;
    }

    /**
     * 获取二维码
     * @param array $poster
     * @param array $member
     * @return string
     */
    private function getQR($id){
        if (empty($id)) {
            return '';
        }
        $image = p('app')->getCodeUnlimit(array('scene' => 'id=' . $id, 'page' => 'pages/groups/goods/index','is_hyaline'=>true));
        return $image;
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
     * 图片圆角
     * @param bool $target
     * @param bool $circle
     * @return resource
     */
    private function imageRadius($target = false, $circle = false)
    {
        $w = imagesx($target);
        $h = imagesy($target);
        $w = min($w, $h);
        $h = $w;
        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $radius = $circle ? $w / 2 : 20;
        $r = $radius;
        $x = 0;

        while ($x < $w) {
            $y = 0;

            while ($y < $h) {
                $rgbColor = imagecolorat($target, $x, $y);
                if ($radius <= $x && $x <= $w - $radius || $radius <= $y && $y <= $h - $radius) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
                else {
                    $y_x = $r;
                    $y_y = $r;

                    if (($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y) <= $r * $r) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }

                    $y_x = $w - $r;
                    $y_y = $r;

                    if (($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y) <= $r * $r) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }

                    $y_x = $r;
                    $y_y = $h - $r;

                    if (($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y) <= $r * $r) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }

                    $y_x = $w - $r;
                    $y_y = $h - $r;

                    if (($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y) <= $r * $r) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }
                }

                ++$y;
            }

            ++$x;
        }

        return $img;
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
            $fontsize = 60;
            break;

        case 'small':
            $fontsize = 40;
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
