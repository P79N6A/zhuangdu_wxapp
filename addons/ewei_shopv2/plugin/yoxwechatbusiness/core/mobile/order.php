<?php
/**
 * 订单
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
class Order_EweiShopV2Page extends PluginMobilePage 
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    $this->order_list();
	}

	//订单列表
    public function order_list(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_order_list();
        
        ($_W['isajax']||$_GPC['isajax'])   && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result)&&die();
        
        include($this->template('yoxwechatbusiness/order/index'));
    }
    //客户管理-统计-销售概况
    public function sales_overview(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_sales_overview();
        
        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
    /**
     * 转单
     */
    public function set_transfer_order(){
        global $_GPC, $_W;
        
        $order_id=$_GPC['order_id'];
        $from_uid=$_W['fans']['uid'];
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->set_transfer_order($order_id,$from_uid);
        
        ($_W['isajax']||$_GPC['isajax'])   && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result)&&die();
        $this->message($result['message']);
    }
    /**
     * 转给我的订单
     */
    public function transfer_to_me_order_list(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_transfer_to_me_order_list();
        
        ($_W['isajax']||$_GPC['isajax'])   && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result)&&die();
        
        include($this->template('yoxwechatbusiness/order/transfer_to_me_order_list'));
    }
    /**
     * 我转出去的订单
     */
    public function transfer_from_me_order_list(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_transfer_from_me_order_list();
        
        ($_W['isajax']||$_GPC['isajax'])   && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result)&&die();
        
        include($this->template('yoxwechatbusiness/order/transfer_from_me_order_list'));
    }
    /**
     * 设置已发货
     */
    public function set_order_shipped() 
	{
	    $yoxwechatbusiness=p("yoxwechatbusiness");
	    $yoxwechatbusiness->page_set_order_shipped();
	    
	    $this->message("已设置为已发货");
	}

}
?>