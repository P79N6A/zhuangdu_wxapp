<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020.
// +----------------------------------------------------------------------
// | Describe: 活动操作模型类
// +----------------------------------------------------------------------
// | Author: woniu
// +----------------------------------------------------------------------
 class model_activity
 {
	/** 
	* 获取单条活动信息
	* 
	* @access static
	* @name getSingleActivity 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleActivity($id,$select,$where=array()){
		global $_W,$_GPC;
		$where['id'] = $id;
		$activity = Util::getSingelData($select,'fx_activity',$where);
		if(empty($activity)) return array();
		if($activity['merchantid']){
			$merchant = pdo_fetch("SELECT name FROM " . tablename('fx_merchant') . " WHERE uniacid = {$_W['uniacid']} and id={$activity['merchantid']}");
			$activity['merchantname'] = $merchant['name'];
		}
		$activity['storeids'] = unserialize($activity['storeids']);
		$activity['prize'] = unserialize($activity['prize']);
		$activity['atlas'] = unserialize($activity['atlas']);
		$activity['openids'] = unserialize($activity['openids']);
		$activity['tmplmsg'] = unserialize($activity['tmplmsg']);		
		$activity['kefu'] = unserialize($activity['kefu']);
		$activity['switch'] = unserialize($activity['switch']);
		$activity['falsedata'] = unserialize($activity['falsedata']);
		$activity['falsedata']['num'] = intval($activity['falsedata']['num']);
		$activity['falsedata']['read'] = intval($activity['falsedata']['read']);
		$activity['falsedata']['share'] = intval($activity['falsedata']['share']);
		
		return $activity;
	}
	/** 
	* 获取单表单信息
	* 
	* @access static
	* @name getSingleActivityForm
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleActivityForm($id){
		global $_W;
		$form = pdo_get('fx_form', array('id' => $id));
		return $form;
	}
	/** 
	* 获取规格选项
	* 
	* @access static
	* @name getSingleActivityOption
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getSingleActivityOption($id){
		global $_W;
		$option = pdo_get('fx_spec_option', array('id' => $id));
		return $option;
	}
	//读取单选规格的选项数据
	static function getSingleSpecOption($specid){
		global $_W;
		$option = pdo_get('fx_spec_option', array('specs' => $specid));
		return $option;
	}
	/** 
	* 获取指定主办方信息 
	* 
	* @access public
	* @name getActivityMerchant 
 	* @param $activity 活动内容
	* @return array 
	*/  		
	static function getActivityMerchant($id){
		global $_W;
		if ($id){
			$merchant  = model_merchant::getSingleMerchant($id, '*');//读取主办方
			$merchant['tel']     = $merchant['linkman_mobile'];
			$merchant['logo']    = tomedia($merchant['logo']);
			$merchant['kefuimg'] = tomedia($merchant['kefuimg']);
			$mcert = Util::getSingelData('*', 'fx_merchant_mcert', array('mid' => $id));
			if (empty($mcert)){
				$merchant['iscert'] = 0;
			}elseif ($mcert['status']==1 && TIMESTAMP <= $mcert['endtime']){			
				$merchant['iscert'] = 1;
			}
		}else{
			$merchant['id']        = 0;
			$merchant['uid']       = 0;
			$merchant['name']      = $_W['_config']['sname'];
			$merchant['logo']      = tomedia($_W['_config']['slogo']);
			$merchant['kefuimg']   = tomedia($_W['_config']['kefu']['qrcode']);
			$merchant['detail']    = $_W['_config']['detail'];
			$merchant['tel']       = $_W['_config']['mobile'];
			$merchant['lng']       = $_W['_config']['lng'];
			$merchant['lat']       = $_W['_config']['lat'];
			$merchant['adinfo']    = $_W['_config']['adinfo'];
			$merchant['address']   = $_W['_config']['address'];
			$merchant['storename'] = $_W['_config']['storename'];
			$merchant['followno']  = $_W['_config']['followno'];
			$merchant['iscert']     = 1;
		}
		return $merchant;
	}
	/** 
	* 获取店铺 
	* 
	* @access static
	* @name getNumActivityStore 
	* @param mixed  参数一的说明 
	* @return array 
	*/  
	static function getNumActivityStore($storeids){
		global $_W;
		if(empty($storeids))return FALSE;
		foreach($storeids as$key=>$value){
			if($value)$stores[$key] =  pdo_fetch("select * from".tablename('fx_store')."where id ='{$value}' and uniacid={$_W['uniacid']}");
		}
		return $stores;
	}
	/** 
	* 获取表单 
	* 
	* @access static
	* @name getNumActivityForm 
	* @param $id  商品ID 
	* @return array 
	*/  
	static function getNumActivityForm($id,$acType = 'web'){
		//获取表单条目 Start
		global $_W;
		$condition = " uniacid = '{$_W['uniacid']}'";
		$allforms = pdo_fetchall("select * from " . tablename('fx_form')." where".$condition." and activityid=:id order by displayorder asc",array(":id"=>$id));
		$condition.= $acType == 'app' ? " and `show`=1" : "";
		foreach ($allforms as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('fx_form_item') . " where".$condition." and formid=".$s['id']." order by displayorder asc");
		}

		return array($allforms);
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
		//获取表单条目 Start
		global $_W;
		$condition = " uniacid = '{$_W['uniacid']}'";
		$allspecs = pdo_fetchall("select * from " . tablename('fx_spec')." where".$condition." and activityid=:id order by displayorder asc",array(":id"=>$id));
		$condition.= $acType == 'app' ? " and `show`=1" : "";
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('fx_spec_item') . " where".$condition." and specid=".$s['id']." order by displayorder asc");
		}
		$options = pdo_fetchall("select * from " . tablename('fx_spec_option') . " where activityid=:id order by id asc", array(':id' => $id));
		
		if (!defined('IN_SYS')) {
			if (!empty($allspecs[0]['items'])){
				foreach ($allspecs[0]['items'] as &$s) {
					$s['option'] = pdo_get("fx_spec_option", array('activityid' => $id, 'specs'=>$s['id']));
				}
			}
			return array($allspecs,'',$options,$specs);
		}
		$specs = array();
		if (count($options) > 0) {
			$specitemids = explode("_", $options[0]['specs']);
			foreach ($specitemids as $itemid) {
				foreach ($allspecs as $ss) {
					$items = $ss['items'];
					foreach ($items as $it) {
						if ($it['id'] == $itemid) {
							$specs[] = $ss;
							break;
						}
					}
				}
			}
			//获取规格条目 End
			//获取价格列表 Start
			$html = '';
			$html .= '<table class="table table-bordered table-condensed">';
			$html .= '<thead>';
			$html .= '<tr class="active">';
			$len = count($specs);
			$newlen = 1;
			//多少种组合
			$h = array();
			//显示表格二维数组
			$rowspans = array();
			//每个列的rowspan
			//计算每个列的行数 得到$rowspans和$newlen
			for ($i = 0; $i < $len; $i++) {
				//表头
				$html .= "<th style='width:80px;'>" . $specs[$i]['title'] . "</th>";
				//计算多种组合
				$itemlen = count($specs[$i]['items']);
				if ($itemlen <= 0) {
					$itemlen = 1;
				}
				$newlen *= $itemlen;
				//初始化 二维数组
				$h = array();
				for ($j = 0; $j < $newlen; $j++) {
					$h[$i][$j] = array();
				}
				//计算rowspan
				$l = count($specs[$i]['items']);
				$rowspans[$i] = 1;
				for ($j = $i + 1; $j < $len; $j++) {
					$rowspans[$i] *= count($specs[$j]['items']);
				}
			}
			//价格列表头部
			$html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_gnum_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_gnum\');"></a></span></div></div></th>';
			if ($_W['allow']['activity']['falseinfo'] || !$_W['allow']) {
			$html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">虚拟报名</div><div class="input-group"><input type="text" class="form-control option_falsenum_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_falsenum\');"></a></span></div></div></th>';
			}
			$html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">单价</div><div class="input-group"><input type="text" class="form-control option_aprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_aprice\');"></a></span></div></div></th>';
			if ($_W['plugin']['card']['config']['card_enable']){
			$html .= '<th class="danger optmarket" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">年卡价</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
			}
			//$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
			$html .= '</tr></thead>';
			//获得表格二维数组$h
			for ($m = 0; $m < $len; $m++) {
				$k = 0;
				$kid = 0;
				$n = 0;
				for ($j = 0; $j < $newlen; $j++) {
					$rowspan = $rowspans[$m];
					if ($j % $rowspan == 0) {
						$h[$m][$j] = array("html" => "<td rowspan='" . $rowspan . "'>" . $specs[$m]['items'][$kid]['title'] . "</td>", "id" => $specs[$m]['items'][$kid]['id']);
					}else{
						$h[$m][$j] = array("html" => "", "id" => $specs[$m]['items'][$kid]['id']);
					}
					$n++;
					if ($n == $rowspan) {
						$kid++;
						if ($kid > count($specs[$m]['items']) - 1) {
							$kid = 0;
						}
						$n = 0;
					}
				}
			}
			//获得整个价格列表
			$hh = "";
			for ($i = 0; $i < $newlen; $i++) {
				$hh .= "<tr>";
				$ids = array();
				for ($j = 0; $j < $len; $j++) {
					$hh .= $h[$j][$i]['html'];
					$ids[] = $h[$j][$i]['id'];
				}
				$ids = implode("_", $ids);
				$val = array("id" => "", "title" => "", "gnum" => "", "falsenum" => "", "costprice" => "", "aprice" => "", "marketprice" => "", "weight" => "");
				foreach ($options as $o) {
					if ($ids === $o['specs']) {
						$val = array("id" => $o['id'], 
							"title" => $o['title'], 
							"gnum" => $o['gnum'], 
							"falsenum" => $o['falsenum'], 
							"costprice" => $o['costprice'], 
							"aprice" => $o['aprice'], 
							"marketprice" => $o['marketprice'],
							"weight" => $o['weight']
						);
						break;
					}
				}
				$hh .= '<td class="info">';
				$hh .= '<input name="option_gnum_' . $ids . '[]"  type="text" class="form-control option_gnum option_gnum_' . $ids . '" value="' . $val['gnum'] . '"/></td>';
				$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
				$hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
				$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
				$hh .= '</td>';
				if ($_W['allow']['activity']['falseinfo'] || !$_W['allow']) {
				$hh .= '<td class="success"><input name="option_falsenum_' . $ids . '[]" type="text" class="form-control option_falsenum option_falsenum_' . $ids . '" value="' . $val['falsenum'] . '"/></td>';
				}
				$hh .= '<td class="warning"><input name="option_aprice_' . $ids . '[]" type="text" class="form-control option_aprice option_aprice_' . $ids . '" " value="' . $val['aprice'] . '"/></td>';
				if ($_W['plugin']['card']['config']['card_enable']){
				$hh .= '<td class="danger optmarket"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
				}
				$//hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
				$hh .= '</tr>';
			}
			$html .= $hh;
			$html .= "</table>";
		}
		//获取价格列表 End
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
			}else{
				pdo_update("fx_activity_param", $a, array('id' => $get_param_id));
				$param_id = $get_param_id;
			}
			$paramids[] = $param_id;
		}
		if (count($paramids) > 0) {
			pdo_query("delete from " . tablename('fx_activity_param') . " where activityid=$id and id not in ( " . implode(',', $paramids) . ")");
		}else{
			pdo_query("delete from " . tablename('fx_activity_param') . " where activityid=$id");
		}
	}
	/** 
	* 更新表单
	* 
	* @access static
	* @name UpdateForm 
	* @param $GPC     表单提交值
	* @return array 
	*/  
	static function UpdateForm($id,$_GPC){
		global $_W;
		$form_ids = $_GPC['form_id'];
		$form_titles = $_GPC['form_title'];
		$form_displaytypes = $_GPC['form_displaytype'];
		$form_essentials = $_GPC['form_essential'];
		$form_fieldstypes = $_GPC['form_fieldstype'];
		$len = count($form_ids);
		$formids = array();
		$form_items = array();
		
		for ($k = 0; $k < $len; $k++) {
			$form_id = "";
			$get_form_id = $form_ids[$k];
			$a = array(
				"uniacid" => $_W['uniacid'],
				"activityid" => $id,
				"displayorder" => $k,
				"title" => $form_titles[$get_form_id],
				"displaytype" => $form_displaytypes[$get_form_id],
				"essential" => $form_essentials[$get_form_id],
				"fieldstype" => $form_fieldstypes[$get_form_id]
			);
			//选项名
			if (is_numeric($get_form_id)) {
				pdo_update("fx_form", $a, array("id" => $get_form_id));
				$form_id = $get_form_id;
			}else{
				pdo_insert("fx_form", $a);
				$form_id = pdo_insertid();
			}
			//子项
			$form_item_ids = $_GPC["form_item_id_".$get_form_id];
			$form_item_titles = $_GPC["form_item_title_".$get_form_id];
			$form_item_shows = $_GPC["form_item_show_".$get_form_id];
			$form_item_thumbs = $_GPC["form_item_thumb_".$get_form_id];
			$form_item_oldthumbs = $_GPC["form_item_oldthumb_".$get_form_id];
			$itemlen = count($form_item_ids);
			$itemids = array();
			for ($n = 0; $n < $itemlen; $n++) {
				$item_id = "";
				$get_item_id = $form_item_ids[$n];
				$d = array(
					"uniacid" => $_W['uniacid'],
					"formid" => $form_id,
					"displayorder" => $n,
					"title" => $form_item_titles[$n],
					"show" => $form_item_shows[$n],
					"thumb"=>$form_item_thumbs[$n]
				);
				$f = "form_item_thumb_" . $get_item_id;
				if (is_numeric($get_item_id)) {
					pdo_update("fx_form_item", $d, array("id" => $get_item_id));
					$item_id = $get_item_id;
				}else{
					pdo_insert("fx_form_item", $d);
					$item_id = pdo_insertid();
				}
				$itemids[] = $item_id;
				//临时记录，用于保存表单项
				$d['get_id'] = $get_item_id;
				$d['id']= $item_id;
				$form_items[] = $d;
			}
			//删除其他的
			if(count($itemids)>0){
				pdo_query("delete from " . tablename('fx_form_item') . " where uniacid={$_W['uniacid']} and formid=$form_id and id not in (" . implode(",", $itemids) . ")");	
			}else{
				pdo_query("delete from " . tablename('fx_form_item') . " where uniacid={$_W['uniacid']} and formid=$form_id");	
			}
			//更新表单项id
			pdo_update("fx_form", array("content" => serialize($itemids)), array("id" => $form_id));
			$formids[] = $form_id;
		}
		//删除其他的
		if( count($formids)>0){
			$result = pdo_fetchall("select id from " . tablename('fx_form')." where uniacid={$_W['uniacid']} and activityid=$id and id not in (" . implode(",", $formids) . ")");		
			pdo_query("delete from " . tablename('fx_form') . " where uniacid={$_W['uniacid']} and activityid=$id and id not in (" . implode(",", $formids) . ")");
			if(!empty($result)) {
				$dl_formids = array();
				foreach($result as $k => $row) {
					$dl_formids[] = $row['id'];
				}
				pdo_query("delete from " . tablename('fx_form_item') . " where uniacid={$_W['uniacid']} and formid in (" . implode(",", $dl_formids) . ")");
			}
		}else{
			$result = pdo_fetchall("select id from " . tablename('fx_form')." where uniacid={$_W['uniacid']} and activityid=$id");
			pdo_query("delete from " . tablename('fx_form') . " where uniacid={$_W['uniacid']} and activityid=$id");
			if(!empty($result)) {
				$dl_formids = array();
				foreach($result as $k => $row) {
					$dl_formids[] = $row['id'];
				}
				pdo_query("delete from " . tablename('fx_form_item') . " where uniacid={$_W['uniacid']} and formid in (" . implode(",", $dl_formids) . ")");
			}
		}
	}
	/** 
	* 更新规格
	* 
	* @access static
	* @name UpdateSpec 
	* @param $GPC     规格提交值
	* @return array 
	*/  
	static function UpdateSpec($id,$_GPC){
		global $_W;
		$spec_ids = $_GPC['spec_id'];
		$spec_titles = $_GPC['spec_title'];
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
				"title" => $spec_titles[$get_spec_id]
			);
			//选项名
			if (is_numeric($get_spec_id)) {
				pdo_update("fx_spec", $a, array("id" => $get_spec_id));
				$spec_id = $get_spec_id;
			}else{
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
				}else{
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
			}else{
				pdo_query("delete from " . tablename('fx_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id");	
			}
			//更新表单项id
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
		}else{
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
		
		//保存规格
		$option_idss = $_GPC['option_ids'];
		$len = count($option_idss);
		$optionids = array();
		for ($k = 0; $k < $len; $k++) {
			$option_id = "";
			$ids = $option_idss[$k];
			$get_option_id = $_GPC['option_id_' . $ids][0];
			$idsarr = explode("_",$ids);
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
				"aprice" => $_GPC['option_aprice_' . $ids][0],
				"marketprice" => $_GPC['option_marketprice_' . $ids][0],
				"costprice" => $_GPC['option_costprice_' . $ids][0],
				"gnum" => $_GPC['option_gnum_' . $ids][0],
				"falsenum" => $_GPC['option_falsenum_' . $ids][0],
				"activityid" => $id,
				"specs" => $newids
			);
			
			$totalgnum+=$a['gnum'];
			if (empty($get_option_id)) {
				pdo_insert("fx_spec_option", $a);
				$option_id = pdo_insertid();
			}else{
				pdo_update("fx_spec_option", $a, array('id' => $get_option_id));
				$option_id = $get_option_id;
			}
			$optionids[] = $option_id;
		}
		if (count($optionids) > 0) {
			pdo_query("delete from " . tablename('fx_spec_option') . " where activityid=$id and id not in ( " . implode(',', $optionids) . ")");
		}else{
			pdo_query("delete from " . tablename('fx_spec_option') . " where activityid=$id");
		}
	}
	/** 
	* 更新优惠 
	* 
	* @access static
	* @name updateMarketing 
	* @type discount  //折扣
			enougth   //满减
			deduction //抵扣
	* @param  $meet   //满足条件值
			  $give   //优惠值：百分比或金额
	* @return array 
	*/  
	static function updateMarketing($discount,$enough,$deduction,$mcgroup,$id){
		global $_W;
		$marketing1 = pdo_fetch("select type from".tablename("fx_marketing")."where activityid={$id} and type=1");
		$marketing2 = pdo_fetch("select type from".tablename("fx_marketing")."where activityid={$id} and type=2");
		$marketing3 = pdo_fetch("select type from".tablename("fx_marketing")."where activityid={$id} and type=3");
		$marketing4 = pdo_fetch("select type from".tablename("fx_marketing")."where activityid={$id} and type=4");
		
		$v1 = !empty($discount)?serialize($discount):''; // 折扣
		if($marketing1){
			pdo_update("fx_marketing",array('value'=>$v1),array('activityid'=>$id,'type'=>1));
		}elseif(!empty($discount)){
			pdo_insert("fx_marketing",array('activityid'=>$id,'uniacid'=>$_W['uniacid'],'type'=>1,'value'=>$v1));
		}
	
		$v2 = !empty($enough)?serialize($enough):''; // 满减
		if($marketing2){
			pdo_update("fx_marketing",array('value'=>$v2),array('activityid'=>$id,'type'=>2));
		}elseif(!empty($enough)){
			pdo_insert("fx_marketing",array('activityid'=>$id,'uniacid'=>$_W['uniacid'],'type'=>2,'value'=>$v2));
		}	
		$v3 = !empty($mcgroup)?serialize($mcgroup):''; //会员级别优惠
		if($marketing3){
			pdo_update("fx_marketing",array('value'=>$v3),array('activityid'=>$id,'type'=>3));
		}elseif(!empty($mcgroup)){
			pdo_insert("fx_marketing",array('activityid'=>$id,'uniacid'=>$_W['uniacid'],'type'=>3,'value'=>$v3));
		}
		$v4 = serialize($deduction); //抵扣
		if(!empty($deduction)){
			if($marketing4){
				pdo_update("fx_marketing",array('value'=>$v4),array('activityid'=>$id,'type'=>4));
			}else{
				pdo_insert("fx_marketing",array('activityid'=>$id,'uniacid'=>$_W['uniacid'],'type'=>4,'value'=>$v4));
			}
		}
	}
	/** 
	* 获取优惠
	* 
	* @access static
	* @name getMarketing 
	* @param  $id
	* @return array 
	*/  
	static function getMarketing($id){
		global $_W;
		$marketing1 = pdo_fetch("select value from".tablename("fx_marketing")."where activityid={$id} and type=1");
		$marketing2 = pdo_fetch("select value from".tablename("fx_marketing")."where activityid={$id} and type=2");
		$marketing3 = pdo_fetch("select value from".tablename("fx_marketing")."where activityid={$id} and type=3");
		$marketing4 = pdo_fetch("select value from".tablename("fx_marketing")."where activityid={$id} and type=4");
		$v1 = !empty($marketing1['value'])?unserialize($marketing1['value']):array(); //折扣
		$v2 = !empty($marketing2['value'])?unserialize($marketing2['value']):array(); //满减
		$v3 = !empty($marketing3['value'])?unserialize($marketing3['value']):array(); //会员级别
		$v4 = !empty($marketing4['value'])?unserialize($marketing4['value']):array();//抵扣
		$v = array();
		if (!empty($v1) || !empty($v2) || !empty($v3) || !empty($v4)){
			$v = array($v1,$v2,$v3,$v4);
		}
		return $v;
	}

}