<?php
/**
 * 微商首页
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
class Index_EweiShopV2Page extends PluginMobilePage 
{
    //
	public function main() 
	{
	    global $_GPC, $_W;
	    $uid_openid = $_W['fans']['uid']?$_W['fans']['uid']:$_W['openid'];
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    
	    //设置微商用户信息
	    $yoxwechatbusiness->set_user();
	    $data=$yoxwechatbusiness->user_info($_W['uniacid'],$uid_openid);
	    
	    $result['status']=1;
	    $result['message']='ok';
	    $result['data']=$data;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    include($this->template('yoxwechatbusiness/index'));
	}

}
?>