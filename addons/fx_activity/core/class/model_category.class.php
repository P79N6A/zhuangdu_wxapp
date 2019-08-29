<?php 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2050.
// +----------------------------------------------------------------------
// | Describe: 活动分类
// +----------------------------------------------------------------------
class model_category
{
	/** 
 	* 获取单条分类数据 
 	* 
 	* @access static
 	* @name getSingleCategory 
 	* @param $id      缓存标志 
 	* @param $select  查询参数 
 	* @param $where   查询条件 
 	* @return array 
 	*/  
	static function getSingleCategory($id,$select,$where){
		$id = intval($id);
		return Util::getSingelData($select,'fx_category',$where=array('id'=>$id));
		//需删除缓存
	}
	/** 
 	* 获取所有分类数据 
 	* 
 	* @access static
 	* @name getNumCategory 
 	* @return array 
 	*/  
	static function getNumCategory(){
		global $_W;
		if($_SESSION['role_id']) $where = array('parentid'=>0,'&open&'=>1);
		else $where = array('parentid'=>0);
		$where['enabled'] = 1;
		$allParentCategory = Util::getNumData('*','fx_category',$where,'id desc',0,0,0);
		foreach($allParentCategory[0] as $key=>$value){
			if (!empty($value['redirect'])){//去出掉连接类型
				unset($allParentCategory[0][$key]);
			}else{
				$category_childs = pdo_fetchall("SELECT id,name FROM " . tablename('fx_category') . " WHERE uniacid = {$_W['uniacid']} and parentid={$value['id']} and enabled=1 ORDER BY displayorder DESC");
				$childs[$value['id']] = $category_childs;
			}
		}
		return array($allParentCategory[0],$childs);
		//需删除缓存
	}
	/** 
 	* 删除分类数据 
 	* 
 	* @access static
 	* @name getNumCategory 
 	* @return array 
 	*/  
	static function deleteCategory($id){
		$id = intval($id);
		$res = pdo_delete('fx_category',array('id'=>$id));
		Util::deleteCache('category',$id);
		Util::deleteCache('category','allCategory');		
		return $res;
	}	
	
	
}