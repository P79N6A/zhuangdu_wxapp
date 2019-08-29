<?php
/**
 * 安装
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
class Install_EweiShopV2Page extends PluginWebPage 
{
	public function main() 
	{
	    global $_GPC, $_W;
	    $is_install=0;
	    if(file_exists(EWEI_SHOPV2_PATH."plugin/yoxarticlevideo/install.lock")){
	        $message='应用已安装，如需重新安装请删除应用目录下的install.lock文件<br/>更多应用请关注作者公众号——零零糖 , 爱你哟~';
		$is_install=1;
	    }
	    
	    $result=array();
	    $result['status']=1;
	    $result['message']=$message;
	    $result['data']['is_install']=$is_install;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
		include($this->template());
	}
	public function install(){
	    include EWEI_SHOPV2_PATH."plugin/yoxarticlevideo/install.php";
	}
}
?>