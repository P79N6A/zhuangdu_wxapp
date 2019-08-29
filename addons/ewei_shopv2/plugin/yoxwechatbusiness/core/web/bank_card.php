<?php
/**
 * 微商银行卡
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
class Bank_card_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    $this->bank_card_list();
	    
	}
	public function bank_card_list(){
	//error_reporting(E_ALL);
	//ini_set('display_errors','ON');
	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $list = pdo_fetchAll("SELECT `bank_card`.* FROM " . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " `bank_card` ".
	        " WHERE `bank_card`.uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " `bank_card` WHERE `bank_card`.uniacid = :uniacid",array( ':uniacid' => $uniacid));
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwechatbusiness/bank_card/bank_card_list'));
	}
	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = intval($_GPC['uid']);
	    $name = $_GPC['name'];
	    $idcard = $_GPC['idcard'];
	    $subbranch = $_GPC['subbranch'];
	    $bank_name = $_GPC['bank_name'];
	    $status = $_GPC['status'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $name && $data['name']=$name;
	        $idcard && $data['idcard']=$idcard;
	        $subbranch&& $data['subbranch']=$subbranch;
	        $bank_name&& $data['bank_name']=$bank_name;
	        $status&& $data['status']=$status;
	        empty($id) && $data['add_time']=time();
	        $id && $data['update_time']=time();
	        
	        if(!$id){
	            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " WHERE uniacid={$uniacid} AND uid={$uid}");
	            if($total>=7){
// 	                message('最多添加七张！', $this->createWebUrl('UserLevelList', array()), 'error');
	                show_json(0, "最多添加七张！");
	            }
	            $total2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " WHERE uniacid={$uniacid} AND idcard={$idcard} ");
	            if($total2>=1){
	                show_json(0, "此卡已存在！");
	            }
	            $message="添加";
	            pdo_insert('ewei_shop_yoxwechatbusiness_bank_card', $data);
	        }
	        if($id){
	            $message="修改";
	            pdo_update("ewei_shop_yoxwechatbusiness_bank_card", $data, array("id" => $id));
	        }
	        
	        show_json(1, $message."成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
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
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_bank_card") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_bank_card', array('id' => $item['id'],'uniacid'=>$uniacid));
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_bank_card") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_bank_card", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwechatbusiness_bank_card.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_bank_card") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_bank_card", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_bank_card.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>