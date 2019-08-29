<?php
/**
 * 微商成分
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
class Ingredients_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    
	    $this->product_list();
	}
	public function collect_product(){
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $yoxwechatbusiness->page_collect_product();
	    include($this->template('yoxwechatbusiness/ingredients/collect_product'));
	}
	public function collect_ingredient(){
	    $yoxwechatbusiness=p('yoxwechatbusiness');
	    $yoxwechatbusiness->collect_ingredient();
	}
	public function product_list(){
	    global $_GPC, $_W;
	    $keyword=$_GPC['keyword'];
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    
	    $list = pdo_fetchAll("SELECT ingredient_product.*,FROM_UNIXTIME(ingredient_product.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product ".
	        " WHERE ingredient_product.uniacid = :uniacid LIMIT " . ($pindex - 1) * $psize . ',' . $psize , array( ':uniacid' => $uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product WHERE ingredient_product.uniacid = :uniacid",array( ':uniacid' => $uniacid));
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwechatbusiness/ingredients/product_list'));
	}
	public function product_edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $name = $_GPC['name'];
	    $ingredient_arr=$_GPC['ingredient'];
	    $thumb=$_GPC['thumb'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $name && $data['name']=$name;
	        $thumb && $data['thumb']=$thumb;
	        empty($id) && $data['add_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxfriendscircle_product', $data) && ($id=pdo_insertid());
	        $id  && pdo_update("ewei_shop_yoxfriendscircle_product", $data, array("id" => $id));
	        
	        count($ingredient_arr)>0 && pdo_delete('ewei_shop_yoxfriendscircle_product_ingredient', array('uniacid' =>$uniacid,'product_id'=>$id));
	        foreach($ingredient_arr as $ingredient_id){
	            pdo_insert('ewei_shop_yoxfriendscircle_product_ingredient', array('uniacid'=>$uniacid,'product_id'=>$id,'ingredient_id'=>$ingredient_id,'add_time'=>time()));
	        }
	        show_json(1, "成功");
	    }
	    
	    $info = pdo_fetch("SELECT ingredient_product.*,FROM_UNIXTIME(ingredient_product.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_product') . " ingredient_product ".
	        " WHERE ingredient_product.id = :id AND ingredient_product.uniacid = :uniacid " , array(':id' => $id, ':uniacid' => $uniacid));
	    $product_ingredient_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " WHERE product_id=:product_id " , array( ':product_id' => $info['id']));
	    
	    $ingredient_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " WHERE uniacid=:uniacid " , array( ':uniacid' => $uniacid));
	    foreach($ingredient_list as &$ingredient_info){
	        foreach($product_ingredient_list as $product_ingredient){
	            if($ingredient_info['id']==$product_ingredient['ingredient_id']){
	                $ingredient_info['checked']=" checked ";
	            }
	        }
	    }
	    unset($ingredient_info);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&&die();

		include($this->template('yoxwechatbusiness/ingredients/product_edit'));
	}
	public function product_ingredient_list(){
	    global $_GPC, $_W;
	    $product_id=$_GPC['product_id'];
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    
	    $list = pdo_fetchAll("SELECT ingredient.* FROM " . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient ON ingredient.id=product_ingredient.ingredient_id ".
	        " WHERE product_ingredient.uniacid = :uniacid AND product_ingredient.product_id=:product_id LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array( ':uniacid' => $uniacid,':product_id'=>$product_id));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_product_ingredient') . " product_ingredient WHERE ingredient.uniacid = :uniacid AND product_ingredient.product_id=:product_id",array( ':uniacid' => $uniacid,':product_id'=>$product_id));
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwechatbusiness/ingredients/product_ingredient_list'));
	}
	public function ingredient_list(){
	    global $_GPC, $_W;
	    $keyword=$_GPC['keyword'];
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    
	    $list = pdo_fetchAll("SELECT ingredient.*,FROM_UNIXTIME(ingredient.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient ".
	        " WHERE ingredient.uniacid = :uniacid AND cn_name LIKE '%{$keyword}%' OR en_name LIKE '%{$keyword}%'  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array( ':uniacid' => $uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient WHERE ingredient.uniacid = :uniacid",array( ':uniacid' => $uniacid));
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template('yoxwechatbusiness/ingredients/ingredient_list'));
	}
	public function ingredient_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $en_name = $_GPC['en_name'];
	    $cn_name = $_GPC['cn_name'];
// 	    $product_id = $_GPC['product_id'];
	    $general_characteristics = $_GPC['general_characteristics'];
	    $acne = $_GPC['acne'];
	    $stimulate = $_GPC['stimulate'];
	    $safety = $_GPC['safety'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($cn_name!=''){
	        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') .
	            " WHERE ingredient.cn_name = :cn_name AND ingredient.uniacid = :uniacid " , array(':cn_name' => $cn_name, ':uniacid' => $uniacid));
	        if($info){
	            show_json(0, "该成分已存在，请勿重复添加");
	        }
	        if(empty($en_name))show_json(0, "请输入成分英文名称");
	        if(empty($cn_name))show_json(0, "请输入成分中文名称");
	            
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $en_name && $data['en_name']=$en_name;
	        $cn_name && $data['cn_name']=$cn_name;
// 	        $product_id && $data['product_id']=$product_id;
	        $general_characteristics && $data['general_characteristics']=$general_characteristics;
	        $acne && $data['acne']=$acne;
	        $stimulate && $data['stimulate']=$stimulate;
	        $safety && $data['safety']=$safety;
	        empty($id) && $data['add_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxfriendscircle_ingredient', $data);
	        $id  && pdo_update("ewei_shop_yoxfriendscircle_ingredient", $data, array("id" => $id));
	        
	        show_json(1, "成功");
	        // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT ingredient.*,FROM_UNIXTIME(ingredient.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_ingredient') . " ingredient ".
	        " WHERE ingredient.id = :id AND ingredient.uniacid = :uniacid " , array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&&die();
	    
	    include($this->template('yoxwechatbusiness/ingredients/ingredient_edit'));
	}
	/**
	 * 删除产品
	 */
	public function product_delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxfriendscircle_product") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxfriendscircle_product', array('id' => $id,'uniacid'=>$uniacid));
	        pdo_delete('ewei_shop_yoxfriendscircle_product_ingredient', array('product_id' => $id,'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	public function product_ingredient_delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxfriendscircle_product_ingredient', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	/**
	 * 删除成分
	 */
	public function ingredient_delete(){
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxfriendscircle_ingredient") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxfriendscircle_ingredient', array('id' => $id,'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	/**
	 * 排序
	 */
	public function displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_user") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_yoxwechatbusiness_user.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
	    }
	    show_json(1);
	}
	/**
	 * 前端是否显示
	 */
	public function product_status()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_user") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxwechatbusiness_user.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>文章: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}

}
?>