<?php
/**
 * 微商等级
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
class Level_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{

	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;

	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;

	    $list = pdo_fetchAll("SELECT `level`.*,shop_package.price FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ".
	        " LEFT JOIN " . tablename('ewei_shop_package') . " shop_package ON (shop_package.id=`level`.package_id AND shop_package.uniacid=:uniacid) ".
	        " WHERE `level`.uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` WHERE `level`.uniacid = :uniacid",array( ':uniacid' => $uniacid));
	    $pager = pagination($total, $pindex, $psize);

	    foreach($list as &$info){
	        $info['package_setting']=json_decode($info['package_setting'],true);
	    }

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;

	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template());
	}
	public function edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $level = $_GPC['level'];
	    $name = $_GPC['name'];
	    $growth_value_money = $_GPC['growth_value_money'];
	    $growth_value = $_GPC['growth_value'];
// 	    $return_scale = $_GPC['return_scale'];
// 	    $return_money = $_GPC['return_money'];
	    $up_return = $_GPC['up_return'];
	    $invite_return = $_GPC['invite_return'];
	    $security_deposit = $_GPC['security_deposit'];
	    $package_setting  = $_GPC['package_setting'];
	    $package_id    = $_GPC['package_id'];
	    $invite_num    = $_GPC['invite_num'];
	    $get_alive_type    = $_GPC['get_alive_type'];

	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $level && $data['level']=$level;
	        $name && $data['name']=$name;
	        $growth_value_money&& $data['growth_value_money']=$growth_value_money;
	        $growth_value&& $data['growth_value']=$growth_value;
// 	        $return_scale&& $data['return_scale']=$return_scale;
// 	        $return_money && $data['return_money']=$return_money;
	        $up_return && $data['up_return']=$up_return;
	        $invite_return && $data['invite_return']=$invite_return;
	        $security_deposit&& $data['security_deposit']=$security_deposit;
	        $package_id && $data['package_id']=$package_id;
	        $invite_num && $data['invite_num']=$invite_num;
	        is_array($package_setting) && $data['package_setting'] = json_encode($package_setting);
			$data['get_alive_type']=$get_alive_type;

	        if(!$id){
	            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid={$uniacid}");
	            if($total>=7){
// 	                message('最多添加七级！', $this->createWebUrl('UserLevelList', array()), 'error');
	                show_json(0, "最多添加七级！");
	            }
	            $total2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid={$uniacid} AND level={$level} ");
	            if($total2>=1){
	                show_json(0, "此等级已存在！");
	            }
	            $message="添加";
	            pdo_insert('ewei_shop_yoxwechatbusiness_level', $data);
	        }
	        if($id){
	            $message="修改";
	            pdo_update("ewei_shop_yoxwechatbusiness_level", $data, array("id" => $id));
	        }

	        show_json(1, $message."成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }

	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['package_setting']=json_decode($info['package_setting'],true);

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
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_level") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_level', array('id' => $id,'uniacid'=>$uniacid));
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxphotovideo") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxphotovideo", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxphotovideo.edit", "修改文章排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxphotovideo") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxphotovideo", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxhotsearch.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 精选
	 */
	public function is_featured(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxphotovideo") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxphotovideo", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxphotovideo.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}






}
?>