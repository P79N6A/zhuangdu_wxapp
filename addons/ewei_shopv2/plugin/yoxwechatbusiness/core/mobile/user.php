<?php
/**
 * 微商用户
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
class User_EweiShopV2Page extends PluginMobilePage 
{
    //列表
	public function main() 
	{
	    $this->my_down_level_user();
	}
	public function my_info(){
	    global $_GPC, $_W;
	    
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_user_info();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template('yoxwechatbusiness/user/my_info'));
	}
	public function member_edit(){
	    global $_GPC, $_W;
	    
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_member_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template('yoxwechatbusiness/user/member_edit'));
	}
	/**
	 * 我的下等级
	 */
    public function my_down_level_user(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_my_down_level_user();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
        
        include($this->template('yoxwechatbusiness/user/my_down_level_user'));
    }
    /**
     * 我邀请的
     */
    public function my_invite_user(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_my_invite_user();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
        
        include($this->template('yoxwechatbusiness/user/my_invite_user'));
    }
    /**
     * 邀请我的
     */
    public function invited_me(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_invite_me();
        
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result);
    }
    public function my_up_level_user(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_my_up_level_user();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
        
        include($this->template('yoxwechatbusiness/user/my_up_level_user'));
    }
    /**
     * 用户列表
     */
    public function user_list(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_user_list();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
        include($this->template('yoxwechatbusiness/user/user_list'));
    }
}
?>