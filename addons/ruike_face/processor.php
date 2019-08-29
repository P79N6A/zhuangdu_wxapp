<?php
/**
 * 人脸识别模块处理程序
 *
 * @author TUGEZIYUAN
 * @url http://www.5kym.cn
 */
defined('IN_IA') or exit('Access Denied');

class ruike_faceModuleProcessor extends WeModuleProcessor {
	public function respond() {
        global $_GPC, $_W;
        $openid = $this->message['from'];
        $content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
        return $this->respNews(array(
            'Title' => '人脸识别',
            'Description' =>'上传一张照片测试年龄',
            'PicUrl' => $_W['siteroot'] . 'addons/ruike_face/template/img/face.jpg',
            'Url' => $this->createMobileUrl('face'),
        ));
	}
}