<?php

//decode by QQ:270656184 http://www.yunlu99.com/
defined('IN_IA') or die('Access Denied');
class life_haibaoModuleSite extends WeModuleSite
{
	public function __construct()
	{
		global $_W, $_GPC;
		if (0 && empty($_W['openid'])) {
			echo '请在微信下使用！';
			die;
		}
	}
	public function doMobilePlay()
	{
		global $_W, $_GPC;
		include $this->template('index');
	}
	public function doWebTemplate()
	{
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$cid = $_GPC['cate'];
		$templates_all = pdo_fetchall('select * from ' . tablename($this->modulename . '_template'));
		if ($op != 'import' && empty($templates_all)) {
			message('请先添加模板!', $this->createWebUrl('template', array('op' => 'import')));
		}
		if ($op == 'post') {
			$id = $_GPC['id'];
			if (checksubmit('submit')) {
				$data = array('name' => $_GPC['name'], 'hots' => $_GPC['hots'], 'price' => $_GPC['price'], 'status' => $_GPC['status']);
				if (pdo_update($this->modulename . '_template', $data, array('id' => $id)) === false) {
					message('保存失败！');
				} else {
					message('保存成功！', $this->createWebUrl('template'));
				}
			}
			$item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where id='{$id}'");
		} elseif ($op == 'check') {
			$cid = $_GPC['id'];
			$item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where id = '{$cid}'");
			if (empty($item)) {
				$datas = 0;
				die('3');
			}
			if ($item['status'] == 1) {
				$data['status'] = 0;
			} else {
				$data['status'] = 1;
			}
			$ret = pdo_update('life_haibao_template', $data, array('id' => $item['id']));
			if (!empty($ret)) {
				if ($item['status'] == 1) {
					$datas = 2;
					die('1');
				} else {
					$datas = 1;
					die('2');
				}
			} else {
				$datas = 0;
				die('3');
			}
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall('select * from ' . tablename($this->modulename . '_template') . " LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->modulename . '_template'));
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('template');
	}
	public function doWebImport()
	{
		global $_W, $_GPC;
		set_time_limit(0);
		include_once '../addons/life_haibao/excel/oleread.php';
		include_once '../addons/life_haibao/excel/excel.php';
		$error = $_FILES['txtfile']['error'];
		if (!empty($_FILES['txtfile']['name']) && $error == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES['txtfile']['tmp_name'];
			$name = $_FILES['txtfile']['name'];
			$filename = ATTACHMENT_ROOT . date('Ymdhis') . '.' . pathinfo($name, PATHINFO_EXTENSION);
			if (move_uploaded_file($tmp_name, $filename)) {
				$xls = new Spreadsheet_Excel_Reader();
				$xls->setOutputEncoding('utf-8');
				$xls->read($filename);
				for ($i = 2; $i <= $xls->sheets[0]['numRows']; $i++) {
					$data_values[] = $xls->sheets[0]['cells'][$i];
				}
			}
			$insert_sql = 'INSERT INTO ' . tablename($this->modulename . '_template') . ' (name,link,hots,price,status,type,createtime) ';
			$insert_val = '';
			$count = 0;
			foreach ($data_values as $value) {
				if ($value[2] == '') {
					break;
				}
				$template = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where name = '{$value[2]}'");
				if (!empty($template)) {
					continue;
				}
				if (empty($value[4])) {
					$hots = 0;
				} else {
					$hots = $value[4];
				}
				if (empty($value[5])) {
					$price = 0;
				} else {
					$price = $value[5];
				}
				if ($value[6] == '') {
					$status = 1;
				} else {
					$status = $value[6];
				}
				if (!empty($insert_val)) {
					$insert_val .= ', ';
				}
				$current_time = date('y-m-d h:i:s', time());
				$insert_val .= " ( '{$value[2]}','{$value[3]}','{$hots}','{$price}','{$status}','{$value[7]}','{$current_time}' ) ";
				$count++;
			}
			if (!empty($insert_val)) {
				$insert_sql .= ' VALUES ' . $insert_val;
				$ret = pdo_query($insert_sql);
				if ($ret > 0) {
					message('导入记录 ' . $count . ' 条', referer());
				} else {
					message('导入错误，请刷新重试！');
				}
			} else {
				message('抱歉！导入信息有误，请重试', referer(), 'error');
			}
		}
	}
	public function doMobileMore()
	{
		global $_W, $_GPC;
		include $this->template('more');
	}
	public function doMobileWatermark()
	{
		global $_W, $_GPC;
		include $this->template('watermark');
	}
	public function doMobileTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwentyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwenty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainzi()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwentyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwenty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirtyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainNineteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainEighteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTrainEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirtyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileThirty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwentyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwenty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTrainEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirtyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirtyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirtyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirtyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirtyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareNinteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantNineteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantEighteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareMerchantEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareEighteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSquareEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixtyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSixty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileShare()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventy()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileNineteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwentyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwenty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirtyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantNineteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFortyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantForty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFifty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantEighteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileMerchantEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLoveTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function payResult($params)
	{
		global $_W, $_GPC;
		$templates_paylog_item = pdo_fetch('select * from ' . tablename($this->modulename . '_templates_paylog') . " where tid='{$params['tid']}'");
		$openid = $templates_paylog_item['openid'];
		$do = $templates_paylog_item['name'];
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$core_paylog_item = pdo_fetch('select * from ' . tablename('core_paylog') . " where tid='{$params['tid']}'");
		if ($params['result'] == 'success' && $core_paylog_item['status'] == '1') {
			$data = array('status' => '1');
			$result = pdo_update($this->modulename . '_templates_paylog', $data, array('tid' => $params['tid']));
			if (empty($result)) {
				message('支付成功', $this->createMobileUrl($do), 'success');
			}
			if (empty($fans_to_templates_item)) {
				$data = array('openid' => $openid, 'templates' => $do);
				$result = pdo_insert($this->modulename . '_fans_to_templates', $data);
				if (empty($result)) {
					message('添加用户记录失败！', '', 'error');
				}
			} else {
				$data = array('templates' => $fans_to_templates_item['templates'] . ',' . $do);
				$result = pdo_update($this->modulename . '_fans_to_templates', $data, array('openid' => $openid));
				if (empty($result)) {
					message('更新用户记录失败！', '', 'error');
				}
			}
		}
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				message('支付成功', $this->createMobileUrl($do), 'success');
			} else {
				message('支付失败！', '', 'error');
			}
		}
	}
	public function doMobileLoveThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLoveOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLoveFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLanternTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLanternThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLanternOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileLanternFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwentyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwenty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirtyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnySixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnySeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyNineteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyFortyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyForty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFunnyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFortyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileForty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftySix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftySeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFiftyEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFifty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileEeleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileEightyTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileEightyThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileEighty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileEighteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileCoverEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringOne()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringTwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringThree()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringFour()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringFive()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringSix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadEightteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadFifteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadFourteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadSeventeen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadSixteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileHeadThirteen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobilePeixunfourty()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobilePeixunfourtyone()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFangtuthirtyeight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFangtuthirtyseven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileFangtuthirtysix()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringEight()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringEleven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringNine()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringSeven()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringTen()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileSpringTwelve()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileZhaoshangfiftyone()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
	public function doMobileZhaoshangfiftytwo()
	{
		global $_W, $_GPC;
		$do = $_GPC['do'];
		$openid = $_W['openid'];
		$template_item = pdo_fetch('select * from ' . tablename($this->modulename . '_template') . " where link='{$do}'");
		$fans_to_templates_item = pdo_fetch('select * from ' . tablename($this->modulename . '_fans_to_templates') . " where openid='{$openid}'");
		$pos = strpos($fans_to_templates_item['templates'], $do);
		$order_id = date('YmdHis', time()) . sprintf("%06d", $template_item['id']);
		if ($pos === false && $template_item['price'] > 0) {
			$data = array('tid' => $order_id, 'openid' => $openid, 'name' => $do, 'price' => $template_item['price'], 'status' => '0', 'createtime' => date('y-m-d h:i:s', time()));
			$result = pdo_insert($this->modulename . '_templates_paylog', $data);
			if (empty($result)) {
				message('创建订单错误，请重试！', '', 'error');
			}
			$param = array('tid' => $order_id, 'ordersn' => $order_id, 'title' => $do, 'fee' => $template_item['price']);
			$this->pay($param);
		} else {
			include $this->template($do);
		}
	}
}