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
class Index_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    global $_GPC, $_W;
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
    public function brand_list(){
        global $_GPC, $_W;
        
        $type=$_GPC['type'];
        $name=$_GPC['name'];
        $cate_id=$_GPC['cate_id'];
        $origin_place=$_GPC['origin_place'];
        $order_by=$_GPC['order_by']?$_GPC['order_by']:' brand.id desc';
        
        $pindex = max(1, intval($_GPC['page']));
        $psize=$_GPC['psize']?$_GPC['psize']:20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $replace=array();
        $uniacid!='' && ($where="  brand.uniacid = :uniacid ") && $replace=array(':uniacid'=>$uniacid);
        $type!='' && ($where.=" AND brand.type = :type ") && $replace+=array(':type'=>$type);
        $origin_place!='' && ($where.=" AND brand.origin_place like :origin_place ") && $replace+=array(':origin_place'=>"%{$origin_place}%");
        
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
	
        $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxbrand_cate') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));

        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        
        include($this->template('yoxbrand/brand_list'));
    }
    public function brand_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $type = $_GPC['type'];
	    $cate_id = $_GPC['cate_id'];
	    $name = $_GPC['name'];
	    $price_range = $_GPC['price_range'];
	    $origin_place = $_GPC['origin_place'];
	    $content = $_GPC['content'];
	    $description = $_GPC['description'];
	    $banner=$_GPC['banner'];
	    $thumb = $_GPC['thumb'];
	    $thumbs=$_GPC['thumbs'];
	    $tab = $_GPC['tab'];
	    $view_count    = $_GPC['view_count'];
	    $collect_count    = $_GPC['collect_count'];
	    //$status = $_GPC['status']!=''?$_GPC['status']:1;
	    $displayorder=$_GPC['displayorder'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=0;
	    $openid =$_W['openid'];
	    if($name!=''||$description||$content||$thumb){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $type && $data['type']=$type;
	        $cate_id && $data['cate_id']=$cate_id;
	        $name && $data['name']=$name;
	        $price_range && $data['price_range']=$price_range;
	        $origin_place && $data['origin_place']=$origin_place;
	        is_array($banner) && $data['banner'] = serialize($banner);
	        $thumb&& $data['thumb']=$thumb;
	        is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
	        $tab && $data['tab']=$tab;
	        $description && $data['description']=$description;
	        $content && $data['content']=$content;
	        $view_count && $data['view_count']=$view_count;
	        $collect_count && $data['collect_count']=$collect_count;
	        $displayorder && $data['displayorder']=$displayorder;
	        $data['add_time']=time();
	        //$data['update_time']=time();
	        if(!$id){
	            $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxbrand') . " WHERE name = :name AND uniacid = :uniacid", array(':name' => $name, ':uniacid' => $uniacid));
	            $info && show_json(0, "品牌已存在，请勿重复添加.");
	        }
	        !$id && pdo_insert('ewei_shop_yoxbrand', $data);
	        $id  && pdo_update("ewei_shop_yoxbrand", $data, array("id" => $id,'uniacid'=>$uniacid));
	        
	        //show_json(1, "成功");
	        if($this->is_from_wechat) return array('status'=>1,'message'=>'ok');
	        //message('OK', $this->createWebUrl('yoxbrand', array()), 'success');
	        show_json(1, "成功");
	    }
	    
	    $left_join=" LEFT JOIN " . tablename('ewei_shop_yoxbrand_cate') . " brand_cate ON brand_cate.id=brand.cate_id ";
	    $cate_field=" brand_cate.name AS cate_name ";
	    $info = pdo_fetch("SELECT brand.*,{$cate_field} FROM " . tablename('ewei_shop_yoxbrand') . " brand {$left_join} WHERE brand.id = :id AND brand.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['thumbs']=unserialize($info['thumbs']);
	    $info['banner']=unserialize($info['banner']);
	    //无此表
	    $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxbrand_cate') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

		include($this->template('yoxbrand/brand_edit'));
	}
	/**
	 * 删除
	 */
	public function brand_delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    pdo_delete('ewei_shop_yoxbrand', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	public function is_hot(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxbrand") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxbrand", array( "is_hot" => intval($_GPC["is_hot"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxbrand.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 精选
	 */
	public function is_featured(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxbrand") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxbrand", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxbrand.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	public function brand_cate_list(){
	    global $_GPC, $_W;
	    
	    $name=$_GPC['name'];
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize=$_GPC['psize']?$_GPC['psize']:20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $where = " brand.uniacid = '{$uniacid}' ";
	    $replace=array();
	    $uniacid!='' && ($where.=" AND brand.uniacid = :uniacid ") && $replace=array(':uniacid'=>$uniacid);
	    
	    $list = pdo_fetchall("SELECT  brand.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxbrand_cate') . " yoxbrand_cate ".
	        "  WHERE $where ORDER BY brand.add_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace, 'brand.id DESC');
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxbrand_cate') . " yoxbrand_cate WHERE $where",$replace);
	    $pager = pagination($total, $pindex, $psize);
	    //	    pdo_debug();
	    // 	    print_r($list);die();
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxbrand/brand_cate_list'));
	}
	public function brand_cate_edit(){
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $name = $_GPC['name'];
	    $thumb = $_GPC['thumb'];
	    $thumbs=$_GPC['thumbs'];
	    //$status = $_GPC['status']!=''?$_GPC['status']:1;
	    $displayorder=$_GPC['displayorder'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    $merchid=0;
	    
	    if($name!=''||$thumb){
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
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxbrand/brand_cate_edit'));
	}
	public function brand_cate_delete(){
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;
	    pdo_delete('ewei_shop_yoxbrand', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	public function setting(){
	    include($this->template());
	}
}
?>