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
	    $this->template_list();
	}
    public function template_list(){
        global $_GPC, $_W;
        
        $yoxdiy=p("yoxdiy");
        $result=$yoxdiy->page_template_list();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        
        include($this->template('yoxdiy/template_list'));
    }
	public function template_edit() 
	{
	    global $_GPC, $_W;
	    
	    $yoxdiy=p("yoxdiy");
	    $result=$yoxdiy->page_template_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template('yoxdiy/template_edit'));
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    $yoxdiy=p("yoxdiy");
	    $result=$yoxdiy->action_template_delete();
	    show_json(1,'删除成功');
	}
}
?>