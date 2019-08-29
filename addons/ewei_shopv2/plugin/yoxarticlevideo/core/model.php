<?php
if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class yoxarticlevideoModel extends PluginModel 
{
    private $is_from_wechat='';
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        $this->is_from_wechat=$_GPC['comefrom']=='wxapp'?1:0;
    }
    //========================以下是页面================================//
    /**
     * 列表 
     */
    public function page_article_list(){
        global $_GPC, $_W;
        
        $isvideo  =$_GPC['isvideo'];
        $isarticle=$_GPC['isarticle'];
        
        $is_featured=$_GPC['is_featured'];
        $status=$_GPC['status'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:20;
        
        $uid=$_W['uid'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        $where = " article.uniacid = '{$uniacid}' ";
        $merchid && ($where.=" AND article.merchid = '{$merchid}' ");
        $is_featured!='' && ($where.=" AND article.is_featured = '{$is_featured}' ");
        $status!='' && ($where.=" AND article.`status` = '{$status}' ");
        $isvideo!='' && ($where.=" AND article.`videos` <> '' ");
        $isarticle!='' && ($where.=" AND (article.`thumbs` <> '' || article.thumb<>'') ");
        
        $list = pdo_fetchall("SELECT article.*,FROM_UNIXTIME(article.add_time) AS add_time_format,`order`.order_sn,`order`.`status` as order_status FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " article ".
            "LEFT JOIN " . tablename('ewei_shop_yoxarticlevideo_order') . " `order` ON (`order`.yoxarticlevideo_id=article.id AND `order`.uid=$uid) WHERE $where ORDER BY article.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxarticlevideo_article') . " article WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        foreach($list as $tmp){
            $ids_arr[]=$tmp['id'];
        }
        $ids_str=implode(',', $ids_arr);
        
        foreach($list as &$info){
            $info['thumbs']=unserialize($info['thumbs']);
            if(!empty($info['price'])&&$info['order_status']!=1){
                $info['thumbs']='';
                $info['content']='';
                $info['pay_desc']='请支付后再打开';
            }
        }
        unset($info);
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    public function page_article_edit(){
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
        $status = $_GPC['status'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        if($name!=''||$description||$thumbs){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $merchid && $data['merchid']=$merchid;
            $cate_id && $data['cate_id']=$cate_id;
            $name && $data['name']=$name;
            $thumb&& $data['thumb']=$thumb;
            is_array($thumbs) && $data['thumbs'] = serialize($thumbs);
            $videos && $data['videos']=$videos;
            $url && $data['url']=$url;
            $description && $data['description']=$description;
            $content && $data['content']=$content;
            $is_featured!='' && $data['is_featured']=$is_featured;
            $status!='' && $data['status']=$status;
            $data['add_time']=time();
            $data['update_time']=time();
            
            !$id && pdo_insert('ewei_shop_yoxarticlevideo_article', $data);
            $id  && pdo_update("ewei_shop_yoxarticlevideo_article", $data, array("id" => $id));
            
            if($this->is_from_wechat)return array('status'=>1,'message'=>'成功');
            show_json(1, "成功");
            // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
        }
        $left_join=" LEFT JOIN " . tablename('mc_members') . " members ON members.uid=articlevideo.uid ";
        $info = pdo_fetch("SELECT articlevideo.*,members.avatar,members.nickname FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " articlevideo {$left_join} WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        $info['thumbs'] && ($info['thumbs']=unserialize($info['thumbs']));
        
        $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " articlevideo WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function action_article_delete(){
        global $_GPC;
        $id = intval($_GPC["id"]);
        pdo_delete('ewei_shop_yoxarticlevideo_article', array('id' => $id));
    }
    /*
     * 分类
     */
    public function page_cate_list(){
        global $_GPC, $_W;
        
        $isvideo  =$_GPC['isvideo'];
        $isarticle=$_GPC['isarticle'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 999;
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        
        $where = " article.uniacid = '{$uniacid}' ";
        $isvideo!='' && ($where.=" AND article.`videos` <> '' ");
        $isarticle!='' && ($where.=" AND (article.`thumbs` <> '' || article.thumb<>'') ");
        $list = pdo_fetchall("SELECT cate_id FROM " . tablename('ewei_shop_yoxarticlevideo_article') . " article  WHERE $where GROUP BY cate_id LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxarticlevideo_article') . " article WHERE $where GROUP BY cate_id");
        $pager = pagination($total, $pindex, $psize);
        
        $cate_list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxarticlevideo_cate') . " cate  WHERE $where GROUP BY cate_id LIMIT 0,999");
        foreach($list as &$info){
            foreach($cate_list as $cate_info){
                if($cate_info['id']==$info['cate_id']){
                    $info=$cate_info;
                }
            }
        }
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    
    //===========================以上是页面==========================================//
    public function test(){
        
    }
}