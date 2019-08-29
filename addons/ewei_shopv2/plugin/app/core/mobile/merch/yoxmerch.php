<?php
/**
 * 视频图文列表
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
class Yoxmerch_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{

	}
	public function category_list(){
        global $_GPC, $_W;
        
        $merch=p("merch");
        
        $data = array();
        if (!empty($_GPC['keyword'])) {
            $data['likecatename'] = $_GPC['keyword'];
        }
        
        $data = array_merge($data, array(
            'status'  => 1,
            'orderby' => array('displayorder' => 'desc', 'id' => 'asc')
        ));
        $list = $merch->getCategory($data);
        
        $result['status']=1;
        $result['message']='ok';
        $result['data']['list']=$list;
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result) && die();
    }
    public function merch_user_list()
	{
	    //报告运行时错误
	    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
	    //报告所有错误
	    //error_reporting(E_ALL);
	    //ini_set("display_errors","On");
	    global $_GPC, $_W;
	    
	    $merch=p("merch");
	    $list = $merch->getMerch(array(
	        'isrecommand' => 1,
	        'status'      => 1,
	        'field'       => 'id,uniacid,merchname,desc,logo,groupid,cateid',
	        'orderby'     => array('id' => 'asc')
	    ));
	    
	    $result['status']=1;
	    $result['message']='ok';
	    $result['data']['list']=$list;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	}
}