<?php
/**
 * 电子合同
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
class Electronic_contract_EweiShopV2Page extends PluginWebPage 
{
    //列表
	public function main() 
	{
	    $this->electronic_contract_list();
	}
	public function electronic_contract_list(){
	    global $_GPC, $_W;
	    
	    $type=$_GPC['type']?$_GPC['type']:'WECHAT_BUSINESS';
	    
	    $pindex = max(1, intval($_GPC['page']));
	    $psize = 20;
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    $where = " electronic_contract.uniacid = '{$uniacid}' ";
	    $merchid && ($where.=" AND electronic_contract.merchid = '{$merchid}' ");
	    $type && ($where.=" AND electronic_contract.type = '{$type}' ");
	    
	    //$left_join=" LEFT JOIN ".tablename('ewei_shop_yoxfriendscircle_article')." article ON (article.id=comment.target_id AND comment.type='ARTICLE')  LEFT JOIN ".tablename('ewei_shop_member')." shop_member ON shop_member.uid=comment.uid ";
	    $list = pdo_fetchall("SELECT electronic_contract.*,FROM_UNIXTIME(electronic_contract.add_time) as add_time_format,CASE type WHEN 'WECHAT_BUSINESS' THEN '微商电子合同' END AS type_name FROM " . tablename('ewei_shop_yoxwechatbusiness_electronic_contract') . " electronic_contract ".
	        " $left_join WHERE $where  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array());
	    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxwechatbusiness_electronic_contract') . " electronic_contract $left_join WHERE $where");
	    $pager = pagination($total, $pindex, $psize);
	    
	    $result['status']=1;
	    $result['data']['list']=$list;
	    $result['data']['pager']=$pager;
	    
	    //pdo_debug();
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template('yoxwechatbusiness/electronic_contract/electronic_contract_list'));
	}
	public function edit() 
	{
	    global $_GPC, $_W;
	    $id = intval($_GPC['id']);
	    $type = $_GPC['type']?$_GPC['type']:'WECHAT_BUSINESS';
	    $title=$_GPC['title']?$_GPC['title']:'';
	    $content=$_GPC['content']?$_GPC['content']:'';
	    
	    $uniacid=$_W['uniacid']?$_W['uniacid']:0;
	    $merchid=$_W['merchid']?$_W['merchid']:0;
	    
	    if($content!=''){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $merchid && $data['merchid']=$merchid;
	        $title && $data['title']=$title;
	        $type && $data['type']=$type;
	        $content && $data['content']=$content;
	        $data['add_time']=time();
	        
	        !$id && pdo_insert('ewei_shop_yoxwechatbusiness_electronic_contract', $data);
	        $id  && pdo_update("ewei_shop_yoxwechatbusiness_electronic_contract", $data, array("uniacid" => $uniacid,'merchid'=>$merchid,'id'=>$id));
	        
	        show_json(1, "成功");
	        }
	        $where=" uniacid = :uniacid AND merchid=:merchid AND type=:type";
	        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxwechatbusiness_electronic_contract') . "  where $where", array( ':uniacid' => $uniacid,'merchid'=>$merchid,'type'=>$type));
	    
	    $result['status']=1;
	    $result['message']='success';
	    $result['data']=$info;
	    
	    ($_W['isajax']||$_GPC['isajax']) && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);

	    include($this->template('yoxwechatbusiness/electronic_contract/post'));
	}
	/**
	 * 删除
	 */
	public function delete() 
	{
	    global $_GPC,$_W;
	    $id = intval($_GPC["id"]);
	    $uniacid=$_W['uniacid'];
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_yoxwechatbusiness_electronic_contract") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_yoxwechatbusiness_electronic_contract', array('id' => $item['id'],'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}

}
?>