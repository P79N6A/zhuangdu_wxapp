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
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Course_EweiShopV2Page extends AppMobilePage
{
    //列表
	public function main() 
	{
	    $this->course_list();
	}
	public function course_list(){
	    global $_GPC, $_W;
	    $name = $_GPC['name']?$_GPC['name']:'';
	    $cate_id=$_GPC['cate_id']?$_GPC['cate_id']:'';
	    $is_hot=$_GPC['is_hot']?$_GPC['is_hot']:'';
	    $is_featured=$_GPC['is_hot']?$_GPC['is_featured']:'';
	    $pindex = max(1, intval($_GPC['page']));
	    $page_size=20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $where = " course.uniacid=:uniacid " && ($replace=array(':uniacid'=>$uniacid));
	    $name && $where.=" course.`name` LIKE '%:name%' " && ($replace+=array(':name'=>$name));
	    $cate_id && $where.=" course.`cate_id` =:cate_id " && ($replace+=array(':cate_id'=>$cate_id));
	    $is_hot && $where.=" course.`is_hot` =:is_hot " && ($replace+=array(':is_hot'=>$is_hot));
	    $is_featured && $where.=" course.`is_featured` =:is_featured " && ($replace+=array(':is_featured'=>$is_featured));
	    $list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE $where LIMIT " . ($pindex - 1) * $page_size . "," . $page_size,$replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $page_size);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && die();
	    
	    include($this->template("yoxwechatbusiness/course/course_list"));
	}
	public function course_edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $cate_id = $_GPC['cate_id'];
	    $cate_id2 = $_GPC['cate_id2'];
	    $type = $_GPC['type'];
	    $name = $_GPC['name'];
	    $price = $_GPC['price'];
	    $descript=$_GPC['descript'];
	    $thumb=$_GPC['thumb'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if(p('yoxwechatbusiness')){//
        $wechatbusiness=p('yoxwechatbusiness');
        $wechatbusiness->check_permission_return($_W['member']['uid']);
        //pdo_debug();
	}
	    if($name!='' || $price!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $uid && $data['uid']=$uid;
	        $cate_id && $data['cate_id']=$cate_id;
	        $cate_id2 && $data['cate_id2']=$cate_id2;
	        $type && $data['type']=$type;
	        $name && $data['name']=$name;
	        $price && $data['price']=$price;
	        $descript && $data['descript']=$descript;
	        $thumb && $data['thumb'] = $thumb;
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
	    $info['uid'] && $user_info = pdo_fetch("select uid,nickname,avatar from " . tablename("mc_members") . " where uid=:uid and uniacid=:uniacid limit 1", array( ":uid" => $info['uid'], ":uniacid" => $_W["uniacid"] ));
	    
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
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_course") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_course', array('id' => $item['id'],'uniacid'=>$uniacid));
	    }
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
	 * 章节列表
	 */
	public function course_chapter_list(){
	    global $_GPC, $_W;
	    $course_id = $_GPC['course_id']?$_GPC['course_id']:'';
	    //$uid=$_GPC['uid']?$_GPC['uid']:'';
	    $name = $_GPC['name']?$_GPC['name']:'';
	    $pindex = max(1, intval($_GPC['page']));
	    $page_size=20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    ($where = " course_chapter.uniacid=:uniacid ") && ($replace=array(':uniacid'=>$uniacid));
	    $course_id && ($where.=" AND course_chapter.`course_id`=:course_id ") && ($replace+=array(':course_id'=>$course_id)); 
	    //$uid && ($where.=" AND course_chapter.`uid`=:uid ") && ($replace+=array(':uid'=>$uid));
	    $name && ($where.=" AND course_chapter.`name` LIKE :name ") && ($replace+=array(':name'=>"%{$name}%"));
	    $list=pdo_fetchall("SELECT course_chapter.*,course.`name` AS course_name FROM " . tablename('ewei_shop_yoxwechatbusiness_course_chapter') . " course_chapter ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_course') . " course ON course.id=course_chapter.course_id ".
	        "WHERE $where LIMIT " . ($pindex - 1) * $page_size . "," . $page_size,$replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course_chapter') . " course_chapter WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $page_size);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result) && pdo_debug() && die();
	    //pdo_debug();
	    include($this->template("yoxwechatbusiness/course/course_chapter_list"));
	}
	/**
	 * 编辑
	 */
	public function course_chapter_edit(){
	    global $_GPC, $_W;
	    $id=$_GPC['id'];
	    $course_id = $_GPC['course_id']?$_GPC['course_id']:'';
	    $name = $_GPC['name']?$_GPC['name']:'';
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    
	    if($name&&$course_id){
	        $data['uniacid']=$uniacid;
	        $data['course_id']=$course_id;
	        $data['name']=$name;
	        !$id && $data['add_time']=time();
	        !$id && pdo_insert('ewei_shop_yoxwechatbusiness_course_chapter', $data);
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_course_chapter", $data, array("id" => $id));
	        show_json(1, "成功");
	    }
	    $info = pdo_fetch("SELECT course_message.*,course.`name` AS course_name FROM " . tablename('ewei_shop_yoxwechatbusiness_course_chapter') . " course_chapter ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_course') . " course ON course.id=course_chapter.course_id ".
	        " WHERE course_chapter.id = :id AND course_chapter.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    $course_list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course') . " course WHERE course.uniacid=:uniacid LIMIT 0,9999",array(':uniacid'=>$uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    $result['data']['course_list']=$course_list;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result)&& pdo_debug();
	    
	    include($this->template());
	}
	/**
	 * 删除
	 */
	public function course_chapter_delete()
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_course_chapter") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_course_chapter', array('id' => $item['id'],'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	public function course_message_list(){
	    global $_GPC, $_W;
	    $course_id = $_GPC['course_id']?$_GPC['course_id']:'';
	    $chapter_id=$_GPC['chapter_id'];
	    $message = $_GPC['message']?$_GPC['message']:'';
	    $pindex = max(1, intval($_GPC['page']));
	    $page_size=20;
	    
	    if(empty($course_id)){
	    die(json_encode(array('status'=>0,'message'=>'course_id empty')));
	    }
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    ($where = " course_message.uniacid=:uniacid ") && ($replace=array(':uniacid'=>$uniacid));
	    $course_id && ($where.=" AND course_message.`course_id`=:course_id ") && ($replace+=array(':course_id'=>$course_id));
	    $chapter_id && ($where.=" AND course_message.`chapter_id`=:chapter_id ") && ($replace+=array(':chapter_id'=>$chapter_id));
	    $message && ($where.=" AND course_message.`message` LIKE '%:message%' ") && ($replace+=array(':message'=>$message));
	    $list=pdo_fetchall("SELECT course_message.*,FROM_UNIXTIME(show_time) AS show_time_format,CASE course_message.type WHEN 'message' THEN '文本消息' WHEN 'image' THEN '图片消息' WHEN 'voice' THEN '语音消息' WHEN 'video' THEN '视频消息' WHEN 'link' THEN '链接' END AS type_name,course.`name` AS course_name,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxwechatbusiness_course_message') . " course_message ".
	        " LEFT JOIN " . tablename('ewei_shop_yoxwechatbusiness_course') . " course ON course.id=course_message.course_id ".
	        " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=course_message.uid ".
	        "WHERE $where LIMIT " . ($pindex - 1) * $page_size . "," . $page_size,$replace);
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course_message') . " course_message WHERE $where ",$replace);
	    $pager = pagination($total, $pindex, $page_size);
	    
	    foreach($list as $info){
	    $ids[]=$info['id'];
	    }
	    $ids_str=implode(',', $ids);
	    if($ids){
	        $attitude_list=pdo_fetchall("SELECT attitude.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude ".
	            "WHERE type='MESSAGE' AND target_id in($ids_str) LIMIT 0,999999",array());
	        
	        $comment_list=pdo_fetchall("SELECT comment.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " comment ".
	            "WHERE type='MESSAGE' AND target_id in($ids_str)  LIMIT 0,999999",array());
	    }
	    
	    foreach($list as &$info){
	        $info['image'] && ($info['image']=unserialize($info['image']));
	        $info['voice'] && ($info['voice']=unserialize($info['voice']));
	        $info['video'] && ($info['video']=unserialize($info['video']));
	        $info['document'] && ($info['file']=unserialize($info['document']));
	        //态度
	        foreach($attitude_list as $attitude_info){
	            if($info['id']==$attitude_info['target_id']){
	                !isset($info['attitude'][strtolower($attitude_info['attitude'])]['number']) && ($info['attitude'][strtolower($attitude_info['attitude'])]['number']=0);
	                $info['attitude']['is_'.strtolower($attitude_info['attitude'])]=$attitude_info['uid']==$_W['member']['uid']?1:0;
	                $info['attitude'][strtolower($attitude_info['attitude'])]['number']++;
	                //$info['attitude'][strtolower($attitude_info['attitude'])]['list'][]=$attitude_info;
	            }
	        }
	        //评论
	        foreach($comment_list as $comment_info){
	            if($info['id']==$comment_info['target_id']){
	                !isset($info['comment']['number']) && ($info['comment']['number']=0);
	                $info['comment']['is_comment']=$comment_info['uid']==$_W['member']['uid']?1:0;
	                $info['comment']['number']++;
	                //$info['comment']['list'][]=$comment_info;
	            }
	        }
	    }
	    unset($info);
	    
	    $result['status']=1;
	    $result['data']['course_teacher']=array('avatar'=>"/addons/ewei_shopv2/icon-custom.jpg","nickname"=>'妆度');
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    
	    include($this->template("yoxwechatbusiness/course/course_message_list"));
	}
	public function course_message_edit()
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $uid = $_GPC['uid'];
	    $course_id=$_GPC['course_id'];
	    $chapter_id=$_GPC['chapter_id'];
	    $type=$_GPC['type'];
	    $message = $_GPC['message'];
	    $image=$_GPC['image'];
	    $voice=$_GPC['voice'];
	    $video=$_GPC['video'];
	    $link=$_GPC['link'];
	    $document=$_GPC['document'];
	    $status=$_GPC['status'];
	    $show_time=$_GPC['show_time'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $uid=$_W['member']['uid']?$_W['member']['uid']:0;
	    if(empty($course_id)){echo json_encode(array('status'=>0,'message'=>'course_id empty'));die();}
	    if(empty($uid)){echo json_encode(array('status'=>0,'message'=>'uid empty'));die();}
	    if($message){$type="message";}if($image){$type="image";}if($voice){$type="voice";}if($video){$type="video";}if($link){$type="link";}if($document){$type="document";}
	    if($message!='' || $type!=''|| $image!=''|| $voice!=''|| $video!=''|| $link!=''|| $status!=''){
	        p('yoxwechatbusiness')->check_permission_return($uid);
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $uid && $data['uid']=$uid;
	        $course_id && $data['course_id']=$course_id;
	        $chapter_id&& $data['chapter_id']=$chapter_id;
	        $message && $data['message']=$message;
	        $type && $data['type']=$type;
	        $image && $data['image']=serialize($image);
	        $voice && $data['voice']=serialize($voice);
	        $video && $data['video']=serialize($video);
	        $link && $data['link']=serialize($link);
	        $document && $data['document']=serialize($document);
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
	    $info['video']=unserialize($info['video']);
	    $info['link']=unserialize($info['link']);
	    $info['document']=unserialize($info['document']);
	    
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
	    $uid=$_W['uid']?$_W['uid']:0;
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_course_message") . " WHERE id in( " . $id . " ) AND uid={$uid} AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_course_message', array('id' => $item['id'],'uniacid'=>$uniacid,'uid'=>$uid));
	    }
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
	    $p_list=pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course WHERE course.uniacid=:uniacid AND pid=0  LIMIT " . ($pindex - 1) * $page_size ."," . $page_size ,array(':uniacid'=>$uniacid));
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course WHERE  course.uniacid=:uniacid ",array(':uniacid'=>$uniacid,));
	    $pager = pagination($total, $pindex, $page_size);
	    
	    foreach($p_list as $p_info){
	        $pid_arr[]=$p_info['id'];
	    }
	    $pid_str=implode(',', $pid_arr);
	    $pid_arr && $c_list=pdo_fetchall("SELECT course_cate.* FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course_cate WHERE course_cate.uniacid=:uniacid AND course_cate.pid in($pid_str)  LIMIT " . ($pindex - 1) * $page_size ."," . $page_size ,array(':uniacid'=>$uniacid));
	    
	    foreach($p_list as $p_info){
	        foreach($c_list as $c_info){
	            if($p_info['id']==$c_info['pid']){
	                $p_info['children'][]=$c_info;
	            }
	        }
	        $list[]=$p_info;
	    }
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
	    $pid = $_GPC['pid'];
	    $name = $_GPC['name'];
	    $thumb = $_GPC['thumb'];
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    if($name!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $pid && $data['pid']=$pid;
	        $name && $data['name']=$name;
	        $thumb && $data['thumb']=$thumb;
// 	        $data['update_time']=time();
	        empty($id) && pdo_insert('ewei_shop_yoxwechatbusiness_course_cate', $data);
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_course_cate", $data, array("id" => $id));
	        
	        show_json(1, "成功");
	        // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
	    }
	    $info = pdo_fetch("SELECT course_cate.* FROM " . tablename('ewei_shop_yoxwechatbusiness_course_cate') . " course_cate ".
	        " WHERE course_cate.id = :id AND course_cate.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
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
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_course_cate") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_course_cate', array('id' => $item['id'],'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
}
