<?php
/**
 * 态度
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
class Attitude_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    $this->attitude_list();
	}
	/**
	 * 列表
	 */
    public function attitude_list(){
        global $_GPC, $_W;
        
        $yoxfriendscircle=p("yoxfriendscircle");
        $result=$yoxfriendscircle->page_attitude_list();
        
// 	    pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
    }
    /**
     * 信息
     */
	public function edit() 
	{
	    global $_GPC, $_W;

	    $yoxfriendscircle=p("yoxfriendscircle");
	    $result=$yoxfriendscircle->page_attitude_edit();
	    //print_r($result);
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    $yoxfriendscircle=p("yoxfriendscircle");
	    $result=$yoxfriendscircle->action_attitude_delete();
	    
	    //pdo_debug();die();
	    show_json(1,'删除成功');
	}
}
?>