<?php
/**
 * 微商朋友圈
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
class yoxfriendscircleModel extends PluginModel 
{
    private $is_from_wechat='';
    const BANNER_NAME='banner';
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        $this->is_from_wechat=$_GPC['comefrom']=='wxapp'?1:0;
    }
    public function test(){
        
    }
    /**
     * 配置信息
     */
    public function setting_info($uniacid,$name){
        if(empty($uniacid)){
            global $_W;
            $uniacid=$_W['uniacid'];
        }
        if(empty($name)){
            return ;
        }
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_setting') . " setting WHERE uniacid=:uniacid AND `name`=:name ", array(':name' =>"{$name}",':uniacid'=>$uniacid));
        if(in_array($info['name'],array(self::BANNER_NAME))){
            $info['value']=unserialize($info['value']);
        }
        return $info;
    }
    /**
     * 配置信息值
     */
    public function setting_value($uniacid,$name){
        $info = $this->setting_info($uniacid, $name);
        //print_r($name);
        //var_dump($info);
        return $info['value'];
    }
    //************************以下是页面**************************//
    /**
     * 朋友圈列表 
     */
    public function page_friendscircle_list(){
        global $_GPC, $_W;
        
        $merchid=$_GPC['merchid'];
        //$uid=$_GPC['uid']?$_GPC['uid']:$_W['member']['uid'];
        $is_featured=$_GPC['is_featured'];
        //empty($uid)&&($openid=$_GPC['openid']?$_GPC['openid']:$_W['openid']);
        
        $pindex = max(1, intval($_GPC['page']));
        $psize=$_GPC['psize']?$_GPC['psize']:20;
        
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $where = " article.uniacid = '{$uniacid}' ";
        $replace=array();
        $merchid!='' && ($where.=" AND article.merchid = :merchid ") && $replace=array(':merchid'=>$merchid);
        $uid!='' && ($where.=" AND article.uid = :uid ") && $replace+=array(':uid'=>$uid);
        $openid && ($where.=" AND article.openid = :openid ") && $replace=array(':openid'=>$openid);
        
        if($merchid){
            $info = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_store') . " WHERE merchid=:merchid",array(':merchid'=>$merchid));
            $data['store_info']=$info;
        }
        if($uid){
            $info = pdo_fetchall("SELECT id,uid,level,nickname,avatar,gender FROM " . tablename('ewei_shop_member') . " WHERE uid=:uid",array(':uid'=>$uid));
            $data['user_info']=$info;
        }
        $left_join=" LEFT JOIN ".tablename('ewei_shop_store')." store ON store.id=article.merchid ".
                   " LEFT JOIN ".tablename('mc_members')." member ON member.uid=article.uid ";
        $list = pdo_fetchall("SELECT  article.*,FROM_UNIXTIME(add_time) AS add_time_format,store.storename,store.logo as store_logo,member.nickname,member.avatar  FROM " . tablename('ewei_shop_yoxfriendscircle_article') . " article ".
            " $left_join  WHERE $where ORDER BY article.add_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$replace, 'article.id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_article') . " article {$left_join} WHERE $where",$replace);
        $pager = pagination($total, $pindex, $psize);
        //	    pdo_debug();
        // 	    print_r($list);die();
        foreach($list as $info){
            $ids_arr[]=$info['id'];
        }
        $ids_str=implode(',', $ids_arr);
        
        $data = m("common")->getSysset("shop");
        foreach($list as $article_info){
            $article_ids_arr[]=$article_info['id'];
        }
        $article_ids=implode(',', $article_ids_arr);
        $article_ids && ($attitude_list = pdo_fetchall("SELECT members.nickname,members.avatar,attitude.* FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude ".
            " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=attitude.uid ".
            " WHERE attitude.uniacid={$uniacid} AND attitude.type='ARTICLE' AND attitude.target_id in($article_ids)"));
        $article_ids && ($comment_list  = pdo_fetchall("SELECT members.nickname,members.avatar,`comment`.* FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " `comment` ".
            " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=`comment`.uid ".
            " WHERE `comment`.uniacid={$uniacid} AND `comment`.type='ARTICLE' AND `comment`.target_id in($article_ids)"));
        //pdo_debug();
        foreach($list as &$article_info){
            $article_info['thumbs']=unserialize($article_info['thumbs']);
            if(empty($article_info['avatar'])){
                $article_info['avatar']=$data['logo'];
            }
            if(empty($article_info['nickname'])){
                $article_info['nickname']=$data['name'];
            }
            $is_like=pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " WHERE uniacid=:uniacid AND type='ARTICLE' AND (attitude='LIKE' OR attitude='like') AND uid=:uid AND target_id=:target_id ",array(':uniacid'=>$uniacid,':uid'=>$_W['member']['uid'],':target_id'=>$article_info['id']) );
            //兼容旧数据
            $like_number=pdo_fetchcolumn("SELECT COUNT(*)  FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " WHERE uniacid=:uniacid AND type='ARTICLE' AND (attitude='LIKE' OR attitude='like') AND target_id=:target_id ",array(':uniacid'=>$uniacid,':target_id'=>$article_info['id']) );
            $comment_number=pdo_fetchcolumn("SELECT COUNT(*) AS number FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " WHERE uniacid=:uniacid AND type='ARTICLE'  AND target_id=:target_id ",array(':uniacid'=>$uniacid,':target_id'=>$article_info['id']) );
            //$article_info['attitude']['like']=$like_number;//兼容旧数据
            $article_info['attitude']['is_like']=$is_like;
            $article_info['comment']['number']=$comment_number;
            foreach($attitude_list as $attitude_info){
                if($attitude_info['target_id']==$article_info['id']){
                    !isset($article_info['attitude'][strtolower($attitude_info['attitude'])]['number']) && ($article_info['attitude'][strtolower($attitude_info['attitude'])]['number']=0);
                    $article_info['attitude']['is_'.strtolower($attitude_info['attitude'])]=$attitude_info['uid']==$_W['member']['uid']?1:0;
                    //print_r($attitude_info);
                    //print_r($article_info);die('aa');
                    $article_info['attitude'][strtolower($attitude_info['attitude'])]['number']++;
                    $article_info['attitude'][strtolower($attitude_info['attitude'])]['list'][]=$attitude_info;
                    $article_info['attitude']['list'][]=$attitude_info;//兼容旧数据
                }
            }
            foreach($comment_list as $comment_info){
                if($comment_info['target_id']==$article_info['id']){
                    !isset($article_info['comment']['number']) && ($article_info['comment']['number']=0);
                    $article_info['comment']['is_comment']=$comment_info['uid']==$_W['member']['uid']?1:0;
                    $article_info['comment']['number']++;
                    $article_info['comment']['list'][]=$comment_info;
                }
            }
        }
        unset($article_info);
        $banner_info=$this->setting_value(0,self::BANNER_NAME);
	//pdo_debug();
//         $data = m("common")->getSysset("shop");
        $result['status']=1;
        $result['data']=$data;
        $result['data']['banner_info']=$banner_info;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 条目信息 
     */
    public function page_friendscircle_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $cate_id = $_GPC['cate_id'];
        $name = $_GPC['name'];
        $content = $_GPC['content'];
        $description = $_GPC['description'];
        $thumb = $_GPC['thumb'];
        $thumbs = $_GPC['thumbs'];
        $videos = $_GPC['videos'];
        $url    = $_GPC['url'];
        $is_featured = $_GPC['is_featured'];
        $status = $_GPC['status']!=''?$_GPC['status']:1;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=0;
        $uid= $_W['uid'];
        $openid =$_W['openid'];
        if($name!=''||$is_featured!=''||$description||$thumbs||$content||$videos||$thumb||$url){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $merchid && $data['merchid']=$merchid;
            $uid     && $data['uid']=$uid;
            $openid     && $data['openid']=$openid;
            $cate_id && $data['cate_id']=$cate_id;
            $name && $data['name']=$name;
            $thumb&& $data['thumb']=$thumb;
            is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
            $videos && $data['videos']=$videos;
            $url && $data['url']=$url;
            $description && $data['description']=$description;
            $is_featured!='' && $data['is_featured']=$is_featured;
            $content && $data['content']=$content;
            $data['add_time']=time();
            $data['update_time']=time();
            
            !$id && pdo_insert('ewei_shop_yoxfriendscircle_article', $data);
            $id  && pdo_update("ewei_shop_yoxfriendscircle_article", $data, array("id" => $id));
            
            // 	        show_json(1, "成功");
            if($this->is_from_wechat) return array('status'=>1,'message'=>'ok');
            message('OK', $this->createWebUrl('yoxfriendscircle', array()), 'success');
        }
	$left_join=" LEFT JOIN ".tablename('mc_members')." members ON members.uid=article.uid ";
        $info = pdo_fetch("SELECT *,members.avatar,members.nickname FROM " . tablename('ewei_shop_yoxfriendscircle_article') . " article {$left_join} WHERE article.id = :id AND article.uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        $info['thumbs']=unserialize($info['thumbs']);
        //无此表
        //$cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_article_cate') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function page_follow_list(){
        global $_GPC, $_W;
        
        $uid=$_GPC['uid'];
        $followed_uid=$_GPC['followed_uid'];
        $followed_merchid=$_GPC['followed_merchid'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $where = " follow.uniacid = '{$uniacid}' ";
        
        $uid && ($where.=" AND follow.uid = '{$uid}' ");
        $followed_uid && ($where.=" AND follow.followed_uid = '{$followed_uid}' ");
        $followed_merchid && ($where.=" AND follow.followed_merchid = '{$followed_merchid}' ");
        
        $left_join=" LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.uid=follow.uid LEFT JOIN ".tablename('ewei_shop_store')." followed_store ON followed_store.id=follow.followed_merchid LEFT JOIN ".tablename('ewei_shop_member')." followed_shop_member ON followed_shop_member.uid=follow.followed_uid ";
        $list = pdo_fetchall("SELECT follow.*,FROM_UNIXTIME(add_time) as add_time_format,shop_member.nickname,shop_member.avatar,followed_store.storename as followed_storename,followed_store.logo as followed_logo,followed_shop_member.nickname as followed_nickname,followed_shop_member.avatar as followed_avatar FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " follow $left_join WHERE $where ORDER BY follow.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_follow') . " follow $left_join WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    
    public function page_follow_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $uid = $_GPC['uid'];
        $followed_uid = $_GPC['followed_uid'];
        $followed_merchid = $_GPC['followed_merchid'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        if($uid!=''||$followed_uid||$followed_merchid){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $uid && $data['uid']=$uid;
            $followed_uid && $data['followed_uid']=$followed_uid;
            $followed_merchid && $data['followed_merchid']=$followed_merchid;
            $data['add_time']=time();
            
            !$id && pdo_insert('ewei_shop_yoxfriendscircle_follow', $data);
            $id  && pdo_update("ewei_shop_yoxfriendscircle_follow", $data, array("id" => $id));
            if($this->is_from_wechat) return array('status'=>1,'message'=>'成功');
            show_json(1, "成功");
        }
        
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
// 	    $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function action_follow(){
        global $_GPC, $_W;
        $followed_uid=$_GPC['followed_uid'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $uid=$_W['uid'];
        
        $status=1;
        
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_follow') . " WHERE followed_uid=:followed_uid AND uid = :uid AND uniacid = :uniacid", array(':followed_uid'=>$followed_uid,':uid' => $uid, ':uniacid' => $uniacid));
        
        //已关注，取消关注
        if($info){
            pdo_delete('ewei_shop_yoxfriendscircle_follow', array('followed_uid' =>$followed_uid,'uid'=>$uid,'uniacid'=>$uniacid));
        }
        //未关注，则关注
        if(empty($info)){
            pdo_insert('ewei_shop_yoxfriendscircle_follow',array('followed_uid' =>$followed_uid,'uid'=>$uid,'uniacid'=>$uniacid,'status'=>1,'add_time'=>time()));
        }
    }
    public function page_comment_list(){
        global $_GPC, $_W;
    
        $uid=$_GPC['uid'];
        $type=$_GPC['type']?$_GPC['type']:'ARTICLE';
        $target_id=$_GPC['target_id'];
        $status=$_GPC['status'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $where = " `comment`.uniacid = '{$uniacid}' ";
        $merchid && ($where.=" AND `comment`.merchid = '{$merchid}' ");
        $uid && ($where .=" AND `comment`.uid = '{$uid}' ");
        $type && ($where.=" AND `comment`.type = '{$type}' ");
        $target_id && ($where.=" AND `comment`.target_id = {$target_id} ");
        $where.=" AND reply_id=0 ";
        
        $type == 'ARTICLE' && ($left_join_article=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_article')." article ON (article.id=`comment`.target_id AND `comment`.type='ARTICLE')  ");
        $type == 'ARTICLE' && ($article_field=",article.id as article_id,article.name as article_name,article.description as article_description");
        $type == 'PRODUCT' && ($left_join_product=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_product')." product ON (product.id=`comment`.target_id AND `comment`.type='PRODUCT')  ");
        $type == 'PRODUCT' && ($product_field=",product.id as product_id,product.name as product_name,product.thumb as product_thumb");
        $type == 'MESSAGE' && ($left_join_message=" LEFT JOIN ".tablename('ewei_shop_yoxwechatbusiness_course_message')." `message` ON (`message`.id=`comment`.target_id AND `comment`.type='MESSAGE')  ");
        $type == 'MESSAGE' && ($message_field=",`message`.id as message_id,`message`.`message` as `message`");
        $type == 'COURSE' && ($left_join_course=" LEFT JOIN ".tablename('ewei_shop_yoxwechatbusiness_course')." `course` ON (`course`.id=`comment`.target_id AND `comment`.type='COMMENT')  ");
        $type == 'COURSE' && ($course_field=",`course`.id as course_id,`course`.`name` as `course_name`");
        $left_join_shop_member=" LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.uid=`comment`.uid ";
        $shop_member_field=",shop_member.nickname,shop_member.avatar";
        $list = pdo_fetchall("SELECT distinct `comment`.*,FROM_UNIXTIME(`comment`.add_time) as add_time_format {$shop_member_field} {$article_field} {$product_field} {$message_field} {$course_field} FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " `comment` ".
        "{$left_join_product} {$left_join_article} {$left_join_shop_member} {$left_join_message}  {$left_join_course} WHERE $where ORDER BY `comment`.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT distinct COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_comment') . " `comment` {$left_join_article} {$left_join_product}{$left_join_message} {$left_join_shop_member} WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        //回复
        $target_id && ($reply_list =pdo_fetchall("SELECT `comment`.*,FROM_UNIXTIME(`comment`.add_time) as add_time_format FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " `comment` {$left_join_article} {$left_join_product} {$left_join_shop_member} WHERE `comment`.uniacid = {$uniacid} AND target_id={$target_id} AND `comment`.type = '{$type}' AND reply_id<>0 ORDER BY `comment`.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC'));
        foreach($list as &$info){
            foreach($reply_list as $reply_info){
                if($info['id']==$reply_info['reply_id']){
                    $info['reply_list'][]=$reply_info;
                }
            }
        }
        unset($info);
        foreach($list as &$info){
            //$is_self_comment=pdo_fetchcolumn("SELECT COUNT(*) AS number FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " WHERE uniacid=:uniacid AND type='ARTICLE' AND uid=:uid AND target_id=:target_id ",array(':uniacid'=>$uniacid,':uid'=>$_W['member']['uid'],':target_id'=>$info['id']) );
            $info['is_self_comment']=$info['uid']==$_W['member']['uid']?1:0;
        }
        unset($info);
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    public function page_comment_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type']?$_GPC['type']:'ARTICLE';
        $target_id = $_GPC['target_id'];
        $comment=$_GPC['comment'];
        $reply_id=$_GPC['reply_id'];
        $status = $_GPC['status'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['uid']?$_W['uid']:0;
        if(empty($target_id)){
            if($this->is_from_wechat) return array('status'=>0,'message'=>'target_id empty',);
            show_json(0, "target_id empty");
        }
        if($uid!=''||$type||$target_id!=''||$status!=''){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $merchid && $data['merchid']=$merchid;
            $uid && $data['uid']=$uid;
            $type && $data['type']=$type;
            $target_id && $data['target_id']=$target_id;
            $comment && $data['comment']=$comment;
            $reply_id && $data['reply_id']=$reply_id;
            $status && $data['status']=$status;
            $data['add_time']=time();
            
            !$id && pdo_insert('ewei_shop_yoxfriendscircle_comment', $data);
            $id  && pdo_update("ewei_shop_yoxfriendscircle_comment", $data, array("id" => $id,'uid'=>$uid));
            if($this->is_from_wechat) return array('status'=>1,'message'=>'成功','data'=>$data);
            show_json(1, "成功");
        }
        
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxfriendscircle_comment') . " WHERE id = :id AND uniacid = :uniacid AND merchid=:merchid", array(':id' => $id, ':uniacid' => $uniacid,'merchid'=>$merchid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function action_comment_delete(){
        global $_GPC,$_W;
        $id = intval($_GPC["id"]);
        $uid = $_W['member']['uid'];
        pdo_delete('ewei_shop_yoxfriendscircle_comment', array('id' => $id,'uid'=>$uid));
    }
    public function page_attitude_list(){
        global $_GPC, $_W;
        
        $uid=$_GPC['uid'];
        $type=$_GPC['type']?$_GPC['type']:'ARTICLE';
        $target_id=$_GPC['target_id'];
        $status=$_GPC['status']!=''?$_GPC['status']:1;
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:1;
        $where = " attitude.uniacid = '{$uniacid}' ";
        $merchid && ($where.=" AND attitude.merchid = '{$merchid}' ");
        $uid && ($where .=" AND attitude.uid = '{$uid}' ");
        $type && ($where.=" AND attitude.type = '{$type}' ");
        
        $type == 'ARTICLE' && ($left_join_article=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_article')." article ON (article.id=`attitude`.target_id AND `attitude`.type='ARTICLE')  ");
        $type == 'ARTICLE' && ($article_field=",article.id as article_id,article.name as article_name,article.description as article_description");
        $type == 'PRODUCT' && ($left_join_product=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_product')." product ON (product.id=`attitude`.target_id AND `attitude`.type='PRODUCT')  ");
        $type == 'PRODUCT' && ($product_field=",product.id as product_id,product.name as product_name,product.thumb as product_thumb");
        $type == 'MESSAGE' && ($left_join_message=" LEFT JOIN ".tablename('ewei_shop_yoxwechatbusiness_course_message')." message ON (message.id=`attitude`.target_id AND `attitude`.type='MESSAGE')  ");
        $type == 'MESSAGE' && ($message_field=",message.id as message_id,message.message as message");
        $type == 'COMMENT' && ($left_join_comment=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_comment')." `comment` ON (`comment`.id=`attitude`.target_id AND `attitude`.type='COMMENT')  ");
        $type == 'COMMENT' && ($comment_field=",`comment`.id as comment_id,`comment`.`comment` as `comment`");
        $type == 'COURSE' && ($left_join_course=" LEFT JOIN ".tablename('ewei_shop_yoxwechatbusiness_course')." `course` ON (`course`.id=`attitude`.target_id AND `attitude`.type='COMMENT')  ");
        $type == 'COURSE' && ($course_field=",`course`.id as course_id,`course`.`name` as `course_name`");
        $left_join_shop_member=" LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.uid=`attitude`.uid ";
        $shop_member_field=",shop_member.nickname,shop_member.avatar";
        $list = pdo_fetchall("SELECT attitude.*,FROM_UNIXTIME(attitude.add_time) as add_time_format {$article_field} {$product_field} {$message_field} {$comment_field} {$course_field} FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude ".
            $left_join_article. $left_join_product. $left_join_message. $left_join_comment.$left_join_course.
        " WHERE $where ORDER BY attitude.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude  WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        foreach($list as &$info){
            //$is_self_attitude=pdo_fetchcolumn("SELECT COUNT(*) AS number FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " WHERE uniacid=:uniacid uid=:uid  ",array(':uniacid'=>$uniacid,':uid'=>$_W['member']['uid']));
            $info['is_self_attitude']=$info['uid']==$_W['member']['uid']?1:0;
        }
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        
        //pdo_debug();
        ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        return $result;
    }
    /**
     * 信息 
     */
    public function page_attitude_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type']?strtoupper($_GPC['type']):'ARTICLE';
        $attitude = $_GPC['attitude']?strtoupper($_GPC['attitude']):'LIKE';
        $target_id = $_GPC['target_id'];
        $status = $_GPC['status']!=''?$_GPC['status']:1;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        $uid=$_W['member']['uid']?$_W['member']['uid']:0;
        if(p('yoxwechatbusiness')){//
        $wechatbusiness=p('yoxwechatbusiness');
        $wechatbusiness->check_permission_return($_W['member']['uid']);
        //pdo_debug();
	}
        if(empty($uid)){
            if($this->is_from_wechat)return array('status'=>0,'message'=>'uid empty',);
            empty($this->is_from_wechat) && show_json(0, "uid empty");
        }
        
        $info = pdo_fetch("SELECT attitude.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude ".
            " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=attitude.uid ".
            "  WHERE attitude.uniacid = :uniacid AND attitude.type=:type AND attitude.target_id=:target_id AND (attitude.attitude=:attitude) AND attitude.uid=:uid", array(':uniacid' => $uniacid,':type'=>$type,':target_id'=>$target_id,':attitude'=>$attitude,':uid'=>$uid));
        //pdo_debug();die();
        if(!empty($info)){
            if($this->is_from_wechat)return array('status'=>0,'message'=>'已点赞','data'=>$info);
            empty($this->is_from_wechat) && show_json(0, "已点赞.");
        }
        if($uid!=''||$type||$target_id||$status!=''){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $merchid && $data['merchid']=$merchid;
            $uid && $data['uid']=$uid;
            $type && $data['type']=$type;
            $attitude && $data['attitude']=$attitude;
            $target_id && $data['target_id']=$target_id;
            // 	        $status && $data['status']=$status;
            $data['add_time']=time();
            
            !$id && ($id=pdo_insert('ewei_shop_yoxfriendscircle_attitude', $data));
            $id  && pdo_update("ewei_shop_yoxfriendscircle_attitude", $data, array("id" => $id,'uid'=>$uid));
            
            $info = pdo_fetch("SELECT attitude.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude ".
                " LEFT JOIN " . tablename('mc_members') . " members ON members.uid=attitude.uid ".
                "  WHERE attitude.uniacid = :uniacid AND attitude.type=:type AND attitude.target_id=:target_id AND (attitude.attitude=:attitude) AND attitude.uid=:uid", array(':uniacid' => $uniacid,':type'=>$type,':target_id'=>$target_id,':attitude'=>$attitude,':uid'=>$uid));
            
//             pdo_debug();die();
            if($this->is_from_wechat)return array('status'=>1,'message'=>'成功','data'=>$info);
            show_json(1, "成功.");
        }
        
        $info = pdo_fetch("SELECT attitude.*,members.nickname,members.avatar FROM " . tablename('ewei_shop_yoxfriendscircle_attitude') . " attitude ".
	" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=attitude.uid ".
	" WHERE attitude.id = :id AND attitude.uniacid = :uniacid ", array(':id' => $id, ':uniacid' => $uniacid));
//         pdo_debug();
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function action_attitude_delete(){
        global $_GPC, $_W;;
        $id = intval($_GPC["id"]);
        $type= strtoupper($_GPC["type"]);
        $target_id= $_GPC["target_id"];
        $uid=$_W['member']['uid']?$_W['member']['uid']:0;
        $where=" 1=1 ";
        $id && $array['id']=$id;
        $uid && $array['uid']=$uid;
        $type && $array['type']=$type;
        $target_id && $array['target_id']=$target_id;
        pdo_delete('ewei_shop_yoxfriendscircle_attitude',$array);
    }
    //************************以上是页面**************************//
}