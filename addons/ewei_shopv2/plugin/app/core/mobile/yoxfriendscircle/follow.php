<?php
/**
 * 关注
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
class Follow_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    $this->follow_list();
	}
    public function follow_list(){
        global $_GPC, $_W;
        
        $yoxfriendscircle=p("yoxfriendscircle");
        $result=$yoxfriendscircle->page_follow_list();
        
        // 	    pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
	public function edit() 
	{
	    global $_GPC, $_W;
	    
	    $yoxfriendscircle=p("yoxfriendscircle");
	    $result=$yoxfriendscircle->page_follow_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	}
	/**
	 * 关注和取消关注
	 */
	public function follow(){
	    global $_GPC, $_W;
	    
	    $yoxfriendscircle=p("yoxfriendscircle");
	    $yoxfriendscircle->action_follow();
	    show_json(1);
	}
}
?>