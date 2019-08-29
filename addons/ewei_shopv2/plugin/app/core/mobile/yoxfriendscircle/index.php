<?php
/**
 * 朋友圈
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
require(EWEI_SHOPV2_PLUGIN . "app/core/page_mobile.php");
class Index_EweiShopV2Page extends AppMobilePage 
{
    public function main()
    {
        $this->friendscircle_list();
    }
    /**
     * 列表
     */
    public function friendscircle_list(){
        global $_GPC, $_W;
        $yoxfriendscircle=p("yoxfriendscircle");
        $result=$yoxfriendscircle->page_friendscircle_list();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    /**
     * 信息
     */
    public function edit(){
        global $_GPC, $_W;
        $yoxfriendscircle=p("yoxfriendscircle");
        $result=$yoxfriendscircle->page_friendscircle_edit();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    
}
?>