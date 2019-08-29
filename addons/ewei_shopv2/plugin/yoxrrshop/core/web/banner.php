<?php

if( !defined("IN_IA") )
{
	exit( "Access Denied" );
}
class Banner_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{
	    global $_GPC, $_W;
	    $this->banner_list();
	}
    public function banner_list(){
        global $_GPC, $_W;
        $where = " uniacid = {$_W['uniacid']} ";
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_banner') . " WHERE {$where}", array(), 'displayorder desc');
        include($this->template('yoxrrshop/banner_list'));
    }
    public function banner_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
        if ($_W['ispost']) {
            $data['uniacid']=$_W['uniacid'];
            $data['banner']=$_GPC['banner'];
            !$id && pdo_insert('ewei_shop_yoxrrshop_banner', $data);
            $id && pdo_update("ewei_shop_yoxrrshop_banner", $data, array("id" => $id));
            show_json(1, "成功");
        }
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxrrshop_banner') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));

		include($this->template('yoxrrshop/banner_edit'));
	}
	/**
	 * 删除
	 */
	public function banner_delete()
	{
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    pdo_delete('ewei_shop_yoxrrshop_banner', array('id' => $id));
	    show_json(1,'删除成功');
	}
	public function setting(){
	    include($this->template());
	}


    public function displayorder(){
        global $_GPC,$_W;
        $id = intval($_GPC['id']);
        $displayorder = intval($_GPC['value']);
        $item = pdo_fetchall('SELECT id FROM ' . tablename('ewei_shop_yoxrrshop_banner') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);
        if (!empty($item)) {
            pdo_update('ewei_shop_yoxrrshop_banner', array('displayorder' => $displayorder), array('id' => $id));
            plog('yoxrrshop.index.edit', '修改banner ID: ' . $item['id'] . ' 排序: ' . $displayorder . ' ');
        }
        show_json(1);
    }
}





?>