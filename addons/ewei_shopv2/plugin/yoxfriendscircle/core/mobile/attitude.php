<?php
/**
 * 态度
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
class Attitude_EweiShopV2Page extends PluginMobilePage 
{
    //列表
	public function main() 
	{
	    $this->attitude_list();
	}
	/**
	 * 列表
	 */
    public function attitude_list(){
        global $_GPC, $_W;
        
        $yoxfriendscircle=p("yoxfriendscircle");
        $result=$yoxfriendscircle->page_attitude_list();
        
// 	    pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
        include($this->template('yoxfriendscircle/attitude/attitude_list'));
    }
    /**
     * 信息
     */
	public function edit() 
	{
	    global $_GPC, $_W;
	    
	    $yoxfriendscircle=p("yoxfriendscircle");
	    $result=$yoxfriendscircle->page_attitude_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template('yoxfriendscircle/attitude/edit'));
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    $yoxfriendscircle=p("yoxfriendscircle");
	    $result=$yoxfriendscircle->action_attitude_delete();
	    
	    //pdo_debug();die();
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
	    $item = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxfriendscircle_attitude") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_attitude", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxfriendscircle_attitude.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxfriendscircle_attitude") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_attitude", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxfriendscircle_attitude.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>