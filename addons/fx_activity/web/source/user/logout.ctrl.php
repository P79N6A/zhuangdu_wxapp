<?php
defined('IN_IA') or exit('Access Denied');
$_SESSION['role']='';
isetcookie('___shop_session___', '', -7 * 86400);
isetcookie('merchantid', '', -7 * 86400);
@header('Location: ' . $_W['siteroot'] . 'addons/'.MODULE_NAME.'/web/merch.php');