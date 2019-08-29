<?php
/**
 * 微商等级
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
class Invite_user_info_EweiShopV2Page extends AppMobilePage
{
	public function main()
	{
	    global $_GPC, $_W;
        $member = $this->member;
        echo "<pre>";

        print_r($_GPC);
        print_r($member);die();








        if ($image) {
            $result['status']=1;
            $result['data']['intval_qrcode']=$image;
        }else{
             $result['status']=0;
            $result['data']['errMsg']='小程序码生成错误';
        }
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));

	}




}
?>