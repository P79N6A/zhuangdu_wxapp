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




}

?>
