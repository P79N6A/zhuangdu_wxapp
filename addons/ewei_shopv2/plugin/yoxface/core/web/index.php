<?php
/**
 * 颜值测试
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Index_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
        $this->face_list();
	}
	public function face_list(){
	    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
	    //报告所有错误
	    //error_reporting(E_ALL);
	    //ini_set("display_errors","On");
	    global $_GPC, $_W;
	    
	    $uid=$_GPC['uid'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = $_GPC['psize']?$_GPC['psize']:20;
	    
	    //         $uid=$_W['uid'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $where = " face.uniacid = '{$uniacid}' ";
	    $uid && ($where.=" AND face.uid = '{$uid}' ");
	    
	    $left_join_member="LEFT JOIN " . tablename('mc_members') . " `members` ON (`members`.uid=face.uid)";
	    $left_join_suggest="LEFT JOIN " . tablename('ewei_shop_yoxface_suggest') . " `suggest` ON (`suggest`.face_value_lt>=face.facequality)";;
	    $member_field=" `members`.nickname,`members`.avatar";
	    $suggest_field=" `suggest`.suggest,`suggest`.goods_ids,`suggest`.package_ids ";
	    $list = pdo_fetchall("SELECT face.*,FROM_UNIXTIME(face.add_time) AS add_time_format,{$member_field},{$suggest_field} FROM " . tablename('ewei_shop_yoxface') . " face ".
	   	    $left_join_member.
	   	    $left_join_suggest.
	   	    " WHERE $where ORDER BY face.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	   	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxface') . " face WHERE $where");
	   	    $pager = pagination($total, $pindex, $psize);
	   	    
	   	    //pdo_debug();
	   	    $result['status']=1;
	   	    $result['data']['list']=$list;
	   	    $result['data']['pager']=$pager;
	   	    
	   	    // 	    pdo_debug();
	   	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	   	    $_GPC['isdebug']&& print_r($result);
	   	    include($this->template());
	}
	public function face_edit(){
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $value = $_GPC['value'];
	    $suggest = $_GPC['suggest'];
	    $gender = $_GPC['gender'];
	    $status = $_GPC['status']!=''?$_GPC['status']:1;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($value!=''||$suggest!=''||$gender){
	        $data=array();
	        $value  && $data['value']=$value;
	        $suggest&& $data['suggest']=$suggest;
	        $gender && $data['gender']=$gender;
// 	        $status!='' && $data['status']=$status;
	        $data['add_time']=time();

	        !$id && pdo_insert('ewei_shop_yoxface', $data);
	        $id  && pdo_update("ewei_shop_yoxface", $data, array("id" => $id));
	        
	        //show_json(1, "成功");
	        if($this->is_from_wechat) return array('status'=>1,'message'=>'ok');
	        message('OK', $this->createWebUrl('yoxface', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxface') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $user_info = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uid' => $info['uid'], ':uniacid' => $uniacid));
	    //无此表
	    //$cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_article_cate') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template());
	}
	/**
	 * 颜值建议
	 */
	public function face_value_suggest_list(){
	    global $_GPC, $_W;
	    
	    $uid=$_GPC['uid'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = $_GPC['psize']?$_GPC['psize']:20;
	    
	    //$uid=$_W['uid'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $where = " suggest.uniacid = '{$uniacid}' ";
	    
	    $list = pdo_fetchall("SELECT suggest.*,FROM_UNIXTIME(suggest.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxface_suggest') . " suggest ".
   	    " WHERE $where ORDER BY suggest.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
   	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxface_suggest') . " suggest WHERE $where");
   	    $pager = pagination($total, $pindex, $psize);
   	    
   	    foreach($list as &$info){
   	        $goods_ids=array_merge((array)$goods_ids,(array)explode(',',$info['goods_ids']));
   	        $package_ids=array_merge((array)$package_ids,(array)explode(',',$info['package_ids']));
   	        $info['goods_ids_arr']=$goods_ids;
   	        $info['package_ids_arr']=$package_ids;
   	    }
   	    $goods_ids_str=implode(',', $goods_ids);
   	    $package_ids_str=implode(',', $package_ids);
   	    unset($info);
   	    $goods_list = pdo_fetchall("SELECT goods.id,goods.title,goods.marketprice FROM " . tablename('ewei_shop_goods') . " goods ".
   	        " WHERE id in($goods_ids_str) ORDER BY goods.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
   	    $package_list = pdo_fetchall("SELECT package.id,package.title,price FROM " . tablename('ewei_shop_package') . " package ".
   	        " WHERE id in($package_ids_str)  ORDER BY package.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
   	    foreach($list as &$info){
   	        foreach($goods_list as $goods_info){
   	            if(in_array($goods_info['id'],$info['goods_ids_arr'])){
   	                $info['goods_list'][]=$goods_info;
                }
            }
            foreach($package_list as $package_info){
                if(in_array($package_info['id'],$info['package_ids_arr'])){
                    $info['package_list'][]=$package_info;
                }
            }
   	    }
   	    unset($info);
	    //print_r($list);
   	    //pdo_debug();
   	    $result['status']=1;
   	    $result['data']['list']=$list;
   	    $result['data']['pager']=$pager;
   	    
   	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
   	    $_GPC['isdebug']&& print_r($result);
   	    include($this->template());
	}
	public function face_value_suggest_edit(){
	    global $_GPC, $_W;
	    $id = $_GPC['id'];
	    $face_value_lt = $_GPC['face_value_lt'];
	    $goods_ids = $_GPC['goods_ids'];
	    $package_ids = $_GPC['package_ids'];
	    $suggest = $_GPC['suggest'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($face_value_lt!=''||$goods_ids!=''){
	        $data=array();
	        $face_value_lt && $data['face_value_lt']=$face_value_lt;
	        $goods_ids && $data['goods_ids']=$goods_ids;
	        $package_ids && $data['package_ids']=$package_ids;
	        $suggest && $data['suggest']=$suggest;
	        
	        !$id && pdo_insert('ewei_shop_yoxface_suggest', $data);
	        $id  && pdo_update("ewei_shop_yoxface_suggest", $data, array("id" => $id));
	        //print_r($data);
	        show_json(1, "成功");
	        //if($this->is_from_wechat) return array('status'=>1,'message'=>'ok');
	        //message('OK', $this->createWebUrl('face_value_goods_list', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxface_suggest') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
// 	    $user_info = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uid' => $info['uid'], ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template());
	}
// 	public function face_value_goods_package_list(){
	    
// 	}
// 	public function face_value_goods_package_edit(){
	    
// 	}
	public function setting(){
	    include($this->template());
	}
}
?>