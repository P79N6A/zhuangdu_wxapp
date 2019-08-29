<?php
/**
 * diy模板
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
class yoxgameModel extends PluginModel 
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
    public function page_template_list(){
        global $_GPC, $_W;
        
        $status=$_GPC['status'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:20;
        
        $uid=$_W['uid'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        
        $where = " page.uniacid = '{$uniacid}' ";
        $status!='' && ($where.=" AND page.`status` = '{$status}' ");
        
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_yoxdiy_page_template') . " page WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxdiy_page_template') . " WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    public function page_template_edit(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];//88海报
        $name = $_GPC['name'];
        $page_value = $_GPC['page_value'];
        $description = $_GPC['description'];
        $preview = $_GPC['preview'];
        $displayorder = $_GPC['displayorder'];
        $status = $_GPC['status'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        if($name!=''||$description||$preview){
            $data=array();
            $uniacid && $data['uniacid']=$uniacid;
            $type && $data['type']=$type;
            $name && $data['name']=$name;
            $preview&& $data['preview']=$preview;
            $displayorder&& $data['displayorder']=$displayorder;
            $description && $data['description']=$description;
            $status!='' && $data['status']=$status;
            $data['add_time']=time();
            $data['update_time']=time();
            
            !$id && pdo_insert('ewei_shop_yoxdiy_page_template', $data);
            $id  && pdo_update("ewei_shop_yoxdiy_page_template", $data, array("id" => $id));
            
            if($this->is_from_wechat)return array('status'=>1,'message'=>'成功');
            show_json(1, "成功");
            // 	        message('成功', $this->createWebUrl('yoxfriendscircle', array()), 'success');
        }
        
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxdiy_page_template') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));
        $info['thumbs'] && ($info['thumbs']=unserialize($info['thumbs']));
        
        $cate_list = pdo_fetchAll("SELECT * FROM " . tablename('ewei_shop_yoxdiy_page_template') . " WHERE uniacid = :uniacid", array( ':uniacid' => $uniacid));
        
        $result['status']=1;
        $result['message']='success';
        $result['data']=$info;
        return $result;
    }
    public function action_template_delete(){
        global $_GPC,$_W;
        $id = intval($_GPC["id"]);
        $uniacid=$_W['uniacid']?$_W['uniacid']:1;
        pdo_delete('ewei_shop_yoxdiy_page_template', array('uniacid'=>$uniacid,'id' => $id));
    }
    //===========================以上是页面==========================================//
    public function test(){
        
    }
}