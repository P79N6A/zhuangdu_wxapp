<?php
if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class yoxchatliveModel extends PluginModel 
{
    private $is_from_wechat='';
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        $this->is_from_wechat=$_GPC['comefrom']=='wxapp'?1:0;
    }
}