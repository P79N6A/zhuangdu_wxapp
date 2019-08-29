<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020.
// +----------------------------------------------------------------------
// | Describe: 活动操作模型类
// +----------------------------------------------------------------------
// | Author: woniu
// +----------------------------------------------------------------------
 class activity
 {
	/** 
	* 获取单条活动信息
	* 
	* @access static
	* @name getSingleActivity 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleActivity($id){
		global $_W;
		$activity = pdo_get('fx_activity', array('uniacid'=>$_W['uniacid'],'id' => $id));
		return $activity;
	}

	/** 
	* 获取单规格信息
	* 
	* @access static
	* @name getSingleActivity 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleActivitySpec($id){
		global $_W;
		$spec = pdo_get('fx_spec', array('uniacid'=>$_W['uniacid'],'id' => $id));
		return $spec;
	}
	
	/** 
	* 获取店铺 
	* 
	* @access static
	* @name getSingleActivityStore 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleActivityStore($id){
		$activityInfo = activity::getSingleActivity($id, '*', array('id'=>$id));
		if(empty($activityInfo['hexiao_id']))return FALSE;
		foreach($activityInfo['hexiao_id'] as$key=>$value){
			if($value)$stores[$key] =  pdo_fetch("select * from".tablename('fx_store')."where id ='{$value}' and uniacid='{$activityInfo['uniacid']}'");
		}
		return array($stores);
	}
	/** 
	* 获取规格 
	* 
	* @access static
	* @name getNumActivitySpec 
	* @param $id  商品ID 
	* @return array 
	*/  
	static function getNumActivitySpec($id,$acType = 'web'){
		//获取规格条目 Start
		global $_W;
		$condition = " uniacid = '{$_W['uniacid']}'";
		$allspecs = pdo_fetchall("select * from " . tablename('fx_spec')." where".$condition." and activityid=:id order by displayorder asc",array(":id"=>$id));
		$condition.= $acType == 'app' ? " and `show`=1" : "";
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('fx_spec_item') . " where".$condition." and specid=".$s['id']." order by displayorder asc");
		}

		return array($allspecs,$html,$options,$specs);
	}
	
	/** 
	* 更新自定义属性 
	* 
	* @access static
	* @name UpdateParam 
	* @param $id  商品ID
	* @param $param_ids     属性ID数组
	* @param $param_titles  属性名称数组
	* @param $param_values  属性值数组
	* @return array 
	*/  
	static function UpdateParam($id,$param_ids,$param_titles,$param_values,$tag){
		$len = count($param_ids);
		$paramids = array();
		for ($k = 0; $k < $len; $k++) {
			$param_id = "";
			$get_param_id = $param_ids[$k];
			$a = array("title" => $param_titles[$k], "value" => $param_values[$k], "displayorder" => $k, "activityid" => $id,"tagcontent" => serialize($tag));
			if (!is_numeric($get_param_id)) {
				pdo_insert("fx_activity_param", $a);
				$param_id = pdo_insertid();
			} else {
				pdo_update("fx_activity_param", $a, array('id' => $get_param_id));
				$param_id = $get_param_id;
			}
			$paramids[] = $param_id;
		}
		if (count($paramids) > 0) {
			pdo_query("delete from " . tablename('fx_activity_param') . " where activityid=$id and id not in ( " . implode(',', $paramids) . ")");
		} else {
			pdo_query("delete from " . tablename('fx_activity_param') . " where activityid=$id");
		}
	}
	/** 
	* 更新规格 
	* 
	* @access static
	* @name UpdateSpec 
	* @param $GPC     表单提交值
	* @return array 
	*/  
	static function UpdateSpec($id,$_GPC){
		global $_W;
		$spec_ids = $_GPC['spec_id'];
		$spec_titles = $_GPC['spec_title'];
		$spec_displaytypes = $_GPC['spec_displaytype'];
		$spec_essentials = $_GPC['spec_essential'];
		$len = count($spec_ids);
		$specids = array();
		$spec_items = array();
		
		for ($k = 0; $k < $len; $k++) {
			$spec_id = "";
			$get_spec_id = $spec_ids[$k];
			$a = array(
				"uniacid" => $_W['uniacid'],
				"activityid" => $id,
				"displayorder" => $k,
				"title" => $spec_titles[$get_spec_id],
				"displaytype" => $spec_displaytypes[$get_spec_id],
				"essential" => $spec_essentials[$get_spec_id]
			);
			//选项名
			if (is_numeric($get_spec_id)) {
				pdo_update("fx_spec", $a, array("id" => $get_spec_id));
				$spec_id = $get_spec_id;
			} else {
				pdo_insert("fx_spec", $a);
				$spec_id = pdo_insertid();
			}
			//子项
			$spec_item_ids = $_GPC["spec_item_id_".$get_spec_id];
			$spec_item_titles = $_GPC["spec_item_title_".$get_spec_id];
			$spec_item_shows = $_GPC["spec_item_show_".$get_spec_id];
			$spec_item_thumbs = $_GPC["spec_item_thumb_".$get_spec_id];
			$spec_item_oldthumbs = $_GPC["spec_item_oldthumb_".$get_spec_id];
			$itemlen = count($spec_item_ids);
			$itemids = array();
			for ($n = 0; $n < $itemlen; $n++) {
				$item_id = "";
				$get_item_id = $spec_item_ids[$n];
				$d = array(
					"uniacid" => $_W['uniacid'],
					"specid" => $spec_id,
					"displayorder" => $n,
					"title" => $spec_item_titles[$n],
					"show" => $spec_item_shows[$n],
					"thumb"=>$spec_item_thumbs[$n]
				);
				$f = "spec_item_thumb_" . $get_item_id;
				if (is_numeric($get_item_id)) {
					pdo_update("fx_spec_item", $d, array("id" => $get_item_id));
					$item_id = $get_item_id;
				} else {
					pdo_insert("fx_spec_item", $d);
					$item_id = pdo_insertid();
				}
				$itemids[] = $item_id;
				//临时记录，用于保存规格项
				$d['get_id'] = $get_item_id;
				$d['id']= $item_id;
				$spec_items[] = $d;
			}
			//删除其他的
			if(count($itemids)>0){
				pdo_query("delete from " . tablename('fx_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");	
			}
			else{
				pdo_query("delete from " . tablename('fx_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id");	
			}
			//更新规格项id
			pdo_update("fx_spec", array("content" => serialize($itemids)), array("id" => $spec_id));
			$specids[] = $spec_id;
		}
		//删除其他的
		if( count($specids)>0){
			$result = pdo_fetchall("select id from " . tablename('fx_spec')." where uniacid={$_W['uniacid']} and activityid=$id and id not in (" . implode(",", $specids) . ")");		
			pdo_query("delete from " . tablename('fx_spec') . " where uniacid={$_W['uniacid']} and activityid=$id and id not in (" . implode(",", $specids) . ")");
			if(!empty($result)) {
				$dl_specids = array();
				foreach($result as $k => $row) {
					$dl_specids[] = $row['id'];
				}
				pdo_query("delete from " . tablename('fx_spec_item') . " where uniacid={$_W['uniacid']} and specid in (" . implode(",", $dl_specids) . ")");
			}
		}
		else{
			$result = pdo_fetchall("select id from " . tablename('fx_spec')." where uniacid={$_W['uniacid']} and activityid=$id");
			pdo_query("delete from " . tablename('fx_spec') . " where uniacid={$_W['uniacid']} and activityid=$id");
			if(!empty($result)) {
				$dl_specids = array();
				foreach($result as $k => $row) {
					$dl_specids[] = $row['id'];
				}
				pdo_query("delete from " . tablename('fx_spec_item') . " where uniacid={$_W['uniacid']} and specid in (" . implode(",", $dl_specids) . ")");
			}
		}
/** 
		//保存规格
		$option_idss = $_GPC['option_ids'];
		$option_productprices = $_GPC['option_productprice'];
		$option_marketprices = $_GPC['option_marketprice'];
		$option_costprices = $_GPC['option_costprice'];
		$option_stocks = $_GPC['option_stock'];
		$option_weights = $_GPC['option_weight'];
		$len = count($option_idss);
		$optionids = array();
		for ($k = 0; $k < $len; $k++) {
			$option_id = "";
			$get_option_id = $_GPC['option_id_' . $ids][0];
			
			$ids = $option_idss[$k]; $idsarr = explode("_",$ids);
			$newids = array();
			foreach($idsarr as $key=>$ida){
				foreach($spec_items as $it){
					if($it['get_id']==$ida){
						$newids[] = $it['id'];
						break;
					}
				}
			}
			
			$newids = implode("_",$newids);
			$a = array(
				"title" => $_GPC['option_title_' . $ids][0],
				"productprice" => $_GPC['option_productprice_' . $ids][0],
				"costprice" => $_GPC['option_costprice_' . $ids][0],
				"stock" => $_GPC['option_stock_' . $ids][0],
				"marketprice" => $_GPC['option_marketprice_' . $ids][0],
				"activityid" => $id,
				"specs" => $newids
			);
			$totalstocks+=$a['stock'];
			if (empty($get_option_id)) {
				pdo_insert("fx_activity_option", $a);
				$option_id = pdo_insertid();
			} else {
				pdo_update("fx_activity_option", $a, array('id' => $get_option_id));
				$option_id = $get_option_id;
			}
			$optionids[] = $option_id;
		}
		if (count($optionids) > 0) {
			pdo_query("delete from " . tablename('fx_activity_option') . " where activityid=$id and id not in ( " . implode(',', $optionids) . ")");
		}else{
			pdo_query("delete from " . tablename('fx_activity_option') . " where activityid=$id");
		}
	*/ 
	}

}