<?php
/**
 * 视频图文支付
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
class Pay_EweiShopV2Page extends PluginMobilePage 
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    
	    $id=intval($_GPC['id']);
	    $yoxarticlevideo_id=intval($_GPC['yoxarticlevideo_id']);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    $time=time();
	    //下单
	    if(empty($id)){
	        $articlevideo_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " WHERE id = :id AND uniacid = :uniacid AND merchid=:merchid", array(':id' => $yoxarticlevideo_id, ':uniacid' => $uniacid,':merchid'=>$merchid));
	        
	        if($articlevideo_info['price']<=0){
	            show_json(0,'免费内容无需支付.');
	        }
	        $order_data=array();
	        $order_data['order_sn']=$time;
	        $order_data['yoxarticlevideo_id']=$articlevideo_info['id'];
	        $order_data['pay_price']=$articlevideo_info['price'];
	        $order_data['total_price']=$articlevideo_info['price'];
	        $order_data['add_time']=time();
	        pdo_insert('ewei_shop_yoxarticlevideo_article_order', $order_data);
	    }
	    //重新支付
	    if($id){
	        $order_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    }
	    
	    $fee=$order_info?$order_info['pay_price']:$order_data['pay_price'];
	    // 一些业务代码。
	    //构造支付请求中的参数
	    $params = array(
	        'tid' => $time,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
	        'ordersn' => $time,  //收银台中显示的订单号
	        'title' => '图文视频购买',          //收银台中显示的标题
	        'fee' => $info['price'],      //收银台中显示需要支付的金额,只能大于 0
	        'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
	    );
	    //调用pay方法
	    $this->pay($params);
	}
}
?>