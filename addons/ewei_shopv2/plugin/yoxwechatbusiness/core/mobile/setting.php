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
class Setting_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{
	    $this->setting_list();
	}
	public function setting_list(){
	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $where = " uniacid = '{$uniacid}' ";
	    $list_tmp = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_setting') . " WHERE $where ", array());
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_setting') . " WHERE $where");
	    $pager = pagination($total, $pindex, $psize);

	    $list=array();
	    foreach($list_tmp as $info){
	        if(in_array($info['name'], array('CERTIFICATE'))){
	            $values=unserialize($info['value']);
		    foreach($values as $value){
		    $list[$info['name']]['value'][]=tomedia($value);
		    }
	        }else{
	            $list[$info['name']]['value']=$info['value'];
	        }

	    }

	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;

	    $_W['isajax'] && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxwechatbusiness/setting/setting_list'));
	}
	public function edit()
	{
	    var_dump(111111111);die();

		load()->func('tpl');
	    global $_GPC, $_W;
	    $name = $_GPC['name'];
	    $value = $_GPC['value'];
	    !$value && $_GPC['name']='contract' && $value['agent']=$_GPC['agent'] && $value['user']=$_GPC['user'];
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($value!='' && in_array($name,array('CERTIFICATE','UPGRADE','INVITE','level_rule','contract'))){
	        $value=serialize($value);
	    }

	    $uniacid && ($where = ' uniacid = :uniacid') && ($replace=array(':uniacid'=>$uniacid));
	    $name    && ($where .= ' AND name = :name') && ($replace+=array(':name'=>$name));
	    $name && ($info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_setting') . " WHERE {$where} ", $replace));

	    if($value!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $name && $data['name']=$name;
	        $value && $data['value']=$value;

	        !$info && pdo_insert('ewei_shop_yoxwechatbusiness_setting', $data);
	        $info  && pdo_update("ewei_shop_yoxwechatbusiness_setting", $data, array("name" => $info['name']));

	        show_json(1,'成功');
	    }

	    if(in_array($info['name'],array('CERTIFICATE','UPGRADE','INVITE','level_rule','contract'))){
	        $info['value']=unserialize($info['value']);
	    }

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;

	    //pdo_debug();
	    $_W['isajax'] && die(json_encode($result));
	    $_GPC['isdebug'] && print_r($result) && die();
	    // include($this->template('yoxwechatbusiness/setting/edit'));
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
}
?>