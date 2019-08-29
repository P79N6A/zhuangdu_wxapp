<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Index_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    global $_GPC,$_W;
	    
	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
	    $keyword = $_GPC['keyword'];
	    $is_hot  = $_GPC['is_hot'];
	    $status  = !empty($_GPC['status']) ? $_GPC['status'] : '';
	    $orderby  = $_GPC['orderby'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 100;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    $replace=array();
	    $where = " uniacid = '{$_W['uniacid']}' ";
	    $type!='' && ($where.=" AND type=:type") && ($replace=array(':type'=>$type));
	    $status!='' && ($where.=" AND status=:status") && ($replace=array(':status'=>$status));
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxhotsearch') . " WHERE $where ORDER BY displayorder ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace, 'id');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxhotsearch') . " WHERE $where");
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
	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
	    $keyword = $_GPC['keyword'];
	    $is_hot  = $_GPC['is_hot'];
	    $status  = $_GPC['status'];
	    $displayorder  = $_GPC['displayorder'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    if($type||$keyword||$is_hot!=''||$status!=''||$displayorder){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $type && $data['type']=$type;
	        $keyword&& $data['keyword']=$keyword;
	        $is_hot!='' && $data['is_hot']=$is_hot;
	        $status!='' && $data['status']=$status;
	        $displayorder!='' && $data['displayorder']=$displayorder;
	        
	        !$id && pdo_insert('ewei_shop_yoxhotsearch', $data);
	        $id && pdo_update("ewei_shop_yoxhotsearch", $data, array("id" => $id));
	        show_json(1,'成功');
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxhotsearch') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['data']=$info;
	    $_W['isajax'] && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template());
	}
	public function delete() 
	{
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    pdo_delete('ewei_shop_yoxhotsearch', array('id' => $id));
	    show_json(1,'删除成功');
	}
	public function displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxhotsearch") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxhotsearch", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("yoxhotsearch.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["article_title"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxhotsearch") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxhotsearch", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxhotsearch.edit", ("修改热搜状态<br/>ID: " . $item["id"] . "<br/>热搜: " . $item["keyword"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>