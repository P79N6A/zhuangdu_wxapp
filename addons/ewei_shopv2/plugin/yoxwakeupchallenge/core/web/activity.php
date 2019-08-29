<?php
/**
 * 早起挑战
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
class Activity_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    $this->activity_list();
	    
	}
	public function activity_list(){
	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity.uid ";
	    $member_field=' members.nickname';
	    $list = pdo_fetchAll("SELECT `activity`.*,FROM_UNIXTIME(begin_time) AS begin_time_format,FROM_UNIXTIME(end_time) AS end_time_format,{$member_field} FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " `activity` ".
	   	    $left_join_member.
	        " WHERE `activity`.uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwakeupchallenge_activity') . " `activity` WHERE `activity`.uniacid = :uniacid",array( ':uniacid' => $uniacid));
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwakeupchallenge/activity/activity_list'));
	}
	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = intval($_GPC['uid']);
	    $name = $_GPC['name'];
	    $image = $_GPC['image'];
	    $status = $_GPC['status'];
	    $begin_time = $_GPC['time']['start'];
	    $end_time = $_GPC['time']['end'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        //$merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $name && $data['name']=$name;
		$image && $data['image']=$image;
	        $status&& $data['status']=$status;
	        $begin_time&& $data['begin_time']=$begin_time;
	        $end_time&& $data['end_time']=$end_time;
	        empty($id) && $data['add_time']=time();
	        $id && $data['update_time']=time();
	        
	        if(!$id){
	            $message="添加";
	            pdo_insert('ewei_shop_yoxwakeupchallenge_activity', $data);
	        }
	        if($id){
	            $message="修改";
	            pdo_update("ewei_shop_yoxwakeupchallenge_activity", $data, array("id" => $id));
	        }
	        
	        show_json(1, $message."成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity.uid ";
	    $member_field=' members.nickname';
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ".
	        $left_join_member." WHERE activity.id = :id AND activity.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    //pdo_debug();
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
	    pdo_delete('ewei_shop_yoxwakeupchallenge_activity', array('id' => $id,'uniacid'=>$uniacid));
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
	    $item = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwakeupchallenge_activity") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwakeupchallenge_activity", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwakeupchallenge_activity.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
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
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwakeupchallenge_activity") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwakeupchallenge_activity", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwakeupchallenge_activity.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 参与活动的人
	 */
	public function activity_user_list(){
	    global $_GPC, $_W;
	    $uid=$_GPC['uid'];
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    ($where=" `activity_user`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
	    $uid && ($where.=" AND `activity_user`.uid=:uid") && ($replace+=array(':uid'=>$uid));
	    
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity_user.uid ";
	    $left_join_activity=" LEFT JOIN " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ON activity.id=activity_user.activity_id ";
	    $member_field=' members.nickname';
	    $activity_field=' activity.name AS activity_name';
	    $list = pdo_fetchAll("SELECT `activity_user`.*,FROM_UNIXTIME(activity_user.add_time) AS add_time_format,{$member_field},{$activity_field} FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_user') . " `activity_user` ".
	   	    $left_join_member.$left_join_activity.
	   	    " WHERE $where ",$replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwakeupchallenge_activity_user') . " `activity_user` ".
	        $left_join_member." WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwakeupchallenge/activity/activity_user_list'));
	}
	public function activity_user_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = intval($_GPC['uid']);
	    $activity_id = $_GPC['activity_id'];
	    $status = $_GPC['status'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($activity_id!='' || $status!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        //$merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $activity_id && $data['activity_id']=$activity_id;
	        $status!='' && $data['status']=$status;
	        empty($id) && $data['add_time']=time();
	        //$id && $data['update_time']=time();
	        
	        if(!$id){
	            $message="添加";
	            pdo_insert('ewei_shop_yoxwakeupchallenge_activity_user', $data);
	        }
	        if($id){
	            $message="修改";
	            pdo_update("ewei_shop_yoxwakeupchallenge_activity_user", $data, array("id" => $id));
	        }
	        
	        show_json(1, $message."成功");
	    }
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity_user.uid ";
	    $left_join_activity=" LEFT JOIN " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ON activity.id=activity_user.activity_id ";
	    $member_field=' members.nickname';
	    $activity_field=' activity.name AS activity_name';
	    $info = pdo_fetch("SELECT activity_user.*,{$member_field},{$activity_field} FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_user') . " activity_user ".
	   	    $left_join_member.$left_join_activity.
	        "  WHERE activity_user.id = :id AND activity_user.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template());
	}
	public function activity_user_delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwakeupchallenge_activity_user', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	/**
	 * 签到列表
	 */
	public function activity_checkin_list(){
	    global $_GPC, $_W;
	    $uid=$_GPC['uid'];
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    ($where=" `activity_checkin`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
	    $uid && ($where.=" AND `activity_checkin`.uid=:uid") && ($replace+=array(':uid'=>$uid));
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity_checkin.uid ";
	    $left_join_activity=" LEFT JOIN " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ON activity.id=activity_checkin.activity_id ";
	    $member_field=' members.nickname';
	    $activity_field=' activity.name AS activity_name';
	    $list = pdo_fetchAll("SELECT `activity_checkin`.*,FROM_UNIXTIME(activity_checkin.add_time) AS add_time_format,{$member_field},{$activity_field} FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_checkin') . " `activity_checkin` ".
	        $left_join_member.$left_join_activity.
	        " WHERE $where ", $replace);
	        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwakeupchallenge_activity_checkin') . " `activity_checkin` WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template());
	}
	/**
	 * 签到编辑
	 */
	public function activity_checkin_edit(){
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = intval($_GPC['uid']);
	    $activity_id = $_GPC['activity_id'];
	    $status = $_GPC['status'];
	    $days = $_GPC['days'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($activity_id!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        //$merchid && $data['merchid']=$merchid;
	        $uid && $data['uid']=$uid;
	        $activity_id && $data['activity_id']=$activity_id;
	        $status !=''&& $data['status']=$status;
	        $days&& $data['days']=$days;
	        empty($id) && $data['add_time']=time();
	        $id && $data['update_time']=time();
	        
	        if(!$id){
	            $message="添加";
	            pdo_insert('ewei_shop_yoxwakeupchallenge_activity_checkin', $data);
	        }
	        if($id){
	            $message="修改";
	            pdo_update("ewei_shop_yoxwakeupchallenge_activity_checkin", $data, array("id" => $id));
	        }
	        
	        show_json(1, $message."成功");
	    }
	    
	    ($where=" `activity_checkin`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
	    $uid && ($where.=" AND `activity_checkin`.uid=:uid") && ($replace+=array(':uid'=>$uid));
	    $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity_checkin.uid ";
	    $left_join_activity=" LEFT JOIN " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ON activity.id=activity_checkin.activity_id ";
	    $member_field=' members.nickname';
	    $activity_field=' activity.name AS activity_name';
	    $info = pdo_fetch("SELECT activity_checkin.*,FROM_UNIXTIME(activity_checkin.add_time) AS add_time_format,{$member_field},{$activity_field}  FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_checkin') . " activity_checkin ".
	   	    $left_join_member.$left_join_activity.
	    " WHERE activity_checkin.id = :id AND activity_checkin.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template());
	}
	/**
	 * 删除签到
	 */
	public function activity_checkin_delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwakeupchallenge_activity_checkin', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	public function setting(){
	    global $_GPC, $_W;
	    //$id = intval($_GPC['id']);
	    $name = $_GPC['name'];
	    $value = $_GPC['value'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_setting') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $name && $data['name']=$name;
	        $value!='' && $data['value']=$value;
	        !$info && pdo_insert('ewei_shop_yoxwakeupchallenge_activity_setting', $data);
	        $info  && pdo_update("ewei_shop_yoxwakeupchallenge_activity_setting", $data, array('uniacid'=>$uniacid,"name" => $name));
	        
	        //pdo_debug();
	        show_json(1, "成功");
	    }
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template());
	}
}
?>