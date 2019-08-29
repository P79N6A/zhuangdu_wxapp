<?php
/**
 * 微商转单
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
class Order_EweiShopV2Page extends PluginWebPage 
{
    /**
     * 列表
     */
	public function main() 
	{
	    global $_GPC, $_W;
	    $to_uid=$_GPC['to_uid'];
	    $from_uid=$_GPC['from_uid'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    ($where = " transfer_order.uniacid = :uniacid ") && ($replace=array(':uniacid'=>$uniacid));
	    $to_uid && ($where.= " AND transfer_order.to_uid = :to_uid ") && ($replace+=array(':to_uid'=>$to_uid));
	    $from_uid && ($where.= " AND transfer_order.from_uid = :from_uid ") && ($replace+=array(':from_uid'=>$from_uid));
	    $field="shop_order.price,shop_order.`status`,shop_order.addressid,shop_order.address,shop_order.refundid,from_members.nickname AS from_nickname,from_members.avatar AS from_avatar,to_members.nickname AS to_nickname,to_members.avatar AS to_avatar";
	    $list = pdo_fetchall("SELECT *,FROM_UNIXTIME(add_time) AS add_time_format,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order ".
	        " LEFT JOIN " . tablename('mc_members') . " from_members ON from_members.uid=transfer_order.from_uid ".
	        " LEFT JOIN " . tablename('mc_members') . " to_members ON to_members.uid=transfer_order.to_uid ".
	        " LEFT JOIN " . tablename('ewei_shop_order') . " shop_order ON shop_order.id=transfer_order.order_id ".
	        " WHERE $where ORDER BY transfer_order.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax'])   && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&& die();
	    
	    include($this->template('yoxwechatbusiness/order/index'));
	}
	/*
	 * 删除
	 */
	public function delete(){
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_transfer_order") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_transfer_order', array('id' => $id,'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	/**
	 * 物流单
	 */
	public function logistics_list(){
	    global $_GPC, $_W;
	    
	    $order_id=$_GPC['order_id']?$_GPC['order_id']:144;
	    $uid=$_GPC['uid'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	     
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	     
	    ($where = " order_goods.uniacid = :uniacid ") && ($replace=array(':uniacid'=>$uniacid));
	    $uid && ($where.= " AND order_goods.uid = :uid ") && ($replace+=array(':uid'=>$uid));
	    $order_id && ($where.= " AND order_goods.orderid = :orderid ") && ($replace+=array(':orderid'=>$order_id));
	    $field="shop_order.price,shop_order.`status`,shop_order.addressid,shop_order.address,shop_order.refundid,members.nickname AS nickname,members.avatar AS avatar,shop_goods.title  AS goods_title";
	    $list = pdo_fetchall("SELECT order_goods.*,FROM_UNIXTIME(order_goods.createtime) AS add_time_format,$field FROM " . tablename('ewei_shop_order_goods') . " order_goods ".
	        " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=order_goods.uid ".
	        " LEFT JOIN " . tablename('ewei_shop_order') . " shop_order ON shop_order.id=order_goods.orderid ".
	        " LEFT JOIN " . tablename('ewei_shop_goods') . " shop_goods ON shop_goods.id=order_goods.goodsid ".
	        " WHERE $where ORDER BY order_goods.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_order_goods') . " order_goods WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    foreach($list as &$info){
	        $info['address']=unserialize($info['address']);
	    }
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	     
	     print_r($result);
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax'])   && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&& die();
	    
	    
	    include($this->template('yoxwechatbusiness/order/logistics_list'));
	}
}
?>