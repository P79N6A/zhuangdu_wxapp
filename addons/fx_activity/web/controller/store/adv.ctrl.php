<?php 
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$where=array();
	$listData = Util::getNumData('*', 'fx_adv', $where, 'displayorder DESC', $pindex,$psize,1);
	$list = $listData[0];
	include fx_template('store/adv');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'advname' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'thumb'=>$_GPC['thumb']
		);
		if (!empty($id)) {
			pdo_update('fx_adv', $data, array('id' => $id));
		} else {
			pdo_insert('fx_adv', $data);
			$id = pdo_insertid();
		}
		message('更新轮播图成功！', web_url('store/adv', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename('fx_adv') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
	include fx_template('store/adv');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM " . tablename('fx_adv') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
	if (empty($adv)) {
		message('抱歉，轮播图不存在或是已经被删除！', web_url('store/adv', array('op' => 'display')), 'error');
	}
	pdo_delete('fx_adv', array('id' => $id));
	message('轮播图删除成功！', web_url('store/adv', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}