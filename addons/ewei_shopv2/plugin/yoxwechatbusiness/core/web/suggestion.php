<?php
/**
 * 投诉建议
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
class Suggestion_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    
	    $is_featured=$_GPC['is_featured'];
	    $status=$_GPC['status'];
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    $where = " uniacid = '{$uniacid}' ";
	    
	    $merchid && $where.=" merchid = '{$merchid}' ";
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_suggestion') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_suggestion') . " WHERE $where", array());
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
		include($this->template());
	}

	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $openid = $_GPC['openid'];
	    $cate_id = $_GPC['cate_id'];
	    $title = $_GPC['title'];
	    $content = $_GPC['content'];
	    $description = $_GPC['description'];
	    $thumb = $_GPC['thumb'];
	    $thumbs = $_GPC['thumbs'];
	    $videos = $_GPC['videos'];
	    $url    = $_GPC['url'];
	    $is_featured = $_GPC['is_featured'];
	    $status = $_GPC['status'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($title!=''||$description||$thumbs){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $cate_id && $data['cate_id']=$cate_id;
	        $title && $data['title']=$title;
	        $thumb&& $data['thumb']=$thumb;
	        is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
	        $videos && $data['videos']=$videos;
	        $url && $data['url']=$url;
	        $description && $data['description']=$description;
	        $content && $data['content']=$content;
	        $data['add_time']=time();
	        $data['update_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxwechatbusiness_suggestion', $data);
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_suggestion", $data, array("id" => $id));
	        
	        show_json(1, "成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_suggestion') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['thumbs']=unserialize($info['thumbs']);
	    
	    $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_suggestion') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    
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
	    pdo_delete('ewei_shop_yoxwechatbusiness_suggestion', array('id' => $id));
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_suggestion") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_suggestion", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwechatbusiness_suggestion.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_suggestion") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_suggestion", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_suggestion.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
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
	    $items = pdo_fetchall("SELECT id,title FROM " . tablename("ewei_shop_yoxwechatbusiness_suggestion") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_suggestion", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_suggestion.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	public function setting(){
	    include($this->template());
	}
}
?>