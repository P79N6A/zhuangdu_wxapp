<?php
/**
 * 微商银行卡
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
class Securitycode_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    $this->securitycode_list();
	}
	public function securitycode_list(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_securitycode_list();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
	public function securitycode_edit(){
	    global $_GPC, $_W;
	    $level_id = $_GPC['id'];
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_securitycode_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
	public function order_goods_securitycode_list(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_order_goods_securitycode_list();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
}
?>