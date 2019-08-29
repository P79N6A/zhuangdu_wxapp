<?php
/**
 * 微商
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @Wechat Yoperman
 * @blog https://blog.csdn.net/chenyoper/
 * @公众号 零零糖
 */
if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class yoxwechatbusinessModel extends PluginModel 
{
    const CERTIFICATE_NAME='CERTIFICATE';
    const UPGRADE_NAME='UPGRADE';
    private $is_from_wechat='';
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        load()->func('logging');
        $this->set_user_up_level_invite_uid();
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
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_setting') . " setting WHERE uniacid=:uniacid AND `name`=:name ", array(':name' =>"{$name}",':uniacid'=>$uniacid));
        if(in_array($info['name'],array(self::CERTIFICATE_NAME,self::UPGRADE_NAME))){
            $info['value']=unserialize($info['value']);
        }
        return $info;
    }
    /**
     * 配置信息值 
     */
    public function setting_value($uniacid,$name){
        $info = $this->setting_info($uniacid, $name);
        //print_r($name);
        //var_dump($info);
        return $info['value'];
    }
    /**
     * 等级列表 
     */
    public function level_list($uniacid){
        
        $list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid = :uniacid",array( ':uniacid' => $uniacid));
        
        foreach($list as &$info){
            $info['package_setting']=json_decode($info['package_setting'],true);
        }
        //unset($info);
	//print_r($list);
        return $list;
    }
    /**
     * 等级信息
     */
    public function level_info($level_id){
        if(empty($level_id)){
            return;
        }
        $data = pdo_fetch("SELECT yoxwechatbusiness_level'.* FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " yoxwechatbusiness_level' ".
            " WHERE yoxwechatbusiness_level.id=:id ",array(":id"=>$level_id));
        return $data;
    }
    /**
     * 添加商品表额外字段
     */
    public function set_goods_field($goods_id,$goods_option_id,$data){
        global $_GPC;
        $goods_id=$goods_id?$goods_id:$_GPC['id'];
        $goods_option_id=$goods_option_id?(int)$goods_option_id:(int)$_GPC['goods_option_id'];
        
        if(empty($data)){
            $data['goods_id']=$goods_id;
            $data['goods_option_id']=$goods_option_id;
            $data['marketprice_level_1']=$_GPC['marketprice_level_1'];
            $data['marketprice_level_2']=$_GPC['marketprice_level_2'];
            $data['marketprice_level_3']=$_GPC['marketprice_level_3'];
            $data['marketprice_level_4']=$_GPC['marketprice_level_4'];
            $data['marketprice_level_5']=$_GPC['marketprice_level_5'];
            $data['marketprice_level_6']=$_GPC['marketprice_level_6'];
            $data['marketprice_level_7']=$_GPC['marketprice_level_7'];
            
            $data['commission_up_level_1']=$_GPC['commission_up_level_1'];
            $data['commission_up_level_2']=$_GPC['commission_up_level_2'];
            $data['commission_up_level_3']=$_GPC['commission_up_level_3'];
            $data['commission_up_level_4']=$_GPC['commission_up_level_4'];
            $data['commission_up_level_5']=$_GPC['commission_up_level_5'];
            $data['commission_up_level_6']=$_GPC['commission_up_level_6'];
            
            $data['commission_invite_level_1']=$_GPC['commission_invite_level_1'];
            $data['commission_invite_level_2']=$_GPC['commission_invite_level_2'];
            $data['commission_invite_level_3']=$_GPC['commission_invite_level_3'];
            $data['commission_invite_level_4']=$_GPC['commission_invite_level_4'];
            $data['commission_invite_level_5']=$_GPC['commission_invite_level_5'];
            $data['commission_invite_level_6']=$_GPC['commission_invite_level_6'];
            $data['commission_invite_level_7']=$_GPC['commission_invite_level_7'];
        }
        
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_goods_field') . " WHERE goods_id=:goods_id AND goods_option_id=:goods_option_id ", array(':goods_id' =>$goods_id,':goods_option_id'=>$goods_option_id));
        empty($info) && ($data) && pdo_insert("ewei_shop_yoxwechatbusiness_goods_field", $data);
        $info && pdo_update('ewei_shop_yoxwechatbusiness_goods_field',$data,array('goods_id'=>$goods_id,'goods_option_id'=>$goods_option_id));
    }
    /**
     * 返回微商商品扩展表信息
     */
    public function goods_field($goods_id,$goods_option_id){
        global $_GPC,$_W;
        if(empty($goods_id)){
            return ;
        }
        if(empty($goods_option_id)){
            $goods_option_id=0;
        }
        $field='marketprice_level_1,marketprice_level_2,marketprice_level_3,marketprice_level_4,marketprice_level_5,marketprice_level_6,marketprice_level_7,';
        $field.='commission_up_level_1,commission_up_level_2,commission_up_level_3,commission_up_level_4,commission_up_level_5,commission_up_level_6,';
        $field.='commission_invite_level_1,commission_invite_level_2,commission_invite_level_3,commission_invite_level_4,commission_invite_level_5,commission_invite_level_6,commission_invite_level_7';
        $field_info = pdo_fetch("SELECT {$field} FROM " . tablename('ewei_shop_yoxwechatbusiness_goods_field') . " goods_field WHERE goods_id=:goods_id AND goods_option_id=:goods_option_id ", array(':goods_id' => $goods_id,':goods_option_id'=>$goods_option_id));
        return $field_info;
    }
    /**
     * 添加微商用户
     */
    public function set_user(){
        global $_GPC,$_W;

        $uid=$_W['fans']['uid'];
        $openid=$_W['openid']?$_W['openid']:$_GPC["openid"];//兼容没有uid的(人人商城公众号内置的小程序没有uid)
        $uniacid =$_W['uniacid'];
        $inviteuid=$_GPC['inviteuid'];//uid邀请
        $invitationcode=$_GPC['invitationcode'];//邀请码邀请

        //兼容小程序，小程序没有生成uid
//         $mc_member_info = $this->shop_member_to_sys_member($openid);
//         $mc_member_info['uid'] && empty($uid) && ($uid=$mc_member_info['uid']);
        $shop_member_info = $this->shop_member_info($uniacid, $openid);
        $shop_member_info['uid'] && empty($uid) && ($uid=$shop_member_info['uid']);
        if(empty($uid)){
            return ;
        }
        
        //微商模块没有这个用户
        $uid && ($where="yoxwechatbusiness_user.uid = :uid  AND yoxwechatbusiness_user.uniacid = :uniacid") && ($replace=array(':uid' => $uid, ':uniacid' => $uniacid));
        empty($uid) && ($where="yoxwechatbusiness_user.openid = :openid  AND yoxwechatbusiness_user.uniacid = :uniacid") && ($replace=array(':openid' => $openid, ':uniacid' => $uniacid));
        $data = pdo_fetch("SELECT yoxwechatbusiness_user.* FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
            " WHERE $where ", $replace);

        //生成邀请码
        $uid && ($myinvitationcode='X'.$uniacid.'X'.$uid);
        empty($uid) && ($myinvitationcode='X'.$uniacid.'X'.substr($openid,6). substr($openid,-5));//兼容没有uid的
        
        //有人邀请
        if(($inviteuid&&($inviteuid!=$uid)) || ($invitationcode&&($invitationcode!=$data['invitationcode']))){
            //邀请人
            $uniacid && ($where=' uniacid=:uniacid ') && ($replace=array(':uniacid'=>$uniacid));
            $inviteuid && ($where.= ' AND uid=:uid') && ($replace+=array(':uid'=>$inviteuid));
            $invitationcode && ($where.= ' AND invitationcode=:invitationcode') && ($replace+=array(':invitationcode'=>$invitationcode));
            $invit_data=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE $where ",$replace);
            if(empty($invit_data)){
                return ;
//                 message('邀请人不存在，请确认邀请码是否正确！', $this->createMobileUrl('reg'), 'error');
            }
            if(!empty($data)){
                return ;
            }
            $path=$invit_data['path']?$invit_data['path'].'-'.$invit_data['invite_uid']:$invit_data['uid'];
            $new_data=array();
            $new_data['uniacid']=$uniacid;
            $openid && ($new_data['openid']=$openid);
            $new_data['uid']=$uid;
            $new_data['path']=$path;
            empty($data) && ($new_data['add_time']=time());
            $myinvitationcode && $new_data['invitationcode']=$myinvitationcode;
            $invit_data['uid'] && $new_data['invite_uid']   =$invit_data['uid'];
            
            empty($data) && pdo_insert('ewei_shop_yoxwechatbusiness_user', $new_data);
            !empty($data)&& pdo_update('ewei_shop_yoxwechatbusiness_user',$new_data, array('uniacid' => $uniacid, 'uid' => $uid));
        }
        //无人邀请
        if(empty($inviteuid) && empty($invitationcode)){
            $new_data=array('uniacid'=>$uniacid,'openid'=>$openid,'uid'=>$uid,'invitationcode'=>$myinvitationcode);
            empty($data) && ($new_data['add_time']=time());
            !$data && pdo_insert('ewei_shop_yoxwechatbusiness_user',$new_data);
            $data  && pdo_update('ewei_shop_yoxwechatbusiness_user',$new_data, array('uniacid' => $uniacid, 'uid' => $uid));
        }
        //更新直属上等级，保证正确，方便后面数据统计查询
        $up_level_user=$this->set_user_up_level_invite_uid('',$uid);
        
        //根据邀请人数升级
        $invited_count= $this->invited_count($inviteuid);
        $setting_value= $this->setting_value($uniacid, 'UPGRADE');
        if($inviteuid && ($setting_value['setting']['type']=='UPGRADE_BY_INVITED')){
            //当前邀请人数所匹配的等级
            for($i=0;$i<7;$i++){
                ($invited_count>=$setting_value['setting']["level_{$i}_number"]) && $invited_user_new_level=$i;
            }
            //升级
            ($invit_data['level']<$invited_user_new_level) && pdo_update('ewei_shop_yoxwechatbusiness_user',array('level'=>$invited_user_new_level), array('uniacid' => $uniacid, 'uid' => $inviteuid));
        }
        
        return $data;
    }
    /**
     * 获取用户信息 yoxwechatbusiness_user
     * 兼容openid获取
     */
    public function user_info($uniacid,$uid){
        is_numeric($uid)  && ($where="yoxwechatbusiness_user.uid = :uid AND yoxwechatbusiness_user.uniacid = :uniacid") && ($replace=array(':uid' => $uid, ':uniacid' => $uniacid));
        !is_numeric($uid) && ($where="yoxwechatbusiness_user.openid=:openid AND yoxwechatbusiness_user.uniacid = :uniacid") && ($replace=array(':openid' => $uid, ':uniacid' => $uniacid));
        $shop_member_field=" shop_member.openid as shop_member_openid,shop_member.nickname,shop_member.realname,shop_member.avatar,shop_member.credit1,shop_member.credit2 ";
        $data = pdo_fetch("SELECT yoxwechatbusiness_user.*,{$shop_member_field} FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
            "LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.openid=yoxwechatbusiness_user.openid WHERE $where ", $replace);

        //佣金利润总额
        $total_commission=pdo_fetchcolumn("SELECT sum(commission.commission) as total_commission FROM ".tablename('ewei_shop_yoxwechatbusiness_commission')." commission WHERE commission.commission_uid=:commission_uid OR commission.commission_openid=:openid",array(':commission_uid'=>$data['uid'],':openid'=>$data['openid']));
        //未分到余额
        $total_commission_status_0 = pdo_fetchcolumn('SELECT sum(commission.commission) as total_commission_status_0 FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE (commission_uid=:commission_uid OR commission.commission_openid=:openid) AND `status`=:status",array(':commission_uid'=>$data['uid'],':openid'=>$data['openid'],':status'=>0));
        //已分到余额
        $total_commission_status_1 = pdo_fetchcolumn('SELECT sum(commission.commission) as total_commission_status_1 FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE (commission_uid=:commission_uid OR commission.commission_openid=:openid) AND `status`=:status",array(':commission_uid'=>$data['uid'],':openid'=>$data['openid'],':status'=>1));
        
        $data['total_commission']=(float)$total_commission;
        $data['total_commission_status_0']=(float)$total_commission_status_0;
        $data['total_commission_status_1']=(float)$total_commission_status_1;
        return $data;
    }
    /**
     *  shop_member信息
     *  兼容openid获取
     */
    private function shop_member_info($uniacid,$uid){
        global $_GPC, $_W;
        if(empty($uniacid)){
            $uniacid=$_W['uniacid'];
        }
        is_numeric($uid)  && ($where="shop_member.uid = :uid AND shop_member.uniacid = :uniacid") && ($replace=array(':uid' => $uid, ':uniacid' => $uniacid));
        !is_numeric($uid) && ($where="shop_member.openid=:openid AND shop_member.uniacid = :uniacid") && ($replace=array(':openid' => $uid, ':uniacid' => $uniacid));
        $data = pdo_fetch("SELECT shop_member.* FROM " . tablename('ewei_shop_member') . " shop_member WHERE $where ", $replace);
        return $data;
    }
    private function mc_member_info($uid){
        is_numeric($uid)  && ($where="mc_members.uid = :uid ") && ($replace=array(':uid' => $uid,));
        $data = pdo_fetch("SELECT mc_members.* FROM " . tablename('mc_members') . " mc_members WHERE $where ", $replace);
        return $data;
    }
    /**
     * 是否无等级
     */
    public function is_zero_level($uid){
        global $_GPC, $_W;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $user_info = $this->user_info($uniacid,$uid);
        if($user_info['level']<1){
            return true;
        }
        return false;
    }
    /**
     * 检查权限并json返回
     */
    public function check_permission_return($uid){
        if($this->is_zero_level($uid)){
            $this->is_from_wechat && show_json(0,'等级权限不够');
            return array('status'=>0,'message'=>'等级权限不够');
        }
        $mc_member_info  =$this->mc_member_info($uid);
        $shop_member_info=$this->shop_member_info(0, $uid);
        if(empty($mc_member_info['mobile'])&&empty($shop_member_info['weixin'])&&empty($mc_member_info['realname'])){
            $this->is_from_wechat && show_json(0,'请完善您的资料(手机号/微信号/真实姓名)再访问');
            return array('status'=>0,'message'=>'请完善您的资料再访问');
        }
        if(empty($mc_member_info['mobile'])){
            $this->is_from_wechat && show_json(0,'请完善您的资料(手机)再访问');
            return array('status'=>0,'message'=>'请完善您的(手机)资料再访问');
        }
        if(empty($shop_member_info['weixin'])){
            $this->is_from_wechat && show_json(0,'请完善您的资料(微信)再访问');
            return array('status'=>0,'message'=>'请完善您的(微信)资料再访问');
        }
        if(empty($mc_member_info['realname'])){
            $this->is_from_wechat && show_json(0,'请完善您的资料(姓名)再访问');
            return array('status'=>0,'message'=>'请完善您的(姓名)资料再访问');
        }
    }
    /**
     * 是否最高等级,兼容openid
     * @param int $uid
     */
    public function is_highest_level($uid){
        if(empty($uid)){
            die('uid empty');
        }
        
        $user_info = pdo_fetch("SELECT level,uniacid FROM " . tablename('ewei_shop_yoxwechatbusiness_user')  . " WHERE uid=:uid OR openid=:openid ", array(':uid' => $uid,':openid'=>$uid));
        //最高等级
        $highest_level_info = pdo_fetch("SELECT level FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uniacid=:uniacid ORDER BY level desc ", array(':uniacid' => $user_info['uniacid']));
        // 	    print_r($user_path_info);
        // 	    print_r($highest_level_info);
        if($user_info['level']<$highest_level_info['level']){
            return false;
        }
        return true;
    }
    /**
     * 是否某人邀请
     * $invite_uid 邀请人
     * $invitee_uid 被邀请人
     */
    public function is_user_invited($invite_uid,$invitee_uid){
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " user WHERE  user.invite_uid=:invite_uid AND uid=:uid",array(':invite_uid'=>$invite_uid,':uid'=>$invitee_uid));
        if(!$total){
            return false;
        }
        return true;
    }
    /**
     * 我的上等级,即时查找比较得出,兼容openid
     */
    public function _up_level_user($uid){
        $uid && ($info_condition=" uid=:uid OR openid=:openid") && ($replace=array(':uid'=>$uid,':openid'=>$uid));
        $user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE {$info_condition} ",$replace);
        //无人邀请
        if(empty($user_info['path'])){
            return;
        }
        $path_uids = str_replace("-",",",$user_info['path']);
        $path_uids && $up_level_user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE level>:level and uid in($path_uids) ORDER BY level asc,field(uid,{$path_uids}) asc", array(':level'=>$user_info['level']));
        //没有上等级邀请人
        if(empty($up_level_user_info)){
            return;
        }
        return $up_level_user_info;
    }
    /**
     * 我的上等级信息(根据记录获取)
     * 兼容openid
     */
    public function up_level_user($uid_openid){
        is_numeric($uid_openid) && ($info_condition=" uid=:uid") && ($replace=array(':uid'=>$uid_openid));
        is_string($uid_openid)  && ($info_condition=" openid=:openid") && ($replace=array(':openid'=>$uid_openid));
        $user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE {$info_condition} ",$replace);
        $up_level_user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid=:uid ",array(':uid'=>$user_info['up_level_invite_uid']));
        
        return $up_level_user_info;
    }
    /**
     * 邀请我的人信息
     * @param int $uid
     * @return boolean|unknown
     */
    public function invite_user($uid){
        $uid && ($info_condition=" uid=:uid") && ($replace=array(':uid'=>$uid));
        $user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE {$info_condition} ",$replace);
        $invite_user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid=:uid ",array(':uid'=>$user_info['invite_uid']));
        
        return $invite_user_info;
    }
    /**
     * 邀请了多少人 
     */
    public function invited_count($uid){
        $total=0;
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " user WHERE  user.uniacid=:uniacid AND user.invite_uid=:invite_uid",array(':uniacid'=>$uniacid,':invite_uid'=>$uid));
        return $total;
    }
    /**
     * 微商用户列表
     */
    public function user_list($condition,$field='*',$page_size=20){
        global $_GPC,$_W;
        $pindex = max(1, intval($_GPC['page']));
        $list = pdo_fetchall("SELECT yoxwechatbusiness_user.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
            " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=yoxwechatbusiness_user.uid ".
            " WHERE $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $page_size . ',' . $page_size, array(), 'id');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE $condition");
        $pager = pagination($total, $pindex, $page_size);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        
        return $result;
    }
    /**
     * 更新直属上等级，保证正确，方便后面数据统计查询
     */
    public function set_user_up_level_invite_uid($uniacid,$uid){
        global $_GPC,$_W;
        if(empty($uniacid)){
            $uniacid=$_W['uniacid'];
        }
        if(empty($uid)){
            $uid=$_W['fans']['uid'];
        }
        if(empty($uid)){
            $shop_member_info = pdo_fetch("SELECT uid FROM " . tablename('ewei_shop_member') . " WHERE openid = :openid ", array(':openid' => $_W['openid']));
            $uid=$shop_member_info['uid'];
        }
        $user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid = :uid ", array(':uid' => $uid));
        if(empty($user_info)){
            return;
        }
        
        $up_level_user=$this->_up_level_user($uid);
        if($up_level_user){
            pdo_update('ewei_shop_yoxwechatbusiness_user',array('up_level_invite_uid'=>$up_level_user['uid']), array('uniacid' => $uniacid, 'uid' => $uid));
        }
        if(empty($up_level_user)){
            pdo_update('ewei_shop_yoxwechatbusiness_user',array('up_level_invite_uid'=>0), array('uniacid' => $uniacid, 'uid' => $uid));
        }
    }
    /**
     * 分佣
     * @param array $order_info
     */
    public function _set_order_commission($order_info){
        logging_run(__METHOD__.':BEGIN');
        logging_run($order_info);
        global $_W;
        if(is_numeric($order_info)){
            $order_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_order') . " WHERE id = :id ", array(':id' => $order_info));
        }
        if(empty($order_info)){
            return ;
        }
        //购买的是等级套餐，不继续分佣
        if($order_info['packageid']){
            return ;
        }
        //购买者uid
        $buyer_uid = mc_openid2uid($order_info['openid']);
        logging_run($buyer_uid);
        //购买的商品
        $marketprice_field='goods_field.marketprice_level_1,goods_field.marketprice_level_2,goods_field.marketprice_level_3,goods_field.marketprice_level_4,goods_field.marketprice_level_5,goods_field.marketprice_level_6,goods_field.marketprice_level_7';
        $commission_up_level_field='goods_field.commission_up_level_1,goods_field.commission_up_level_2,goods_field.commission_up_level_3,goods_field.commission_up_level_4,goods_field.commission_up_level_5,goods_field.commission_up_level_6';
        $commission_invite_level_field='goods_field.commission_invite_level_1,goods_field.commission_invite_level_2,goods_field.commission_invite_level_3,goods_field.commission_invite_level_4,goods_field.commission_invite_level_5,goods_field.commission_invite_level_6,goods_field.commission_invite_level_7';
        $order_goods_list = pdo_fetchall("SELECT order_goods.*,$marketprice_field,$commission_up_level_field,$commission_invite_level_field FROM " . tablename('ewei_shop_order_goods') . " order_goods ".
            " LEFT JOIN ". tablename('ewei_shop_yoxwechatbusiness_goods_field')." goods_field ON goods_field.goods_id=order_goods.goodsid ".
            " WHERE order_goods.orderid=:orderid ORDER BY order_goods.id DESC LIMIT 0,99999", array(':orderid'=>$order_info['id']), 'id');
        
        logging_run($order_goods_list);
        //购买者
        $user_path_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid = :uid ", array(':uid' => $buyer_uid));
        //购买者级别信息
        $level_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid=:uniacid AND level = :level ", array(':uniacid'=>$user_path_info['uniacid'],':level' => $user_path_info['level']));
        //邀请人
        $invite_path_user_info = $this->invite_user($buyer_uid);
        //最近关联上等级
        $up_user_path_info = $this->up_level_user($buyer_uid);
        
        //上等级库存
        $up_user_stock_list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " WHERE uid=:uid LIMIT 0,99999", array(':uid'=>$up_user_path_info['uid']));
        

        //邀请关系
        $path_arr= explode('-',$user_path_info['path']);
        $path_count = count($path_arr);
        
        //关联上等级会员拥有的商品
        foreach($up_user_stock_list as $up_user_stock_info){
            $up_level_user_goodsid_optionid_arr[]=$up_user_stock_info['goods_id'].'_'.$up_user_stock_info['goods_option_id'];
        }
        
        //上等级或上级返佣
        foreach($order_goods_list as $order_goods_info){
            //加减库存
            $this->increase_stock($_W['uniacid'],$user_path_info['uid'],$order_goods_info['goodsid'],$order_goods_info['optionid']);
            //邀请人返佣金*N件
            if($invite_path_user_info){
                $single_commission=$this->invite_goods_commission($order_goods_info['goodsid'],  $order_goods_info['optionid'], $user_path_info['uid']);
                $commission=bcmul($single_commission,$order_goods_info['total'],2);
                $commission>=0 && pdo_insert("ewei_shop_yoxwechatbusiness_commission",array('uniacid'=>$_W['uniacid'],'order_id'=>$order_info['id'],'type'=>'INVITE_USER_COMMISSION','goods_id'=>$order_goods_info['goodsid'],'price'=>bcmul($order_goods_info['price'],$order_goods_info['total'],2),'total'=>$order_goods_info['total'],'uid'=>$user_path_info['uid'],'commission_uid'=>$invite_path_user_info['uid'],'commission'=>$commission,'remark'=>"邀请人返佣{$commission}",'add_time'=>time()));
            }
            //上等级会员返佣金*N件
            if($up_user_path_info){
                $goodsid_optionid=$order_goods_info['goodsid'].'_'.$order_goods_info['optionid'];
                logging_run($goodsid_optionid);
//                 logging_run($up_level_user_goodsid_optionid_arr);
                if(!in_array($goodsid_optionid, $up_level_user_goodsid_optionid_arr)){
                continue;
                }
                $single_commission=$this->up_level_goods_commission($order_goods_info['goodsid'], $order_goods_info['optionid'], $user_path_info['uid']);
                $commission=bcmul($single_commission,$order_goods_info['total'],2);
                $commission>=0 && pdo_insert("ewei_shop_yoxwechatbusiness_commission",array('uniacid'=>$_W['uniacid'],'order_id'=>$order_info['id'],'type'=>'UP_LEVEL_COMMISSION','goods_id'=>$order_goods_info['goodsid'],'price'=>bcmul($order_goods_info['price'],$order_goods_info['total'],2),'total'=>$order_goods_info['total'],'uid'=>$user_path_info['uid'],'commission_uid'=>$up_user_path_info['uid'],'commission'=>$commission,'remark'=>"上等级会员返佣{$commission}",'add_time'=>time()));
            }
        }

        //上等级会员获得的利润——利润=上等级购买价格-当前等级购买价格
        if($up_user_path_info){
            foreach($order_goods_list as $order_goods_info){
                $goodsid_optionid=$order_goods_info['goodsid'].'_'.$order_goods_info['optionid'];
                if(!in_array($goodsid_optionid, $up_level_user_goodsid_optionid_arr)){
                    continue;
                }
                $up_level_goods_price_key='marketprice_level_'.$up_user_path_info['level'];
                $buyer_level_goods_key   ='marketprice_level_'.$user_path_info['level'];
                //上等级会员商品拿货价格
                $up_level_goods_price=$order_goods_info[$up_level_goods_price_key];
                //当前等级购买价格
                $buyer_level_goods_price=$order_goods_info[$buyer_level_goods_key];
                $commission = bcsub($buyer_level_goods_price,$up_level_goods_price,2);
                ($commission>=0.01) && pdo_insert("ewei_shop_yoxwechatbusiness_commission",array('uniacid'=>$_W['uniacid'],'order_id'=>$order_info['id'],'type'=>'UP_LEVEL_PROFIT','goods_id'=>$order_goods_info['goodsid'],'price'=>bcmul($order_goods_info['price'],$order_goods_info['total'],2),'uid'=>$user_path_info['uid'],'commission_uid'=>$up_user_path_info['uid'],'commission'=>$commission,'remark'=>"上等级会员获得利润{$commission}元",'add_time'=>time()));
            }
        }
        logging_run(__METHOD__.':END');
    }
    
    /**
     * 升级
     * 升级操作-升级、返佣、购买者加库存、上等级减库存
     * @param int $package_id
     */
    public function _upgrade_level($order){
        logging_run(__METHOD__.':BEGIN');
        $level_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid=:uniacid AND package_id = :package_id ", array(':uniacid'=>$order['uniacid'],':package_id' => $order['packageid']));
        
        if(empty($level_info)){
            return ;
        }
        $uid=mc_openid2uid($order['openid']);
        $user_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid = :uid ", array(':uid' => $uid));
        
        //原等级比当前等级高，不处理，请联系客服退狂
        if($user_info['level']>=$level_info['level']){
            return ;
        }
        
        //根据购买套餐升级
        $setting_value=$this->setting_value($order['uniacid'], 'UPGRADE');
        if(empty($setting_value)||$setting_value['setting']['type']=='UPGRADE_BY_PACKAGE'){
            pdo_update("ewei_shop_yoxwechatbusiness_user",array('level'=>$level_info['level']),array('uid'=>$uid));
        }
        
        //升级一次性返佣,返上等级，返上级
        //UPGRADE_LEVEL_UP_USER_COMMISSION UPGRADE_LEVEL_INVITE_USER_COMMISSION
        $up_return_money=$level_info['up_return']<0?bcmul($level_info['up_return'],$order['price'],2):$level_info['up_return'];
        $up_return_money>0 && pdo_insert("ewei_shop_yoxwechatbusiness_commission",array('uniacid'=>$order['uniacid'],'order_id'=>$order['id'],'type'=>'UPGRADE_LEVEL_UP_USER_COMMISSION','goods_id'=>0,'price'=>$order['price'],
            'total'=>0,'uid'=>$uid,'commission_uid'=>$user_info['up_level_invite_uid'],'commission'=>$up_return_money,'remark'=>"购买套餐上等级会员返佣{$up_return_money}",'add_time'=>time()));
        $invite_return_money=$level_info['invite_return']<0?bcmul($level_info['invite_return'],$order['price'],2):$level_info['invite_return'];
        $invite_return_money>0 && pdo_insert("ewei_shop_yoxwechatbusiness_commission",array('uniacid'=>$order['uniacid'],'order_id'=>$order['id'],'type'=>'UPGRADE_LEVEL_INVITE_USER_COMMISSION','goods_id'=>0,'price'=>$order['price'],
            'total'=>0,'uid'=>$uid,'commission_uid'=>$user_info['up_level_invite_uid'],'commission'=>$invite_return_money,'remark'=>"购买套餐邀请人返佣{$invite_return_money}",'add_time'=>time()));
        
        //购买等级加库存,上等级减库存
        $package_goods_list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_package_goods') . " package_goods WHERE pid = :pid ", array(':pid' =>$order['packageid']));
        foreach($package_goods_list as $package_goods_info){
            //有规格套餐
            if($package_goods_info['option']){
                $package_option_list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_package_goods_option') . " package_goods WHERE package_goods.pid = :pid AND package_goods.optionid in({$package_goods_info['option']})", array(':pid' =>$order['packageid']));
                foreach($package_option_list as $package_option){
                    //购买者加库存
                    $num=$package_option['num']?$package_option['num']:1;
                    $user_stock_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE user_stock.uniacid=:uniacid AND user_stock.uid=:uid AND user_stock.goods_id=:goods_id AND goods_option_id=:goods_option_id ",array(':uniacid'=>$order['uniacid'],':uid'=>$uid,':goods_id'=>$package_goods_info['goodsid'],':goods_option_id'=>$package_option['optionid']));
                    $user_stock_info && pdo_update("ewei_shop_yoxwechatbusiness_user_stock",array('stock'=>$num+$user_stock_info['stock']),array('uniacid'=>$order['uniacid'],'uid'=>$uid,'goods_id'=>$package_goods_info['goodsid'],'goods_option_id'=>$package_option['optionid']));
                    empty($user_stock_info) && pdo_insert("ewei_shop_yoxwechatbusiness_user_stock",array('uniacid'=>$order['uniacid'],'uid'=>$uid,'goods_id'=>$package_goods_info['goodsid'],'goods_option_id'=>$package_option['optionid'],'stock'=>$num));
                    //上等级减库存
                    $up_level_user_stock_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE user_stock.uniacid=:uniacid AND user_stock.uid=:uid AND user_stock.goods_id=:goods_id AND goods_option_id=:goods_option_id",array(':uniacid'=>$order['uniacid'],':uid'=>$user_info['up_level_invite_uid'],':goods_id'=>$package_goods_info['goodsid'],'goods_option_id'=>$package_option['optionid']));
                    $new_stock=$up_level_user_stock_info['stock']-$num;
                    $user_info['up_level_invite_uid'] && pdo_update("ewei_shop_yoxwechatbusiness_user_stock",array('stock'=>$new_stock),array('uniacid'=>$order['uniacid'],'uid'=>$user_info['up_level_invite_uid'],'goods_id'=>$package_goods_info['goodsid'],'goods_option_id'=>$package_option['optionid']));
                }
                continue;
            }
            //没规格套餐
            //购买者加库存
            $num=$package_goods_info['num']?$package_goods_info['num']:1;
            $user_stock_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE user_stock.uniacid=:uniacid AND user_stock.uid=:uid AND user_stock.goods_id=:goods_id",array(':uniacid'=>$order['uniacid'],':uid'=>$uid,':goods_id'=>$package_goods_info['goodsid']));
            $user_stock_info && pdo_update("ewei_shop_yoxwechatbusiness_user_stock",array('stock'=>$num+$user_stock_info['stock']),array('uniacid'=>$order['uniacid'],'uid'=>$uid,'goods_id'=>$package_goods_info['goodsid']));
            empty($user_stock_info) && pdo_insert("ewei_shop_yoxwechatbusiness_user_stock",array('uniacid'=>$order['uniacid'],'uid'=>$uid,'goods_id'=>$package_goods_info['goodsid'],'stock'=>$num));
            //上等级减库存
            $up_level_user_stock_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE user_stock.uniacid=:uniacid AND user_stock.uid=:uid AND user_stock.goods_id=:goods_id",array(':uniacid'=>$order['uniacid'],':uid'=>$user_info['up_level_invite_uid'],':goods_id'=>$package_goods_info['goodsid']));
            $new_stock=$up_level_user_stock_info['stock']-$num;
            $user_info['up_level_invite_uid'] && pdo_update("ewei_shop_yoxwechatbusiness_user_stock",array('stock'=>$new_stock),array('uniacid'=>$order['uniacid'],'uid'=>$user_info['up_level_invite_uid'],'goods_id'=>$package_goods_info['goodsid']));
        }
        logging_run(__METHOD__.':END');
    }
    /**
     * 支付完成后
     * @param array $order_info
     */
    public function after_payment_process($order_info){
        //以下顺序不可变化
        //先分佣加减库存
        $this->_set_order_commission($order_info);
        //再升级
        $this->_upgrade_level($order_info);
    }
    /**
     * 获取会员等级商品价格
     */
    public function goods_price($goods_id,$option_id,$uid){
        if(empty($uid)){
            die('uid empty');
        }
        if(empty($goods_id)){
            die('goods_id and option_id empty');
        }
        if(empty($option_id)){
            $option_id=0;
        }
        $replace=array(':goods_id' => $goods_id,);
        if(!empty($option_id)){
            $leftjoin_option=" LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.goodsid=shop_goods.id ";
            $price_field="goods_option.marketprice";
            $replace+=array('goods_option_id'=>$option_id);
            $option_where=" AND goods_field.goods_option_id=:goods_option_id ";
        }
        //当前的会员等级
        $user_path_info = pdo_fetch("SELECT level FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid = :uid ", array(':uid' => $uid));
        $level=$user_path_info['level'];
        
        empty($level) && ($price_field='marketprice');
        $level && ($price_field='marketprice_level_'.$level);
        
        //等级价格
        $goods_field_info = pdo_fetch("SELECT {$price_field} FROM " . tablename('ewei_shop_goods') . " shop_goods " .
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_goods_field') . " goods_field ON goods_field.goods_id=shop_goods.id ".
            $leftjoin_option.
            " WHERE shop_goods.id = :goods_id $option_where ",$replace);

        $price = $goods_field_info[$price_field];
        return $price;
    }
    /**
     * 获取会员给上等级商品佣金
     */
    protected function up_level_goods_commission($goods_id,$option_id,$uid){
        logging_run(__METHOD__.':BEGIN');
        if(empty($uid)){
            return 0;
        }
        if(empty($goods_id)&&empty($option_id)){
            return 0;
        }
        if(empty($option_id)){
            $option_id=0;
        }
        //当前的会员等级
        $user_path_info = pdo_fetch("SELECT `level` FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid = :uid ", array(':uid' => $uid));
        if(empty($user_path_info)){
            return 0;
        }
        $level=$user_path_info['level'];
        if(empty($level)) return 0;
        
        $commission_field ="commission_up_level_{$level}";
        $marketprice_field="marketprice_level_{$level} ";
        
        //等级佣金
        $goods_field_info = pdo_fetch("SELECT {$commission_field},{$marketprice_field} FROM " . tablename('ewei_shop_goods') . " shop_goods ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_goods_field') . " goods_field ON goods_field.goods_id=shop_goods.id AND goods_field.goods_option_id=:goods_option_id".
            " WHERE shop_goods.id = :goods_id  ",array(':goods_id'=>$goods_id,':goods_option_id'=>$option_id));
        
        $goods_field_info[$commission_field]=floatval($goods_field_info[$commission_field]);
        //是比例
        if($goods_field_info[$commission_field]>0 && $goods_field_info[$commission_field]<1){
            $commission=bcmul($goods_field_info[$commission_field],$goods_field_info[$marketprice_field]);
            return $commission;
        }
        //是金额
        $commission =floatval($goods_field_info[$commission_field]);
        logging_run(__METHOD__.':END');
        return $commission;
    }
    /**
     * 获取会员给邀请人商品佣金 
     */
    protected function invite_goods_commission($goods_id,$option_id,$uid){
        logging_run(__METHOD__.':BEGIN');
        if(empty($uid)){
            return 0;
        }
        if(empty($goods_id)&&empty($option_id)){
            return 0;
        }
        if(empty($option_id)){
            $option_id=0;
        }
        //当前的会员等级
        $user_path_info = pdo_fetch("SELECT `level` FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uid = :uid ", array(':uid' => $uid));
        if(empty($user_path_info)){
            return 0;
        }
        $level=$user_path_info['level'];
        if(empty($level)) return 0;
        
        $commission_field='commission_invite_level_'.$level;
        $marketprice_field="marketprice_level_{$level} ";
        
        //等级佣金
        $goods_field_info = pdo_fetch("SELECT {$commission_field},{$marketprice_field} FROM " . tablename('ewei_shop_goods') . " shop_goods ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_goods_field') . " goods_field ON goods_field.goods_id=shop_goods.id AND goods_field.goods_option_id=:goods_option_id ".
            " WHERE shop_goods.id = :goods_id  ",array(':goods_id'=>$goods_id,':goods_option_id'=>$option_id));
        logging_run("SELECT {$commission_field},{$marketprice_field} FROM " . tablename('ewei_shop_goods') . " shop_goods ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_goods_field') . " goods_field ON goods_field.goods_id=shop_goods.id AND goods_field.goods_option_id=:goods_option_id ".
            " WHERE shop_goods.id = :goods_id  ");
        logging_run($goods_field_info);
        $goods_field_info[$commission_field]=floatval($goods_field_info[$commission_field]);
        //是比例
        if($goods_field_info[$commission_field]>0 && $goods_field_info[$commission_field]<1){
            $commission=bcmul($goods_field_info[$commission_field],$goods_field_info[$marketprice_field]);
            return $commission;
        }
        //是金额
        $commission =floatval($goods_field_info[$commission_field]);
        logging_run(__METHOD__.':END');
        return $commission;
    }
    /**
     * 转单
     * 兼容openid
     * @param int $order_id
     * @param int $from_uid
     * @param int $to_uid
     * @return string|boolean|number
     */
    public function set_transfer_order($order_id,$from_uid,$to_uid){
        $result['status']=0;
        if(empty($order_id)){
            $result['message']='订单为空';
            return $result;
            return false;
        }
        if(empty($from_uid)){
            $result['message']='uid为空';
            return $result;
            return false;
        }
        is_numeric($from_uid)&& ($openid=mc_uid2openid($from_uid));
        is_string($from_uid) && ($openid=$from_uid);
        $order_info = pdo_fetch("SELECT `status` FROM " . tablename('ewei_shop_order') . " WHERE id = :id AND openid=:openid OR uid=:uid ", array(':id' => $order_id,':openid'=>$openid,));
        //状态 -1取消状态（交易关闭），0普通状态（没付款: 待付款 ; 付了款: 待发货），1 买家已付款（待发货），2 卖家已发货（待收货），3 成功（可评价: 等待评价 ; 不可评价 : 交易完成）4 退款申请
        //未支付，不转单
        if($order_info['status']==0){
            $result['message']='未支付，不转单';
            return $result;
            return false;
        }
        //已发货，不转单
        if($order_info['status']==2){
            $result['message']='已发货，不转单';
            return $result;
            return false;
        }
        //已完成，不转单
        if($order_info['status']==3){
            $result['message']='已完成，不转单';
            return $result;
            return false;
        }
        //退款申请，不转单
        if($order_info['status']==4){
            $result['message']='退款申请，不转单';
            return $result;
            return false;
        }
        //默认转给上等级
        if(empty($to_uid)){
            $up_level_user_info = $this->up_level_user($from_uid);
            $to_uid=$up_level_user_info['uid'];
        }
        if(empty($to_uid)){
            $result['message']='上等级为空，不能转单';
            return $result;
            return false;
        }
        
        //转单
        pdo_insert("ewei_shop_yoxwechatbusiness_transfer_order",array('uniacid'=>$_W['uniacid'],'order_id'=>$order_id,'from_uid'=>$from_uid,'to_uid'=>$to_uid,'add_time'=>time()));
        //佣金归属修改
        pdo_update("ewei_shop_yoxwechatbusiness_commission",array('commission_uid'=>$to_uid),array('order_id'=>$order_id,));
        
        $result['status']=1;
        $result['message']='转单成功';
        return $result;
    }
    /**
     * 是否有库存
     */
    protected function in_stock($uid,$goods_id=0,$goods_option_id=0,$num=1){
        if(empty($uid)){
            return ;
        }
        $user_stock_info = pdo_fetch("SELECT level FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " WHERE uid = :uid AND goods_id =:goods_id AND goods_option_id=:goods_option_id AND stock>=$num ", array(':uid' => $uid,':goods_id'=>$goods_id,':goods_option_id'=>$goods_option_id));
        
        if(empty($user_stock_info)){
            return false;
        }
        return true;
    }
    /**
     * 购买者增加库存，关联上等级减库存
     * @param int $uid
     * @param int $goods_id
     * @param int $goods_option_id
     * @param int $num
     */
    protected function increase_stock($uniacid=0,$user_info,$goods_id=0,$goods_option_id=0,$num=1){
        global $_GPC,$_W;
        $num=$num?$num:1;
        empty($uniacid) && ($uniacid=$_W['uniacid']);
        if(is_numeric($user_info)){
            $user_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " user WHERE user.uniacid=:uniacid AND user.uid=:uid ",array(':uniacid'=>$uniacid,':uid'=>$user_info));
        }
        
        //找上等级，没有，再找上上等级以此类推
        $user_info && ($decrease_user_info=$this->_up_level_user($user_info['uid']));
        
        //购买者加库存
        $user_stock_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE user_stock.uniacid=:uniacid AND user_stock.uid=:uid AND user_stock.goods_id=:goods_id AND goods_option_id=:goods_option_id ",array(':uniacid'=>$uniacid,':uid'=>$user_info['uid'],':goods_id'=>$goods_id,':goods_option_id'=>$goods_option_id));
        $user_stock_info && pdo_update("ewei_shop_yoxwechatbusiness_user_stock",array('stock'=>$num+$user_stock_info['stock']),array('uniacid'=>$uniacid,'uid'=>$user_info['uid'],'goods_id'=>$goods_id,'goods_option_id'=>$goods_option_id));
        empty($user_stock_info) && pdo_insert("ewei_shop_yoxwechatbusiness_user_stock",array('uniacid'=>$uniacid,'uid'=>$user_info['uid'],'goods_id'=>$goods_id,'goods_option_id'=>$goods_option_id,'stock'=>$num));
        
        //关联上等级微商减库存，
        if($decrease_user_info){
            $decrease_user_stock_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock WHERE user_stock.uniacid=:uniacid AND user_stock.uid=:uid AND user_stock.goods_id=:goods_id AND goods_option_id=:goods_option_id ",array(':uniacid'=>$uniacid,':uid'=>$decrease_user_info['uid'],':goods_id'=>$goods_id,'goods_option_id'=>$goods_option_id));
            $new_stock=$decrease_user_stock_info['stock']-$num;
            $decrease_user_stock_info && (pdo_update("ewei_shop_yoxwechatbusiness_user_stock",array('stock'=>$new_stock),array('uniacid'=>$uniacid,'uid'=>$decrease_user_info['uid'],'goods_id'=>$goods_id,'goods_option_id'=>$goods_option_id)));
        }
    }
    /**
     *人人商城的小程序用户没有插入系统表，没有uid，为了兼容人人商城的小程序，新增一个用户在mc_members表
     */
    public function shop_member_to_sys_member($openid){
        global $_W;
        if(empty($openid)){
            return ;
            die('openid empty');
        }
        if($openid=='sns_wa_'){
            return ;
        }
        $uniacid=$_W['uniacid'];
        $mc_fans_info = pdo_fetch("SELECT fans.* FROM " . tablename('mc_mapping_fans') . " fans WHERE fans.uniacid=:uniacid AND fans.openid=:openid ",array(':uniacid'=>$uniacid,':openid'=>$openid));
        $mc_fans_info && ($mc_member_info =pdo_fetch("SELECT member.* FROM " . tablename('mc_members') . " member WHERE member.uniacid=:uniacid AND member.uid=:uid ",array(':uniacid'=>$uniacid,':uid'=>$mc_fans_info['uid'])));
        $shop_member_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_member') . " shop_member WHERE shop_member.uniacid=:uniacid AND shop_member.openid=:openid ",array(':uniacid'=>$uniacid,':openid'=>$openid));
        
        //插入 mc_members
        $salt='BIPIMICN';$password=md5($shop_member_info['openid'] . $salt . $_W['config']['setting']['authkey']);
        empty($mc_member_info) && (pdo_insert("mc_members",array('uniacid'=>$uniacid,'email'=>$shop_member_info['openid']."@qq.com",'password'=>$password,'salt'=>$salt,'realname'=>$shop_member_info['realname'],'nickname'=>$shop_member_info['nickname'],
            'avatar'=>$shop_member_info['avatar'],'mobile'=>$shop_member_info['mobile'],'createtime'=>time()))) && ($uid = pdo_insertid());
        empty($uid) && ($uid=$mc_member_info['uid']);
        //插入mc_mapping_fans
        empty($mc_fans_info) && pdo_insert("mc_mapping_fans",array('acid'=>$uniacid,'uniacid'=>$uniacid,'uid'=>$uid,'openid'=>$shop_member_info['openid'],'nickname'=>$shop_member_info['nickname'],'salt'=>$salt,'nickname'=>$shop_member_info['nickname']));
        //为没有uid的人人商城小程序用户填补上uid
        $uid && pdo_update("ewei_shop_member",array('uid'=>$uid),array('uniacid'=>$uniacid,'openid'=>$openid));
        
        $mc_member_info =pdo_fetch("SELECT member.uid,member.mobile,member.realname,member.nickname,member.avatar,member.gender,member.credit1,member.credit2,member.credit3,fans.openid FROM " . tablename('mc_members') . " member ".
            " LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON fans.uid=member.uid".
            " WHERE member.uniacid=:uniacid AND member.uid=:uid ",array(':uniacid'=>$uniacid,':uid'=>$uid));
        //pdo_debug();
	//print_r($mc_member_info);die('bb');
        return $mc_member_info;
    }
    /**
     * 分类列表
     */
    public function course_cate_list(){
        global $_GPC, $_W;
        
        $name = $_GPC['name'];
        
        $list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " cate WHERE cate.uniacid=:uniacid AND cate.name=:name " ,array(':uniacid'=>$uniacid,':name'=>$name));
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 课程分类信息 
     */
    public function course_cate_info(){
        global $_GPC, $_W;
        $id = $_GPC['id'];
        
        $info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " cate WHERE cate.uniacid=:uniacid AND cate.id=:id ",array(':uniacid'=>$uniacid,':id'=>$id));
        
        return $info;
    }
    public function course_list(){
        global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $page_size=20;
        
        $list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE course.uniacid=:uniacid AND course.name=:name LIMIT " . ($pindex - 1) * $page_size . ""," . $page_size" ,array(':uniacid'=>$uniacid,':name'=>$name));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE  course.uniacid=:uniacid AND course.name=:name",array(':uniacid'=>$uniacid,':name'=>$name));
        $pager = pagination($total, $pindex, $page_size);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    public function course_info(){
        global $_GPC, $_W;
        $id = $_GPC['id'];
        
        $info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE course.uniacid=:uniacid AND course.id=:id ",array(':uniacid'=>$uniacid,':id'=>$id));
        
        return $info;
    }
    //====================================================以下是页面，方便网页和小程序调取===============================================================//
    /**
     * 我的商品
     */
    public function page_my_goods_list(){
        global $_GPC, $_W;
        $_GPC['uid']=$_W['member']['uid'];
        $this->page_goods_list();
    }
    /**
     * 微商商品列表
     */
    public function page_goods_list(){
        global $_GPC, $_W;
        $uid=$_GPC['uid'];
        
        $pindex=intval($_GPC['pindex']);
        $psize=15;
        
        $uniacid=$_W['uniacid'];
        
        $where = " user_stock.uniacid = '{$uniacid}' ";
        //$status!='' && ($where.=" AND `status` = '{$status}' ");
        $uid!='' && ($where.=" AND user_stock.`uid` ={$uid}");
        $left_join_goods=" LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=user_stock.goods_id";
        $left_join_goods_option=" LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=user_stock.goods_option_id";
        $goods_field=" goods.title AS goods_title,goods.thumb";
        $option_field=" goods_option.title AS option_title,goods.option_thumb";
        $list = pdo_fetchall("SELECT user_stock.*,{$goods_field},{$option_field} FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock ".
            $left_join_goods.$left_join_goods_option.
        "WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 微商代理招募/授权证书
     */
    public function page_agent(){
    //error_reporting(E_ALL);
    //ini_set("display_errors","On");
        global $_GPC, $_W;
        $uid_openid= $_W['fans']['uid']?$_W['fans']['uid']:$_W['openid'];
        $uniacid=$_W['uniacid'];
	
	$this->check_permission_return($uid);
	
        $user_info = $this->user_info($uniacid,$uid_openid);
        $setting_value=$this->setting_value($uniacid,self::CERTIFICATE_NAME); 
	//pdo_debug();
        //var_dump($setting_value);die('YOPER');
        $result['data']['certificate']=$setting_value;
        if(!$this->is_from_wechat){
            // 	    "https://zdu.igdlrj.com/app/index.php?i={$_W['uniacid']}&c=entry&m=ewei_shopv2&do=mobile&r=yoxwechatbusiness&inviteuid={$_W['fans']['uid']}"
            $r="https://zdu.igdlrj.com/app/index.php?i={$_W['uniacid']}&c=entry&m=ewei_shopv2&do=mobile&r=yoxwechatbusiness&inviteuid={$user_info['uid']}";
            $r=urlencode("https://zdu.igdlrj.com/app/index.php?i={$_W['uniacid']}&c=entry&m=ewei_shopv2&do=mobile&r=yoxwechatbusiness&inviteuid={$user_info['uid']}");
            //$img_url="http://qr.liantu.com/api.php?text=https://zdu.igdlrj.com/app/index.php?i={$_W['uniacid']}&c=entry&m=ewei_shopv2&do=mobile&r=yoxwechatbusiness&inviteuid={$uid_openid}";
            //$img_url="http://bshare.optimix.asia/barCode?site=weixin&url=https://zdu.igdlrj.com/app/index.php?i={$_W['uniacid']}&c=entry&m=ewei_shopv2&do=mobile&r=yoxwechatbusiness&inviteuid={$uid_openid}";
            //$img_url="http://api.k780.com:88/?app=qr.get&data=https://zdu.igdlrj.com/app/index.php?i={$_W['uniacid']}&c=entry&m=ewei_shopv2&do=mobile&r=yoxwechatbusiness&inviteuid={$uid_openid};
            //$img_url="https://www.kuaizhan.com/common/encode-png?large=true&data=".$r;
            //$img_url="https://cli.im/api/qrcode/code?text=".$r;
            $img_url="http://bshare.optimix.asia/barCode?site=weixin&url=".$r;
            $result['data']['img_url']=$img_url;
        }
        if($this->is_from_wechat){
	//$user_info['uid']=999;
            $img = intval($_GPC['img']);//$img=1;
            $ret = p("app")->getCodeUnlimit(array('scene' => 'inviteuid=' . $user_info['uid'], 'page' =>'pages/index/index'));//pages/goods/detail/index、packageYoxwechatbusiness/pages/index/index
// 	    var_dump($ret);die();
            if ($img) {
                header('content-type: image/png');
                exit($ret);
            }
            if(empty($img)){
                $data='image/png;base64,'.base64_encode($ret);
                $result['data']['img_base64']=$data;
//                 show_json(is_error($ret) ? 0 : 1);
            }
        }
        $result['status']=1;
        return $result;
    }
    /**
     * 微商佣金列表 
     */
    public function page_commission(){
        global $_GPC, $_W;
        $type=$_GPC['type'];
        $commission_uid=$_GPC['commission_uid'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_GPC['uid']?$_GPC['uid']:mc_openid2uid($_W['openid']);
        
	$this->check_permission_return($uid);
	
        $uniacid && ($where=' commission.uniacid = :uniacid ') && ($replace=array(':uniacid' => $uniacid));
        $type!=''&& ($where.=' AND commission.type=:type') && ($replace+=array(':type' => $type));
        $uid && ($where.=' AND commission.uid=:uid') && ($replace+=array(':uid' => $uid));
        $commission_uid && ($where.=' AND commission.commission_uid=:commission_uid') && ($replace+=array(':commission_uid' => $commission_uid));
        $field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title,transfer_order.from_uid,transfer_order.to_uid";
        $list = pdo_fetchAll("SELECT commission.*,CASE commission.`status` WHEN 1 THEN '已返佣' WHEN 0 THEN '未分佣' END AS status_name,member.nickname,member.avatar,member2.nickname AS commission_nickname,member2.avatar AS commission_avatar,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission ".
            " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=commission.uid ".
            " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=commission.commission_uid ".
            " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=commission.goods_id ".
            " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=commission.goods_option_id ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order ON transfer_order.order_id=commission.order_id ".
            " WHERE $where ", $replace);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission  WHERE $where",$replace);
        $pager = pagination($total, $pindex, $psize);
        
        //佣金利润总额
        $total_commission=pdo_fetchcolumn("SELECT sum(commission.commission) as total_commission FROM ".tablename('ewei_shop_yoxwechatbusiness_commission')." commission WHERE $where",$replace);
        //未分到余额
        $commission_uid && $total_commission_status_0 = pdo_fetchcolumn('SELECT sum(commission.commission) as total_commission_status_0 FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE commission_uid=:commission_uid AND `status`=:status",array(':commission_uid'=>$commission_uid,':status'=>0));
        //已分到余额
        $commission_uid && $total_commission_status_1 = pdo_fetchcolumn('SELECT sum(commission.commission) as total_commission_status_1 FROM ' . tablename('ewei_shop_yoxwechatbusiness_commission') . " commission WHERE commission_uid=:commission_uid AND `status`=:status",array(':commission_uid'=>$commission_uid,':status'=>1));
        
        //pdo_debug();
        $result['status']=1;
        $result['total_commission']=$total_commission;
        $commission_uid && $result['total_commission_status_0']=$total_commission_status_0;
        $commission_uid && $result['total_commission_status_1']=$total_commission_status_1;
        $result['message']='success';
        $result['data']['list']=$list;
        return $result;
    }
    /**
     * 微商佣金信息
     */
    public function page_commission_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $status = $_GPC['status'];
        $is_effective = $_GPC['is_effective'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        
        $field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title";
        $info = pdo_fetch("SELECT commision.*,CASE commision.`status` WHEN 1 THEN '已返佣' WHEN 0 THEN '未分佣' END AS status_name,member.nickname,member.avatar,member2.nickname AS commission_nickname,member2.avatar AS commission_avatar,{$field} FROM " . tablename('ewei_shop_yoxwechatbusiness_commission') . " commision ".
            " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=commision.uid ".
            " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=commision.commission_uid ".
            " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=commision.goods_id ".
            " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=commision.goods_option_id ".
            " WHERE commision.id = :id AND commision.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        /*
         if($status!='' || $is_effective!=''){
         $data=array();
         $uniacid && $data['uniacid']=$uniacid;
         $merchid && $data['merchid']=$merchid;
         $status!='' && $data['status']=$status;
         $is_effective!=''&& $data['is_effective']=$is_effective;
         
         if(!$id){
         $message="添加";
         // 	            pdo_insert('ewei_shop_yoxwechatbusiness_commission', $data);
         }
         if($id){
         $message="修改";
         $is_update=pdo_update("ewei_shop_yoxwechatbusiness_commission", $data, array("id" => $id));
         }
         
         //分佣到余额
         if($is_update && $info['status']==0 && $status==1){
         
         load()->model('mc');
         mc_credit_update($info['commission_uid'], 'credit2', $info['commission'], array(0, '确认分佣到余额'));
         $message='已分佣到用户余额，用户可申请提现';
         }
         
         show_json(1, $message."成功");
         // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
         }
         */
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    /**
     * 微商 订单列表(客户管理-客户分析)
     */
    public function page_order_list(){
        global $_GPC, $_W;
        
        $user_mark=$_GPC['user_mark'];//TEAM/SINGLE、
        $begin_time=$_GPC['begin_time']?$_GPC['begin_time']:0;
        $end_time=$_GPC['end_time']?$_GPC['end_time']:time();
        
        $status  =$_GPC['status'];
        $ordersn=$_GPC['ordersn'];
        
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:9999;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        //单人统计
        //如果传的是uid，则获取指定的人订单，不传获取当前的用户订单
        if($user_mark=='SINGLE'){
            $openid = mc_uid2openid($_W['member']['uid']);
            if($_GPC['uid']){
                $openid=mc_uid2openid($_GPC['uid']);
            }
        }
        
        //团队
        if($user_mark=='TEAM'){
            $list = pdo_fetchall("SELECT `user`.openid FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " `user` ".
                " WHERE invite_uid ={$_W['member']['uid']} ORDER BY id DESC LIMIT 0,999999", array(), 'id DESC');
            foreach($list as $info){
                $openids_arr[]=$info['openid'];
            }
            $openids_str=implode(',', $openids_arr);
            
            //前N名
            $top_where = " shop_order.uniacid = '{$uniacid}' ";
            $openids_str!='' && ($top_where.=" AND shop_order.`openid` in({$openids_str})");
            $left_join=" LEFT JOIN " . tablename('ewei_shop_member') . " shop_member ON shop_member.openid=shop_order.openid ";
            $shop_member_field=" shop_member.nickname,shop_member.avatar ";
            $top_list = pdo_fetchall("SELECT SUM(shop_order.price) AS total_price,{$shop_member_field} FROM " . tablename('ewei_shop_order') . " shop_order {$left_join}  WHERE {$top_where}  GROUP BY shop_order.openid  ORDER BY total_price DESC LIMIT 0,5", array());
        }
        
        
        $where = " uniacid = '{$uniacid}' ";
        $merchid!=''&& ($where.=" AND `merchid` = '{$merchid}' ");
        $status!='' && ($where.=" AND `status` = '{$status}' ");
        $ordersn!=''&& ($where.=" AND `ordersn` = '{$ordersn}' ");
        $openid!='' && ($where.=" AND `openid` = '{$openid}' ");
        $openids_str!='' && ($where.=" AND `openid` in({$openids_str})");
        $begin_time!='' && $end_time!='' && ($where.=" AND createtime >={$begin_time} AND createtime<{$end_time} ");
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_order') . " WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $pay_price_total=0;
        foreach($list as $info){
            $pay_price_total+=$info['price'];
        }
        
        //成交额
        $result['data']['pay_price_total']=$pay_price_total;
        //成交数
        $result['data']['total']=$total;
        
        $result['status']=1;
        $result['data']['top_list']=$top_list;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 客户管理-销售概况
     */
    public function page_sales_overview(){
        global $_GPC, $_W;
        $begin_time=$_GPC['begin_time']?$_GPC['begin_time']:0;
        $end_time=$_GPC['end_time']?$_GPC['end_time']:time();
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:9999;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        
        //团队
        $list = pdo_fetchall("SELECT `user`.openid FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " `user` ".
            " WHERE invite_uid ={$_W['member']['uid']} ORDER BY id DESC LIMIT 0,999999", array(), 'id DESC');
        foreach($list as $info){
            $openids_arr[]=$info['openid'];
        }
        $openids_str=implode(',', $openids_arr);
        
        $where = " uniacid = '{$uniacid}' ";
        $openids_str!='' && ($where.=" AND `openid` in({$openids_str})");
        $begin_time!='' && $end_time!='' && ($where.=" AND createtime >={$begin_time} AND createtime<{$end_time} ");
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_order') . " WHERE $where AND `status`>=1  ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $order_pay_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_order') . " WHERE $where  AND `status`>=1");//已支付订单
        $order_not_pay_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_order') . " WHERE $where");//未支付订单
        $total_pay_price = pdo_fetchcolumn('SELECT SUM(price) FROM ' . tablename('ewei_shop_order') . " WHERE $where AND `status`>=1");//已支付总金额
        $total_pay_user = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_order') . " shop_order WHERE $where AND `status`>=1 GROUP BY shop_order.openid");//支付用户数
        $total_user = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_order') . " shop_order WHERE $where GROUP BY shop_order.openid");//全部下单用户
        $pager = pagination($order_pay_num, $pindex, $psize);
        
        //pdo_debug();
        //客单价
        $result['data']['customer_unit_price']=bcdiv($total_pay_price,$total_pay_user,2);
        //支付订单数量
        $result['data']['order_pay_num']=$order_pay_num;
        //支付订单数量
        $result['data']['order_not_pay_num']=$order_not_pay_num;
        //订单数量
        $result['data']['order_total']=$order_pay_num+$order_not_pay_num;
        //下单总金额
        $result['data']['total_pay_price']=$total_pay_price;
        //下单人数
        $result['data']['total_user']=$total_user;
        //转化率
        $result['data']['order_conversion_rate']=bcdiv($order_pay_num,$order_pay_num+$order_not_pay_num,2);
        //付款人数
        $result['data']['total_pay_user']=$total_pay_user;
        return $result;
        
    }
    /**
     * 客户管理-商品分析
     */
    public function page_goods_analysis(){
        global $_GPC, $_W;
        $begin_time=$_GPC['begin_time']?$_GPC['begin_time']:0;
        $end_time=$_GPC['end_time']?$_GPC['end_time']:time();
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:9999;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        
        $where = " uniacid = '{$uniacid}' ";
        //在售商品数量
        $begin_time!='' && $end_time!='' && ($where.=" AND createtime >={$begin_time} AND createtime<{$end_time} ");
        //$list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_goods') . " WHERE $where AND `status`>=1  ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $sale_goods_total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_goods') . " shop_goods WHERE $where AND `status`>=1 ");
        
        //交易额
        $left_join_order=" LEFT JOIN " . tablename('ewei_shop_order') . " shop_order ON shop_order.id=order_goods.orderid ";
        $left_join_order && ($where.=" shop_order.`status` >=1 ");
        $total_pay_price = pdo_fetchcolumn("SELECT SUM(shop_order.price) FROM " . tablename('ewei_shop_order') . " shop_order WHERE {$where} `status`>=1  ");
        //商品总销量
        $order_goods_num=pdo_fetchcolumn("SELECT SUM(total) FROM " . tablename('ewei_shop_order_goods') . " `order_goods` ".
            " WHERE $where ", array());
        
        //热销排行商品列表 按照销量排序
        $order_goods_list_hot=pdo_fetchcolumn("SELECT goodsid,title FROM " . tablename('ewei_shop_order_goods') . " `order_goods` ".
            " WHERE {$where} GROUP BY order_goods.goodsid ORDER BY goods_total DESC LIMIT 0,5", array());
        //热销排行商品列表 按照金额排序
        $order_goods_list_hot2=pdo_fetchcolumn("SELECT SUM(price) AS sum_price,goodsid,title FROM " . tablename('ewei_shop_order_goods') . " `order_goods` ".
            " WHERE {$where} GROUP BY order_goods.goodsid ORDER BY sum_price DESC LIMIT 0,5", array());
        
        $result['data']['sale_goods_num']=$sale_goods_total;
        $result['data']['total_pay_price']=$total_pay_price;
        $result['data']['order_goods_num']=$order_goods_num;
        $result['data']['order_goods_list_hot']=$order_goods_list_hot;
        $result['data']['order_goods_list_hot2']=$order_goods_list_hot2;
        return $result;
    }
    /**
     * 手动添加订单 
     */
    public function page_create_order_manual(){
        global $_GPC, $_W;
        
        $buyeruid=$_GPC['buyeruid'];
        $goodslist_json=$_GPC['goodslist_json'];//{goods:[['goodsid':1,'total':1]['goodsid':2,'total':1],['optionid':6,'total':1],['optionid':7,'total':1]]}
        $status=$_GPC['status'];//订单状态
        $remark=$_GPC["remark"];
        
        $uniacid=$_W['uniacid'];
        $merchid=$_W['merchid'];
        $buyeropenid=mc_uid2openid($buyeruid);
        
        $goodslist=json_decode($goodslist_json);
        
        $ismerch=$merchid?1:0;
        
        $up_level_user_info=$this->up_level_user($buyeruid);
        
        if(empty($up_level_user_info)){
            show_json(0,'选择的用户没有上等级，不可添加');
        }
        if($up_level_user_info['uid']!=$_W['member']['uid']){
            show_json(0,'选择的用户不是您的下等级，不可添加');
        }
        foreach($goodslist['goods'] as $data){
            $goodsid_arr[] =$data['goodsid'];
            $optionid_arr[]=$data['optionid'];
        }
        $goodsid_str =implode(',', $goodsid_arr);
        $optionid_str=implode(',',$optionid_arr);
        
        if(empty($buyeropenid)){
            show_json(0,"查无此用户({$buyeruid})");
        }
        //没有传参数
        if(empty($goodsid_arr)&&empty($optionid_arr)){
            show_json(0,'商品参数为空');
        }
        
        //商品列表(无规格)
        if($goodsid_arr){
            $where = " uniacid = '{$uniacid}' ";
            ($where.=" AND shop_goods.`id` in({$goodsid_str})");
            $list = pdo_fetchall("SELECT shop_goods.*,shop_goods.id AS goodsid FROM " . tablename('ewei_shop_goods') . " shop_goods WHERE {$where} ORDER BY id DESC LIMIT 0,999", array(), 'id DESC');
        }
        
        //商品列表(有规格)
        if($optionid_arr){
            $where = " uniacid = '{$uniacid}' ";
            ($where.=" AND goods_option.`id` in({$optionid_str})");
            $list = pdo_fetchall("SELECT goods_option.*,goods_option.id AS optionid FROM " . tablename('ewei_shop_goods_option') . " goods_option WHERE {$where} ORDER BY id DESC LIMIT 0,999", array(), 'id DESC');
        }
        
        //不存在
        if(empty($list)){
            show_json(0,'商品不存在');
        }
        
        foreach($list as $info){
            foreach($goodslist['goods'] as $data){
                if($info['goodsid']==$data['goodsid']||$info['optionid']==$data['optionid']){
                    $goodstotal=$data['total'];
                    break;
                }
            }
            $level_price=$this->goods_price($data['goodsid'],$data['optionid'],$buyeruid);
            $level_price>0 && ($info["ggprice"]=$gprice=$level_price * $goodstotal);//单个总价
            $level_price>0 && ($yoxwechatbusiness_payprice+= $gprice);//微商等级全部支付总价格
            $level_price>0 && ($yoxwechatbusiness_price+= $info["marketprice"] * $goodstotal - $gprice);//优惠了多少钱
            $level_price>0 && ($goodsprice+= $info["marketprice"] * $goodstotal);//商品原总价
            
            $order_goods=array();
            $order_goods["merchid"] = $merchid;
            $order_goods["merchsale"] = $info["merchsale"];
            $order_goods["uniacid"] = $uniacid;
            //$order_goods["orderid"] = $order_id;
            $order_goods["goodsid"] = $info["goodsid"];
            $order_goods["price"] = $info["ggprice"];
            $order_goods["total"] = $goodstotal;
            $order_goods["optionid"] = $info["optionid"];
            $order_goods["createtime"] = time();
            $order_goods["optionname"] = $info["optiontitle"];
            $order_goods["goodssn"] = $info["goodssn"];
            $order_goods["productsn"] = $info["productsn"];
            $order_goods["realprice"] = $info["ggprice"];
            $order_goods_list[]=$order_goods;
            $goodstotal=1;
        }
        
        
        if( 0 < $ismerch )
        {
            $ordersn = m("common")->createNO("order", "ordersn", "ME");
        }
        else
        {
            $ordersn = m("common")->createNO("order", "ordersn", "SH");
        }
        
        //添加订单
        $order_data = array( );
        $order_data["merchid"] = $merchid?$merchid:0;
        $order_data["ismerch"] = $merchid?1:0;//是否商户
        $order_data["parentid"] = 0;
        $order_data["uniacid"] = $uniacid;
        $order_data["openid"] = $buyeropenid;
        $order_data["ordersn"] = $ordersn;
        $order_data["price"] = $yoxwechatbusiness_payprice;
        $order_data["oldprice"] = $goodsprice;
        $order_data["grprice"] = $yoxwechatbusiness_payprice;
        $order_data["taskdiscountprice"] = 0;//任务折扣价
        $order_data["discountprice"] = 0;//会员折扣
        $order_data["discountprice"] = 0;//订单号 SH 正常订单 RC 充值订单 DH 兑换订单
        $order_data["isdiscountprice"] = 0;//折扣价
        $order_data["merchisdiscountprice"] = 0;//商户折扣价格
        $order_data["cash"] = 1;//现付 货到付款？
        $order_data["status"] = 0;
        $order_data["iswxappcreate"] = 1;//是否小程序订单
        $order_data["remark"] = trim($remark);//备注
        $order_data["addressid"] = (empty($dispatchtype) ? $addressid : 0);//地址id
        $order_data["goodsprice"] = $goodsprice;
        $order_data["dispatchtype"] = 1;//0 商家配送 1 自提
        $order_data["dispatchid"] = 0;//配送方式ID
        $order_data["storeid"] = 0;//自提门店ID
        $order_data["carrier"] = 'a:0:{}';//自提联系人信息
        $order_data["createtime"] = time();
        $order_data["olddispatchprice"] = 0;//原运费
        $order_data["couponid"] = 0;//优惠券ID
        $order_data["couponmerchid"] = 0;//商户优惠券
        $order_data["paytype"] = 11;//	支付类型 1为余额支付 2在线支付 3 货到付款 11 后台付款 21 微信支付 22 支付宝支付 23 银联支付
        $order_data["deductprice"] = 0;//积分抵扣
        $order_data["deductcredit"] = 0;//余额抵扣
        $order_data["deductcredit2"] = 0;//余额抵扣
        $order_data["deductenough"] = 0;//满额优惠
        $order_data["merchdeductenough"] = 0;// 商户满减
        $order_data["couponprice"] = 0;//优惠券价格
        $order_data["merchshow"] = 0;//商户显示
        $order_data["buyagainprice"] = 0;//复购价格
        $order_data["ispackage"] = 0;//是否套餐
        $order_data["packageid"] = 0;//套餐ID
        $order_data["seckilldiscountprice"] = 0;//秒杀折扣价
        $order_data["quickid"] = intval($_GPC["fromquick"]);//快速购买
        $order_data["coupongoodprice"] = 0;//优惠商品价格
        $order_id=pdo_insert("ewei_shop_order",$order_data);
        
        $order_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_order') . " WHERE uniacid=:uniacid AND id=:id  ",array(':uniacid' =>$uniacid,':id'=>$order_id));
        
        if(empty($order_info)){
            show_json(0,"订单添加失败({$order_id})");
        }
        
        //添加商品订单
        $order_goods=array();
        foreach($order_goods_list as $order_goods){
            $order_goods["orderid"] = $order_id;
            pdo_insert("ewei_shop_order_goods",$order_goods);
        }
        
        //分佣、减库存、升级
        $this->after_payment_process($order_info);
        
        
        $result['status']=1;
        $result['data']=$order_info;
        return $result;
    }
    /**
     * 微商 转给我的订单
     */
    public function page_transfer_to_me_order_list(){
        global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['fans']['uid'];
        
        $where = " uniacid = :uniacid " && ($replace=array(':uniacid'=>$uniacid));
        $where.= " AND to_uid = :uid " && ($replace+=array(':to_uid'=>$uid));
        $field="shop_order.price,shop_order.`status`,shop_order.addressid,shop_order.address,shop_order.refundid,from_members.nickname AS from_nickname,from_members.avatar AS from_avatar,to_members.nickname AS to_nickname,to_members.avatar AS to_avatar";
        $list = pdo_fetchall("SELECT *,FROM_UNIXTIME(add_time) AS add_time_format,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order ".
            " LEFT JOIN " . tablename('mc_members') . " from_members ON from_members.uid=transfer_order.from_uid ".
            " LEFT JOIN " . tablename('mc_members') . " to_members ON to_members.uid=transfer_order.to_uid ".
            " LEFT JOIN " . tablename('ewei_shop_order') . " shop_order ON shop_order.id=transfer_order.order_id ".
            " WHERE $where ORDER BY transfer_order.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 微商 我转出去的订单
     */
    public function page_transfer_from_me_order_list(){
        global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['fans']['uid'];
        
        $where = " uniacid = :uniacid " && ($replace=array(':uniacid'=>$uniacid));
        $where.= " AND from_uid = :from_uid " && ($replace+=array(':from_uid'=>$uid));
        $field="shop_order.price,shop_order.`status`,shop_order.addressid,shop_order.address,shop_order.refundid,from_members.nickname AS from_nickname,from_members.avatar AS from_avatar,to_members.nickname AS to_nickname,to_members.avatar AS to_avatar";
        $list = pdo_fetchall("SELECT *,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order ".
            " LEFT JOIN " . tablename('mc_members') . " from_members ON from_members.uid=transfer_order.from_uid ".
            " LEFT JOIN " . tablename('mc_members') . " to_members ON to_members.uid=transfer_order.to_uid ".
            " LEFT JOIN " . tablename('ewei_shop_order') . " shop_order ON shop_order.id=transfer_order.order_id ".
            " WHERE $where ORDER BY transfer_order.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_transfer_order') . " transfer_order WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 设置为已发货
     */
    public function page_set_order_shipped(){
        global $_GPC, $_W;
        
        $_GPC['id'] && $id=$_GPC['id'];
        $order_goods_id=$_GPC['order_goods_id'];
        $express=$_GPC['express'];
        $expresscom=$_GPC['expresscom'];
        $expresssn=$_GPC['expresssn'];
        $securitycodes=$_GPC['securitycodes'];
        $sendtype=1; //v2 发货类型  0按订单发货 1< 分包发货 （多个快递单号）
        $sendtime=time();
        $uniacid=$_W['uniacid'];
        
        //取url最后12位 http://zdu.315-china.com/static/zdu/home/q.php?s=500664203022 
        foreach($securitycodes as $key=>&$securitycode){
            $isMatched = preg_match('/^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+/', $securitycode, $matches);
            if($isMatched){
                $securitycode=substr($securitycode,-12);
            }
        }
        
        $order = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_order') . " WHERE uniacid=:uniacid AND id=:id  ",array(':uniacid' =>$uniacid,':id'=>$id));
        if( empty($order["addressid"]) )
        {
            $message="无收货地址，无法发货";
            return $message;
        }
        
        $order_send_data=array('express'=>$express,'expresscom' => $expresscom,'expresssn'=>$expresssn,'sendtime'=>$sendtime);
        
        //订单发货
        if(empty($order_goods_id)&& $sendtype==0){
            pdo_update('ewei_shop_order', $order_send_data, array('id' => $id, 'uniacid' => $uniacid));
        }
        
        //分包发货
        if($sendtype==1){
            $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_order_goods') . " WHERE uniacid=:uniacid AND orderid=:orderid ORDER BY id DESC LIMIT 0,1000", array(':uniacid'=>$uniacid,':id'=>$order_goods_id), 'id DESC');
            foreach($list as $order_goods_info){
                pdo_update('ewei_shop_order_goods', $order_send_data, array('id' => $order_goods_info['id'], 'uniacid' => $uniacid));
            }
        }
        
        //如果有安全码
        if($securitycodes){
            /*此处查找有没有小码，有就加进小码*/
            foreach($securitycodes as $securitycode){
                pdo_insert("ewei_shop_order_goods_securitycode",array('uniacid'=>$uniacid,'order_id'=>$id,'order_goods_id'=>$order_goods_id,'securitycode'=>$securitycode));
            }
        }
        
        //订单商品
        $order_goods_list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_order_goods') . " WHERE uniacid=:uniacid AND id=:id  ",array(':uniacid' =>$uniacid,':id'=>$id));
        //安全码
        $order_goods_securitycode_list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_order_goods_securitycode') . " WHERE uniacid=:uniacid AND order_id=:id  ",array(':uniacid' =>$uniacid,':id'=>$id));
        foreach($order_goods_securitycode_list as $securitycode){
            $shipped_order_goods_securitycode_list[$securitycode['order_goods_id']][]=$securitycode;
        }
        
        $result['status']=1;
        $result['data']['order']=array_merge((array)$order,(array)$order_send_data);
        $result['data']['order_goods_list']=$order_goods_list;
        $result['data']['shipped_order_goods_securitycode_list']=$shipped_order_goods_securitycode_list;
        return $result;
    }
    /**
     * 微商库存列表 
     */
    public function page_user_stock_list(){
        global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid = $_W['fans']['uid']?$_W['fans']['uid']:0;
        $_GPC['uid']!='' && ($uid=$_GPC['uid']);
        
        $goods_field="goods.title AS goods_title,goods.thumb AS goods_thumb,goods_option.title AS goods_option_title";
        $list = pdo_fetchAll("SELECT user_stock.*,member.nickname,member.avatar,{$goods_field} FROM " . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " user_stock".
            " LEFT JOIN " . tablename('mc_members') . " member ON member.uid=user_stock.uid ".
            " LEFT JOIN " . tablename('ewei_shop_goods') . " goods ON goods.id=user_stock.goods_id ".
            " LEFT JOIN " . tablename('ewei_shop_goods_option') . " goods_option ON goods_option.id=user_stock.goods_option_id ".
            "WHERE user_stock.uniacid = :uniacid AND user_stock.uid=:uid", array( ':uniacid' => $uniacid,':uid'=>$uid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user_stock') . " WHERE uniacid = :uniacid",array( ':uniacid' => $uniacid));
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['message']='success';
        $result['data']['list']=$list;
        return $result;
    }
    /**
     * 微商 我的下等级
     */
    public function page_my_down_level_user(){
        global $_GPC, $_W;
        
        $gender =$_GPC['gender'];
        $begin_time=$_GPC['begin_time'];
        $end_time=$_GPC['end_time'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['member']['uid'];
        
        $this->check_permission_return($uid);
	
        $where = " `user`.uniacid = '{$uniacid}' ";
        $merchid!=''&& ($where.=" AND `merchid` = '{$merchid}' ");
        $gender!=''&& ($where.=" AND `gender` = '{$gender}' ");
        $begin_time!='' && $end_time!='' && ($where.=" AND user.`add_time` >= '{$begin_time}' AND user.`add_time`<{$end_time} ");
        $where.=" AND `up_level_invite_uid` = '{$uid}' ";//只有这句不同
        $left_join=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=`user`.uid ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON `level`.`level`=`user`.`level` AND `level`.uniacid=$uniacid ";
        $list = pdo_fetchall("SELECT `user`.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " `user` ".
            $left_join.
            " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " user WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $male_num  =0;
        $female_num=0;
        $unknown=0;
        foreach($list as &$info){
            //性别统计
            $info['gender']==0 && ($unknown++);
            $info['gender']==1 && ($male_num++);
            $info['gender']==2 && ($female_num++);
            //城市
            empty($info['residecity']) && $info['residecity']='未知';
            empty($city[$info['residecity']]['num']) && ($city[$info['residecity']]['num']=1) && ($city[$info['residecity']]['name']=$info['residecity']);
            $city[$info['residecity']]['num'] && ($city[$info['residecity']]['num']++) && ($city[$info['residecity']]['name']=$info['residecity']);
        }
        $result['data']['gender']['unknown']=$unknown;
        $result['data']['gender']['male_num']=$male_num;
        $result['data']['gender']['female_num']=$female_num;
        
        array_multisort(array_column($city,'num'),SORT_DESC,$city);
        $result['data']['city']=$city;
        
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    public function page_invite_me(){
        global $_GPC, $_W;
        $up_level_user_info = $this->invite_user($_W['uid']);
        $result['status']=1;
        $result['data']=$up_level_user_info;
        return $result;
    }
    /**
     * 微商 我的上等级
     */
    public function page_my_up_level_user(){
        global $_GPC, $_W;
        $up_level_user_info = $this->up_level_user($_W['uid']);
        $result['status']=1;
        $result['data']=$up_level_user_info;
        return $result;
    }
    /**
     * 我邀请的用户 
     */
    public function page_my_invite_user(){
        global $_GPC, $_W;
        
        $status =$_GPC['status'];
        $ordersn=$_GPC['ordersn'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['member']['uid'];
        
        $this->check_permission_return($uid);
	
        $where = " user.uniacid = '{$uniacid}' ";
        $merchid!=''&& ($where.=" AND `merchid` = '{$merchid}' ");
        $status!='' && ($where.=" AND `status` = '{$status}' ");
        $ordersn!=''&& ($where.=" AND `ordersn` = '{$ordersn}' ");
        $where.=" AND user.`invite_uid` = '{$uid}' ";//只有这句不同
        $left_join=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=user.uid ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON `level`.`level`=user.`level` AND `level`.uniacid=$uniacid ";
        $list = pdo_fetchall("SELECT user.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " user ".
            $left_join.
            " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " user WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    
    /**
     * 成分社采集商品
     */
    public function page_collect_product(){
        global $_GPC, $_W;
        $keyword=$_GPC['keyword']?$_GPC['keyword']:"";
        $uniacid=$_W['uniacid'];
        /*if(empty($keyword)){
            show_json(0,"请输入关键字");
        }*/
        if($keyword){
    	//$data_list=pdo_fetch("SELECT * FROM " . tablename('ims_ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.name=:name ",array(':uniacid'=>$uniacid,':name'=>$keyword));
            include EWEI_SHOPV2_VENDOR . 'querylist/phpQuery.php';
            include EWEI_SHOPV2_VENDOR . 'querylist/QueryList.php';
            $doname='http://www.cosdna.com';
            if(true){
                $p=$_GPC['p'];
                $url="http://www.cosdna.com/chs/product.php?q={$keyword}&p=$p";
                $rules=array('title'=>array('table tbody a','text'),'href'=>array('table tbody a','href'));
                $list = QueryList::Query($url,$rules)->data;
            }
            if($list){
                $p++;
                $url="http://www.cosdna.com/chs/product.php?q={$keyword}&p=$p";
                $list2 = (array)QueryList::Query($url,$rules)->data;
            }
            if($list2){
                $p++;
                $url="http://www.cosdna.com/chs/product.php?q={$keyword}&p=$p";
                $list3 = (array)QueryList::Query($url,$rules)->data;
            }
            $data_list = array_merge((array)$list,(array)$list2,(array)$list3);
            //参入数据库
            foreach($data_list as $info){
                $total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.name=:name",array(':uniacid'=>$uniacid,':name'=>$info['title']));
                empty($total) && $info && pdo_insert("ewei_shop_yoxfriendscircle_product",array('uniacid'=>$uniacid,'name'=>$info['title'],'url'=>$doname.'/chs/'.$info['href'],'add_time'=>time()));
            }
            $count=count($data_list);
            show_json(1,"执行成功,采集了{$count}入库");
        }
        
    }
    public function collect_ingredient(){
        global $_GPC, $_W;
        $product_id=$_GPC['product_id']?$_GPC['product_id']:$_GPC['product_id'];
        $uniacid=$_W['uniacid'];
        
//         $ingredient_field="ingredient.en_name,ingredient.cn_name,ingredient.general_characteristics,ingredient.acne,ingredient.stimulate,ingredient.safety";
        $product_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.id= :id ",array(':uniacid'=>$uniacid,':id'=>$product_id));
//         $list=pdo_fetchall("SELECT product_ingredient.*,{$ingredient_field} FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
//             " LEFT JOIN " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient ON ingredient.id=product_ingredient.ingredient_id ".
//             " WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.product_id=:product_id " ,array(':uniacid'=>$uniacid,':product_id'=>$product_id));
        include EWEI_SHOPV2_VENDOR . 'querylist/phpQuery.php';
        include EWEI_SHOPV2_VENDOR . 'querylist/QueryList.php';
        //$rules=array("url"=>array('.iStuffETitle a','href'),'en_name'=>array('.iStuffETitle a','text'),'cn_name'=>array('.iStuffCTitle a','text'),'general_characteristics'=>array('.iStuffChar','text'),'acne'=>array(),'stimulate'=>array(),'safetyL'=>array('.SafetyL','text'),'SafetyM'=>array('.SafetyM','text'),'SafetyH'=>array('.SafetyH','text'));
        $rules=array('en_name'=>array('td:eq(0) a','text'),'cn_name'=>array('td:eq(1) a','text'),'general_characteristics'=>array('td:eq(2) span','text'),'acne'=>array('td:eq(3) span','text'),'stimulate'=>array('td:eq(4) span','text'),'safety'=>array('td:eq(5)','text'));
        $data_list = QueryList::Query($product_info['url'],$rules,"table>tr[valign='top']")->data;
        //获取该产品的成分，如果该成分不存在，则添加该成分进数据库
        //新增产品成分关联
        foreach($data_list as $data){
            //$safety=$data['safetyL'].'~'.$data['SafetyM'].'~'.$data['SafetyH'];
            $safety=$data['safety'];
            $ingredient_data=array('uniacid'=>$uniacid,'en_name'=>$data['en_name'],'cn_name'=>$data['cn_name'],'general_characteristics'=>$data['general_characteristics'],'stimulate'=>$data['stimulate'],'safety'=>$safety,'add_time'=>time());
            //该成分信息
            $ingredient_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient WHERE ingredient.uniacid=:uniacid AND ingredient.en_name= :en_name ",array(':uniacid'=>$uniacid,':en_name'=>$ingredient_data['en_name']));
            //该成分不存在，添加性的成分
            empty($ingredient_info) && pdo_insert("ewei_shop_yoxfriendscircle_ingredient",$ingredient_data) && ($ingredient_data['id'] = pdo_insertid());
            
            //关联化妆品-成分
            $ingredient_id=$ingredient_info['id']?$ingredient_info['id']:$ingredient_data['id'];
            $product_ingredient_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.product_id= :product_id AND ingredient_id=:ingredient_id ",array(':uniacid'=>$uniacid,':product_id'=>$product_id,'ingredient_id'=>$ingredient_id));
            empty($product_ingredient_info) && pdo_insert("ewei_shop_yoxfriendscircle_product_ingredient",array('uniacid'=>$uniacid,'product_id'=>$product_info['id'],'ingredient_id'=>$ingredient_id,'add_time'=>time()));
            
        }
        $count=count($data_list);
        show_json(1,"执行成功,采集了{$count}入库");
    }
    /**
     * 成分
     */
    public function page_ingredient_product(){
    global $_GPC, $_W;
    $keyword=$_GPC['keyword']?$_GPC['keyword']:"";
    $pindex=$_GPC['page']?$_GPC['page']:1;
    $psize=15;
    $uniacid=$_W['uniacid'];
    
    //前台用户权限判断//
    $this->check_permission_return($_W['member']['uid']);
//     $data_list=pdo_fetch("SELECT * FROM " . tablename('ims_ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.name=:name ",array(':uniacid'=>$uniacid,':name'=>$keyword));
    include EWEI_SHOPV2_VENDOR . 'querylist/phpQuery.php';
    include EWEI_SHOPV2_VENDOR . 'querylist/QueryList.php';
    $doname='http://www.cosdna.com';
    if(false){
	    $p=$_GPC['p'];
	    $url="http://www.cosdna.com/chs/product.php?q={$keyword}&p=$p";
	    $rules=array('title'=>array('table tbody a','text'),'href'=>array('table tbody a','href'));
	    $list = QueryList::Query($url,$rules)->data;
	}
	if($list){
	    $p++;
	    $url="http://www.cosdna.com/chs/product.php?q={$keyword}&p=$p";
	    $list2 = (array)QueryList::Query($url,$rules)->data;
	}
    if($list2){
    	$p++;
    	$url="http://www.cosdna.com/chs/product.php?q={$keyword}&p=$p";
    	$list3 = (array)QueryList::Query($url,$rules)->data;
	}
	$data_list = array_merge((array)$list,(array)$list2,(array)$list3);
    //参入数据库
    foreach($data_list as $info){
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.name=:name",array(':uniacid'=>$uniacid,':name'=>$info['title']));
        empty($total) && $info && pdo_insert("ewei_shop_yoxfriendscircle_product",array('uniacid'=>$uniacid,'name'=>$info['title'],'url'=>$doname.'/chs/'.$info['href'],'add_time'=>time()));
    }
    
    ($where= " ingredient_product.uniacid=:uniacid") && ($replace=array(':uniacid'=>$uniacid));
    $keyword && ($where.=" AND ingredient_product.`name` LIKE :name ") && ($replace+=array(':name'=>"%{$keyword}%"));
    $list=pdo_fetchall("SELECT *,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE $where  LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE $where",$replace);
    $pager = pagination($total, $pindex, $psize);
    
    foreach($list as &$info){
        $info['ischecked']=false;
    }
    unset($info);
    
     //pdo_debug();
    $result['status']=1;
    $result['data']['list']=$list;
    $result['data']['pager']=$pager;
    return $result;
    }
    /**
     * 成分
     */
    public function page_ingredient(){
        global $_GPC, $_W;
        $ingredient_product_id=$_GPC['id']?$_GPC['id']:$_GPC['product_id'];
        $pindex=$_GPC['page']?$_GPC['page']:1;
        $psize=25;
//         $keyword=$_GPC['keyword'];
        $uniacid=$_W['uniacid'];
        //前台用户权限判断//
        $this->check_permission_return($_W['member']['uid']);
        $ingredient_field="ingredient.en_name,ingredient.cn_name,ingredient.general_characteristics,ingredient.acne,ingredient.stimulate,ingredient.safety";
        $product_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.id= :id ",array(':uniacid'=>$uniacid,':id'=>$ingredient_product_id));
        $list=pdo_fetchall("SELECT product_ingredient.*,{$ingredient_field} FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
            " LEFT JOIN " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient ON ingredient.id=product_ingredient.ingredient_id ".
            " WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.product_id=:product_id " ,array(':uniacid'=>$uniacid,':product_id'=>$ingredient_product_id));
        if(false && empty($list)){
            include EWEI_SHOPV2_VENDOR . 'querylist/phpQuery.php';
            include EWEI_SHOPV2_VENDOR . 'querylist/QueryList.php';
//             $rules=array("url"=>array('.iStuffETitle a','href'),'en_name'=>array('.iStuffETitle a','text'),'cn_name'=>array('.iStuffCTitle a','text'),'general_characteristics'=>array('.iStuffChar','text'),'acne'=>array(),'stimulate'=>array(),'safetyL'=>array('.SafetyL','text'),'SafetyM'=>array('.SafetyM','text'),'SafetyH'=>array('.SafetyH','text'));
            $rules=array('en_name'=>array('td:eq(0) a','text'),'cn_name'=>array('td:eq(1) a','text'),'general_characteristics'=>array('td:eq(2) span','text'),'acne'=>array('td:eq(3) span','text'),'stimulate'=>array('td:eq(4) span','text'),'safety'=>array('td:eq(5)','text'));
            $data_list = QueryList::Query($product_info['url'],$rules,"table>tr[valign='top']")->data;
            //获取该产品的成分，如果该成分不存在，则添加该成分进数据库
            //新增产品成分关联
            foreach($data_list as $data){
//                 $safety=$data['safetyL'].'~'.$data['SafetyM'].'~'.$data['SafetyH'];
                $safety=$data['safety'];
                $ingredient_data=array('uniacid'=>$uniacid,'en_name'=>$data['en_name'],'cn_name'=>$data['cn_name'],'general_characteristics'=>$data['general_characteristics'],'stimulate'=>$data['stimulate'],'safety'=>$safety,'add_time'=>time());
                //该成分信息
                $ingredient_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient WHERE ingredient.uniacid=:uniacid AND ingredient.en_name= :en_name ",array(':uniacid'=>$uniacid,':en_name'=>$ingredient_data['en_name']));
                //该成分不存在，添加性的成分
                empty($ingredient_info) && pdo_insert("ewei_shop_yoxfriendscircle_ingredient",$ingredient_data) && ($ingredient_data['id'] = pdo_insertid());
                
                //关联化妆品-成分
                $ingredient_id=$ingredient_info['id']?$ingredient_info['id']:$ingredient_data['id'];
                $product_ingredient_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.product_id= :product_id AND ingredient_id=:ingredient_id ",array(':uniacid'=>$uniacid,':product_id'=>$ingredient_product_id,'ingredient_id'=>$ingredient_id));
                empty($product_ingredient_info) && pdo_insert("ewei_shop_yoxfriendscircle_product_ingredient",array('uniacid'=>$uniacid,'product_id'=>$product_info['id'],'ingredient_id'=>$ingredient_id,'add_time'=>time()));
//                 echo $ingredient_id;
//                 print_r($ingredient_info);print_r($ingredient_data);die('aaa');
            }
        }
        $list=pdo_fetchall("SELECT product_ingredient.*,{$ingredient_field} FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
            " LEFT JOIN " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient ON ingredient.id=product_ingredient.ingredient_id ".
            " WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.product_id=:product_id LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':uniacid'=>$uniacid,':product_id'=>$ingredient_product_id));
        $comment_list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " comment WHERE comment.uniacid=:uniacid AND comment.target_id=:product_id " ,array(':uniacid'=>$uniacid,':product_id'=>$ingredient_product_id));
	
        //pdo_debug();
        $result['status']=1;
        $result['data']['ingredient_list']=$list;
        $result['data']['comment_list']=$comment_list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 相同成分的产品
     */
    public function page_same_ingredient_goods_list(){
        global $_GPC, $_W;
        $product_id=$_GPC['product_id']?$_GPC['product_id']:'';
        
        $uniacid=$_W['uniacid'];
        
        $product_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid=:uniacid AND ingredient_product.id= :id ",array(':uniacid'=>$uniacid,':id'=>$product_id));
        
        //产品的成分
        $product_ingredient_list=pdo_fetchall("SELECT product_ingredient.ingredient_id,product_ingredient.product_id FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
            " WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.product_id=:product_id LIMIT 0,999" ,array(':uniacid'=>$uniacid,':product_id'=>$product_info['id']));
        
        //产品成分的id
        foreach($product_ingredient_list as $product_ingredient_info){
            $product_ingredient_ids[]=$product_ingredient_info['ingredient_id'];
        }
        $product_ingredient_ids=implode(',', $product_ingredient_ids);
        //有相同成分的产品ID
        $product_ingredient_ids && $list=pdo_fetchall("SELECT product.*,product_ingredient.ingredient_id,product_ingredient.product_id FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
            " LEFT JOIN " . tablename('ewei_shop_yoxfriendscircle_product') . " product ON product.id=product_ingredient.product_id ".
            " WHERE product_ingredient.uniacid=:uniacid AND product_ingredient.ingredient_id in(:product_ingredient_ids) GROUP BY product_ingredient.product_id LIMIT 0,50" ,array(':uniacid'=>$uniacid,':product_ingredient_ids'=>$product_ingredient_ids));
        
	//pdo_debug();
        $result['status']=1;
        $result['data']['same_ingredient_goods_list']=$list;
//         $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 成分列表 
     */
    public function page_ingredient_list(){
        global $_GPC, $_W;
        $keyword= $_GPC['keyword'];
        $pindex=$_GPC['page']?$_GPC['page']:1;
        $psize=15;
        
        $uniacid=$_W['uniacid'];
        
        ($where=" uniacid=:uniacid") && ($replace=array(':uniacid'=>$uniacid));
        $keyword!='' && ($where.= " AND cn_name LIKE :keyword OR en_name LIKE :keyword ") && ($replace+=array(':keyword'=>"%{$keyword}%"));
        $list=pdo_fetchall("SELECT *,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient WHERE $where  LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient WHERE $where",$replace);
        $pager = pagination($total, $pindex, $psize);
        
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 成分详情
     */
    public function page_ingredient_info(){
        global $_GPC, $_W;
        $keyword= $_GPC['keyword'];
        $id= $_GPC['id'];
        
        $uniacid=$_W['uniacid'];
        $uniacid && ($where = " uniacid=:uniacid") && ($replace=array(':uniacid'=>$uniacid));
        $id && $where=" ingredient.id=:id" && ($replace+=array(':id'=>$id));
        $ingredient_info=pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient WHERE $where ",$replace);
        
        //有此成分的产品
        $product_list=pdo_fetchall("SELECT product.*,FROM_UNIXTIME(product_ingredient.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
            " LEFT JOIN " . tablename('ewei_shop_yoxfriendscircle_product') . " product ON product.id=product_ingredient.product_id ".
            " WHERE product_ingredient.ingredient_id=:ingredient_id GROUP BY product_ingredient.product_id LIMIT 0,50",array(':ingredient_id'=>$ingredient_info['id']));
        
	//pdo_debug();
        $result['status']=1;
        $result['data']=$ingredient_info;
        $result['data']['product_list']=$product_list;
        return $result;
    }
    /**
     * 用户信息 
     */
    public function page_user_info(){
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $uid = $_W['member']['uid'];
        $data = $this->user_info($uniacid, $uid);
        //pdo_debug();
        $result['status']=1;
        $result['data']=$data;
        return $result;
    }
    public function page_member_edit(){
        global $_GPC, $_W;
        $uid = intval($_GPC['uid']);
        $mobile  = $_GPC['mobile'];
        $realname  = $_GPC['realname'];
        $qq  = $_GPC['qq'];
	$weixin  = $_GPC['weixin'];
        $reside  = $_GPC['reside'];//省市区
        $address  = $_GPC['address'];
        
        $level  =$_GPC['level'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $login_uid=$_W['fans']['uid'];
        
        $up_level_user_info=$this->up_level_user($uid);
        $user_info    =$this->user_info($uniacid, $uid);
        
        if($uid && $up_level_user_info['uid']!=$login_uid){
            if($this->is_from_wechat)return array('status'=>0,'message'=>'您不是该用户的上等级，无法修改此资料');
            empty($this->is_from_wechat) && show_json(0, "您不是该用户的上等级，无法修改此资料");
        }
        //没传uid则是更改自己资料
        if(empty($uid)){
            $uid=$login_uid;
            $user_info=$this->user_info($uniacid, $uid);
            $is_self=true;
        }
        
        $is_save=false;
	//微擎用户表
        if($qq!='' || $reside!='' || $mobile!='' || $realname!='' ||$address!=''){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $mobile &&  $data['mobile']=$mobile;
            $realname &&  $data['realname']=$realname;
            $qq &&  $data['qq']=$qq;
            $reside &&  $data['reside']=$reside;
            $address &&  $data['address']=$address;
            $uid && $data && ($is_save=mc_update($uid,$data));
            
        }
	//微商表
        if($level!=''){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            !$is_self && $data['level']=$level;
            $data && ($is_save=pdo_update("ewei_shop_yoxwechatbusiness_user", $data, array("uid" => $uid)));
        }
	//商城用户表
	if($weixin){
	$data=array();
	$weixin && $data['weixin']=$weixin;
	$data && ($is_save=pdo_update("ewei_shop_member", $data, array("uid" => $uid)));
	}
        if($is_save){
            if($this->is_from_wechat)return array('status'=>1,'message'=>'成功');
            empty($this->is_from_wechat) && show_json(1, "成功");
        }
        
//         $info = mc_fetch($uid);
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$this->user_info($uniacid, $uid);;
        //pdo_debug();
        return $result;
    }
    public function page_update_lat_lng(){
        global $_GPC, $_W;
        $lng=$_GPC['lng'];
        $lat=$_GPC['lat'];
        $uid=$_W['member']['uid'];
        if($lng=='' || $lat==''){
            if($this->is_from_wechat)return array('status'=>0,'message'=>'经纬度不能为空');
            empty($this->is_from_wechat) && show_json(0, "经纬度不能为空");
        }
        
        include EWEI_SHOPV2_VENDOR . 'geohash/Geohash.class.php';
        $geohash = new Geohash;
        $data=array();
        $lng && $data['lng']=$lng;
        $lat && $data['lat']=$lat;
        $data['geohash']=$geohash->encode($data['lat'], $data['lng']);//纬度、经度
        $data && ($is_save=pdo_update("ewei_shop_yoxwechatbusiness_user", $data, array("uid" => $uid)));
        
        if($this->is_from_wechat)return array('status'=>1,'message'=>'ok');
        empty($this->is_from_wechat) && show_json(1, "ok");
    }
    /**
     * 附近的人 
     */
    public function page_people_nearby(){
        global $_GPC, $_W;
        $lng=$_GPC['lng'];//经度
        $lat=$_GPC['lat'];//纬度
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid'];
        $uid    =$_W['uid'];
        
        $lng=$lng*3.1415926535898 / 180.0;
        $lat=$lat*3.1415926535898 / 180.0;
        
        // 根据经纬来计算附近的好友
        $sql = "select * ,(ROUND( (2 * asin(sqrt(pow(sin(('".$lat."'-(lat*3.1415926535898 / 180.0))/2),2)+cos('".$lat."')*cos(lat*3.1415926535898 / 180.0)*pow(sin(('".$lng."'-lng*3.1415926535898 / 180.0)/2),2))))* 6378.137 *10000)/10)as distance from " . tablename('ewei_shop_yoxwechatbusiness_user') . " where uid!='".$uid."' order by distance asc limit " . ($pindex - 1) * $psize . ',' . $psize;

        $list = pdo_fetchall($sql);
        $total = pdo_fetchcolumn("select count(*) from " . tablename('ewei_shop_yoxwechatbusiness_user') . " where uid!='{$uid}'",array());
        $pager = pagination($total, $pindex, $psize);
        pdo_debug();
        $result['status']=1;
        $result['message']='success';
        $result['data']['list']=$list;
        $result['data']['page']['pager']=$pager;
	$result['data']['page']['total']=$total;
        return $result;
    }
    /**
     * 用户列表
     */
    public function page_user_list(){
        global $_GPC, $_W;
        
        $invite_uid=$_GPC['invite_uid'];
        $invitationcode=$_GPC['invitationcode'];
        $up_level_invite_uid=$_GPC['up_level_invite_uid'];
        $level = $_GPC['level'];
        
        $uniacid=$_W['uniacid'];
        
        $condition=" yoxwechatbusiness_user.uniacid=$uniacid " ;
        $invite_uid!='' && ($condition.=" AND yoxwechatbusiness_user.invite_uid=$invite_uid ");
        $invitationcode!='' && ($condition.=" AND yoxwechatbusiness_user.invitationcode='$invitationcode' ");
        $up_level_invite_uid!='' && ($condition.=" AND yoxwechatbusiness_user.up_level_invite_uid=$up_level_invite_uid ");
        $level!='' && ($condition.=" AND yoxwechatbusiness_user.`level`=$level ");
        $result=$this->user_list($condition,$field='yoxwechatbusiness_user.*',$page_size=20);
        return $result;
    }
    public function page_bank_card_list(){
        global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['uid']?$_W['uid']:0;
        $list = pdo_fetchAll("SELECT `bank_card`.* FROM " . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " `bank_card` ".
            " WHERE `bank_card`.uniacid = :uniacid AND uid=:uid ", array( ':uniacid' => $uniacid,':uid'=>$uid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_bank_card') . " `bank_card` WHERE `bank_card`.uniacid = :uniacid AND uid=:uid ",array( ':uniacid' => $uniacid,':uid'=>$uid));
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['message']='success';
        $result['data']['list']=$list;
        return $result;
    }
    public function page_bank_card_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $name = $_GPC['name'];
        $idcard = $_GPC['idcard'];
        $subbranch = $_GPC['subbranch'];
        $bank_name = $_GPC['bank_name'];
        $status = $_GPC['status'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['fans']['uid'];
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
        return $result;
    }
    /**
     * 我的朋友
     */
    public function page_my_frient(){
        global $_GPC,$_W;
        
        $remark_name  = $_GPC['remark_name'];
        $status  = !empty($_GPC['status']) ? $_GPC['status'] : '';
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 100;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $uid=$_W['member']['uid'];
        
        $replace=array();
        $where = " friend.uniacid = {$uniacid} ";
        $where.= " AND friend.uid = {$uid} ";
//         	    $type!='' && ($where.=" AND type=:type") && ($replace=array(':type'=>$type));
        $status!='' && ($where.=" AND status=:status") && ($replace=array(':status'=>$status));
        $left_join_member1=" LEFT JOIN " . tablename('mc_members') . " members1 on members1.uid=friend.uid ";
        $left_join_member2=" LEFT JOIN " . tablename('mc_members') . " members2 on members2.uid=friend.frient_uid ";
        $member_field1=" members1.nickname,members1.avatar ";
        $member_field2=" members2.nickname as frient_nickname,members2.avatar as frient_avatar ";
        $list = pdo_fetchall("SELECT friend.*,FROM_UNIXTIME(add_time) AS add_time_format,{$member_field1},{$member_field2} FROM " . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend ".
            $left_join_member1.$left_join_member2.
            "WHERE $where ORDER BY displayorder ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace, 'id');
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend WHERE {$where}");
            $pager = pagination($total, $pindex, $psize);
            
            $result['status']=1;
            $result['data']['list']=$list;
            $result['data']['pager']=$pager;
	    return $result;
    }
    /**
     * 添加和编辑好友
     */
    public function page_frient_edit(){
        global $_GPC,$_W;
        
        $id=$_GPC['id'];
        // 	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
        // 	    $keyword = $_GPC['keyword'];
        $frient_uid=$_GPC['frient_uid'];
        $remark_name  = $_GPC['remark_name'];
        $status  = $_GPC['status'];
        $displayorder  = $_GPC['displayorder'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $uid=$_W['member']['uid'];
        
        if($remark_name!=''||$frient_uid){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $uid && $data['uid']=$uid;
            $frient_uid && $data['frient_uid']=$frient_uid;
            $remark_name!='' && $data['remark_name']=$remark_name;
            $status!='' && $data['status']=$status;
            $displayorder!='' && $data['displayorder']=$displayorder;
            
            if(!$id){
                $frient_info = pdo_fetch("SELECT friend.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend ".
                    "WHERE uid = :uid AND uniacid = :uniacid AND frient_uid=:frient_uid ", array(':uid' => $uid,'frient_uid'=>$frient_uid, ':uniacid' => $uniacid));
                if($frient_info){
                    show_json(0,'他/她已经是您的好友，请勿重复添加');
                }
                pdo_insert('ewei_shop_yoxwechatbusiness_friend', $data);
            }
            $id && pdo_update("ewei_shop_yoxwechatbusiness_friend", $data, array("id" => $id));
            show_json(1,'成功');
        }
        $left_join_member1=" LEFT JOIN " . tablename('mc_members') . " members1 on members1.uid=friend.uid ";
        $left_join_member2=" LEFT JOIN " . tablename('mc_members') . " members2 on members2.uid=friend.frient_uid ";
        $member_field1=" members1.nickname,members1.avatar ";
        $member_field2=" members2.nickname as frient_nickname,members2.avatar as frient_avatar ";
        $info = pdo_fetch("SELECT friend.*,FROM_UNIXTIME(add_time) AS add_time_format,{$member_field1},{$member_field2} FROM " . tablename('ewei_shop_yoxwechatbusiness_friend') . " friend ".
            $left_join_member1.$left_join_member2.
            "WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
            
            $result['status']=1;
            $result['data']=$info;
	    return $result;
    }
    /**
     * 删除好友
     */
    public function page_frient_delete(){
        global $_GPC,$_W;
        $id = intval($_GPC["id"]);
        $uid=$_W['member']['uid'];
        pdo_delete('ewei_shop_yoxwechatbusiness_friend', array('id' => $id,'uid'=>$uid));
        if($this->is_from_wechat)return array('status'=>1,'message'=>'成功');
        empty($this->is_from_wechat) && show_json(1, "成功");
    }
    public function page_weather(){
        global $_GPC, $_W;
        $location=$_GPC['location'];
        $data=$this->heweather_requestByKey($location);
        $result['status']=1;
        $result['message']='ok';
        $result['data']=$data;
	//print_r($result);
        return $result;
    }
    private function heweather_requestByKey($location){
        //准备请求参数
        $key ="4a817b4338e04cc59bdb92da7771411e";
        empty($location) && ($location = "北京");
        $curlPost = "key=".$key."&location=".urlencode($location);
        //初始化请求链接
        $req=curl_init();
        //设置请求链接
        curl_setopt($req, CURLOPT_URL,'https://free-api.heweather.net/s6/weather/now?'.$curlPost);
        //设置超时时长(秒)
        curl_setopt($req, CURLOPT_TIMEOUT,5);
        //设置链接时长
        curl_setopt($req, CURLOPT_CONNECTTIMEOUT,10);
        //设置头信息
        $headers=array( "Accept: application/json", "Content-Type: application/json;charset=utf-8" );
        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        $weather_json_data = curl_exec($req);
        //天气
        curl_setopt($req, CURLOPT_URL,'https://free-api.heweather.net/s6/air/now?'.$curlPost);
        $air_json_data = curl_exec($req);
        
        curl_close($req);
        
        $weather_data=json_decode($weather_json_data,true);//print_r($weather_data);
        $air_data    =json_decode($air_json_data,true);//print_r($air_data);die();
        //$data = array_merge((array)$weather_data,(array)$air_data);print_r($data);
	$weather_data['HeWeather6'][0]['air_now_city']=$air_data['HeWeather6'][0]['air_now_city'];
	$data=$weather_data;
        return $data;
    }
    public function test(){
        
    }
}