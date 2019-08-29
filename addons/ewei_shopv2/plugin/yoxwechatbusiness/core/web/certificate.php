<?php
/**
 * 授权证书
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
class Certificate_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    $this->certificate_list();
	    
	}
	public function certificate_list(){
	//error_reporting(E_ALL);
	//ini_set('display_errors','ON');
	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " mc_members ON mc_members.uid=certificate.uid ";
	    $left_join_merch_user =" LEFT JOIN " . tablename('ewei_shop_merch_user') . " merch_user ON merch_user.id=certificate.merchid ";
	    $member_field=" mc_members.nickname,mc_members.realname,mc_members.avatar ";
	    $merch_user_field =" merch_user.merchname";
	    $list = pdo_fetchAll("SELECT `certificate`.*,{$member_field},{$merch_user_field} FROM " . tablename('ewei_shop_yoxwechatbusiness_certificate') . " `certificate` ".
	   	    $left_join_member. $left_join_merch_user.
	        " WHERE `certificate`.uniacid = :uniacid AND merchid=:merchid ", array( ':uniacid' => $uniacid,':merchid'=>$merchid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_certificate') . " `certificate` WHERE `certificate`.uniacid = :uniacid",array( ':uniacid' => $uniacid,':merchid'=>$merchid));
	    $pager = pagination($total, $pindex, $psize);
	    foreach($list as &$info){
	        $info['certificate']=json_decode($info['certificate']);
	    }
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwechatbusiness/certificate/certificate_list'));
	}
	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $merchid=intval($_GPC['merchid']);
	    $uid = intval($_GPC['uid']);
	    $certificate=$_GPC['certificate'];
	    $is_certificate_approved=$_GPC['is_certificate_approved'];
	    $status = $_GPC['status'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    //$merchid=$_W['merchid']?$_W['merchid']:0;
	    if($certificate!=''||$is_certificate_approved!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        //$merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $certificate && $data['certificate']=json_encode($certificate);
	        $is_certificate_approved!='' && $data['is_certificate_approved']=$is_certificate_approved;
	        $status&& $data['status']=$status;
	        empty($id) && $data['add_time']=time();
	        $id && $data['update_time']=time();
	        
	        if(!$id){
	            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_certificate') . " WHERE uniacid={$uniacid} AND uid={$uid} AND merchid={$merchid}");

	            if($total>=1){
	                show_json(0, "此证书已存在！");
	            }
	            $message="添加";
	            pdo_insert('ewei_shop_yoxwechatbusiness_certificate', $data);
	        }
	        if($id){
	            $message="修改";
	            pdo_update("ewei_shop_yoxwechatbusiness_certificate", $data, array("id" => $id,"uniacid"=>$uniacid,"merchid"=>$merchid));
	        }
	        
	        show_json(1, $message."成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " mc_members ON mc_members.uid=certificate.uid ";
	    $left_join_merch_user =" LEFT JOIN " . tablename('ewei_shop_merch_user') . " merch_user ON merch_user.id=certificate.merchid ";
	    $member_field=" mc_members.nickname,mc_members.realname,mc_members.avatar ";
	    $merch_user_field =" merch_user.merchname";
	    $info = pdo_fetch("SELECT certificate.*,{$member_field},{$merch_user_field} FROM " . tablename('ewei_shop_yoxwechatbusiness_certificate') . " certificate ".
	   	    $left_join_member.$left_join_merch_user.
	    "WHERE certificate.id = :id AND certificate.uniacid = :uniacid AND certificate.merchid=:merchid", array(':id' => $id, ':uniacid' => $uniacid,":merchid"=>$merchid));
	    $info['certificate']=json_decode($info['certificate']);
	    
	    if(empty($info['merchname'])){
	        $info['merchname']='系统';
	    }
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template());
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_certificate") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_certificate', array('id' => $item['id'],'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	/**
	 * 排序
	 */
	public function displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_certificate") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_certificate", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwechatbusiness_certificate.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
	    }
	    show_json(1);
	}
	/**
	 * 前端是否显示
	 */
	public function status()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_certificate") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_certificate", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_certificate.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>