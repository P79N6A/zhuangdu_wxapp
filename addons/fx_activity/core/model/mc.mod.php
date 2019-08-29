<?php
/**
 * [woniu] Copyright (c) 2016/8/18 
 * mc_
 */
//根据UID获取openid
if (IMS_VERSION=='0.8'){//兼容1.0,mc_uid2openid()
	function mc_uid2openid($uid) {
		global $_W;
		if (!is_array($uid)) {
			$sql = 'SELECT openid FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid`=:uniacid AND `uid`=:uid';
			$pars = array();
			$pars[':uniacid'] = $_W['uniacid'];
			$pars[':uid'] = $uid;
			$openid = pdo_fetchcolumn($sql, $pars);
			return $openid;
		}
		if (is_array($uid)) {
			$openids = array();
			foreach ($uid as $k => $v) {
				$fans[] = $v;
			}
			if (!empty($fans)) {
				$sql = 'SELECT uid, openid FROM ' . tablename('mc_mapping_fans') . " WHERE `uniacid`=:uniacid AND `uid` IN ('" . implode("','", $fans) . "')";
				$pars = array(':uniacid' => $_W['uniacid']);
				$fans = pdo_fetchall($sql, $pars, 'openid');
				$fans = array_keys($fans);
				$openids = array_merge((array)$openids, $fans);
			}
			return $openids;
		}
		return false;
	}
}

function mc_fans_follow($uid) {
	global $_W;
	$uid = mc_openid2uid($uid);

	$sql = 'SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid`=:uniacid AND `uid`=:uid AND `follow`=:follow';
	$pars = array();
	$pars[':uniacid'] = $_W['uniacid'];
	$pars[':uid'] = $uid;
	$pars[':follow'] = 1;
	$column = pdo_fetchcolumn($sql, $pars);
	if ($column) return true;
	return false;
}