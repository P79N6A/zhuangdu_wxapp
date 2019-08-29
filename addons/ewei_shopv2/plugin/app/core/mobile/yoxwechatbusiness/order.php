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
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Order_EweiShopV2Page extends AppMobilePage
{
    //列表
    public function main()
    {
        global $_GPC, $_W;
        $this->order_list();
    }

    //客户管理-统计-客户分析、客户管理-订单管理(取list字段)
    public function order_list(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_order_list();

        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
    //客户管理-统计-销售概况
    public function sales_overview(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_sales_overview();

        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
    //客户管理-统计-商品分析
    public function goods_analysis(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_goods_analysis();

        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
    //客户管理-订单-手动添加订单
    public function create_order_manual(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p('yoxwechatbusiness');
        $result = $yoxwechatbusiness->page_create_order_manual();

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
        $result=$yoxwechatbusiness->set_transfer_order($order_id,$from_uid,'');

        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&& die();
    }
    /**
     * 转给我的订单
     */
    public function transfer_to_me_order_list(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_transfer_to_me_order_list();

        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&&die();
    }
    /**
     * 我转出去的订单
     */
    public function transfer_from_me_order_list(){
        global $_GPC, $_W;

        $yoxwechatbusiness=p("yoxwechatbusiness");
        $result=$yoxwechatbusiness->page_transfer_from_me_order_list();

        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&& die();
    }
    /**
     * 设置已发货
     */
    public function set_order_shipped()
    {
        $yoxwechatbusiness=p("yoxwechatbusiness");
        $yoxwechatbusiness->page_set_order_shipped();
        app_json(array('status'=>1,'message'=>"已设置为已发货"));
    }

    public function get_express()
    {
        global $_GPC, $_W;
        $result=pdo_fetchall("select name,express from ".tablename('ewei_shop_express'));
        echo json_encode($result);die();
        $_GPC['isdebug']&& print_r($result)&& die();
    }

    protected function merchData()
    {
        $merch_plugin = p("merch");
        $merch_data = m("common")->getPluginset("merch");
        if( $merch_plugin && $merch_data["is_openmerch"] )
        {
            $is_openmerch = 1;
        }
        else
        {
            $is_openmerch = 0;
        }
        return array( "is_openmerch" => $is_openmerch, "merch_plugin" => $merch_plugin, "merch_data" => $merch_data );
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;
        $uniacid = $_W["uniacid"];
        $openid = $_W["openid"];
        $uid=$_W["openid"]=$_W['member']["uid"];//
        if( empty($openid) )
        {
            app_error(AppError::$ParamsError);
        }
        $pindex = max(1, intval($_GPC["page"]));
        $psize = 10;

        if(p('yoxwechatbusiness')){//
        p('yoxwechatbusiness')->check_permission_return($uid);
        }

        $show_status = $_GPC["status"];
        $r_type = array( "退款", "退货退款", "换货" );
        $condition = " and up_level_uid=:uid and ismr=0 and deleted=0 and uniacid=:uniacid ";
        $params = array( ":uniacid" => $uniacid, ":uid" => $uid );
        $merchdata = $this->merchData();
        extract($merchdata);
        $condition .= " and merchshow=0 ";
        if( $show_status != "" )
        {
            $show_status = intval($show_status);
            switch( $show_status )
            {
                case 0: $condition .= " and status=0 and paytype!=3";
                break;
                case 2: $condition .= " and (status=2 or status=0 and paytype=3)";
                break;
                case 4: $condition .= " and refundstate>0";
                break;
                case 5: $condition .= " and userdeleted=1 ";
                break;
                default: $condition .= " and status=" . intval($show_status);
            }
            if( $show_status != 5 )
            {
                $condition .= " and userdeleted=0 ";
            }
        }
        else
        {
            $condition .= " and userdeleted=0 ";
        }
        $com_verify = com("verify");

        if ($_GPC['begin_time']) {
           $begin_time=strtotime($_GPC['begin_time']);
           $condition .= " and createtime>= ".$begin_time;
        }

        if ($_GPC['end_time']) {
           $end_time=strtotime($_GPC['end_time']);
           $condition .= " and createtime<=".$end_time;
        }

        if ($_GPC['keyword']) {
             $list = pdo_fetchall("select o.id,o.createtime,o.openid,o.ordersn,o.price,o.userdeleted,o.isparent,o.refundstate,o.paytype,o.status,o.addressid,o.refundid,o.isverify,o.dispatchtype,o.verifytype,o.verifyinfo,o.verifycode,o.iscomment,o.iscycelbuy,o.verified from " . tablename("ewei_shop_order") . " o right join (select a.orderid,a.goodsid,b.title from ims_ewei_shop_order_goods a left join ims_ewei_shop_goods b on a.goodsid=b.id  where b.title like '%{$_GPC['keyword']}%') c on c.orderid=o.id where 1 " . $condition . " order by o.createtime desc LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
            $total = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_order") . " o right join (select a.orderid,a.goodsid,b.title from ims_ewei_shop_order_goods a left join ims_ewei_shop_goods b on a.goodsid=b.id  where b.title like '%{$_GPC['keyword']}%') c on c.orderid=o.id where 1 " . $condition, $params);

        }else{
             $list = pdo_fetchall("select id,createtime,openid,ordersn,price,userdeleted,isparent,refundstate,paytype,status,addressid,refundid,isverify,dispatchtype,verifytype,verifyinfo,verifycode,iscomment,iscycelbuy,verified from " . tablename("ewei_shop_order") . " where 1 " . $condition . " order by createtime desc LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
            $total = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_order") . " where 1 " . $condition, $params);
        }

        $total = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_order") . " where 1 " . $condition, $params);



        $refunddays = intval($_W["shopset"]["trade"]["refunddays"]);
        if( $is_openmerch == 1 )
        {
            $merch_user = $merch_plugin->getListUser($list, "merch_user");
        }
        $isonlyverifygoods = false;
        foreach( $list as &$row )
        {
            $row['goods_total']= pdo_fetchcolumn("select sum(total) from " . tablename("ewei_shop_order_goods") . " where orderid={$row['id']}");
            $uid= pdo_fetchcolumn("select uid from " . tablename("ewei_shop_yoxwechatbusiness_user") . " where openid='{$row['openid']}'");
            $row['nickname']= pdo_fetchcolumn("select nickname from " . tablename("mc_members") . " where uid={$uid}");
            $param = array( );
            if( $row["isparent"] == 1 )
            {
                $scondition = " og.parentorderid=:parentorderid";
                $param[":parentorderid"] = $row["id"];
            }
            else
            {
                $scondition = " og.orderid=:orderid";
                $param[":orderid"] = $row["id"];
            }
            $sql = "SELECT og.goodsid,og.total,g.title,g.thumb,g.type, og.price,og.optionname as optiontitle,og.optionid,op.specs,g.merchid,g.status FROM " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id " . " left join " . tablename("ewei_shop_goods_option") . " op on og.optionid = op.id " . " where " . $scondition . " order by og.id asc";
            $goods = pdo_fetchall($sql, $param);
            $goods = set_medias($goods, array( "thumb" ));
            $ismerch = 0;
            $merch_array = array( );
            $g = 0;
            $nog = 0;
            foreach( $goods as &$r )
            {
                $merchid = $r["merchid"];
                $merch_array[$merchid] = $merchid;
                if( !empty($r["specs"]) )
                {
                    $thumb = m("goods")->getSpecThumb($r["specs"]);
                    if( !empty($thumb) )
                    {
                        $r["thumb"] = tomedia($thumb);
                    }
                }
                if( $r["status"] == 2 )
                {
                    $row["gift"][$g] = $r;
                    $g++;
                }
                else
                {
                    $row["nogift"][$nog] = $r;
                    $nog++;
                }
                if( $r["type"] == 5 )
                {
                    $isonlyverifygoods = true;
                }
            }
            unset($r);
            if( !empty($merch_array) && 1 < count($merch_array) )
            {
                $ismerch = 1;
            }
            if( empty($goods) )
            {
                $goods = array( );
            }
            foreach( $goods as &$r )
            {
                $r["thumb"] .= "?t=" . random(50);
            }
            unset($r);
            $goods_list = array( );
            $i = 0;
            if( $ismerch )
            {
                $getListUser = $merch_plugin->getListUser($goods);
                $merch_user = $getListUser["merch_user"];
                foreach( $getListUser["merch"] as $k => $v )
                {
                    if( empty($merch_user[$k]["merchname"]) )
                    {
                        $goods_list[$i]["shopname"] = $_W["shopset"]["shop"]["name"];
                    }
                    else
                    {
                        $goods_list[$i]["shopname"] = $merch_user[$k]["merchname"];
                    }
                    $goods_list[$i]["goods"] = $v;
                    $i++;
                }
            }
            else
            {
                if( $merchid == 0 )
                {
                    $goods_list[$i]["shopname"] = $_W["shopset"]["shop"]["name"];
                }
                else
                {
                    $merch_data = $merch_plugin->getListUserOne($merchid);
                    $goods_list[$i]["shopname"] = $merch_data["merchname"];
                }
                $goods_list[$i]["goods"] = $goods;
            }
            $row["goods"] = $goods_list;
            $statuscss = "text-cancel";
            switch( $row["status"] )
            {
                case "-1": $status = "已取消";
                break;
                case "0": if( $row["paytype"] == 3 )
                {
                    $status = "待发货";
                }
                else
                {
                    $status = "待付款";
                }
                $statuscss = "text-cancel";
                break;
                case "1": if( $row["isverify"] == 1 )
                {
                    $status = "使用中";
                }
                else
                {
                    if( empty($row["addressid"]) )
                    {
                        $status = "待取货";
                    }
                    else
                    {
                        $status = "待发货";
                    }
                }
                $statuscss = "text-warning";
                break;
                case "2": $status = "待收货";
                $statuscss = "text-danger";
                break;
                case "3": if( empty($row["iscomment"]) )
                {
                    if( $show_status == 5 )
                    {
                        $status = "已完成";
                    }
                    else
                    {
                        $status = (empty($_W["shopset"]["trade"]["closecomment"]) ? "待评价" : "已完成");
                    }
                }
                else
                {
                    $status = "交易完成";
                }
                $statuscss = "text-success";
                break;
            }
            $row["statusstr"] = $status;
            $row["statuscss"] = $statuscss;
            if( 0 < $row["refundstate"] && !empty($row["refundid"]) )
            {
                $refund = pdo_fetch("select * from " . tablename("ewei_shop_order_refund") . " where id=:id and uniacid=:uniacid and orderid=:orderid limit 1", array( ":id" => $row["refundid"], ":uniacid" => $uniacid, ":orderid" => $row["id"] ));
                if( !empty($refund) )
                {
                    $row["statusstr"] = "待" . $r_type[$refund["rtype"]];
                }
            }
            $row["canverify"] = false;
            $canverify = false;
            if( $com_verify )
            {
                $showverify = ($row["dispatchtype"] || $row["isverify"]) && !$isonlyverifygoods;
                if( $row["isverify"] )
                {
                    if( !$isonlyverifygoods )
                    {
                        if( $row["verifytype"] == 0 || $row["verifytype"] == 1 )
                        {
                            $vs = iunserializer($row["verifyinfo"]);
                            $verifyinfo = array( array( "verifycode" => $row["verifycode"], "verified" => ($row["verifytype"] == 0 ? $row["verified"] : $row["goods"][0]["total"] <= count($vs)) ) );
                            if( $row["verifytype"] == 0 )
                            {
                                $canverify = empty($row["verified"]) && $showverify;
                            }
                            else
                            {
                                if( $row["verifytype"] == 1 )
                                {
                                    $canverify = count($vs) < $row["goods"][0]["total"] && $showverify;
                                }
                            }
                        }
                        else
                        {
                            $verifyinfo = iunserializer($row["verifyinfo"]);
                            $last = 0;
                            foreach( $verifyinfo as $v )
                            {
                                if( !$v["verified"] )
                                {
                                    $last++;
                                }
                            }
                            $canverify = 0 < $last && $showverify;
                        }
                    }
                }
                else
                {
                    if( !empty($row["dispatchtype"]) )
                    {
                        $canverify = $row["status"] == 1 && $showverify;
                    }
                }
            }
            $row["canverify"] = $canverify;
            if( $is_openmerch == 1 )
            {
                $row["merchname"] = ($merch_user[$row["merchid"]]["merchname"] ? $merch_user[$row["merchid"]]["merchname"] : $_W["shopset"]["shop"]["name"]);
            }
            $row["isonlyverifygoods"] = $isonlyverifygoods;
            if( $row["isonlyverifygoods"] )
            {
                $row["canverify"] = false;
                $verifygood = pdo_fetch("select * from " . tablename("ewei_shop_verifygoods") . " where orderid=:orderid limit 1", array( ":orderid" => $row["id"] ));
                if( !empty($verifygood) )
                {
                    $row["verifygoods_id"] = $verifygood["id"];
                    $verifynum = pdo_fetchcolumn("select sum(verifynum) from " . tablename("ewei_shop_verifygoods_log") . "    where verifygoodsid =:id  ", array( ":id" => $verifygood["id"] ));
                    if( empty($verifygood["limittype"]) )
                    {
                        $limitdate = intval($verifygood["starttime"]) + intval($verifygood["limitdays"]) * 86400;
                    }
                    else
                    {
                        $limitdate = intval($verifygood["limitdate"]);
                    }
                    $row["canverify"] = time() <= $limitdate;
                    if( 0 < $verifygood["limitnum"] )
                    {
                        $row["canverify"] = $verifynum < $verifygood["limitnum"];
                    }
                }
            }
            $row["cancancel"] = !$row["userdeleted"] && !$row["status"];
            $row["canpay"] = $row["paytype"] != 3 && !$row["userdeleted"] && $row["status"] == 0;
            $row["canverify"] = $row["canverify"] && $row["status"] != -1 && $row["status"] != 0;
            $row["candelete"] = $row["status"] == 3 || $row["status"] == -1;
            $row["cancomment"] = $row["status"] == 3 && $row["iscomment"] == 0 && empty($_W["shopset"]["trade"]["closecomment"]);
            $row["cancomment2"] = $row["status"] == 3 && $row["iscomment"] == 1 && empty($_W["shopset"]["trade"]["closecomment"]);
            $row["cancomplete"] = $row["status"] == 2;
            $row["cancancelrefund"] = 0 < $row["refundstate"] && isset($refund) && $refund["status"] != 5;
            $row["candelete2"] = $row["userdeleted"] == 1;
            $row["canrestore"] = $row["userdeleted"] == 1;
            $row["hasexpress"] = 1 < $row["status"] && 0 < $row["addressid"];
        }
        unset($row);
        app_json(array( "list" => $list, "pagesize" => $psize, "total" => $total, "page" => $pindex ));
    }
    public function detail()
    {
        global $_W;
        global $_GPC;
        $uniacid = $_W["uniacid"];
        $orderid = intval($_GPC["id"]);
        if( empty($orderid))
        {
            app_error(AppError::$ParamsError);
        }
        $order = pdo_fetch("select * from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid limit 1", array( ":id" => $orderid, ":uniacid" => $uniacid));
        $seckill_color = "";
        if( 0 < $order["seckilldiscountprice"] )
        {
            $where = " WHERE uniacid=:uniacid AND type = 5";
            $params = array( ":uniacid" => $_W["uniacid"] );
            $page = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_wxapp_page") . $where . " LIMIT 1 ", $params);
            if( !empty($page) )
            {
                $data = base64_decode($page["data"]);
                $diydata = json_decode($data, true);
                $seckill_color = $diydata["page"]["seckill"]["color"];
            }
        }
        $isonlyverifygoods = m("order")->checkisonlyverifygoods($order["id"]);
        if( empty($order) )
        {
            app_error(AppError::$OrderNotFound);
        }
        if( $order["merchshow"] == 1 )
        {
            app_error(AppError::$OrderNotFound);
        }
        if( $order["userdeleted"] == 2 )
        {
            app_error(AppError::$OrderNotFound);
        }
        $merchdata = $this->merchData();
        extract($merchdata);
        $merchid = $order["merchid"];
        $diyform_plugin = p("diyform");
        $diyformfields = "";
        if( $diyform_plugin )
        {
            $diyformfields = ",og.diyformfields,og.diyformdata";
        }
        $param = array( );
        $param[":uniacid"] = $_W["uniacid"];
        if( $order["isparent"] == 1 )
        {
            $scondition = " og.parentorderid=:parentorderid";
            $param[":parentorderid"] = $orderid;
        }
        else
        {
            $scondition = " og.orderid=:orderid";
            $param[":orderid"] = $orderid;
        }
        $gift = array( );
        $nogift = array( );
        $gn = 0;
        $nog = 0;
        $goods = pdo_fetchall("select g.id,g.type, og.goodsid,og.price,g.title,g.thumb,g.status,og.total,g.credit,og.optionid,og.optionname as optiontitle,g.isverify,g.isfullback,g.storeids" . $diyformfields . "  from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where " . $scondition . " and og.uniacid=:uniacid ", $param);
        $goods = set_medias($goods, array( "thumb" ));
        if( !empty($goods) )
        {
            foreach( $goods as &$g )
            {
                if( $g["isfullback"] )
                {
                    $fullbackgoods = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_fullback_goods") . " WHERE goodsid = :goodsid and uniacid = :uniacid  limit 1 ", array( ":goodsid" => $g["goodsid"], ":uniacid" => $uniacid ));
                    if( $g["optionid"] )
                    {
                        $option = pdo_fetch("select `day`,allfullbackprice,fullbackprice,allfullbackratio,fullbackratio,isfullback\r\n                      from " . tablename("ewei_shop_goods_option") . " where id = :id and uniacid = :uniacid ", array( ":id" => $g["optionid"], ":uniacid" => $uniacid ));
                        $fullbackgoods["minallfullbackallprice"] = $option["allfullbackprice"];
                        $fullbackgoods["fullbackprice"] = $option["fullbackprice"];
                        $fullbackgoods["minallfullbackallratio"] = $option["allfullbackratio"];
                        $fullbackgoods["fullbackratio"] = $option["fullbackratio"];
                        $fullbackgoods["day"] = $option["day"];
                    }
                    $g["fullbackgoods"] = $fullbackgoods;
                    unset($fullbackgoods);
                    unset($option);
                }
                if( !empty($g["optionid"]) )
                {
                    $thumb = m("goods")->getOptionThumb($g["goodsid"], $g["optionid"]);
                    if( !empty($thumb) )
                    {
                        $g["thumb"] = $thumb;
                    }
                }
            }
        }
        $diyform_flag = 0;
        if( $diyform_plugin )
        {
            foreach( $goods as &$g )
            {
                $g["diyformfields"] = iunserializer($g["diyformfields"]);
                $g["diyformdata"] = iunserializer($g["diyformdata"]);
                unset($g);
            }
            if( !empty($order["diyformfields"]) && !empty($order["diyformdata"]) )
            {
                $order_fields = iunserializer($order["diyformfields"]);
                $order_data = iunserializer($order["diyformdata"]);
            }
        }
        $address = false;
        if( !empty($order["addressid"]) )
        {
            $address = iunserializer($order["address"]);
            if( !is_array($address) )
            {
                $address = pdo_fetch("select * from  " . tablename("ewei_shop_member_address") . " where id=:id limit 1", array( ":id" => $order["addressid"] ));
            }
        }
        $carrier = @iunserializer($order["carrier"]);
        if( !is_array($carrier) || empty($carrier) )
        {
            $carrier = false;
        }
        $store = false;
        if( !empty($order["storeid"]) )
        {
            if( 0 < $merchid )
            {
                $store = pdo_fetch("select * from  " . tablename("ewei_shop_merch_store") . " where id=:id limit 1", array( ":id" => $order["storeid"] ));
            }
            else
            {
                $store = pdo_fetch("select * from  " . tablename("ewei_shop_store") . " where id=:id limit 1", array( ":id" => $order["storeid"] ));
            }
        }
        $stores = false;
        $showverify = false;
        $canverify = false;
        $verifyinfo = false;
        if( com("verify") )
        {
            $showverify = $order["dispatchtype"] || $order["isverify"];
            if( $order["isverify"] )
            {
                $storeids = array( );
                foreach( $goods as $g )
                {
                    if( !empty($g["storeids"]) )
                    {
                        $storeids = array_merge(explode(",", $g["storeids"]), $storeids);
                    }
                }
                if( empty($storeids) )
                {
                    if( 0 < $merchid )
                    {
                        $stores = pdo_fetchall("select * from " . tablename("ewei_shop_merch_store") . " where  uniacid=:uniacid and merchid=:merchid and status=1 and type in(2,3)", array( ":uniacid" => $_W["uniacid"], ":merchid" => $merchid ));
                    }
                    else
                    {
                        $stores = pdo_fetchall("select * from " . tablename("ewei_shop_store") . " where  uniacid=:uniacid and status=1 and type in(2,3)", array( ":uniacid" => $_W["uniacid"] ));
                    }
                }
                else
                {
                    if( 0 < $merchid )
                    {
                        $stores = pdo_fetchall("select * from " . tablename("ewei_shop_merch_store") . " where id in (" . implode(",", $storeids) . ") and uniacid=:uniacid and merchid=:merchid and status=1 and type in(2,3)", array( ":uniacid" => $_W["uniacid"], ":merchid" => $merchid ));
                    }
                    else
                    {
                        $stores = pdo_fetchall("select * from " . tablename("ewei_shop_store") . " where id in (" . implode(",", $storeids) . ") and uniacid=:uniacid and status=1 and type in(2,3)", array( ":uniacid" => $_W["uniacid"] ));
                    }
                }
                if( !$isonlyverifygoods )
                {
                    if( $order["verifytype"] == 0 || $order["verifytype"] == 1 )
                    {
                        $vs = iunserializer($order["verifyinfo"]);
                        $verifyinfo = array( array( "verifycode" => $order["verifycode"], "verified" => ($order["verifytype"] == 0 ? $order["verified"] : $goods[0]["total"] <= count($vs)) ) );
                        if( $order["verifytype"] == 0 )
                        {
                            $canverify = empty($order["verified"]) && $showverify;
                        }
                        else
                        {
                            if( $order["verifytype"] == 1 )
                            {
                                $canverify = count($vs) < $goods[0]["total"] && $showverify;
                            }
                        }
                    }
                    else
                    {
                        $verifyinfo = iunserializer($order["verifyinfo"]);
                        $last = 0;
                        foreach( $verifyinfo as $v )
                        {
                            if( !$v["verified"] )
                            {
                                $last++;
                            }
                        }
                        $canverify = 0 < $last && $showverify;
                    }
                }
            }
            else
            {
                if( !empty($order["dispatchtype"]) )
                {
                    $verifyinfo = array( array( "verifycode" => $order["verifycode"], "verified" => $order["status"] == 3 ) );
                    $canverify = $order["status"] == 1 && $showverify;
                }
            }
        }
        $order["canverify"] = $canverify;
        $order["showverify"] = $showverify;
        if( $order["status"] == 1 || $order["status"] == 2 )
        {
            $canrefund = true;
            if( $order["status"] == 2 && $order["price"] == $order["dispatchprice"] )
            {
                if( 0 < $order["refundstate"] )
                {
                    $canrefund = true;
                }
                else
                {
                    $canrefund = false;
                }
            }
        }
        else
        {
            if( $order["status"] == 3 && $order["isverify"] != 1 && empty($order["virtual"]) )
            {
                if( 0 < $order["refundstate"] )
                {
                    $canrefund = true;
                }
                else
                {
                    $tradeset = m("common")->getSysset("trade");
                    $refunddays = intval($tradeset["refunddays"]);
                    if( 0 < $refunddays )
                    {
                        $days = intval((time() - $order["finishtime"]) / 3600 / 24);
                        if( $days <= $refunddays )
                        {
                            $canrefund = true;
                        }
                    }
                }
            }
        }
        $order["canrefund"] = $canrefund;
        $express = false;
        if( 2 <= $order["status"] && empty($order["isvirtual"]) && empty($order["isverify"]) )
        {
            $expresslist = m("util")->getExpressList($order["express"], $order["expresssn"]);
            if( 0 < count($expresslist) )
            {
                $express = $expresslist[0];
            }
        }
        $shopname = $_W["shopset"]["shop"]["name"];
        if( !empty($order["merchid"]) && $is_openmerch == 1 )
        {
            $merch_user = $merch_plugin->getListUser($order["merchid"]);
            $shopname = $merch_user["merchname"];
            $shoplogo = tomedia($merch_user["logo"]);
        }
        $order["statusstr"] = "";
        if( empty($order["status"]) )
        {
            if( $order["paytype"] == 3 )
            {
                $order["statusstr"] = "货到付款，等待发货";
            }
            else
            {
                $order["statusstr"] = "等待付款";
            }
        }
        else
        {
            if( $order["status"] == 1 )
            {
                $order["statusstr"] = "买家已付款";
            }
            else
            {
                if( $order["status"] == 2 )
                {
                    $order["statusstr"] = "卖家已发货";
                }
                else
                {
                    if( $order["status"] == 3 )
                    {
                        $order["statusstr"] = "交易完成";
                    }
                    else
                    {
                        if( $order["status"] == -1 )
                        {
                            $order["statusstr"] = "交易关闭";
                        }
                    }
                }
            }
        }
        if( is_array($verifyinfo) && isset($verifyinfo) )
        {
            foreach( $verifyinfo as &$v )
            {
                $status = "";
                if( $v["verified"] )
                {
                    $status = "已使用";
                }
                else
                {
                    if( $order["dispatchtype"] )
                    {
                        $status = "未取货";
                    }
                    else
                    {
                        if( $order["verifytype"] == 1 )
                        {
                            $status = "剩余" . ($goods[0]["total"] - count($vs)) . "次";
                        }
                        else
                        {
                            $status = "未使用";
                        }
                    }
                }
                $v["status"] = $status;
            }
            unset($v);
        }
        $newFields = array( );
        if( is_array($order_fields) && !empty($order_fields) )
        {
            foreach( $order_fields as $k => $v )
            {
                $v["diy_type"] = $k;
                $newFields[] = $v;
                if( $v["data_type"] == 5 && !empty($order_data[$k]) && is_array($order_data[$k]) )
                {
                    $order_data[$k] = set_medias($order_data[$k]);
                }
            }
        }
        if( !empty($verifyinfo) && empty($order["status"]) )
        {
            foreach( $verifyinfo as &$lala )
            {
                $lala["verifycode"] = "";
            }
            unset($lala);
        }
        $icon = "";
        if( empty($order["status"]) )
        {
            if( $order["paytype"] == 3 )
            {
                $icon = "e623";
            }
            else
            {
                $icon = "e711";
            }
        }
        else
        {
            if( $order["status"] == 1 )
            {
                $icon = "e74c";
            }
            else
            {
                if( $order["status"] == 2 )
                {
                    $icon = "e623";
                }
                else
                {
                    if( $order["status"] == 3 )
                    {
                        $icon = "e601";
                    }
                    else
                    {
                        if( $order["status"] == -1 )
                        {
                            $icon = "e60e";
                        }
                    }
                }
            }
        }
        $cycelbuy_periodic = explode(",", $order["cycelbuy_periodic"]);
        $order = array( "id" => $order["id"], "ordersn" => $order["ordersn"], "createtime" => date("Y-m-d H:i:s", $order["createtime"]), "paytime" => (!empty($order["paytime"]) ? date("Y-m-d H:i:s", $order["paytime"]) : ""), "sendtime" => (!empty($order["sendtime"]) ? date("Y-m-d H:i:s", $order["sendtime"]) : ""), "finishtime" => (!empty($order["finishtime"]) ? date("Y-m-d H:i:s", $order["finishtime"]) : ""), "status" => $order["status"], "statusstr" => $order["statusstr"], "price" => $order["price"], "goodsprice" => $order["goodsprice"], "dispatchprice" => $order["dispatchprice"], "ispackage" => $order["ispackage"], "seckilldiscountprice" => $order["seckilldiscountprice"], "deductenough" => $order["deductenough"], "couponprice" => $order["couponprice"], "discountprice" => $order["discountprice"], "isdiscountprice" => $order["isdiscountprice"], "deductprice" => $order["deductprice"], "deductcredit2" => $order["deductcredit2"], "diyformfields" => (empty($newFields) ? array( ) : $newFields), "diyformdata" => (empty($order_data) ? array( ) : $order_data), "showverify" => $order["showverify"], "verifytitle" => ($order["dispatchtype"] ? "自提码" : "消费码"), "dispatchtype" => $order["dispatchtype"], "verifyinfo" => $verifyinfo, "invoicename" => (empty($order["invoicename"]) ? NULL : m("sale")->parseInvoiceInfo($order["invoicename"])), "merchid" => intval($order["merchid"]), "virtual" => $order["virtual"], "virtual_str" => ($order["status"] == 3 ? $order["virtual_str"] : ""), "virtual_info" => ($order["status"] == 3 ? $order["virtual_info"] : ""), "isvirtualsend" => $order["isvirtualsend"], "virtualsend_info" => (empty($order["virtualsend_info"]) ? "" : $order["virtualsend_info"]), "canrefund" => $order["canrefund"], "refundtext" => (($order["status"] == 1 ? "申请退款" : "申请售后")) . ((!empty($order["refundstate"]) ? "中" : "")), "refundtext_btn" => "", "cancancel" => !$order["userdeleted"] && !$order["status"], "canpay" => $order["paytype"] != 3 && !$order["userdeleted"] && $order["status"] == 0, "canverify" => $order["canverify"] && $order["status"] != -1 && $order["status"] != 0, "candelete" => $order["status"] == 3 || $order["status"] == -1, "cancomment" => $order["status"] == 3 && $order["iscomment"] == 0 && empty($_W["shopset"]["trade"]["closecomment"]), "cancomment2" => $order["status"] == 3 && $order["iscomment"] == 1 && empty($_W["shopset"]["trade"]["closecomment"]), "cancomplete" => $order["status"] == 2, "cancancelrefund" => 0 < $order["refundstate"], "candelete2" => $order["userdeleted"] == 1, "canrestore" => $order["userdeleted"] == 1, "verifytype" => $order["verifytype"], "refundstate" => $order["refundstate"], "icon" => $icon, "city_express_state" => $order["city_express_state"], "iscycelbuy" => $order["iscycelbuy"], "isonlyverifygoods" => $isonlyverifygoods, "remark" => $order["remark"], "remarksaler" => $order["remarksaler"] );
        if( $order["iscycelbuy"] == 1 )
        {
            $order["cycelComboPeriods"] = $cycelbuy_periodic[2];
        }
        if( $order["isonlyverifygoods"] )
        {
            $order["canverify"] = false;
            $verifygood = pdo_fetch("select * from " . tablename("ewei_shop_verifygoods") . " where orderid=:orderid limit 1", array( ":orderid" => $order["id"] ));
            if( !empty($verifygood) )
            {
                $order["verifygoods_id"] = $verifygood["id"];
                $verifynum = pdo_fetchcolumn("select sum(verifynum) from " . tablename("ewei_shop_verifygoods_log") . "    where verifygoodsid =:id  ", array( ":id" => $verifygood["id"] ));
                if( empty($verifygood["limittype"]) )
                {
                    $limitdate = intval($verifygood["starttime"]) + intval($verifygood["limitdays"]) * 86400;
                }
                else
                {
                    $limitdate = intval($verifygood["limitdate"]);
                }
                $order["canverify"] = time() <= $limitdate;
                if( 0 < $verifygood["limitnum"] )
                {
                    $order["canverify"] = $verifynum < $verifygood["limitnum"];
                }
            }
        }
        if( $order["canrefund"] )
        {
            if( !empty($order["refundstate"]) )
            {
                $order["refundtext_btn"] = "查看";
            }
            if( $order["status"] == 1 )
            {
                $order["refundtext_btn"] .= "申请退款";
            }
            else
            {
                $order["refundtext_btn"] .= "申请售后";
            }
            if( !empty($order["refundstate"]) )
            {
                $order["refundtext_btn"] .= "进度";
            }
        }
        $allgoods = array( );
        foreach( $goods as &$g )
        {
            $newFields = array( );
            if( is_array($g["diyformfields"]) )
            {
                foreach( $g["diyformfields"] as $k => $v )
                {
                    $v["diy_type"] = $k;
                    $newFields[] = $v;
                }
            }
            $allgoods[] = array( "id" => $g["goodsid"], "title" => $g["title"], "price" => $g["price"], "thumb" => tomedia($g["thumb"]), "total" => $g["total"], "isfullback" => $g["isfullback"], "fullbackgoods" => $g["fullbackgoods"], "status" => $g["status"], "optionname" => $g["optiontitle"], "diyformdata" => (empty($g["diyformdata"]) ? array( ) : $g["diyformdata"]), "diyformfields" => $newFields );
        }
        unset($g);
        if( !empty($allgoods) )
        {
            foreach( $allgoods as $gk => $og )
            {
                if( $og["status"] == 2 )
                {
                    $gift[$gn] = $og;
                    $gn++;
                }
                else
                {
                    $nogift[$nog] = $og;
                    $nog++;
                }
            }
        }
        $shop = array( "name" => $shopname, "logo" => $shoplogo );
        $result = array( "order" => $order, "goods" => $allgoods, "gift" => $gift, "nogift" => $nogift, "address" => $address, "express" => $express, "carrier" => $carrier, "store" => $store, "stores" => $stores, "shop" => $shop, "customer" => intval($_W["shopset"]["app"]["customer"]), "phone" => intval($_W["shopset"]["app"]["phone"]) );
        if( !empty($result["customer"]) )
        {
            $result["customercolor"] = (empty($_W["shopset"]["app"]["customercolor"]) ? "#ff5555" : $_W["shopset"]["app"]["customercolor"]);
        }
        if( !empty($result["phone"]) )
        {
            $result["phonecolor"] = (empty($_W["shopset"]["app"]["phonecolor"]) ? "#ff5555" : $_W["shopset"]["app"]["phonecolor"]);
            $result["phonenumber"] = (empty($_W["shopset"]["app"]["phonenumber"]) ? "#ff5555" : $_W["shopset"]["app"]["phonenumber"]);
        }
        if( !empty($order["virtual"]) && !empty($order["virtual_str"]) )
        {
            if( $order["status"] == 3 )
            {
                $result["ordervirtual"] = m("order")->getOrderVirtual($order);
            }
            else
            {
                $result["ordervirtual"] = "";
            }
            $result["virtualtemp"] = pdo_fetch("SELECT linktext, linkurl FROM " . tablename("ewei_shop_virtual_type") . " WHERE id=:id AND uniacid=:uniacid LIMIT 1", array( ":id" => $order["virtual"], ":uniacid" => $_W["uniacid"] ));
        }
        if( $order["iscycelbuy"] == 1 )
        {
            $cycelSql = "SELECT * FROM " . tablename("ewei_shop_cycelbuy_periods") . " WHERE orderid=:orderid AND uniacid=:uniacid";
            $cycelParams = array( ":orderid" => $order["id"], ":uniacid" => $_W["uniacid"] );
            $cycelData = pdo_fetchall($cycelSql, $cycelParams);
            $cycelUnderway = pdo_fetch("SELECT count(*) as count FROM " . tablename("ewei_shop_cycelbuy_periods") . " WHERE orderid=" . $order["id"] . " AND status<=1 AND uniacid=" . $_W["uniacid"]);
            if( count($cycelData) == $cycelUnderway["count"] )
            {
                $result["notStart"] = 1;
            }
            else
            {
                $result["notStart"] = 0;
            }
            if( !empty($cycelData) )
            {
                $cycelids = array( );
                $notArray = array( );
                $start = false;
                foreach( $cycelData as $ck => $cv )
                {
                    $cycelData[$ck]["receipttime"] = date("Y-m-d", $cv["receipttime"]);
                    if( $cv["status"] == 1 )
                    {
                        $cycelids[] = $cv["id"];
                    }
                }
                $showCycelid = max($cycelids);
                foreach( $cycelData as $ck => $cv )
                {
                    if( $cv["status"] == 0 )
                    {
                        $notArray[] = $ck;
                    }
                    else
                    {
                        if( $cv["status"] == 1 )
                        {
                            $start = true;
                            $period_index = $ck;
                            $receipttime = $cv["receipttime"];
                        }
                        else
                        {
                            if( $cv["status"] == 2 )
                            {
                            }
                        }
                    }
                    if( $cv["id"] == $showCycelid )
                    {
                        $result["selectIndex"] = $ck;
                    }
                }
                if( empty($start) && !empty($notArray) )
                {
                    $period_index = min($notArray);
                }
                $result["selectid"] = $showCycelid;
            }
            $result["period_index"] = $period_index;
            $result["cycelUnderway"] = $cycelUnderway["count"];
            $result["cycelData"] = $cycelData;
        }
        $result["fullbacktext"] = m("sale")->getFullBackText();
        $result["seckill_color"] = $seckill_color;
        $use_membercard = false;
        $card_free_dispatch = false;
        $membercard_info = array( );
        $plugin_membercard = p("membercard");
        if( $plugin_membercard )
        {
            $ifuse = $plugin_membercard->if_order_use_membercard($orderid);
            if( $ifuse )
            {
                $use_membercard = true;
                $membercard_info["card_text"] = $ifuse["name"] . "优惠";
                $membercard_info["card_dec_price"] = $ifuse["dec_price"];
                $membercard_info["discount_rate"] = $ifuse["discount_rate"];
                if( $ifuse["shipping"] )
                {
                    $card_free_dispatch = true;
                }
                $membercard_info["card_free_dispatch"] = $card_free_dispatch;
            }
        }
        $result["use_membercard"] = $use_membercard;
        $result["membercard_info"] = $membercard_info;
        $result["saler"] =pdo_fetchcolumn('select nickname from ims_ewei_shop_member where uid='.$_W['member']['uid']);
        app_json($result);
    }



    /**
     * 更改订单状态
     *
     */
    public function update_order(){
        global $_GPC, $_W;
        foreach ($_GPC['id'] as $key => $value) {
            if ($_GPC['status']) {
                $bool=pdo_update('ewei_shop_order',array('status'=>$_GPC['status']),array('id'=>$value,'uniacid'=>$_W['uniacid']));
            }
            if ($_GPC['deleted']) {
                $bool=pdo_update('ewei_shop_order',array('deleted'=>$_GPC['deleted']),array('id'=>$value,'uniacid'=>$_W['uniacid']));
            }
        }
        $result['status']=0;
        $bool && $result['status']=1;
        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&& die();
    }



    /**
     * 扫码
     *
     */
    public function scan_code(){
        global $_GPC, $_W;
        $len=strlen($_GPC['code']);
        if ($len==14) {
           $num=pdo_fetchcolumn("select count(*) from ims_ewei_shop_goods_securitycode where big_code='".$_GPC['code']."'");
           $num=$num-4;
           $goods_id=pdo_fetchcolumn("select goods_id from ims_ewei_shop_goods_securitycode where big_code='".$_GPC['code']."'");
        }elseif($len==10){
           $num=pdo_fetchcolumn("select count(*) from ims_ewei_shop_goods_securitycode where middle_code='".$_GPC['code']."'");
           $goods_id=pdo_fetchcolumn("select goods_id from ims_ewei_shop_goods_securitycode where middle_code='".$_GPC['code']."'");
           $num=$num-2;
        }elseif($len==12){
           $num=1;
           $goods_id=pdo_fetchcolumn("select goods_id from ims_ewei_shop_goods_securitycode where small_code='".$_GPC['code']."'");

        }
        if (!$goods_id) {
             $result['status']=0;
             $result['message']='此码未绑定商品';
             app_json($result);die();
         }

        $is_this_order=pdo_fetch('select id from ims_ewei_shop_order_goods where orderid='.$_GPC['id']." and goodsid=".$goods_id);
        if (!$is_this_order) {
             $result['status']=0;
             $result['message']='此商品与本订单不符合';
             app_json($result);die();
         }
        $goods_id && $goods=pdo_fetch('select title,marketprice from ims_ewei_shop_goods where id='.$goods_id);

       //返回条码，数量，商品名，金额
        $result['status']=1;
        $result['code']=$_GPC['code'];
        $result['num']=$num;
        $result['goods_name']=$goods['title'];
        $result['goods_id']=$goods_id;
        $result['goods_price']=(float)$goods['marketprice']*$num;
        ($_W['isajax']||$_GPC['isajax'])   && app_json($result);
        $_GPC['isdebug']&& print_r($result)&& die();
    }







}
?>