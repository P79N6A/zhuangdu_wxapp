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
class yoxwakeupchallengeModel extends PluginModel 
{
    const IS_OPEN='IS_OPEN';
    private $is_from_wechat;
    private $checkin_order_by=array('order_by_add_time_asc'=>' ORDER BY add_time ASC','order_by_add_time_desc'=>' ORDER BY add_time DESC','order_by_days_asc'=>' ORDER BY days ASC ','order_by_days_desc'=>'ORDER BY days DESC');
    
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        $this->is_from_wechat=$_GPC['comefrom']=='wxapp'?1:0;
    }
    /**
     * 配置信息
     */
    public function setting_info($uniacid,$name){
        if(empty($uniacid)){
            return ;
        }
        if(empty($name)){
            return ;
        }
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_setting') . " setting WHERE uniacid=:uniacid AND `name`=:name ", array(':name' =>$name,':uniacid'=>$uniacid));
        
        return $info;
    }
    /**
     * 配置信息值
     */
    public function setting_value($name,$uniacid){
        if(empty($uniacid)){
            global $_W;
            $uniacid=$_W['uniacid'];
        }
        $info = $this->setting_info($uniacid, $name);
        //print_r($name);
        //var_dump($info);
        return $info['value'];
    }
    public function page_activity_list(){
        global $_GPC, $_W;
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        if(!$this->setting_value(self::IS_OPEN, $uniacid)){
            return array('status'=>0,'message'=>'功能未开启');
        }
        
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
        $result['page']['total']=$total;
        $result['page']['pager']=$pager;
        //pdo_debug();
        return $result;
    }
    public function page_activity_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $name = $_GPC['name'];
        $image = $_GPC['image'];
        $status = $_GPC['status'];
        $begin_time = $_GPC['time']['start'];
        $end_time = $_GPC['time']['end'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $uid = intval($_W['member']['uid']);
        
        if(!$this->setting_value(self::IS_OPEN, $uniacid)){
            return array('status'=>0,'message'=>'功能未开启');
        }
        
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
                $message="添加成功";
                pdo_insert('ewei_shop_yoxwakeupchallenge_activity', $data);
            }
            if($id){
                $message="修改成功";
                pdo_update("ewei_shop_yoxwakeupchallenge_activity", $data, array("id" => $id));
            }
            if($this->is_from_wechat)return array('status'=>1,'message'=>$message);
            empty($this->is_from_wechat) && show_json(1, $message);
        }
        
        $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity.uid ";
        $member_field=' members.nickname';
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ".
            $left_join_member." WHERE activity.id = :id AND activity.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        //pdo_debug();
        return $result;
    }
    public function page_activity_delete(){
        global $_GPC,$_W;
        $id = intval($_GPC["id"]);
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $uid=$_W['member']['uid'];
        pdo_delete('ewei_shop_yoxwakeupchallenge_activity', array('id' => $id,'uid'=>$uid,'uniacid'=>$uniacid));
        show_json(1,'删除成功');
    }
    public function page_activity_user_list(){
        global $_GPC, $_W;
        $activity_id=$_GPC['activity_id'];
        $uid=$_GPC['uid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        ($where=" `activity_user`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
        $uid && ($where.=" AND `activity_user`.uid=:uid") && ($replace+=array(':uid'=>$uid));
        $activity_id && ($where.=" AND `activity_user`.activity_id=:activity_id") && ($replace+=array(':activity_id'=>$activity_id));
        
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
        $result['page']['total']=$total;
        $result['page']['pager']=$pager;
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        return $result;
            
    }
    public function page_activity_user_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $activity_id = intval($_GPC['activity_id']);
        $uid = $_GPC['uid'];
        $status = $_GPC['status']?$_GPC['status']:0;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        if($activity_id!=''&&$uid!=''){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $activity_id && $data['activity_id']=$activity_id;
            $uid && $data['uid']=$uid;
            $status&& $data['status']=$status;
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
            
            if($this->is_from_wechat)return array('status'=>1,'message'=>$message);
            empty($this->is_from_wechat) && show_json(1, $message);
        }
        
        $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity_user.uid ";
        $member_field=' members.nickname';
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_user') . " activity_user ".
            $left_join_member." WHERE activity_user.id = :id AND activity_user.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        //pdo_debug();
        return $result;
    }
    /**
     * 签到列表 当天签到时间排行
     */
    public function page_activity_checkin_list(){
        global $_GPC, $_W;
        $activity_id=$_GPC['activity_id'];
        $uid=$_GPC['uid'];
        $add_time_begin=$_GPC['add_time_begin'];
        $add_time_end=$_GPC['add_time_end'];
        $order_by=$_GPC['order_by']?$_GPC['order_by']:$this->checkin_order_by['order_by_add_time_asc'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        $group_by='';
        //按签到天数排序
        if($order_by==$this->checkin_order_by['order_by_days_asc'] ||$order_by==$this->checkin_order_by['order_by_days_desc']){
            $group_by=' GROUP BY uid ';
        }
        
        ($where=" `activity_checkin`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
        $uid && ($where.=" AND `activity_checkin`.uid=:uid") && ($replace+=array(':uid'=>$uid));
        $activity_id && ($where.=" AND `activity_user`.activity_id=:activity_id") && ($replace+=array(':activity_id'=>$activity_id));
        ($add_time_begin && $add_time_end) && ($where.=" AND `activity_user`.add_time>=:add_time_begin AND `activity_user`.add_time<=:add_time_end") && ($replace+=array(':add_time_begin'=>$add_time_begin,'add_time_end'=>$add_time_end));
        $left_join_member=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=activity_checkin.uid ";
        $left_join_activity=" LEFT JOIN " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ON activity.id=activity_checkin.activity_id ";
        $member_field=' members.nickname';
        $activity_field=' activity.name AS activity_name';
        $list = pdo_fetchAll("SELECT `activity_checkin`.*,FROM_UNIXTIME(activity_checkin.add_time) AS add_time_format,{$member_field},{$activity_field} FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_checkin') . " `activity_checkin` ".
            $left_join_member.$left_join_activity.
            " WHERE {$where} {$group_by} {$order_by}", $replace);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwakeupchallenge_activity_checkin') . " `activity_checkin` WHERE $where ",$replace);
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['message']='success';
        $result['data']['list']=$list;
        $result['page']['total']=$total;
        $result['page']['pager']=$pager;
            //pdo_debug();
        return $result;
    }
    /**
     * 签到
     */
    public function page_checkin(){
        global $_GPC, $_W;
        $uid = intval($_GPC['uid']);
        $activity_id = $_GPC['activity_id'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        
        if(!$this->setting_value(self::IS_OPEN, $uniacid)){
            return array('status'=>0,'message'=>'功能未开启');
        }
        
	//活动信息
        $activity_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity') . " activity ".
            " WHERE activity.id = :id AND activity.uniacid = :uniacid", array(':id' => $activity_id, ':uniacid' => $uniacid));
        
        $activity_user = pdo_fetch("SELECT activity_user.* FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_user') . " activity_user ".
            "  WHERE activity_user.activity_id=:activity_id AND activity_user.uid = :uid AND activity_user.uniacid = :uniacid", array(':activity_id'=>$activity_id,':uid' => $uid, ':uniacid' => $uniacid));
        
	//是否需要入团(邀请)才能签到
        if($activity_info['must_invited']==1 && empty($activity_user)){
            return array('status'=>0,'message'=>'您没有被邀请，不能签到');
        }
        
        ($where=" `activity_checkin`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
        $uid && ($where.=" AND `activity_checkin`.uid=:uid") && ($replace+=array(':uid'=>$uid));
        $activity_id && ($where.=" AND `activity_checkin`.activity_id=:activity_id") && ($replace+=array(':activity_id'=>$activity_id));
        
        $today=date('Y-m-d');
        $yesterday=date("Y-m-d",strtotime("-1 day"));
        $info = pdo_fetch("SELECT activity_checkin.*,FROM_UNIXTIME(activity_checkin.add_time) AS add_time_format  FROM " . tablename('ewei_shop_yoxwakeupchallenge_activity_checkin') . " activity_checkin ".
            " WHERE activity_checkin.uid = :uid AND activity_checkin.uniacid = :uniacid ORDER BY activity_checkin.id desc", array(':uid' => $id, ':uniacid' => $uniacid,':date'=>$today));
        
        //已经签过
        if($info['date']==$today){
            return array('status'=>0,'message'=>'您已签到，请勿重复签到');
        }
        
        //连续签到
        $days=1;
        if($yesterday==$info['date']){
            $days=$info['days']+1;
        }
        
        $data=array();
        $data['uniacid']=$uniacid;
        $data['activity_id']=$activity_id;
        $data['uid']=$uid;
        $data['date']=$today;
        $data['days']=$days;
        $data['add_time']=time();
        $is_insert=pdo_insert('ewei_shop_yoxwakeupchallenge_activity_checkin', $data);
        return array('status'=>1,'message'=>'签到成功');
    }
    /**
     * 数量 
     */
    public function page_activity_user_count(){
        global $_GPC, $_W;
        $activity_id = $_GPC['activity_id'];
        ($where=" `activity_user`.uniacid = :uniacid ") && ($replace=array( ':uniacid' => $uniacid));
        $activity_id && ($where.=" AND `activity_user`.activity_id=:activity_id") && ($replace+=array(':activity_id'=>$activity_id));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwakeupchallenge_activity_user') . " `activity_user` WHERE $where ",$replace);
        $result['status']=1;
        $result['message']='ok';
        $result['total']=$total;
        return $result;
    }
    public function test(){
        
    }
}