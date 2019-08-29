<?php
/**
 * 商品
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
class Goods_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
	    $this->order_list();
	}

	//我的商品列表
    public function my_goods_list(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_my_goods_list();
        
        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
    //商品列表
    public function goods_list(){
        global $_GPC, $_W;
        
        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_goods_list();
        
        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
}
?>