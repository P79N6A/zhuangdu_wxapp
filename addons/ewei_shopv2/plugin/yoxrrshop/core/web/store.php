<?php

if( !defined("IN_IA") )
{
	exit( "Access Denied" );
}
class Store_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{
	    global $_GPC, $_W;
	    $this->store_list();
	}
    public function store_list(){
        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $where = " uniacid = {$_W['uniacid']} ";
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_store') . " WHERE {$where} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxrrshop_store') . " WHERE $where");
        $pager = pagination($total, $pindex, $psize);

        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);

        include($this->template('yoxrrshop/store_list'));
    }
    public function store_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $type = $_GPC['type'];
	    $name = $_GPC['name'];
	    $description = $_GPC['description'];
	    $preview = $_GPC['preview'];
	    $url    = $_GPC['url'];
	    $page_value=$_POST['page_value'];
	    $status = $_GPC['status'];

	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($name!=''||$description||$thumbs||$page_value!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $type && $data['type']=$type;
	        $name && $data['name']=$name;
	        $preview&& $data['preview']=$preview;
	        $description && $data['description']=$description;
	        $status!='' && $data['status']=$status;
		$page_value!='' && $data['page_value']=$page_value;
	        $data['add_time']=time();
	        $data['update_time']=time();

	        !$id && pdo_insert('ewei_shop_yoxrrshop_page_template', $data);
	        $id  && pdo_update("ewei_shop_yoxrrshop_page_template", $data, array("id" => $id));
	        //print_r($data);die();
	        show_json(1, "成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }

	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_page_template') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['thumbs']=unserialize($info['thumbs']);

	    $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_page_template') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;

	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template('yoxrrshop/store_edit'));
	}
	/**
	 * 删除
	 */
	public function store_delete()
	{
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    pdo_delete('ewei_shop_yoxrrshop_store', array('id' => $id));
	    show_json(1,'删除成功');
	}
	public function setting(){
	    include($this->template());
	}
}
?>