<?php
/**
 * 微商佣金
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
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Commission_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main()
	{
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $result = $yoxwechatbusiness->page_commission();
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && app_json($result);
	    $_GPC['isdebug']&& print_r($result) && die();

	}
	/**
	 * 编辑
	 */
	public function edit()
	{
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $result = $yoxwechatbusiness->page_commission_edit();

	    ($_W['isajax']||$_GPC['isajax']) && app_json($result);
	    $_GPC['isdebug'] && print_r($result) && die();

	}
	/**
	 * 删除
	 */
	public function delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwechatbusiness_commission', array('id' => $id,'uniacid'=>$uniacid));

	    $result['status']=1;
	    $result['message']='删除成功';
	    app_json($result);
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
	    $item = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_commission", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("yoxwechatbusiness_commission.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
	    }
	    app_json(array('status'=>1));
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
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_commission", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.commission.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    app_json(array('status'=>1));
	}
	/**
	 * 0未确认，1确认有效，2确认无效
	 */
	public function is_effective(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_commission") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_commission", array( "is_effective" => intval($_GPC["is_effective"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.commission.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "有效" : "普通"));
	    }
	    //pdo_debug();
	    app_json(array('status'=>1));
	}

	/**
	 * 佣金统计
	 */
	public function count(){
	    global $_W;
	    global $_GPC;
	    $all=pdo_fetchcolumn('select sum(commission) from '.tablename('ewei_shop_yoxwechatbusiness_commission').' where commission_uid='.$_W['member']['uid']);

	    $yestoday_begin=strtotime(date("Y-m-d",strtotime("-1 day")));
	    $yestoday_end=strtotime(date("Y-m-d"));
	    $yestoday=pdo_fetchcolumn('select sum(commission) from '.tablename('ewei_shop_yoxwechatbusiness_commission').' where commission_uid='.$_W['member']['uid'].' and add_time>='.$yestoday_begin.' and add_time<'.$yestoday_end);

	    $daishou=pdo_fetchcolumn('select sum(commission) from '.tablename('ewei_shop_yoxwechatbusiness_commission').' where commission_uid='.$_W['member']['uid'].' and status=0');
	    $level=pdo_fetchcolumn('select level from '.tablename('ewei_shop_yoxwechatbusiness_user').' where uid='.$_W['member']['uid']);
	    $level_name=pdo_fetchcolumn('select name from '.tablename('ewei_shop_yoxwechatbusiness_level').' where level='.$level);


	    $result['status']=1;
	    $result['all']=$all;
	    $result['yestoday']=$yestoday;
	    $result['daishou']=$daishou;
	    $result['level_name']=$level_name;
	    app_json($result);
	}


}
?>