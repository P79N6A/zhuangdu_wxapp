<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020.
// +----------------------------------------------------------------------
// | Describe: 报名操作模型类
// +----------------------------------------------------------------------
// | Author: woniu
// +----------------------------------------------------------------------
 class records
 {
	/** 
	* 获取单条报名信息
	* 
	* @access static
	* @name getSingleRecords 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleRecords($id){
		global $_W;
		$records = pdo_get('fx_activity_records', array('uniacid'=>$_W['uniacid'],'id' => $id));
		return $records;
	}
	
	static function getNumSpecData($id){
		global $_W;
		$specdata = pdo_fetchall("select * from " . tablename('fx_spec_data') . " where recordid=".$id." order by id asc");
		return $specdata;
	}

}