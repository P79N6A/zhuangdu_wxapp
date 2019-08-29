<?php
/**
 * 电子合同
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
class Electronic_contract_EweiShopV2Page extends PluginMobilePage
{
    //列表
	public function main() 
	{
	    $this->edit();
	}
	public function electronic_contract_list(){
	    global $_GPC, $_W;
	    
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_electronic_contract_edit();
	    
	    // 	    pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxwechatbusiness/electronic_contract/electronic_contract_list'));
	}
	public function edit() 
	{
	    global $_GPC, $_W;
	    
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $result = $yoxwechatbusiness->page_electronic_contract_edit();
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template('yoxwechatbusiness/electronic_contract/post'));
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    pdo_delete('ewei_shop_yoxwechatbusiness_electronic_contract', array('id' => $id));
	    show_json(1,'删除成功');
	}

}
?>