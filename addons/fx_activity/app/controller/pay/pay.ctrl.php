<?php

/**

 * [WeEngine System] Copyright (c) 2014 WE7.CC

 * WeEngine is NOT a free software, it under the license terms, visited http://bbs.mhyma.com/ for more details.

 */

define('IN_MOBILE', true);

if($_GPC['done'] == '1') {

	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';

	$pars = array();

	$pars[':tid'] = $_GPC['tid'];

	$log = pdo_fetch($sql, $pars);

	if(!empty($log) && !empty($log['status'])) {

		if (!empty($log['tag'])) {

			$tag = iunserializer($log['tag']);

			$log['uid'] = $tag['uid'];

		}

		$site = WeUtility::createModuleSite($log['module']);

		if(!is_error($site)) {

			$method = 'payResult';

			if (method_exists($site, $method)) {

				$ret = array();

				$ret['weid'] = $log['uniacid'];

				$ret['uniacid'] = $log['uniacid'];

				$ret['result'] = 'success';

				$ret['type'] = $log['type'];

				$ret['from'] = 'return';

				$ret['tid'] = $log['tid'];

				$ret['uniontid'] = $log['uniontid'];

				$ret['user'] = $log['openid'];

				$ret['fee'] = $log['fee'];

				$ret['tag'] = $tag;

				$ret['is_usecard'] = $log['is_usecard'];

				$ret['card_type'] = $log['card_type'];

				$ret['card_fee'] = $log['card_fee'];

				$ret['card_id'] = $log['card_id'];

				exit($site->$method($ret));

			}

		}

	}

}

?>