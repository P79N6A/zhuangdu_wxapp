<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Hotsearch_EweiShopV2Page extends AppMobilePage
{
	public function main() 
	{
	    global $_GPC,$_W;
	    
	    $type = $_GPC['type']?$_GPC['type']:'GOODS';
	    $is_hot = $_GPC['is_hot'];
	    $status = '1';
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 1000;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    
	    $replace=array();
	    $where = " uniacid = '{$_W['uniacid']}' ";
	    $type!='' && ($where.=" AND type=:type") && ($replace=array(':type'=>$type));
	    $status!='' && ($where.=" AND status=:status") && ($replace+=array(':status'=>$status));
	    $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_hotsearch') . " WHERE $where ORDER BY displayorder ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace, 'id');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_hotsearch') . " WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    //pdo_debug();
	    ($_W['isajax'] || $_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	}
}
