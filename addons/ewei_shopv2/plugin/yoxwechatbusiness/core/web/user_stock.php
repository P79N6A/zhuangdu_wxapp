<?php
/**
 * 微商库存
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
class User_stock_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{

	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;

	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    $_GPC['uid'] && ($uid=$_GPC['uid']);

	    $uniacid && ($where = " user_stock.uniacid = :uniacid ") && ($replace=array(':uniacid'=>$uniacid));
	    $uid && ($where.=" AND user_stock.uid=:uid ") && ($replace+=array(':uid'=>$uid));
	    $goods_field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title";
	    $list = pdo_fetchAll("SELECT user_stock.*,member.nickname,member.avatar,{$goods_field} FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock".
	        " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=user_stock.uid ".
	        " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=user_stock.goods_id ".
	        " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=user_stock.goods_option_id ".
	        " WHERE  $where ", $replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $psize);

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;

	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template());
	}
	public function edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $goods_id = $_GPC['goods_id'];
	    $goods_option_id = $_GPC['goods_option_id'];
	    $stock = $_GPC['stock'];
	    $user_sales = $_GPC['user_sales'];

	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($stock!='' || $user_sales!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $goods_id && $data['goods_id']=$goods_id;
	        $goods_option_id&& $data['goods_option_id']=$goods_option_id;
	        $stock&& $data['stock']=$stock;
	        $user_sales&& $data['user_sales']=$user_sales;

	        if(!$id){
	            pdo_insert('ewei_shop_yoxwechatbusiness_user_stock', $data);
	        }
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_user_stock", $data, array("id" => $id));

	        show_json(1, "成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $goods_field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title";
	    $info = pdo_fetch("SELECT user_stock.*,member.nickname,member.avatar,$goods_field FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock ".
	        " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=user_stock.uid ".
	        " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=user_stock.goods_id ".
	        " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=user_stock.goods_option_id ".
	        " WHERE user_stock.id = :id AND user_stock.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

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
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_user_stock") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_user_stock', array('id' => $id,'uniacid'=>$uniacid));
	    }
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_user_stock") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user_stock", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("yoxwechatbusiness.user_stock.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_user_stock") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user_stock", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.user_stock.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
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
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_user_stock") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user_stock", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.user_stock.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
}
?>