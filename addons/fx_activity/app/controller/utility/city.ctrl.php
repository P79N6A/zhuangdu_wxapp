<?php 

/**

 * [WeEngine System] Copyright (c) 2014 WE7.CC

 * WeEngine is NOT a free software, it under the license terms, visited http://bbs.mhyma.com/ for more details.

 */

global $_W,$_GPC;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if($op =='display'){

	$pagetitle = '选择城市';

	include $this->template ('utility/city');

	exit;

}