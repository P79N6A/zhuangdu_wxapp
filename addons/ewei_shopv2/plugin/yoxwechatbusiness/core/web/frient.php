<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Frient_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    $this->frient_list();
	}
    public function frient_list(){
        global $_GPC,$_W;
        
        // 	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
        // 	    $keyword = $_GPC['keyword'];
        $remark_name  = $_GPC['remark_name'];
        $status  = !empty($_GPC['status']) ? $_GPC['status'] : '';
        // 	    $orderby  = $_GPC['orderby'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 100;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        
        $replace=array();
        $where = " friend.uniacid = '{$_W['uniacid']}' ";
        // 	    $type!='' && ($where.=" AND type=:type") && ($replace=array(':type'=>$type));
        $status!='' && ($where.=" AND status=:status") && ($replace=array(':status'=>$status));
        $left_join_member1=" LEFT JOIN " . tablename('mc_members') . " members1 on members1.uid=friend.uid ";
        $left_join_member2=" LEFT JOIN " . tablename('mc_members') . " members2 on members2.uid=friend.frient_uid ";
        $member_field1=" members1.nickname,members1.avatar ";
        $member_field2=" members2.nickname as frient_nickname,members2.avatar as frient_avatar ";
        $list = pdo_fetchall("SELECT friend.*,FROM_UNIXTIME(add_time) AS add_time_format,{$member_field1},{$member_field2} FROM " . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend ".
            $left_join_member1.$left_join_member2.
            "WHERE $where ORDER BY displayorder ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace, 'id');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
            
            //pdo_debug();
            $_W['isajax'] && die(json_encode($result));
            $_GPC['isdebug']&& print_r($result);
            include($this->template());
    }
	public function edit() 
	{
	    global $_GPC,$_W;
	    
	    $id=$_GPC['id'];
// 	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
	    $uid = $_GPC['uid'];
	    $frient_uid = $_GPC['frient_uid'];
	    $remark_name  = $_GPC['remark_name'];
	    $status  = $_GPC['status'];
	    $displayorder  = $_GPC['displayorder'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    if($status!=''||$displayorder){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $uid && $data['uid']=$uid;
	        $frient_uid&& $data['frient_uid']=$frient_uid;
	        $remark_name!='' && $data['remark_name']=$remark_name;
	        $status!='' && $data['status']=$status;
	        $displayorder!='' && $data['displayorder']=$displayorder;
	        
	        !$id && pdo_insert('ewei_shop_yoxwechatbusiness_friend', $data);
	        $id && pdo_update("ewei_shop_yoxwechatbusiness_friend", $data, array("id" => $id));
	        show_json(1,'成功');
	    }
	    $left_join_member1=" LEFT JOIN " . tablename('mc_members') . " members1 on members1.uid=friend.uid ";
	    $left_join_member2=" LEFT JOIN " . tablename('mc_members') . " members2 on members2.uid=friend.frient_uid ";
	    $member_field1=" members1.nickname,members1.avatar ";
	    $member_field2=" members2.nickname as frient_nickname,members2.avatar as frient_avatar ";
	    $info = pdo_fetch("SELECT friend.*,FROM_UNIXTIME(add_time) AS add_time_format,{$member_field1},{$member_field2} FROM " . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend ".
	        $left_join_member1.$left_join_member2.
	        "WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['data']=$info;
	    $_W['isajax'] && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template());
	}
	public function delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid'];
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_friend") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_friend', array('id' => $item['id'],'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	public function displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxwechatbusiness_friend") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_friend", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwechatbusiness_friend.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["id"] . " 排序: " . $displayorder . " ");
	    }
	    show_json(1);
	}
	public function status()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxwechatbusiness_friend") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_friend", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_friend.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>热搜: " . $item["keyword"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>