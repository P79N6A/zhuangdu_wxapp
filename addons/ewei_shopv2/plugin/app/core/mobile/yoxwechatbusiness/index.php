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
require(EWEI_SHOPV2_PLUGIN . "app/core/page_mobile.php");
class Index_EweiShopV2Page extends AppMobilePage 
{
    public function main()
    {
        global $_GPC, $_W;
        $uid_openid = $_W['fans']['uid']?$_W['fans']['uid']:$_W['openid'];
        $yoxwechatbusiness=p("yoxwechatbusiness");
        
        //设置微商用户信息
        $data=$yoxwechatbusiness->user_info($_W['uniacid'],$uid_openid);
        
        $result['status']=1;
        $result['message']='ok';
        $result['data']=$data;
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
}
?>