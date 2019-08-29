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
class yoxbrandModel extends PluginModel 
{
    private $is_from_wechat='';
    private $brand_order_by=array('brand_id_asc'=>"brand.id asc",'brand_id_desc'=>'brand.id desc','brand_price_range_asc'=>'brand.price_range asc','brand_price_range_desc'=>'brand.price_range desc','brand_view_count_asc'=>'brand.view_count asc','brand_view_count_desc'=>'brand.view_count desc','brand_collect_count_asc'=>'brand.collect_count asc','brand_collect_count_desc'=>'brand.collect_count desc');
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        $this->is_from_wechat=$_GPC['comefrom']=='wxapp'?1:0;
    }
    public function test(){
        
    }
    //************************以下是页面**************************//
    /**
     * 朋友圈列表 
     */
    public function page_brand_list(){
        global $_GPC, $_W;
        
        $type=$_GPC['type'];
        $name=$_GPC['name'];
        $cate_id=$_GPC['cate_id'];
        $origin_place=$_GPC['origin_place'];
	$tab=$_GPC['tab'];
        $order_by=$_GPC['order_by']?$_GPC['order_by']:$this->brand_order_by['brand_id_asc'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize=$_GPC['psize']?$_GPC['psize']:20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $replace=array();
        $uniacid!='' && ($where="  brand.uniacid = :uniacid ") && $replace=array(':uniacid'=>$uniacid);
        $type!='' && ($where.=" AND brand.type = :type ") && $replace+=array(':type'=>$type);
        $origin_place!='' && ($where.=" AND brand.origin_place like :origin_place ") && $replace+=array(':origin_place'=>"%{$origin_place}%");
        $tab!='' && ($where.=" AND brand.tab like :tab ") && $replace+=array(':tab'=>"%{$tab}%");
	
        $left_join=" LEFT JOIN " . tablename('ewei_shop_yoxbrand_cate') . " brand_cate ON brand_cate.id=brand.cate_id ";
        $cate_field=" brand_cate.name AS cate_name ";
        $list = pdo_fetchall("SELECT  brand.*,FROM_UNIXTIME(brand.add_time) AS add_time_format,CASE brand.type WHEN 0 THEN '未知' WHEN 1 THEN '工厂' WHEN 2 THEN '品牌' WHEN 3 THEN '加盟' END AS type_name,{$cate_field}  FROM " . tablename('ewei_shop_yoxbrand') . " brand ".
        $left_join.
        "  WHERE $where ORDER BY {$order_by} LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace, 'brand.id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxbrand') . " brand WHERE $where",$replace);
        $pager = pagination($total, $pindex, $psize);
        foreach($list as &$info){
            $info['thumbs']=unserialize($info['thumbs']);
            $info['banner']=unserialize($info['banner']);
        }
        //pdo_debug();
        //print_r($list);die();
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        
        return $result;
    }
    /**
     * 条目信息 
     */
    public function page_brand_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $cate_id = $_GPC['cate_id'];
        $name = $_GPC['name'];
        $price = $_GPC['price'];
        $content = $_GPC['content'];
        $description = $_GPC['description'];
        $thumb = $_GPC['thumb'];
        $thumbs=$_GPC['thumbs'];
        $view_count    = $_GPC['view_count'];
        $collect_count    = $_GPC['collect_count'];
        $status = $_GPC['status']!=''?$_GPC['status']:1;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $uid=$_W['member']['uid'];
        $merchid=0;
        $openid =$_W['openid'];
        if($name!=''||$description||$content||$thumb){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $merchid && $data['merchid']=$merchid;
            $type && $data['type']=$type;
            $cate_id && $data['cate_id']=$cate_id;
            $name && $data['name']=$name;
            $price && $data['price']=$price;
            $thumb&& $data['thumb']=$thumb;
            is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
            $description && $data['description']=$description;
            $content && $data['content']=$content;
            $view_count && $data['view_count']=$view_count;
            $collect_count && $data['collect_count']=$collect_count;
            $data['add_time']=time();
            //$data['update_time']=time();
            
            !$id && pdo_insert('ewei_shop_yoxbrand', $data);
            $id  && pdo_update("ewei_shop_yoxbrand", $data, array("id" => $id));
            
            // 	        show_json(1, "成功");
            if($this->is_from_wechat) return array('status'=>1,'message'=>'ok');
            message('OK', $this->createWebUrl('yoxfriendscircle', array()), 'success');
        }
        $left_join=" LEFT JOIN " . tablename('ewei_shop_yoxbrand_cate') . " brand_cate ON brand_cate.id=brand.cate_id ";
        $cate_field=" brand_cate.name AS cate_name ";
        $info = pdo_fetch("SELECT brand.*,{$cate_field} FROM " . tablename('ewei_shop_yoxbrand') . " brand {$left_join} WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        $info['banner'] && $info['banner']=unserialize($info['banner']);
        $info['thumbs'] && $info['thumbs']=unserialize($info['thumbs']);
        
        $is_collect=pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " WHERE uniacid=:uniacid AND type='BRAND' AND (attitude='COLLECT' OR attitude='collect') AND uid=:uid AND target_id=:target_id ",array(':uniacid'=>$uniacid,':uid'=>$uid,':target_id'=>$info['id']) );
        $collect_total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude WHERE attitude.uniacid=:uniacid AND attitude.type=:type AND attitude.target_id=:target_id AND attitude.attitude=:attitude",array(':uniacid'=>$uniacid,':type'=>'BRAND',':target_id'=>$info['id'],':attitude'=>'COLLECT'));
        $is_follow=pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " WHERE uniacid=:uniacid AND type='BRAND' AND (attitude='FOLLOW' OR attitude='follow') AND uid=:uid AND target_id=:target_id ",array(':uniacid'=>$uniacid,':uid'=>$uid,':target_id'=>$info['id']) );
        $follow_total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude WHERE attitude.uniacid=:uniacid AND attitude.type=:type AND attitude.target_id=:target_id AND attitude.attitude=:attitude",array(':uniacid'=>$uniacid,':type'=>'BRAND',':target_id'=>$info['id'],':attitude'=>'FOLLOW'));
        
        //
        $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxbrand_cate') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        $info['attitude']['collect']=$collect_total;
        $info['attitude']['is_collect']=$is_collect;
        $info['attitude']['follow']=$follow_total;
        $info['attitude']['is_follow']=$is_follow;
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function page_brand_cate_list(){
        global $_GPC, $_W;
        
        $name=$_GPC['name'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize=$_GPC['psize']?$_GPC['psize']:20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $where = " yoxbrand_cate.uniacid = '{$uniacid}' ";
        $replace=array();
        //$uniacid!='' && ($where.=" AND yoxbrand_cate.uniacid = :uniacid ") && $replace=array(':uniacid'=>$uniacid);
        
        $list = pdo_fetchall("SELECT  yoxbrand_cate.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxbrand_cate') . " yoxbrand_cate ".
            "  WHERE $where ORDER BY yoxbrand_cate.add_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace, 'brand.id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxbrand_cate') . " yoxbrand_cate WHERE $where",$replace);
        $pager = pagination($total, $pindex, $psize);
        //pdo_debug();
        //print_r($list);die();
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    public function page_brand_cate_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $name = $_GPC['name'];
        $thumb = $_GPC['thumb'];
        $thumbs=$_GPC['thumbs'];
        //$status = $_GPC['status']!=''?$_GPC['status']:1;
        $displayorder=$_GPC['displayorder'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=0;
        
        if(false && $name){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $merchid && $data['merchid']=$merchid;
            $name && $data['name']=$name;
            $thumb&& $data['thumb']=$thumb;
            is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
            $displayorder && $data['displayorder']=$displayorder;
            $data['add_time']=time();
            //$data['update_time']=time();
            if(!$id){
                $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxbrand_cate') . " WHERE name = :name AND uniacid = :uniacid", array(':name' => $name, ':uniacid' => $uniacid));
                $info && show_json(0, "分类已存在，请勿重复添加.");
            }
            !$id && pdo_insert('ewei_shop_yoxbrand_cate', $data);
            $id  && pdo_update("ewei_shop_yoxbrand_cate", $data, array("id" => $id,'uniacid'=>$uniacid));
            
            //show_json(1, "成功");
            if($this->is_from_wechat) return array('status'=>1,'message'=>'ok');
            //message('OK', $this->createWebUrl('yoxbrand', array()), 'success');
            show_json(1, "成功");
        }
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxbrand_cate') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        $info['thumbs']=unserialize($info['thumbs']);
        //无此表
        //$cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxbrand_cate') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
    }
    public function page_origin_place_list(){
        global $_GPC, $_W;
        
        $pindex = max(1, intval($_GPC['page']));
        $psize=$_GPC['psize']?$_GPC['psize']:20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $replace=array();
        $uniacid!='' && ($where="  brand.uniacid = :uniacid ") && $replace=array(':uniacid'=>$uniacid);
        
        $list = pdo_fetchall("SELECT  brand.origin_place  FROM " . tablename('ewei_shop_yoxbrand') . " brand ".
            "  WHERE $where GROUP BY origin_place LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace, 'brand.id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxbrand') . " brand WHERE $where",$replace);
        $pager = pagination($total, $pindex, $psize);

        //pdo_debug();
        //print_r($list);die();
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        
        return $result;
    }
    //************************以上是页面**************************//
}