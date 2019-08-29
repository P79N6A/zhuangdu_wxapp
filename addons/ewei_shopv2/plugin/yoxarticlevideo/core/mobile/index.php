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
class Index_EweiShopV2Page extends PluginMobilePage 
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
        
        include($this->template('yoxarticlevideo/index'));
    }
	public function edit() 
	{
	    global $_GPC, $_W;
	    
	    $yoxarticlevideo=p("yoxarticlevideo");
	    $result=$yoxarticlevideo->page_article_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template('yoxarticlevideo/edit'));
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
	    include($this->template('yoxarticlevideo/cate_list'));
	}
	public function cate_delete(){
	    $yoxarticlevideo=p("yoxarticlevideo");
	    $result=$yoxarticlevideo->action_cate_delete();
	    show_json(1,'删除成功');
	}
	/**
	 * 排序
	 */
	public function displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxarticlevideo_article") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxarticlevideo_article", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxarticlevideo_article.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
	    }
	    show_json(1);
	}
	/**
	 * 前端是否显示
	 */
	public function status()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxarticlevideo_article") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxarticlevideo_article", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxhotsearch.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 精选
	 */
	public function is_featured(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxarticlevideo_article") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxarticlevideo_article", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxarticlevideo_article.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	public function setting(){
	    include($this->template());
	}
}
?>