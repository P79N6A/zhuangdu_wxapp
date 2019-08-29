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
class Commission_EweiShopV2Page extends PluginWebPage 
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
	    $_GPC['commission_uid'] && ($commission_uid=$_GPC['commission_uid']);
	    
	    $uniacid && ($where = " commission.uniacid = :uniacid ") && ($replace=array(':uniacid'=>$uniacid));
	    $uid && $where.=" AND commission.uid=:uid" && ( $replace+=array(':uid'=>$uid));
	    $commission_uid && $where.=" AND commission.commission_uid=:commission_uid" && ( $replace+=array(':commission_uid'=>$commission_uid));
	    $field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title,transfer_order.from_uid,transfer_order.to_uid";
	    $list = pdo_fetchAll("SELECT commission.*,CASE commission.`status` WHEN 1 THEN '已返佣' WHEN 0 THEN '未分佣' END AS status_name,member.nickname,member.avatar,member2.nickname AS commission_nickname,member2.avatar AS commission_avatar,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission ".
	        " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=commission.uid ".
	        " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=commission.commission_uid ".
	        " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=commission.goods_id ".
	        " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=commission.goods_option_id ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order ON transfer_order.order_id=commission.order_id ".
	        " WHERE $where ", $replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $psize);
	    
	    //佣金利润总额
	    $total_commission=pdo_fetchcolumn("SELECT sum(commission.commission) as total_commission FROM ".tablename('ewei_shop_yoxwechatbusiness_commission')." commission WHERE $where",$replace);
	    //未分到余额
	    $commission_uid && $total_commission_status_0 = pdo_fetchcolumn('SELECT sum(commission.commission) as total_commission_status_0 FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE commission_uid=:commission_uid AND `status`=:status",array(':commission_uid'=>$commission_uid,':status'=>0));
	    //已分到余额
	    $commission_uid && $total_commission_status_1 = pdo_fetchcolumn('SELECT sum(commission.commission) as total_commission_status_1 FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE commission_uid=:commission_uid AND `status`=:status",array(':commission_uid'=>$commission_uid,':status'=>1));
	    //pdo_debug();
	    $result['status']=1;
	    $result['total_commission']=$total_commission;
	    $commission_uid && $result['total_commission_status_0']=$total_commission_status_0;
	    $commission_uid && $result['total_commission_status_1']=$total_commission_status_1;
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
	    $status = $_GPC['status'];
	    $is_effective = $_GPC['is_effective'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    
	    $field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title";
	    $info = pdo_fetch("SELECT commision.*,CASE commision.`status` WHEN 1 THEN '已返佣' WHEN 0 THEN '未分佣' END AS status_name,member.nickname,member.avatar,member2.nickname AS commission_nickname,member2.avatar AS commission_avatar,{$field} FROM " . tablename('ewei_shop_yoxwechatbusiness_commission') . " commision ".
	   	    " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=commision.uid ".
	   	    " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=commision.commission_uid ".
	   	    " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=commision.goods_id ".
	   	    " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=commision.goods_option_id ".
	   	    " WHERE commision.id = :id AND commision.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    if($status!='' || $is_effective!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $status!='' && $data['status']=$status;
	        $is_effective!=''&& $data['is_effective']=$is_effective;
	        
	        if(!$id){
	            $message="添加";
// 	            pdo_insert('ewei_shop_yoxwechatbusiness_commission', $data);
	        }
	        if($id){
	            $message="修改";
	            $is_update=pdo_update("ewei_shop_yoxwechatbusiness_commission", $data, array("id" => $id));
	        }
	        
	        //分佣到余额
	        if($is_update && $info['status']==0 && $status==1){
	            
	            load()->model('mc');
	            mc_credit_update($info['commission_uid'], 'credit2', $info['commission'], array(0, '确认分佣到余额'));
	            $message='已分佣到用户余额，用户可申请提现';
	        }
	        
	        show_json(1, $message."成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&&die();

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
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_commission', array('id' => $item['id'],'uniacid'=>$uniacid));
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