<?php
/**
 * 微商成分社
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
class Ingredients_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    $this->product();
	}
	/**
	 * 化妆品搜寻
	 */
	public function product(){
	    //报告运行时错误
	    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
	    //报告所有错误
	    //error_reporting(E_ALL);
	    //ini_set("display_errors","On");
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_ingredient_product();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 成分
     */
    public function ingredient(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_ingredient();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
    }
    /**
     * 相同成分的产品
     */
    public function same_ingredient_goods_list(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_same_ingredient_goods_list();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        
        include($this->template('yoxwechatbusiness/ingredients/page_same_ingredient_goods_list'));
    }
    /**
     * 成分列表
     */
    public function ingredient_list(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_ingredient_list();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        
        include($this->template('yoxwechatbusiness/ingredients/ingredient_list'));
    }
    /**
     * 成分详情
     */
    public function ingredient_info(){
        global $_GPC, $_W;
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_ingredient_info();
        
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        
        include($this->template('yoxwechatbusiness/ingredients/ingredient_info'));
    }
}
?>