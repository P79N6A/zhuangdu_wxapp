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
class Index_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    header("location:/web/index.php?c=site&a=entry&m=ewei_shopv2&do=web&r=yoxwakeupchallenge.activity");
	}
}