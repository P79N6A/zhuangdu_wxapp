<?php
/**
 * 本程序有悟空源码社区提供
 *
 * www.5kym.cn
 * www.5kym.cn
 */
defined('IN_IA') or exit('Access Denied');

class Lshd_yanzhiModuleSite extends WeModuleSite {
	public function doWebMessage() {
		global $_W, $_GPC;
		$ops = array('index', 'save', 'update', 'del');
		$op = empty($_GPC['op']) ? $ops[0] : trim($_GPC['op']);
		if ($op == 'save') {
			if (checksubmit()) {
				if (empty($_GPC['title'])) {
					message('温馨提示：请填写标题！', '', 'error');
				}
				if (empty($_GPC['mid'])) {
					message('温馨提示：请填写模板ID！', '', 'error');
				}
				$value = array();
				foreach ($_GPC['key'] as $key => $v) {
					$value[$v] = array('value' => trim($_GPC['value'][$key]), 'color' => trim($_GPC['color'][$key]));
				}
				$data = array(
					'uniacid' => $_W['uniacid'],
					'title' => trim($_GPC['title']),
					'mid' => trim($_GPC['mid']),
					'content' => trim($_GPC['content']),
					'data' => json_encode($value),
					'url' => trim($_GPC['url']),
					'emphasis' => trim($_GPC['emphasis']),
				);
				if (pdo_insert('lshd_yanzhi_tplmsg', $data)) {
					message('温馨提示：新增模板消息成功！', $this->createWebUrl('message'), 'success');
				}
				message('温馨提示：新增模板消息失败！', '', 'error');
			}
		} else {
			if ($op == 'update') {
				if (checksubmit()) {
					if (empty($_GPC['title'])) {
						message('温馨提示：请填写标题！', '', 'error');
					}
					if (empty($_GPC['mid'])) {
						message('温馨提示：请填写模板ID！', '', 'error');
					}
					$value = array();
					foreach ($_GPC['key'] as $key => $v) {
						$value[$v] = array('value' => trim($_GPC['value'][$key]), 'color' => trim($_GPC['color'][$key]));
					}
					$data = array(
						'title' => trim($_GPC['title']),
						'mid' => trim($_GPC['mid']),
						'content' => trim($_GPC['content']),
						'data' => json_encode($value),
						'url' => trim($_GPC['url']),
						'emphasis' => trim($_GPC['emphasis']),
					);
					if (pdo_update('lshd_yanzhi_tplmsg', $data, array('uniacid' => $_W['uniacid'], 'id' => intval($_GPC['id'])))) {
						message('温馨提示：编辑模板消息成功！', $this->createWebUrl('message'), 'success');
					}
					message('温馨提示：编辑模板消息失败！', '', 'error');
				}
				$tplmsg = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
				if (empty($tplmsg)) {
					message('温馨提示：该模板消息不存在！', '', 'error');
				}
				$tplmsg['data'] = json_decode($tplmsg['data'], true);
			} else {
				if ($op == 'del') {
					$tplmsg = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
					if (empty($tplmsg)) {
						message('温馨提示：该模板消息不存在！', '', 'error');
					}
					if (pdo_delete('lshd_yanzhi_tplmsg', array('uniacid' => $_W['uniacid'], 'id' => intval($_GPC['id'])))) {
						message('温馨提示：删除模板消息成功！', $this->createWebUrl('message'), 'success');
					}
					message('温馨提示：删除模板消息失败！', '', 'error');
				} else {
					if ($op == 'test') {
						$ids = $_GPC['id'];
					} else {
						$pageindex = max(1, intval($_GPC['page']));
						$pagesize = 20;
						$where = ' WHERE uniacid=:uniacid';
						$params = array(':uniacid' => $_W['uniacid']);
						$order = ' ORDER BY `createtime` DESC,`id` DESC';
						$sql = 'SELECT COUNT(*) FROM ' . tablename('lshd_yanzhi_tplmsg') . $where;
						$total = pdo_fetchcolumn($sql, $params);
						$pager = pagination($total, $pageindex, $pagesize);
						$sql = 'SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . $where . $order . ' LIMIT ' . ($pageindex - 1) * $pagesize . ',' . $pagesize;
						$list = pdo_fetchall($sql, $params);
					}
				}
			}
		}
		include $this->template('message');
	}
	public function doWebSendtest() {
		global $_W, $_GPC;
		load()->func('communication');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $_W['uniaccount']['key'] . '&secret=' . $_W['uniaccount']['secret']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$gettoken = curl_exec($curl);
		curl_close($curl);
		$settoken = json_decode($gettoken, true);
		$access_token = $settoken['access_token'];
		$tplmsg = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
		$result = $this->sendwxappmsg($_GPC['formid'], $_GPC['openid'], $tplmsg['mid'], $tplmsg['data'], $tplmsg['url'], $tplmsg['emphasis'], $access_token);
		return $result;
	}
	public function doWebSendmsg() {
		global $_W, $_GPC;
		load()->func('communication');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $_W['uniaccount']['key'] . '&secret=' . $_W['uniaccount']['secret']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$gettoken = curl_exec($curl);
		curl_close($curl);
		$settoken = json_decode($gettoken, true);
		$access_token = $settoken['access_token'];
		$ops = array('index', 'save', 'send', 'update', 'del');
		$op = empty($_GPC['op']) ? $ops[0] : trim($_GPC['op']);
		if ($op == 'save') {
			$tplmsg = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
			$tplmsg['data'] = json_decode($tplmsg['data'], true);
			load()->model('mc');
			// $groups = mc_fans_groups(true);
			if (checksubmit()) {
				if (empty($_GPC['remark'])) {
					message('温馨提示：请填写备注！', '', 'error');
				}
				$data = array('uniacid' => $_W['uniacid'], 'mid' => intval($_GPC['mid']), 'group' => intval($_GPC['type']) == 0 ? '-1' : trim($_GPC['group']), 'remark' => trim($_GPC['remark']), 'status' => 0, 'type' => intval($_GPC['type']), 'openids' => trim($_GPC['openids']), 'createtime' => TIMESTAMP);
				if (pdo_insert('lshd_yanzhi_tplmsg_bulk', $data)) {
					message('温馨提示：保存群发成功！即将进入群发任务……', $this->createWebUrl('sendmsg', array('op' => 'send', 'id' => pdo_insertid())), 'success');
				}
				message('温馨提示：保存群发失败！', '', 'error');
			}
		} else if ($op == 'send') {
			$id = intval($_GPC['id']);
			$bulk = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg_bulk') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
			if ($bulk['status'] == 0) {
				$tplmsg = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => $bulk['mid']));
				// $tplmsg['data'] = json_decode($tplmsg['data'], true);
				$where = 'uniacid=:uniacid AND formid IS NOT NULL';
				$params = array(':uniacid' => $_W['uniacid']);
				$page = max(1, intval($_GPC['page']));
				$pagesize = 100;
				$fansCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lshd_yanzhi_fans') . ' WHERE ' . $where, $params);
				$total = ceil($fansCount / $pagesize);
				$fans = pdo_fetchall('SELECT * FROM ' . tablename('lshd_yanzhi_fans') . ' WHERE ' . $where . ' LIMIT ' . ($page - 1) * $pagesize . ',' . $pagesize, $params);
				foreach ($fans as $key => $fan) {
					$this->sendwxappmsg($fans[$key]['formid'], $fans[$key]['openid'], $tplmsg['mid'], $tplmsg['data'], $tplmsg['url'], $tplmsg['emphasis'], $access_token);
				}
				if ($page < $total) {
					message('温馨提示：请不要关闭页面，群发任务正在进行中！（' . $page . '/' . $total . '）', $this->createWebUrl('sendmsg', array('op' => 'send', 'id' => $bulk['id'], 'page' => $page + 1)), 'error');
				} else {
					if ($page == $total) {
						if (pdo_update('lshd_yanzhi_tplmsg_bulk', array('status' => 2), array('uniacid' => $_W['uniacid'], 'id' => $bulk['id']))) {
							message('温馨提示：群发任务已完成！（' . $page . '/' . $total . '）', $this->createWebUrl('sendmsg', array('op' => 'index')), 'success');
						}
						message('温馨提示：群发任务出错！', '', 'error');
					} else {
						pdo_update('lshd_yanzhi_tplmsg_bulk', array('status' => 2), array('uniacid' => $_W['uniacid'], 'id' => $bulk['id']));
						message('温馨提示：该群发任务已完成！', $this->createWebUrl('sendmsg'), 'error');
					}
				}
			} else {
				if ($bulk['status'] == 1) {
					message('温馨提示：该群发任务正在进行中！', '', 'error');
				} else {
					if ($bulk['status'] == 2) {
						message('温馨提示：该群发任务已完成！', '', 'error');
					}
				}
			}
		} else if ($op == 'update') {
			if (checksubmit()) {
				if (empty($_GPC['remark'])) {
					message('温馨提示：请填写备注！', '', 'error');
				}
				$data = array('remark' => trim($_GPC['remark']));
				if (pdo_update('lshd_yanzhi_tplmsg_bulk', $data, array('uniacid' => $_W['uniacid'], 'id' => intval($_GPC['id'])))) {
					message('温馨提示：编辑群发任务成功！', referer(), 'success');
				}
				message('温馨提示：编辑群发任务失败！', '', 'error');
			}
			load()->model('mc');
			$groups = mc_fans_groups(true);
			$bulk = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg_bulk') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
			$tplmsg = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => $bulk['mid']));
			$tplmsg['data'] = json_decode($tplmsg['data'], true);
			if (empty($bulk)) {
				message('温馨提示：该群发任务不存在！', '', 'error');
			}
		} else if ($op == 'del') {
			$bulk = pdo_fetch('SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg_bulk') . ' WHERE `uniacid`=:uniacid AND `id`=:id', array(':uniacid' => $_W['uniacid'], ':id' => intval($_GPC['id'])));
			if (empty($bulk)) {
				message('温馨提示：该群发任务不存在！', '', 'error');
			}
			if (pdo_delete('lshd_yanzhi_tplmsg_bulk', array('uniacid' => $_W['uniacid'], 'id' => intval($_GPC['id'])))) {
				message('温馨提示：删除群发任务成功！', referer(), 'success');
			}
			message('温馨提示：删除群发任务失败！', '', 'error');
		} else {
			$pageindex = max(1, intval($_GPC['page']));
			$pagesize = 15;
			$where = ' WHERE uniacid=:uniacid';
			$params = array(':uniacid' => $_W['uniacid']);
			$order = ' ORDER BY `createtime` DESC,`id` DESC';
			$sql = 'SELECT COUNT(*) FROM ' . tablename('lshd_yanzhi_tplmsg_bulk') . $where;
			$total = pdo_fetchcolumn($sql, $params);
			$pager = pagination($total, $pageindex, $pagesize);
			$sql = 'SELECT * FROM ' . tablename('lshd_yanzhi_tplmsg_bulk') . $where . $order . ' LIMIT ' . ($pageindex - 1) * $pagesize . ',' . $pagesize;
			$list = pdo_fetchall($sql, $params);
		}

		include $this->template('sendmsg');
	}
	function sendwxappmsg($formid, $openid, $templateid, $datas, $urls, $emphasis, $access_token) {
		global $_W, $_GPC;
		//下面是要填充模板的信息
		$formwork = '{
            "touser": "' . $openid . '",
            "template_id": "' . $templateid . '",
            "page": "' . $urls . '",
            "form_id": "' . $formid . '",
            "data":' . $datas . ',
            "emphasis_keyword": "' . $emphasis . '"
        }';
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $access_token;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $formwork);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	public function doWebUser() {
		global $_W, $_GPC;
		$condition = ' uniacid = ' . $_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$user = pdo_fetchall("SELECT * FROM " . tablename('lshd_yanzhi_user') . " WHERE " . $condition . " ORDER BY id DESC LIMIT " . (($pindex - 1) * $psize) . "," . $psize);
		$counts = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('lshd_yanzhi_user') . " where " . $condition);
		$pager = pagination($counts, $pindex, $psize);
		include $this->template('user');
	}
	public function doWebSetpkg() {
		global $_W, $_GPC;
		header("Content-Security-Policy: upgrade-insecure-requests");
		define('THEME_URL', MODULE_URL . 'template/static/');
		$seting = pdo_fetchall("SELECT * FROM " . tablename('lshd_yanzhi_seting') . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']))[0];
		if ($_GPC['op'] == 'seting') {
			$data = array(
				'name' => $_GPC['name'],
				'color' => $_GPC['color'],
				'image' => $_GPC['image'],
				'banquan' => $_GPC['banquan'],
				'uniacid' => $_W['uniacid'],
				'wecatimg' => $_GPC['wecatimg'],
				'adid' => $_GPC['adid'],
				'backimg' => $_GPC['backimg'],
				'alipay' => $_GPC['alipay'],
			);
			if (empty($_GPC['ids'])) {
				$result = pdo_insert('lshd_yanzhi_seting', $data);
				if (!empty($result)) {
					message('设置成功！', $this->createWebUrl('setpkg'), 'success');
				} else {
					message('设置失败！', $this->createWebUrl('setpkg'), 'success');
				}
			} else {
				$result = pdo_update('lshd_yanzhi_seting', $data, array('id' => $_GPC['ids']));
				if (!empty($result)) {
					message('更新成功！', $this->createWebUrl('setpkg'), 'success');
				} else {
					message('更新失败！', $this->createWebUrl('setpkg'), 'success');
				}
			}
		}
		include $this->template('setpkg');
	}
	public function doWebAd() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$menuurl = pdo_fetchall("SELECT * FROM " . tablename('lshd_yanzhi_ad') . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
		if ($op == 'adds') {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'title' => $_GPC['title'],
				'urls' => $_GPC['urls'],
				'image' => $_GPC['image'],
				'type' => $_GPC['type'],
			);
			if (empty($_GPC['ids'])) {
				$result = pdo_insert('lshd_yanzhi_ad', $data);
				if (!empty($result)) {
					message('添加成功！', $this->createWebUrl('ad'), 'success');
				} else {
					message('添加失败！', $this->createWebUrl('ad'), 'success');
				}
			} else {
				$result = pdo_update('lshd_yanzhi_ad', $data, array('id' => $_GPC['ids']));
				if (!empty($result)) {
					message('更新成功！', $this->createWebUrl('ad'), 'success');
				} else {
					message('更新失败！', $this->createWebUrl('ad'), 'success');
				}
			}
		}
		if ($op == 'edit') {
			$edit = pdo_fetchall("SELECT * FROM " . tablename('lshd_yanzhi_ad') . " where id=:id", array(':id' => $_GPC['id']))[0];
		}
		if ($op == 'delete') {
			pdo_delete("lshd_yanzhi_ad", array('id' => $_GPC['id']));
			message(" 删除成功", referer(), 'success');
		}
		include $this->template('ad');
	}
	public function doWebSlider() {
		global $_W, $_GPC;
		$slides = pdo_fetchAll("SELECT * FROM " . tablename('lshd_yanzhi_slider') . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
		if ($_GPC['op'] == 'create') {
			$date = array(
				'remark' => $_GPC['remark'],
				'image' => $_GPC['image'],
				'uniacid' => $_W['uniacid'],
				'types' => $_GPC['types'],
				'inurl' => $_GPC['inurl'],
			);
			$result = pdo_insert('lshd_yanzhi_slider', $date);
			if (!empty($result)) {
				message('添加成功', $this->createWebUrl('slider'));
			} else {
				message('添加失败', $this->createWebUrl('slider'));
			}
		}
		if ($_GPC['op'] == 'edit') {
			$edit = pdo_fetchAll("SELECT * FROM " . tablename('lshd_yanzhi_slider') . " where id=" . $_GPC['id'] . " and uniacid=" . $_W['uniacid'] . "")[0];
		}
		if ($_GPC['op'] == 'delete') {
			pdo_delete("lshd_yanzhi_slider", array('id' => $_GPC['id']));
			message(" 删除成功", referer(), 'success');
		}
		if ($_GPC['op'] == 'update') {
			$date = array(
				'remark' => $_GPC['remark'],
				'image' => $_GPC['image'],
				'types' => $_GPC['types'],
				'inurl' => $_GPC['inurl'],
			);
			$result = pdo_update('lshd_yanzhi_slider', $date, array('id' => $_GPC['id']));
			if (!empty($result)) {
				message('更新成功', $this->createWebUrl('slider'));
			} else {
				message('错误！', $this->createWebUrl('slider'));
			}
		}
		include $this->template('slider');
	}
	//////////////////////////
	public function doWebMenu() {
		global $_W, $_GPC;
		header("Content-Security-Policy: upgrade-insecure-requests");
		define('THEME_URL', MODULE_URL . 'template/static/');
		$slides = pdo_fetchAll("SELECT * FROM " . tablename('lshd_yanzhi_menu') . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
		if ($_GPC['op'] == 'create') {
			if ($_GPC['types'] > -1) {
				$date = array(
					'title' => $_GPC['title'],
					'image' => $_GPC['image'],
					'uniacid' => $_W['uniacid'],
					'types' => $_GPC['types'],
					'inurl' => $_GPC['inurl'],
					'sort' => $_GPC['sort'],
					'visit' => $_GPC['visit'],
				);
				$result = pdo_insert('lshd_yanzhi_menu', $date);
				if (!empty($result)) {
					message('添加成功', $this->createWebUrl('menu'));
				} else {
					message('添加失败', $this->createWebUrl('menu'));
				}
			} else {
				message(" 请选择类型", '', 'success');
			}
		}
		if ($_GPC['op'] == 'edit') {
			$edit = pdo_fetchAll("SELECT * FROM " . tablename('lshd_yanzhi_menu') . " where id=" . $_GPC['id'] . " and uniacid=" . $_W['uniacid'])[0];
		}
		if ($_GPC['op'] == 'delete') {
			pdo_delete("lshd_yanzhi_menu", array('id' => $_GPC['id']));
			message(" 删除成功", $this->createWebUrl('menu'), 'success');
		}
		if ($_GPC['op'] == 'update') {
			$date = array(
				'title' => $_GPC['title'],
				'image' => $_GPC['image'],
				'types' => $_GPC['types'],
				'inurl' => $_GPC['inurl'],
				'sort' => $_GPC['sort'],
				'visit' => $_GPC['visit'],
			);
			$result = pdo_update('lshd_yanzhi_menu', $date, array('id' => $_GPC['id']));
			if (!empty($result)) {
				message('更新成功', $this->createWebUrl('menu'));
			} else {
				message('错误！', $this->createWebUrl('menu'));
			}
		}
		if ($_GPC['op'] == 'chucd') {
			pdo_delete("lshd_yanzhi_menu", array('uniacid' => $_W['uniacid']));
			$sql = "INSERT INTO " . tablename('lshd_yanzhi_menu') . "(uniacid,title,image,types,inurl,sort,visit) VALUES (" . $_W['uniacid'] . ",'颜值','',0,'',100,1),(" . $_W['uniacid'] . ",'魔幻变脸','',1,'../facemerge/facemerge?name=getFacemerge&types=0',1,1),(" . $_W['uniacid'] . ",'大头贴','',1,'../facemerge/facemerge?name=getFacesticker&types=1',99,1),(" . $_W['uniacid'] . ",'变妆','',1,'../facemerge/facemerge?name=getfacedecoration&types=2',1,1),(" . $_W['uniacid'] . ",'滤镜','',1,'../facemerge/facemerge?name=getimgfilter&types=3',1,1);";
			pdo_query($sql);
			message('初始化成功！', $this->createWebUrl('menu'));
		}
		include $this->template('menu');
	}
	public function doWebShare() {
		global $_GPC, $_W;
		$sql = "update " . tablename('lshd_yanzhi_user') . " set apply=" . $_GPC['nums'];
		pdo_query($sql);
	}
	public function doWebShop() {
		global $_W, $_GPC;
		$menuurl = pdo_fetchall("SELECT * FROM " . tablename('lshd_yanzhi_shop') . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']))[0];
		if ($_GPC['op'] == 'shop') {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'name' => $_GPC['name'],
				'urls' => $_GPC['urls'],
				'image' => $_GPC['image'],
				'types' => $_GPC['types'],
				'title' => $_GPC['title'],
			);
			if (empty($_GPC['ids'])) {
				if (!empty($_GPC['types'])) {
					$result = pdo_insert('lshd_yanzhi_shop', $data);
					if (!empty($result)) {
						message('添加成功！', $this->createWebUrl('shop'), 'success');
					} else {
						message('添加失败！', $this->createWebUrl('shop'), 'success');
					}
				} else {
					message('请选择类型！', '', 'success');
				}
			} else {
				$result = pdo_update('lshd_yanzhi_shop', $data, array('id' => $_GPC['ids']));
				if (!empty($result)) {
					message('更新成功！', $this->createWebUrl('shop'), 'success');
				} else {
					message('更新失败！', $this->createWebUrl('shop'), 'success');
				}
			}
		}
		include $this->template('shop');
	}
}