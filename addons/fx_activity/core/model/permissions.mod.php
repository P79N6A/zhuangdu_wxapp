<?php
/**
 * [woniu] Copyright (c) 2016 WE7.CC
 * 主办方权限mod
 */
defined('IN_IA') or exit('Access Denied');
global $_W;
function allow_params() {
	if (defined('IN_SYS')) {
		$result['records']['status']      = allow('records', 'records', 'status', MERCHANTID);
		$result['records']['canceljoin']  = allow('records', 'records', 'canceljoin', MERCHANTID);
		$result['records']['confrimjoin'] = allow('records', 'records', 'confrimjoin', MERCHANTID);
		$result['records']['confrimpay']  = allow('records', 'records', 'confrimpay', MERCHANTID);
		$result['records']['cancelpay']   = allow('records', 'records', 'cancelpay', MERCHANTID);
		$result['records']['hexiao']      = allow('records', 'records', 'hexiao', MERCHANTID);
		$result['records']['delete']      = allow('records', 'records', 'delete', MERCHANTID);
		$result['records']['refund']      = allow('records', 'records', 'refund', MERCHANTID);
		$result['records']['review']      = allow('records', 'records', 'review', MERCHANTID);
		
		$result['activity']['sms']          = allow('activity', 'activity', 'sms', MERCHANTID);
		$result['activity']['credit']       = allow('activity', 'activity', 'credit', MERCHANTID);
		$result['activity']['displayorder'] = allow('activity', 'activity', 'displayorder', MERCHANTID);
		$result['activity']['recommend']    = allow('activity', 'activity', 'recommend', MERCHANTID);
		$result['activity']['delete']       = allow('activity', 'activity', 'delete', MERCHANTID);
		$result['activity']['falseinfo']    = allow('activity', 'activity', 'falseinfo', MERCHANTID);
	}else{
		$result['activity']['post']      = allow('mobile', 'activity', 'post', MERCHANTID);
	}
	$result['activity']['proreview']    = allow('activity', 'activity', 'proreview', MERCHANTID);
	return $result;
} 