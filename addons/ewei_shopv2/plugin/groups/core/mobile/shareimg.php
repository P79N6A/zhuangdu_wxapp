<?php
require __DIR__ . '/base.php';
class Shareimg_EweiShopV2Page extends Base_EweiShopV2Page
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

        $md5 = md5(json_encode(array('siteroot' => $_W['siteroot'], 'openid' => $member['openid'], 'nickname' => $member['nickname'], 'bg' => $poster['bgimg'], 'data' => $poster['data'], 'version' => 1)));
        $filename = $md5 .$good['id']. '.png';
        $filepath = $path . $filename;

        if (is_file($filepath)) {
            app_json(array('thumb' => $this->getImgUrl($filename)));
        }
        $bgimg = $this->createImage(tomedia(IA_ROOT . "/attachment/ewei_shopv2/groups/share_bg.jpg"));

        $bgsize = array(imagesx($bgimg), imagesy($bgimg));
        $target = imagecreatetruecolor($bgsize[0], $bgsize[1]);
        imagecopy($target, $bgimg, 0, 0, 0, 0, $bgsize[0], $bgsize[1]);
        imagedestroy($bgimg);

        $qrcode = $this->getQR($poster, $member);
        $data['type']='qrcode';
        $target = $this->mergeImage($target, $data, $qrcode, true);

        $data['type']='avatar';
        $avatar = preg_replace('/\\/0$/i', '/96', $member['avatar']);
        $target = $this->mergeImage($target, $data, $avatar);




        imagepng($target, $filepath);
        $width_thumb = $bgsize[0];
        $height_thumb = $bgsize[1];
        $final_width = 640;
        $final_height = round($final_width * $height_thumb / $width_thumb);
        $target_thumb = imagecreatetruecolor($final_width, 1135);
        imagecopyresized($target_thumb, $target, 0, 0, 0, 0, $final_width, $final_height, $width_thumb, $height_thumb);
        imagepng($target_thumb, $filepath_thumb);
        imagedestroy($target_thumb);
        imagedestroy($target);
        app_json(array('thumb' => $this->getImgUrl($filename_thumb), 'poster' => $this->getImgUrl($filename)));
    }

    /**
     * 获取图片路径
     * @param $filename
     * @return string
     */
    private function getImgUrl($filename)
    {
        global $_W;
        return $_W['attachurl_local'].'ewei_shopv2/yoxwechatbusiness/invite_qrcode/' . $_W['uniacid'] . '/'.$filename;
    }

    /**
     * 获取二维码
     * @param array $poster
     * @param array $member
     * @return string
     */
    private function getQR(){
        if (empty($member)) {
            return '';
        }

        $image = p('app')->getCodeUnlimit(array('scene' => 'mid=' . $member['id'], 'page' => 'pages/index/index'));
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
                $sizes = array('width' => 150, 'height' => 150);
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
                case 'big':
                    $sizes = array('width' => 240, 'height' => 240);
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
            $fontsize = 32;
            break;

        case 'medium':
            $fontsize = 28;
            break;

        case 'small':
            $fontsize = 24;
            break;
        }

        $textbox = imagettfbbox($fontsize, 0, $font, $text);
        $textwidth = $textbox[4] - $textbox[6];
        $left = intval($data['left']) * 2;

        if ($data['align'] == 'center') {
            $left = imagesx($target) / 2 - $textwidth / 2;
        }

        imagettftext($target, $fontsize, 0, $left, intval($data['top']) * 2 + $fontsize * 1.5, $color, $font, $text);
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
