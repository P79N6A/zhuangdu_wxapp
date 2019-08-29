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
class Upgrade_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{

	    global $_GPC, $_W;

	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;

	    $where=" upgrade_submit=1 ";
	    $left_join=" LEFT JOIN " . tablename('mc_members') . " member ON member.uid=yoxwechatbusiness_user.uid ".
	   	    " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=yoxwechatbusiness_user.invite_uid ".
	   	    " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON `level`.`level`= yoxwechatbusiness_user.`level` ";
	    $field='member.nickname,member.avatar,member.credit1,member.credit2,member2.nickname AS invite_nickname,member2.realname AS invite_realname,member2.avatar AS invite_avatar,`level`.`name` AS level_name';
	    $list = pdo_fetchAll("SELECT yoxwechatbusiness_user.*,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
	        $left_join.
	        " WHERE $where ");

	    foreach ($list as $key => &$value) {
	    	$value['level_name'] = pdo_fetchcolumn("SELECT `level`.`name` FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " user ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ".
            "on user.level=level.level   WHERE user.uid = :uid AND user.uniacid = :uniacid ", array(':uid' => $value['uid'], ':uniacid' => $_W['uniacid']));
        	$next_level_info = pdo_fetch("SELECT `level`.`name`,`level`.growth_value_money,`level`.growth_value_invite_num FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ".
            "WHERE `level`.`level` > {$value['level']} AND level.uniacid ={$_W['uniacid']}");
            $value['next_level_name'] = $next_level_info['name'];
            $value['next_growth_value_money'] = $next_level_info['growth_value_money'];
            $value['next_growth_value_invite_num'] = $next_level_info['growth_value_invite_num'];

	    }
	    unset($value);

	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user $left_join WHERE $where ",array());
	    $pager = pagination($total, $pindex, $psize);

	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['level_list']=$level_list;
	    $result['data']['list']=$list;

	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template());
	}


	public function upgrade_check(){
	    global $_GPC, $_W;
	    if ($_W['isajax']) {
	    	$user_info=pdo_get('ewei_shop_yoxwechatbusiness_user',array('uid'=>$_GPC['uid'],'uniacid'=>$_W['uniacid']));
	        $next_level = pdo_fetchcolumn("SELECT `level`.`level` FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` "."WHERE `level`.`level` > :level AND level.uniacid = :uniacid ", array(':level' => $user_info['level'], ':uniacid' => $_W['uniacid']));
	        pdo_update('ewei_shop_yoxwechatbusiness_user',array('level'=>$next_level,'upgrade_submit'=>0),array('uid'=>$_GPC['uid'],'uniacid'=>$_W['uniacid']));

	    	show_json(1,'审核成功');
	    }
	     $left_join=" LEFT JOIN " . tablename('mc_members') . " member ON member.uid=yoxwechatbusiness_user.uid ".
	   	    " LEFT JOIN " . tablename('mc_members') . " member2 ON member2.uid=yoxwechatbusiness_user.invite_uid ".
	   	    " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ON `level`.`level`= yoxwechatbusiness_user.`level` ";
	    $field='member.nickname,member.avatar,member.credit1,member.credit2,member2.nickname AS invite_nickname,member2.realname AS invite_realname,member2.avatar AS invite_avatar,`level`.`name` AS level_name';
	    $result = pdo_fetch("SELECT yoxwechatbusiness_user.*,$field FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " yoxwechatbusiness_user ".
	        $left_join." where yoxwechatbusiness_user.uid=".$_GPC['uid']);
	    $result['level_name'] = pdo_fetchcolumn("SELECT `level`.`name` FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " user ".
            " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_level') . " `level` ".
            "on user.level=level.level   WHERE user.uid = :uid AND user.uniacid = :uniacid ", array(':uid' => $result['uid'], ':uniacid' => $_W['uniacid']));

	    include($this->template());

	}





}
?>