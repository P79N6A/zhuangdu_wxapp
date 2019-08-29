<?php
/**
 * 微商代理招募
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
class Agent_EweiShopV2Page extends AppMobilePage
{
	public function main() 
	{
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $result=$yoxwechatbusiness->page_agent();
	    // var_dump($_W);
	    ($_W['isajax'] || $_GPC['isajax']) && app_json($result);
	    $_GPC['isdebug']&& print_r($result) && die('Yoper');
	}

}
?>