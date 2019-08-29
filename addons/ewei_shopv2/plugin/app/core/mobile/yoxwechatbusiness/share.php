<?php
/**
 * 分享活动
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
class Share_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	}
	public function share_activity(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_share_activity($_W['uniacid']);
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	}
}
?>