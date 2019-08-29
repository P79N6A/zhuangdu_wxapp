<?php
/**
 * 品牌馆
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
class Index_EweiShopV2Page extends PluginMobilePage 
{
    //商户列表
	public function main() 
	{
	    global $_GPC, $_W;
	    
	    $merchid =$_GPC['merchid'];
	    $groupid =$_GPC['groupid'];
	    $merchname=$_GPC['merchname'];
	    $status=$_GPC['status']?$_GPC['status']:1;
	    
	    $status=$_GPC['status'];
	    
	    $pindex = max(1, intval($_GPC['pindex']));
	    $psize = $_GPC['psize']?$_GPC['psize']:20;
	    
	    $uid=$_W['uid'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    $where = " merch.uniacid = '{$uniacid}' ";
	    $merchid && ($where.=" AND merch.merchid = '{$merchid}' ");
	    $groupid!='' && ($where.=" AND merch.groupid = '{$groupid}' ");
	    $merchname!='' && ($where.=" AND merch.merchname LIKE '%{$merchname}%' ");
	    $status!='' && ($where.=" AND merch.`status` = '{$status}' ");
	    
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_merch_user') . " merch WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_merch_user') . " WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxmerch/index'));
	}
	/**
	 * 商户分组列表
	 */
	public function merch_group_list(){
	    global $_GPC, $_W;
	    $pindex=$_GPC['pindex']?$_GPC['pindex']:0;
	    $psize=$_GPC['psize']?$_GPC['psize']:20;
	    
	    $status=1;
	    $where=" merch.`status` =1 ";
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_merch_group') . " merch WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_merch_group') . " WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax'] || $_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template('yoxmerch/merch_group_list'));
	}
	/**
	 * 商户分类列表
	 */
	public function merch_category_list(){
	    global $_GPC, $_W;
	    
	    $pindex=$_GPC['pindex']?$_GPC['pindex']:0;
	    $psize=$_GPC['psize']?$_GPC['psize']:20;
	    
	    $status=1;
	    $where=" merch.`status` =1 ";
	    
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_merch_category') . " merch WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_merch_category') . " WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax'] || $_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template('yoxmerch/merch_category_list'));
	}
    public function list(){
        
    }
	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $cate_id = $_GPC['cate_id'];
	    $name = $_GPC['name'];
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
	    if($name!=''||$description||$thumbs){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $cate_id && $data['cate_id']=$cate_id;
	        $name && $data['name']=$name;
	        $thumb&& $data['thumb']=$thumb;
	        is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
	        $videos && $data['videos']=$videos;
	        $url && $data['url']=$url;
	        $description && $data['description']=$description;
	        $content && $data['content']=$content;
		$is_featured!='' && $data['is_featured']=$is_featured;
		$status!='' && $data['status']=$status;
	        $data['add_time']=time();
	        $data['update_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxarticlevideo_article', $data);
	        $id  && pdo_update("ewei_shop_yoxarticlevideo_article", $data, array("id" => $id));
	        
	        show_json(1, "成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['thumbs']=unserialize($info['thumbs']);
	    
	    $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    
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
	    pdo_delete('ewei_shop_yoxarticlevideo_article', array('id' => $id));
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxarticlevideo_article") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxarticlevideo_article", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxarticlevideo_article.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxarticlevideo_article") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxarticlevideo_article", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxhotsearch.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
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
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxarticlevideo_article") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxarticlevideo_article", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxarticlevideo_article.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	public function setting(){
	    include($this->template());
	}
}
?>