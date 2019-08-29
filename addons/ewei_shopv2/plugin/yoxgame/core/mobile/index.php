<?php
/**
 * diy自定义页面
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
	    $this->game_list();
	}
    public function game_list(){
        global $_GPC, $_W;
        
        $yoxdiy=p("yoxdiy");
        $result=$yoxdiy->page_game_list();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        
        include($this->template('yoxgame/game_list'));
    }
	public function game() 
	{
	    global $_GPC, $_W;
	    
	    $yoxdiy=p("yoxdiy");
	    $result=$yoxdiy->page_game_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template('yoxgame/game'));
	}
	public function qqppp(){
	    include($this->template('yoxgame/qqppp'));
	}

}
?>