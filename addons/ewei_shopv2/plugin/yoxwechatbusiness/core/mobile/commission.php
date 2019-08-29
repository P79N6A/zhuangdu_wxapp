<?php
/**
 * 微商佣金
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
class Commission_EweiShopV2Page extends PluginMobilePage
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $result = $yoxwechatbusiness->page_commission();
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template());
	}
	/**
	 * 编辑
	 */
	public function edit() 
	{
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $result = $yoxwechatbusiness->page_commission_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug'] && print_r($result) && die();

		include($this->template());
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwechatbusiness_commission', array('id' => $id,'uniacid'=>$uniacid));
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_commission", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("yoxwechatbusiness_commission.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_commission", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.commission.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 0未确认，1确认有效，2确认无效
	 */
	public function is_effective(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_commission", array( "is_effective" => intval($_GPC["is_effective"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.commission.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "有效" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
}
?>