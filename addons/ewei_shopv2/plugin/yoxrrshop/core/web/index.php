<?php

if( !defined("IN_IA") )
{
	exit( "Access Denied" );
}
class Index_EweiShopV2Page extends PluginWebPage
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
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_store') . " WHERE {$where} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'displayorder desc');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxrrshop_store') . " WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        include($this->template('yoxrrshop/store_list'));
    }
    public function store_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
        if ($_W['ispost']) {
            $data['uniacid']=$_W['uniacid'];
            $data['store_number']=$_GPC['store_number'];
            $data['name']=$_GPC['name'];
            $data['tel']=$_GPC['tel'];
            $data['address']=$_GPC['address'];
            $data['business_time']=$_GPC['business_time'];
            $data['manager_wechat']=$_GPC['manager_wechat'];
            $data['star']=$_GPC['star'];
            $data['manager_wechat']=$_GPC['manager_wechat'];
            $data['province']=$_GPC['area']['province'];
            $data['city']=$_GPC['area']['city'];
            $data['district']=$_GPC['area']['district'];
            $data['status']=1;
            $data['thumbs']=serialize($_GPC['thumbs']);
            $data['lal']=$_GPC['lal']['lng'].",".$_GPC['lal']['lat'];
            !$id && pdo_insert('ewei_shop_yoxrrshop_store', $data);
            $id && pdo_update("ewei_shop_yoxrrshop_store", $data, array("id" => $id));
            show_json(1, "成功");
        }
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_store') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
        $info['lal']=explode(',',$info['lal']);
        $info['thumbs']=unserialize($info['thumbs']);

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


    public function status(){
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }
        $items = pdo_fetchall('SELECT id,name FROM ' . tablename('ewei_shop_yoxrrshop_store') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);
        foreach ($items as $item) {
            pdo_update('ewei_shop_yoxrrshop_store', array('status' => intval($_GPC['status'])), array('id' => $item['id']));
            plog('yoxrrshop.index.edit', '修改店铺<br/>ID: ' . $item['id'] . '<br/>名字: ' . $item['name'] . '<br/>状态: ' . $_GPC['status'] == 1 ? '显示' : '隐藏');
        }
        show_json(1, array('url' => referer()));
    }

    public function displayorder(){
        global $_GPC,$_W;
        $id = intval($_GPC['id']);
        $displayorder = intval($_GPC['value']);
        $item = pdo_fetchall('SELECT id,name FROM ' . tablename('ewei_shop_yoxrrshop_store') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);
        if (!empty($item)) {
            pdo_update('ewei_shop_yoxrrshop_store', array('displayorder' => $displayorder), array('id' => $id));
            plog('yoxrrshop.index.edit', '修改店铺 ID: ' . $item['id'] . ' 名字: ' . $item['name'] . ' 排序: ' . $displayorder . ' ');
        }
        show_json(1);
    }
}





?>