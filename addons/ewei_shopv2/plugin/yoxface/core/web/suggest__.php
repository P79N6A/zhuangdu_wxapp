<?php
/**
 * 颜值建议
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
class Suggest_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
//error_reporting(E_ALL);
//ini_set("display_errors","On");
	    $this->suggest_list();
	}
	public function suggest_list(){
	    global $_GPC, $_W;
	    
	    $uid      =$_GPC['uid'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    $where = " uniacid = '{$uniacid}' ";
	    $uid     && ($where.=" AND uid = '{$uid}' ");
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxface_suggest') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxface_suggest') . " WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxface/suggest_list'));
	}
	public function suggest_edit(){
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $face_value_lt = $_GPC['face_value_lt'];
	    $suggest = $_GPC['suggest'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($suggest!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $face_value_lt && $data['face_value_lt']=$face_value_lt;
	        $suggest&& $data['suggest']=$suggest;
	        $data['add_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxface_suggest', $data);
	        $id  && pdo_update("ewei_shop_yoxface_suggest", $data, array("id" => $id));

	        show_json(1, "成功");
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxface_suggest') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxface/suggest_edit'));
	}
	public function setting(){
	    include($this->template());
	}
}
?>