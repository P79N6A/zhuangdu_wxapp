<?php
/**
 * 品牌馆
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
require(EWEI_SHOPV2_PLUGIN . "app/core/page_mobile.php");
class Index_EweiShopV2Page extends AppMobilePage
{
    /**
     * 
     */
	public function main()
	{
	    $this->brand_list();
	}
	public function pp_list(){
	    global $_GPC, $_W;
	    $_GPC['type']=2;
	    $this->brand_list();
	}
	public function gc_list(){
	    global $_GPC, $_W;
	    $_GPC['type']=1;
	    $this->brand_list();
	}
	public function jm_list(){
	    global $_GPC, $_W;
	    $_GPC['type']=3;
	    $this->brand_list();
	}
	/**
	 * 列表
	 */
	public function brand_list(){
	    global $_GPC, $_W;
	    $yoxbrand=p('yoxbrand');
	    $result=$yoxbrand->page_brand_list();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxbrand/index'));
	}
	/**
	 * 信息
	 */
	public function brand_edit()
	{
	    global $_GPC, $_W;
	    $yoxbrand=p('yoxbrand');
	    $result=$yoxbrand->page_brand_edit();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxbrand/brand_edit'));
	}
	public function brand_cate_list(){
	    global $_GPC, $_W;
	    $yoxbrand=p('yoxbrand');
	    $result=$yoxbrand->page_brand_cate_list();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxbrand/brand_cate_list'));
	}
	public function brand_cate_edit(){
	    global $_GPC, $_W;
	    $yoxbrand=p('yoxbrand');
	    $result=$yoxbrand->page_brand_cate_edit();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxbrand/brand_cate_edit'));
	}
	public function origin_place_list(){
	    global $_GPC, $_W;
	    $yoxbrand=p('yoxbrand');
	    $result=$yoxbrand->page_origin_place_list();
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxbrand/origin_place_list'));
	}

}
?>