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
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class User_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main()
	{
	    $this->my_down_level_user();
	}
	public function my_performance(){
	    global $_GPC, $_W;

	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_my_performance();

	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
	public function my_info(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_user_info();

	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
	public function member_edit(){
	    global $_GPC, $_W;

	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_member_edit();

	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();

	    include($this->template('yoxwechatbusiness/user/member_edit'));
	}
	/**
	 * 我的下等级
	 * 用户画像
	 */
    public function my_down_level_user(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_my_down_level_user();

        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result);

    }
    /**
     * 我邀请的
     */
    public function my_invite_user(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_my_invite_user();

        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 邀请我的
     */
    public function invited_me(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_invite_me();

        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 我的上等级
     */
    public function my_up_level_user(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_my_up_level_user();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();

        include($this->template('yoxwechatbusiness/user/my_up_level_user'));
    }
    /**
     * 用户列表
     */
    public function user_list(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_user_list();

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 状态审核
     */
    public function user_status(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_user_status();

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 统计各个等级分别邀请了多少人
     */
    public function invite_statistics(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_invite_statistics();

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 我要升级
     */
    public function i_wand_up(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_i_wand_up();

        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }


    /**
     * 邀请结果
     */
    public function invited_result()
    {
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_invited_result();
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }

    public function search()
    {
        global $_GPC, $_W;

        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['member']['uid'];
        $where = " user.uniacid = '{$uniacid}' ";
        $where = " members.nickname like '%{$_GPC['search_data']}%' ";
        $merchid!=''&& ($where.=" AND `merchid` = '{$merchid}' ");
        $where.=" AND (user.`invite_uid` = '{$uid}' or user.`up_level_invite_uid` = '{$uid}') ";//只有这句不同
        $left_join=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=user.uid ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON (`level`.`level`=user.`level` AND `level`.uniacid=$uniacid) ";
        $list = pdo_fetchall("SELECT user.*,members.nickname,members.avatar,level.name FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " user ".
            $left_join.
            " WHERE $where ORDER BY id DESC");
        $result['status']=1;
        $result['data']['list']=$list;
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
   public function getInviteUid()
    {
        global $_GPC, $_W;
        $uid=$_W['member']['uid'];
        $result['status']=1;
        $result['data']['inviteuid']=$uid;
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }
   public function getAlive()
    {
        global $_GPC, $_W;
        $bool=pdo_update('ewei_shop_yoxwechatbusiness_user',array('status'=>1),array('uid'=>$_GPC['uid']));
        if ($bool) {
            $result['status']=1;
        }else{
            $result['status']=0;
        }
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }

   public function getcheck()
    {
        global $_GPC, $_W;
        $bool=pdo_update('ewei_shop_yoxwechatbusiness_user',array('check'=>1),array('uid'=>$_GPC['uid']));
        if ($bool) {
            $result['status']=1;
        }else{
            $result['status']=0;
        }
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }

    public function getLevelName()
    {
        global $_GPC, $_W;
        $result['name']=pdo_fetchcolumn('select name from '.tablename('ewei_shop_yoxwechatbusiness_level').' where level='.$_GPC['level']);
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }



    public function upLevelResult()
    {
        global $_GPC, $_W;
        $user_info=pdo_get('ewei_shop_yoxwechatbusiness_user',array('uid'=>$_W['member']['uid'],'uniacid'=>$_W['uniacid']));

        $bool=pdo_update('ewei_shop_yoxwechatbusiness_user',array('upgrade_submit'=>1),array('uid'=>$_W['member']['uid'],'uniacid'=>$_W['uniacid']));
        $bool && $result['status']=1;
        !$bool && $result['status']=0;
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }


    public function getContract()
    {
        global $_GPC, $_W;
        $value=pdo_fetchcolumn("select value from ".tablename("ewei_shop_yoxwechatbusiness_setting")." where name='contract'");
        $value=unserialize($value);
        if ($_GPC['level']>0) {
            $result['contract']=$value['agent'];
        }else{
            $result['contract']=$value['user'];
        }
        ($_W['isajax']||$_GPC['isajax']) && app_json($result);
        $_GPC['isdebug']&& print_r($result) && die();
    }








}
?>