<?php
/**
 * 颜值测试
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
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    $this->face();
	}
    public function face() 
	{
	    global $_W,$_GPC;
	    load()->func('tpl');
	    
	    include($this->template('yoxface/face'));
	}
	public function face_result(){
	    global $_GPC, $_W;
	    //error_reporting(E_ALL);
	    //ini_set("display_errors",'ON');
	    $yoxface=p("yoxface");
	    $result=$yoxface->page_face_result();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template('yoxface/face_result'));
	}

}
?>