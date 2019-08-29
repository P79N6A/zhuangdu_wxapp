<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Storegoods_EweiShopV2Page extends WebPage
{

	public function main()
	{
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$paras = array(':uniacid' => $_W['uniacid'],':id'=>$_GPC['id']);
		$condition = " uniacid = :uniacid and storeid=:id ";

		$sql = 'SELECT * FROM ' . tablename('ewei_shop_storegoods') . (' WHERE ' . $condition . ' ORDER BY displayorder desc,id desc');
		$sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$sql_count = 'SELECT count(1) FROM ' . tablename('ewei_shop_storegoods') . (' WHERE ' . $condition);
		$total = pdo_fetchcolumn($sql_count, $paras);
		$pager = pagination2($total, $pindex, $psize);
		$list = pdo_fetchall($sql, $paras);

		foreach ($list as &$row) {
			$goods= pdo_fetchcolumn('select * from ' . tablename('ewei_shop_goods') . ' where id=:id limit 1', array(':id' => $row['goodsid']));
			$row['title'] =$goods['title'];
			$row['price'] =$goods['price'];
		}
		unset($row);

		$storename= pdo_fetchcolumn('select storename from ' . tablename('ewei_shop_store') . ' where id=:id limit 1', array(':id' => $_GPC['id']));
		include $this->template();
	}


	public function add()
	{
		$this->post();
	}

	public function edit()
	{
		$this->post();
	}

	protected function post()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		if ($_W['ispost']) {
			if (!empty($_GPC['perms'])) {
				$perms = implode(',', $_GPC['perms']);
			}
			else {
				$perms = '';
			}



			$data = array('uniacid' => $_W['uniacid'], 'storename' => trim($_GPC['storename']), 'address' => trim($_GPC['address']), 'province' => trim($_GPC['province']), 'city' => trim($_GPC['city']), 'area' => trim($_GPC['area']), 'provincecode' => trim($_GPC['chose_province_code']), 'citycode' => trim($_GPC['chose_city_code']), 'areacode' => trim($_GPC['chose_area_code']), 'tel' => trim($_GPC['tel']), 'lng' => $_GPC['map']['lng'], 'lat' => $_GPC['map']['lat'], 'type' => intval($_GPC['type']), 'realname' => trim($_GPC['realname']), 'mobile' => trim($_GPC['mobile']), 'label' => $label, 'tag' => $tag, 'fetchtime' => trim($_GPC['fetchtime']), 'saletime' => trim($_GPC['saletime']), 'logo' => save_media($_GPC['logo']), 'desc' => trim($_GPC['desc']), 'opensend' => intval($_GPC['opensend']), 'status' => intval($_GPC['status']), 'cates' => $cates, 'perms' => $perms);

			if (P('newstore')) {
				$data['storegroupid'] = intval($_GPC['storegroupid']);
			}

			$data['order_printer'] = is_array($_GPC['order_printer']) ? implode(',', $_GPC['order_printer']) : '';
			$data['order_template'] = intval($_GPC['order_template']);
			$data['ordertype'] = is_array($_GPC['ordertype']) ? implode(',', $_GPC['ordertype']) : '';

			if (!empty($id)) {
				pdo_update('ewei_shop_store', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
				plog('shop.verify.store.edit', '编辑门店 ID: ' . $id);
			}
			else {
				pdo_insert('ewei_shop_store', $data);
				$id = pdo_insertid();
				plog('shop.verify.store.add', '添加门店 ID: ' . $id);
			}

			show_json(1, array('url' => webUrl('store')));
		}


		$store = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_store') . ' WHERE id =:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));

		include $this->template();
	}

	public function delete()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
		}

		$items = pdo_fetchall('SELECT id,storename FROM ' . tablename('ewei_shop_store') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_delete('ewei_shop_store', array('id' => $item['id']));
			plog('shop.verify.store.delete', '删除门店 ID: ' . $item['id'] . ' 门店名称: ' . $item['storename'] . ' ');
		}

		show_json(1, array('url' => referer()));
	}

	public function displayorder()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$displayorder = intval($_GPC['value']);
		$item = pdo_fetchall('SELECT id,storename FROM ' . tablename('ewei_shop_store') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);

		if (!empty($item)) {
			pdo_update('ewei_shop_store', array('displayorder' => $displayorder), array('id' => $id));
			plog('shop.verify.store.edit', '修改门店排序 ID: ' . $item['id'] . ' 门店名称: ' . $item['storename'] . ' 排序: ' . $displayorder . ' ');
		}

		show_json(1);
	}

	public function status()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
		}

		$items = pdo_fetchall('SELECT id,storename FROM ' . tablename('ewei_shop_store') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_update('ewei_shop_store', array('status' => intval($_GPC['status'])), array('id' => $item['id']));
			plog('shop.verify.store.edit', '修改门店状态<br/>ID: ' . $item['id'] . '<br/>门店名称: ' . $item['storename'] . '<br/>状态: ' . $_GPC['status'] == 1 ? '启用' : '禁用');
		}

		show_json(1, array('url' => referer()));
	}











}

?>
