<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class AccountModules extends \We7Table {
	protected $tableName = 'uni_account_modules';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'module',
		'enabled',
		'shortcut',
		'displayorder',
		'settings',
		'display',
	);
	protected $default = array(
		'uniacid' => '',
		'module' => '',
		'enabled' => 0,
		'shortcut' => 0,
		'displayorder' => 0,
		'settings' => '',
		'display' => '',
	);

}