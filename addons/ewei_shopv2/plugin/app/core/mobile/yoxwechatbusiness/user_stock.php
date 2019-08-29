<?php
/**
 * 微商库存
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
class User_stock_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    $this->user_stock_list();
	}
	public function user_stock_list(){
	    global $_GPC, $_W;
	    
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_user_stock_list();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && app_json($result);
	    $_GPC['isdebug']&& print_r($result) && die();
	}
	
}
?>