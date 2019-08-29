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
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Level_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main()
	{
	    $this->level_list();
	}
	public function level_list(){
	    global $_GPC, $_W;
        $list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_level') . " WHERE uniacid = {$_W['uniacid']}");
        $invite_setting=pdo_fetchcolumn("SELECT value FROM " . tablename('ewei_shop_yoxwechatbusiness_setting') . " WHERE uniacid = {$_W['uniacid']} and name='INVITE' ");
        $invite_setting=unserialize($invite_setting);
        $invite_setting=$invite_setting['setting']['invite'];
        $user_level=pdo_fetchcolumn("SELECT level FROM " . tablename('ewei_shop_yoxwechatbusiness_user') . " WHERE uniacid = {$_W['uniacid']} and uid={$_W['fans']['uid']} ");
        foreach ($list as $key => $value) {
        	if ($invite_setting=="JUST_DOWN") {
        		if ($user_level<=$value['level']) {
        			unset($list[$key]);
        		}
        	}else{
            	if ($user_level<$value['level']) {
        			unset($list[$key]);
        		}
        	}
        }

	    $result['status']=1;
	    $result['data']['list']=$list;
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template('yoxwechatbusiness/level/level_list'));
	}
	public function level_info(){
	    global $_GPC, $_W;
	    $level_id = $_GPC['id'];
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $data = $yoxwechatbusiness->level_info($level_id);

	    $result['status']=1;
	    $result['data']=$data;
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxwechatbusiness/level/level_info'));
	}
}
?>