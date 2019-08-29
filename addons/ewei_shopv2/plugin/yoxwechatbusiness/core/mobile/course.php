<?php
/**
 * 微商课程
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
class Course_EweiShopV2Page extends PluginMobilePage
{
    //列表
	public function main() 
	{
	    $this->course_list();
	}
	public function course_list(){
	    global $_GPC, $_W;
	    $name = $_GPC['name']?$_GPC['name']:'';
	    $cate_id = $_GPC['cate_id']?$_GPC['cate_id']:'';
	    $is_hot=$_GPC['is_hot']?$_GPC['is_hot']:'';
	    $is_featured=$_GPC['is_hot']?$_GPC['is_featured']:'';
	    $pindex = max(1, intval($_GPC['page']));
	    $page_size=20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $where = " course.uniacid=:uniacid " && ($replace=array(':uniacid'=>$uniacid));
	    $name && $where.=" course.`name` LIKE :name " && ($replace+=array(':name'=>"%{$name}%"));
	    $cate_id && $where.=" course.`cate_id` = :cate_id " && ($replace+=array(':cate_id'=>$cate_id));
	    $is_hot && $where.=" course.`is_hot` =:is_hot " && ($replace+=array(':is_hot'=>$is_hot));
	    $is_featured && $where.=" course.`is_featured` =:is_featured " && ($replace+=array(':is_featured'=>$is_featured));
	    $list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE $where LIMIT " . ($pindex - 1) * $page_size . "," . $page_size,$replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $page_size);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    //pdo_debug();
	    include($this->template("yoxwechatbusiness/course/course_list"));
	}
	public function course_edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $name = $_GPC['name'];
	    $price = $_GPC['price'];
	    $descript=$_GPC['descript'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($name!='' || $price!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $uid && $data['uid']=$uid;
	        $name && $data['name']=$name;
	        $price && $data['price']=$price;
	        $descript && $data['descript']=$descript;
	        $data['update_time']=time();
	        if(!$id){
	            pdo_insert('ewei_shop_yoxwechatbusiness_course', $data);
	        }
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_course", $data, array("id" => $id));
	        
	        show_json(1, "成功");
// 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT course.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course ".
	        " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=course.uid ".
	        " WHERE course.id = :id AND course.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&& pdo_debug();;

		include($this->template());
	}
	/**
	 * 删除
	 */
	public function course_delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwechatbusiness_course', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	/**
	 * 排序
	 */
	public function course_displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_course") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_course", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("yoxwechatbusiness.course.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["name"] . " 排序: " . $displayorder . " ");
	    }
	    show_json(1);
	}
	/**
	 * 前端是否显示
	 */
	public function course_status()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,keyword FROM " . tablename("ewei_shop_yoxwechatbusiness_course") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_user_stock", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("yoxwechatbusiness.course.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 精选
	 */
	public function is_course_featured(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_course") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_course", array( "is_featured" => intval($_GPC["is_featured"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxfriendscircle_article.edit", ("修改精选状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	/**
	 * 精选
	 */
	public function is_course_hot(){
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id,name FROM " . tablename("ewei_shop_yoxwechatbusiness_course") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_yoxwechatbusiness_course", array( "is_hot" => intval($_GPC["is_hot"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_yoxfriendscircle_article.edit", ("修改热门状态<br/>ID: " . $item["id"] . "<br/>: " . $item["name"] . "<br/>状态: " . $_GPC["is_featured"] == 1 ? "精选" : "普通"));
	    }
	    //pdo_debug();
	    show_json(1, array( "url" => referer() ));
	}
	public function course_message_list(){
	    global $_GPC, $_W;
	    $message = $_GPC['message']?$_GPC['message']:'';
	    $pindex = max(1, intval($_GPC['page']));
	    $page_size=20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $where = " course_message.uniacid=:uniacid " && ($replace=array(':uniacid'=>$uniacid));
	    $message && $where.=" course_message.`message` LIKE :message " && ($replace+=array(':message'=>"%{$message}%"));
	    $list=pdo_fetchall("SELECT course_message.*,FROM_UNIXTIME(show_time) AS show_time_format,CASE type WHEN 'message' THEN '文本消息' WHEN 'image' THEN '图片消息' WHEN 'voice' THEN '语音消息' WHEN 'video' THEN '视频消息' WHEN 'link' THEN '链接' END AS type_name,course.name AS course_name,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxwechatbusiness_course_message') . " course_message ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_course') . " course ON course.id=course_message.course_id ".
	        " LEFT JOIN " . tablename('mc_members') . " members members.uid=course_message.uid ".
	        "WHERE $where LIMIT " . ($pindex - 1) * $page_size . "," . $page_size,$replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course_message') . " course_message WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $page_size);
	    
	    foreach($list as &$info){
	        $info['image'] && ($info['image']=unserialize($info['image']));
	        $info['voice'] && ($info['voice']=unserialize($info['voice']));
	    }
	    unset($info);
	    
	    $result['status']=1;
	    $result['data']['course_teacher']=array('avatar'=>"/addons/ewei_shopv2/icon-custom.jpg","nickname"=>'妆度');
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    //pdo_debug();
	    include($this->template("yoxwechatbusiness/course/course_message_list"));
	}
	public function course_message_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $type=$_GPC['type'];
	    $message = $_GPC['message'];
	    $image=$_GPC['image'];
	    $voice=$_GPC['voice'];
	    $video=$_GPC['video'];
	    $link=$_GPC['link'];
	    $status=$_GPC['status'];
	    $show_time=$_GPC['show_time'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    
	    if($message){$type="message";}if($image){$type="image";}if($voice){$type="voice";}if($video){$type="video";}if($link){$type="link";}
	    if($message!='' || $type!=''|| $image!=''|| $voice!=''|| $video!=''|| $link!=''|| $status!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $uid && $data['uid']=$uid;
	        $message && $data['message']=$message;
	        $type && $data['type']=$type;
	        $image && $data['image']=serialize($image);
	        $voice && $data['voice']=serialize($voice);
	        $video && $data['video']=$video;
	        $link && $data['link']=$link;
	        $show_time && $data['show_time']=$show_time;
	        $data['update_time']=time();
	        if(!$id){
	            pdo_insert('ewei_shop_yoxwechatbusiness_course_message', $data);
	        }
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_course_message", $data, array("id" => $id));
	        
	        show_json(1, "成功");
	        // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT course_message.*,course.`name` AS course_name FROM " . tablename('ewei_shop_yoxwechatbusiness_course_message') . " course_message ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_course') . " course ON course.id=course_message.course_id ".
	        " WHERE course_message.id = :id AND course_message.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $info['image']=unserialize($info['image']);
	    $info['voice']=unserialize($info['voice']);
	    
	    //课程
	    $course_list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE course.uniacid=:uniacid LIMIT 0,9999",array(':uniacid'=>$uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    $result['data']['course_list']=$course_list;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&& pdo_debug();
	    
	    include($this->template());
	}
	public function course_message_delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwechatbusiness_course_message', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
	/**
	 * 课程分类列表
	 */
	public function course_cate_list(){
	    global $_GPC, $_W;
	    $pindex = max(1, intval($_GPC['page']));
	    $page_size=20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course WHERE course.uniacid=:uniacid  LIMIT " . ($pindex - 1) * $page_size ."," . $page_size ,array(':uniacid'=>$uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course WHERE  course.uniacid=:uniacid ",array(':uniacid'=>$uniacid,));
	    $pager = pagination($total, $pindex, $page_size);
	    //pdo_debug();
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && pdo_debug() && die();
	    
	    include($this->template());
	}
	public function course_cate_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $pid = intval($_GPC['pid']);
	    $name = $_GPC['name'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
		$pid && $data['pid']=$pid;
	        $name && $data['name']=$name;
// 	        $data['update_time']=time();
	        empty($id) && pdo_insert('ewei_shop_yoxwechatbusiness_course_cate', $data);
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_course_cate", $data, array("id" => $id));
	        
	        show_json(1, "成功");
	        // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT course_cate.* FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course_cate ".
	        " WHERE course_cate.id = :id AND course_cate.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $course_list=pdo_fetchall("SELECT course.* FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course WHERE course.uniacid=:uniacid AND course.pid=0  LIMIT 0,100" ,array(':uniacid'=>$uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    $result['data']['course_list']=$course_list;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template());
	}
	/**
	 * 删除
	 */
	public function course_cate_delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    pdo_delete('ewei_shop_yoxwechatbusiness_course_cate', array('id' => $id,'uniacid'=>$uniacid));
	    show_json(1,'删除成功');
	}
}
