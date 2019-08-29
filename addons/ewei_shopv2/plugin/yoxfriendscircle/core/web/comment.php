<?php
/**
 * 评论
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
class Comment_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    
	    $uid=$_GPC['uid'];
	    $type=$_GPC['type']?$_GPC['type']:'ARTICLE';
	    $target_id=$_GPC['target_id'];
	    $status=$_GPC['status']!=''?$_GPC['status']:1;
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:1;
	    $where = " comment.uniacid = '{$uniacid}' ";
	    $merchid && ($where.=" AND comment.merchid = '{$merchid}' ");
	    $uid && ($where .=" AND comment.uid = '{$uid}' ");
	    $type && ($where.=" AND comment.type = '{$type}' ");
	    
	    $left_join=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_article')." article ON (article.id=comment.target_id AND comment.type='ARTICLE')  LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.uid=comment.uid ";
	    $list = pdo_fetchall("SELECT comment.*,FROM_UNIXTIME(comment.add_time) as add_time_format,article.id as article_id,article.name as article_name,article.description as article_description,shop_member.nickname,shop_member.avatar FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " comment $left_join WHERE $where ORDER BY comment.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_comment') . " comment $left_join WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
// 	    pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
		include($this->template());
	}

	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $type = $_GPC['type']?$_GPC['type']:'ARTICLE';
	    $target_id = $_GPC['target_id'];
	    $status = $_GPC['status']!=''?$_GPC['status']:1;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:1;
	    if($uid!=''||$type||$target_id||$status!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $type && $data['type']=$type;
	        $target_id && $data['target_id']=$target_id;
	        $status && $data['status']=$status;
	        $data['add_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxfriendscircle_comment', $data);
	        $id  && pdo_update("ewei_shop_yoxfriendscircle_comment", $data, array("id" => $id));
	        
	        show_json(1, "成功");
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " WHERE id = :id AND uniacid = :uniacid AND merchid=:merchid", array(':id' => $id, ':uniacid' => $uniacid,'merchid'=>$merchid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template());
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    pdo_delete('ewei_shop_yoxfriendscircle_comment', array('id' => $id));
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
	    $item = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxfriendscircle_comment") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_comment", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxfriendscircle_comment.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxfriendscircle_comment") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_comment", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxfriendscircle_comment.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
	public function is_featured(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,comment FROM " . tablename("ewei_shop_yoxfriendscircle_comment") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_comment", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxfriendscircle_comment.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
}
?>