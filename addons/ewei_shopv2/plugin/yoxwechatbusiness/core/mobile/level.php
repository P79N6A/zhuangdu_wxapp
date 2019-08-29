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
class Level_EweiShopV2Page extends PluginMobilePage 
{
    //列表
	public function main() 
	{
	    $this->level_list();
	}
	public function level_list(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $list = $yoxwechatbusiness->level_list($_W['uniacid']);
	    
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