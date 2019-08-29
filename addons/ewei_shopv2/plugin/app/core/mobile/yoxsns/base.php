<?php
/**
 * 全民社区
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注个人公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
 //报告运行时错误
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
//error_reporting(E_ALL);
//ini_set("display_errors","On");
 if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
require(EWEI_SHOPV2_PLUGIN . "app/core/page_mobile.php");
class Base_EweiShopV2Page extends AppMobilePage 
{
    protected $islogin=0;
    public $model;
	public function __construct() 
	{
		parent::__construct();
		global $_W;
		$this->model = p("sns");
		$this->model->checkMember();
		$this->islogin = empty($_W['openid']) ? 0 : 1;
	}
}
