<?php
/**
 * 微商银行卡
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
class Bank_card_EweiShopV2Page extends PluginMobilePage 
{
    //列表
	public function main() 
	{
	    $this->level_list();
	}
	public function level_list(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_bank_card_list();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwechatbusiness/bank_card/bank_card_list'));
	}
	public function bank_card_edit(){
	    global $_GPC, $_W;
	    $level_id = $_GPC['id'];
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_bank_card_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxwechatbusiness/bank_card/bank_card_info'));
	}
}
?>