<?php
/**
 * 视频图文列表
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
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    $this->article_list();
	}
    public function article(){
        global $_GPC, $_W;
        $_GPC['isarticle']=1;
        
        $this->article_list();
    }
    public function video(){
        global $_GPC, $_W;
        $_GPC['isvideo']=1;
        
        $this->article_list();
    }
    public function article_list(){
        global $_GPC, $_W;
        
        $yoxarticlevideo=p("yoxarticlevideo");
        $result=$yoxarticlevideo->page_article_list();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
	public function edit() 
	{
	    global $_GPC, $_W;
	    
	    $yoxarticlevideo=p("yoxarticlevideo");
	    $result=$yoxarticlevideo->page_article_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    $yoxarticlevideo=p("yoxarticlevideo");
	    $result=$yoxarticlevideo->action_article_delete();
	    show_json(1,'删除成功');
	}
	/**
	 * 列表
	 */
	public function cate_list(){
	    global $_GPC, $_W;
	    
	    $yoxarticlevideo=p("yoxarticlevideo");
	    $result=$yoxarticlevideo->page_cate_list();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
}