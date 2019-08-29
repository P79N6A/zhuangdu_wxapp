<?php
defined('IN_IA') or exit('Access Denied');

$ops = array('display', 'post', 'delete', 'status');
$op = in_array($op, $ops) ? $op : 'display';
$eid = $_GPC['eid'];
if ($op == 'display') {
	$_W['page']['title'] = '活动分类管理 - 活动分类';
	$cateTitle = '添加';
	
	if (checksubmit('submit')) {
		if (!empty($_GPC['add_parent_name'])) {
			foreach ($_GPC['add_parent_name'] as $key => $value) {
				if (!empty($value)) {
					$insert = array(
						'name' => $value,
						'description' => $_GPC['add_parent_description'][$key],
						'displayorder' => $_GPC['add_parent_displayorder'][$key]
					);
					pdo_insert('fx_category', $insert);
				}
			}
		}
		if (!empty($_GPC['add_name'])) {
			foreach ($_GPC['add_name'] as $key => $value) {
				if (!empty($value)) {
					$insert = array(
						'parentid' => $_GPC['add_pid'][$key],
						'name' => $value,
						'description' => $_GPC['add_description'][$key],
						'displayorder' => $_GPC['add_displayorder'][$key]
					);
					pdo_insert('fx_category', $insert);
				}
				
			}
		}
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			$update = array(
				'displayorder' => intval($displayorder),
				'name' => $_GPC['name'][$id],
				'description' => $_GPC['description'][$id]
			);
			pdo_update('fx_category', $update, array('id' => $id));
			//Util::deleteCache('activity', $id);
		}
		
		message('活动分类更新成功', 'refresh', 'success');
	}
	$category = pdo_fetchall("SELECT * FROM ".tablename('fx_category')."WHERE uniacid = {$_W['uniacid']} ORDER BY visible_level desc, `parentid` DESC, `displayorder` DESC, id ASC");
	$children = array();
	if (!empty($category)) {
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])){
				$children[$row['parentid']][] = $row;
				unset($category[$index]);
			}
		}
	}

}
if ($op == 'post') {
	$cateId = intval($_GPC['id']);
	$level = $_GPC['visible_level']?$_GPC['visible_level']:0;//=isfounder为首页分类
	$category = pdo_get('fx_category', array('id' => $cateId), array('parentid'));
	$faterId = intval($_GPC['fatherid']);
	$cateTitle = empty($cateId) ? '添加' : '更新';
	$_W['page']['title'] = $cateTitle . '活动分类 - 活动分类';

	if (checksubmit('submit')) {
		if (empty($_GPC['name'])) {
			message('分类名称不能为空');
		}
		$data = array(
			'uniacid'       => $_W['uniacid'],
			'name' 			=> $_GPC['name'],
			'description' 	=> $_GPC['description'],
			'displayorder' 	=> $_GPC['displayorder'],
			'enabled' 		=> $_GPC['enabled'],
			'open' 			=> $_GPC['open'],
			'thumb' 		=> $_GPC['thumb'],
			'parentid' 		=> $category['parentid'],
			'visible_level' => $level,
			'color' => $_GPC['color'],
			'redirect' => $_GPC['redirect']
		);
		if (!empty($faterId)) {
			$data['parentid'] = $faterId;
		}
		if (empty($cateId) || !empty($faterId)) {
			$where = array(
				'name' => $_GPC['name'], 
				'uniacid' => $_W['uniacid']
			);
			if (!empty($faterId)) $where['parentid'] = $faterId;
			
			$result = pdo_get('fx_category', $where, array('id'));
			if (!empty($result)) {
				message('分类名称已存在');
			}
			pdo_insert('fx_category', $data);
			$cateId = pdo_insertid();
		} else {
			pdo_update('fx_category', $data, array('id' => $cateId));
			//Util::deleteCache('activity', $id);
		}
		
		message('活动分类更新成功', web_url('activity/category/display', array('id' => $cateId,'eid'=>$eid)), 'success');
	}

	$category = array();
	if (!empty($cateId)) {
		$category = pdo_get('fx_category', array('id' => $cateId));
	}

}

if ($op == 'delete') {
	$category_id = intval($_GPC['cateid']);
	if ($category_id) {
		$category = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_category') . " WHERE id = " . $category_id);
	}
	if (empty($category)) {
		die(json_encode(array('errno' => 1, 'message' => '删除失败: 未指定活动分类.')));
	}
	
	$pcatetotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE parentid = " . $category_id);
	$ccatetotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_activity') . " WHERE childid = " . $category_id);
	if ($pcatetotal + $ccatetotal > 0) {
		die(json_encode(array('errno'=>1, 'message'=>'有活动正使用改分类，不可删除！')));
	}
	$child_category_count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fx_category') . " WHERE parentid = " . $category_id);
	if ($child_category_count > 0) {
		die(json_encode(array('errno' => 1,'message' => '该分类有子分类, 请先删除子分类')));
	}
	$result = pdo_delete('fx_category', array('id' => $category_id));
	die(json_encode(array('errno' => $result ? 0 : 1, 'message' => $result ? '删除成功' : '删除失败')));
}

if ($op == 'status') {
	$id = intval($_GPC['id']);
	$status = intval($_GPC['status']);
	$result = pdo_update('fx_category', array('enabled' => $status), array('id' => $id));
	//Util::deleteCache('activity', $id);
	die(json_encode(array('message' => $result ? '设置成功' : '设置失败')));
}
include fx_template('activity/category');