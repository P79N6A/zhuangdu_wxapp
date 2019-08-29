<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Frient_EweiShopV2Page extends PluginMobilePage 
{
	public function main() 
	{
	    $this->frient_list();
	}
	public function frient_list(){
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $yoxwechatbusiness->page_my_frient();
	    include($this->template('yoxwechatbusiness/frient/frient_list'));
	}
	public function frient_edit(){
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $yoxwechatbusiness->page_frient_edit();
	    include($this->template('yoxwechatbusiness/frient/frient_edit'));
	}
	public function frient_delete(){
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $yoxwechatbusiness->page_frient_edit();
	}
	public function update_lat_lng(){
	    global $_GPC, $_W;
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result=$yoxwechatbusiness->page_update_lat_lng();
	}
}
?>