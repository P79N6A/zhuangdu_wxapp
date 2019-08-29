<?php  
/**
 * 用户指定升级商品
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
class User_upgrade_goods_EweiShopV2Page extends AppMobilePage
{
	public function main() 
	{
	    $this->user_upgrade_goods_list();
	}
	public function user_upgrade_goods_list(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_user_upgrade_goods_list();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	}
	public function batch_add_user_upgrade_goods(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_batch_add_user_upgrade_goods();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	}
	public function user_upgrade_goods_delete(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_user_upgrade_goods_delete();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	}
}
