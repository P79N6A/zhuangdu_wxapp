<?php
/**
 * 关注
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
class Follow_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    
	    $uid=$_GPC['uid'];
	    $followed_uid=$_GPC['followed_uid'];
	    $followed_merchid=$_GPC['followed_merchid'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $where = " follow.uniacid = '{$uniacid}' ";
	    
	    $uid && ($where.=" AND follow.uid = '{$uid}' ");
	    $followed_uid && ($where.=" AND follow.followed_uid = '{$followed_uid}' ");
	    $followed_merchid && ($where.=" AND follow.followed_merchid = '{$followed_merchid}' ");
	    
	    $left_join=" LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.uid=follow.uid LEFT JOIN ".tablename('ewei_shop_store')." followed_store ON followed_store.id=follow.followed_merchid LEFT JOIN ".tablename('ewei_shop_member')." followed_shop_member ON followed_shop_member.uid=follow.followed_uid ";
	    $list = pdo_fetchall("SELECT follow.*,FROM_UNIXTIME(add_time) as add_time_format,shop_member.nickname,shop_member.avatar,followed_store.storename as followed_storename,followed_store.logo as followed_logo,followed_shop_member.nickname as followed_nickname,followed_shop_member.avatar as followed_avatar FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " follow $left_join WHERE $where ORDER BY follow.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_follow') . " follow $left_join WHERE $where");
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
	    $followed_uid = $_GPC['followed_uid'];
	    $followed_merchid = $_GPC['followed_merchid'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    if($uid!=''||$followed_uid||$followed_merchid){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $uid && $data['uid']=$uid;
	        $followed_uid && $data['followed_uid']=$followed_uid;
	        $followed_merchid && $data['followed_merchid']=$followed_merchid;
	        $data['add_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxfriendscircle_follow', $data);
	        $id  && pdo_update("ewei_shop_yoxfriendscircle_follow", $data, array("id" => $id));
	        
	        show_json(1, "成功");
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['thumbs']=unserialize($info['thumbs']);
	    
	    $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    
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
	    pdo_delete('ewei_shop_yoxfriendscircle_follow', array('id' => $id));
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
	    $item = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxfriendscircle_follow") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_follow", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxfriendscircle_follow.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_yoxfriendscircle_follow") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxfriendscircle_follow", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxfriendscircle_follow.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>