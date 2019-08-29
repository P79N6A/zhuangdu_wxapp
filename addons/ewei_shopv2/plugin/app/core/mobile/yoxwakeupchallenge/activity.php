<?php
/**
 * 早起挑战
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
class Activity_EweiShopV2Page extends AppMobilePage 
{
    public function main()
    {
        $this->acitivit_list();
    }
    /**
     * 列表
     */
    public function acitivit_list(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_activity_list();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    /**
     * 信息
     */
    public function activity_edit(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_activity_edit();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function activity_delete(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_activity_delete();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function activity_user_list(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_activity_user_list();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function activity_user_edit(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_activity_user_edit();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function activity_checkin_list(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_checkin_list();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function checkin(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_checkin();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function activity_user_count(){
        global $_GPC, $_W;
        $yoxwakeupchallenge=p("yoxwakeupchallenge");
        $result=$yoxwakeupchallenge->page_activity_user_count();
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
}
?>