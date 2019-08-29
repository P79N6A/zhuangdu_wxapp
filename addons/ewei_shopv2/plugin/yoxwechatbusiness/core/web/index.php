<?php
/**
 * 微商用户
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

	    global $_GPC, $_W;
	    $invite_uid=$_GPC['invite_uid'];//邀请人uid
	    $invite_realname=$_GPC['invite_realname'];//邀请人姓名
	    $realname=$_GPC['realname'];//姓名
	    $mobile=$_GPC['mobile'];//手机
	    $invitationcode=$_GPC['invitationcode'];//自己的邀请码
	    $level=$_GPC['level'];//等级

	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;

	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;

	    if($invite_realname){
	        $invite_list = pdo_fetchAll("SELECT mc_members.*FROM " . tablename('mc_members') . " mc_members ".
	            " WHERE mc_members.realname like '%{$invite_realname}%' ", array());
	        foreach($invite_list as $invite_info){
	            $invite_uids_arr[]=$invite_info['uid'];
	        }
	        $invite_uids_str=implode(',', $invite_uids_arr);
	        $is_invite_uids_where=true;
	    }
	    if($realname){
	        $mc_memer_info = pdo_fetch("SELECT mc_members.* FROM " . tablename('mc_members') . " mc_members WHERE mc_members.realname='{$realname}' ", array());
	        $uid=$mc_memer_info['uid'];
	    }
	    if($mobile){
	        $mc_memer_info = pdo_fetch("SELECT mc_members.* FROM " . tablename('mc_members') . " mc_members WHERE mc_members.mobile='{$mobile}' ", array());
	        $uid=$mc_memer_info['uid'];
	    }
	    $where=" yoxwechatbusiness_user.uniacid = $uniacid";
	    $level && ($where.=" AND yoxwechatbusiness_user.`level`='{$level}'");
	    $invite_uid&&($where.=" AND yoxwechatbusiness_user.`invite_uid`={$invite_uid}");
	    $is_invite_uids_where && ($where.=" AND yoxwechatbusiness_user.`invite_uid` in({$invite_uids_str})");
	    $uid && ($where.=" AND yoxwechatbusiness_user.`uid` ={$uid}");
	    $left_join=" LEFT JOIN " . tablename('mc_members') . " member ON member.uid=yoxwechatbusiness_user.uid ".
	   	    " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=yoxwechatbusiness_user.invite_uid ".
	   	    " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON `level`.`level`= yoxwechatbusiness_user.`level` ";
	    $field='member.nickname,member.avatar,member.credit1,member.credit2,member2.nickname AS invite_nickname,member2.realname AS invite_realname,member2.avatar AS invite_avatar,`level`.`name` AS level_name';
	    $list = pdo_fetchAll("SELECT yoxwechatbusiness_user.*,FROM_UNIXTIME(yoxwechatbusiness_user.add_time) AS add_time_format,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
	        $left_join.
	        " WHERE $where ", array());
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user $left_join WHERE $where ",array());
	    $pager = pagination($total, $pindex, $psize);

	    //等级
	    $level_list = pdo_fetchAll("SELECT `level`.* FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ", array());

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['level_list']=$level_list;
	    $result['data']['list']=$list;

	    //pdo_debug();
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
	    $thumbs = $_GPC['thumbs'];
	    $banner = $_GPC['banner'];
	    $status = $_GPC['status'];
	    $docking_people_uid = $_GPC['docking_people_uid'];

	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($name!=''||$level!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $level && $data['level']=$level;
	        $name && $data['name']=$name;

	        $status && $data['status']=$status;
	        if (!$data['status']) {
	        	$data['status']=0;
	        }
	        $thumbs && $data['thumbs']=serialize($thumbs);
	        $banner && $data['banner']=$banner;
	        $docking_people_uid && $data['docking_people_uid']=$docking_people_uid;

	        !$id && pdo_insert('ewei_shop_yoxwechatbusiness_user', $data);
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_user", $data, array("id" => $id));

	        show_json(1, "成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $field='member.nickname,member.avatar,member.credit2,member2.nickname AS invite_nickname,member2.avatar AS invite_avatar,`level`.`name` AS level_name,member3.avatar AS up_level_avatar,member3.nickname AS up_level_nickname,member4.nickname AS docking_people_nickname';
	    $info = pdo_fetch("SELECT yoxwechatbusiness_user.*,FROM_UNIXTIME(yoxwechatbusiness_user.add_time) AS add_time_format,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
	        " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=yoxwechatbusiness_user.uid ".
	        " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=yoxwechatbusiness_user.invite_uid ".
	        " LEFT JOIN " . tablename('mc_members') . " member3 ON member3.uid=yoxwechatbusiness_user.up_level_invite_uid ".
	        " LEFT JOIN " . tablename('mc_members') . " member4 ON member4.uid=yoxwechatbusiness_user.docking_people_uid ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON `level`.`level`= yoxwechatbusiness_user.`level` AND `level`.uniacid=:level_uniacid ".
	        " WHERE yoxwechatbusiness_user.id = :id AND yoxwechatbusiness_user.uniacid = :uniacid " , array(':id' => $id, ':uniacid' => $uniacid,':level_uniacid'=>$uniacid));

	    //$shop_member_info=p('yoxwechatbusiness')->shop_member_info($uniacid,$info['uid']);
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    $result['data']['shop_member_info']=$shop_member_info;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&&die();

		include($this->template('yoxwechatbusiness/user/post'));
	}
	/**
	 * 删除
	 */
	public function delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwechatbusiness_user', array('id' => $id,'uniacid'=>$uniacid));
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
	    $item = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_user") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwechatbusiness_user.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_user") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_user.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}

	public function setting(){
	    include($this->template());
	}
}
?>