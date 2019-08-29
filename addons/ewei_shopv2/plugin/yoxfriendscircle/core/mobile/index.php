<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Index_EweiShopV2Page extends PluginMobilePage 
{
    /**
     * 精选页面、数据接口
     */
	public function main()
	{
	    $this->friendscircle_list();
	}
	/**
	 * 列表
	 */
	public function friendscircle_list(){
	    global $_GPC, $_W;
	    $yoxfriendscircle=p('yoxfriendscircle');
	    $result=$yoxfriendscircle->page_friendscircle_list();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxfriendscircle/index'));
	}
	/**
	 * 信息
	 */
	public function edit()
	{
	    global $_GPC, $_W;
	    $yoxfriendscircle=p('yoxfriendscircle');
	    $result=$yoxfriendscircle->page_friendscircle_edit();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxfriendscircle/edit'));
	}
	/**
	 * 发现页面
	 */
	public function find(){
	    include($this->template('yoxfriendscircle/find'));
	}
	/**
	 * 资讯
	 */
	public function information(){
	    include($this->template('yoxfriendscircle/information'));
	}
}
?>