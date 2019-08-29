<?php
/**
 * 人脸识别模块微站定义
 *
 * @author TUGEZIYUAN
 * @url http://www.5kym.cn
 */
defined('IN_IA') or exit('Access Denied');

class ruike_faceModuleSite extends WeModuleSite {


	public function doWebRules() {
		//这个操作被定义用来呈现 规则列表
	}
	public function doMobileBanner() {
		//这个操作被定义用来呈现 微站首页导航图标
	}
	public function doMobileCenter() {
		//这个操作被定义用来呈现 微站个人中心导航
	}
	public function doMobileFuncts() {
		//这个操作被定义用来呈现 微站快捷功能导航
	}
	public function doWebAlone() {
		//这个操作被定义用来呈现 微站独立功能
	}

}