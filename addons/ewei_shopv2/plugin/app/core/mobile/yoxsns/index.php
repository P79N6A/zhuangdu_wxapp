<?php
/**
 * 全民社区
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
require(__DIR__ . "/base.php");
class Index_EweiShopV2Page extends Base_EweiShopV2Page
{
    public function main()
    {
        global $_W;
        global $_GPC;
        
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $sns_model=p("sns");
        $shop = m('common')->getSysset('shop');
        $advs = pdo_fetchall('select id,advname,link,thumb from ' . tablename('ewei_shop_sns_adv') . ' where uniacid=:uniacid and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
        
        $credit = m('member')->getCredit($openid, 'credit1');
        $category = pdo_fetchall('select id,`name`,thumb,isrecommand from ' . tablename('ewei_shop_sns_category') . ' where uniacid=:uniacid and isrecommand = 1 and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
        $recommands = pdo_fetchall('select sb.id,sb.title,sb.logo,sb.`desc`  from ' . tablename('ewei_shop_sns_board') . " as sb\r\n\t\t\t\t\t\tleft join " . tablename('ewei_shop_sns_category') . " as sc on sc.id = sb.cid\r\n\t\t\t\t\t\twhere sb.uniacid=:uniacid and sb.isrecommand=1 and sb.status=1 and sc.enabled = 1 order by sb.displayorder desc", array(':uniacid' => $uniacid));
        
        foreach ($recommands as &$row) {
            $row['postcount'] = $sns_model->getPostCount($row['id']);
            $row['followcount'] = $sns_model->getFollowCount($row['id']);
        }
        
        unset($row);
        $_W['shopshare'] = array('title' => $this->set['share_title'], 'imgUrl' => tomedia($this->set['share_icon']), 'link' => mobileUrl('sns', array(), true), 'desc' => $this->set['share_desc']);
        
        $data['adv']=$advs;
        $data['shop']=$shop;
        $data['credit']=$credit;
        $data['category']=$category;
        $data['recommands']=$recommands;
        $data['shopshare']=$_W['shopshare'];
        ($_W['isajax'] || $_GPC['isajax']) && show_json(1, array('data' => $board));
        $_GPC['isdebug']&& print_r($board) && die();
        
        include $this->template();
    }
    
}
