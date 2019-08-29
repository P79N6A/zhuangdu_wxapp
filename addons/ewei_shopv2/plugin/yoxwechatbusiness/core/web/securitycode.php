<?php  if( !defined("IN_IA") )
{
	exit( "Access Denied" );
}
class Securitycode_EweiShopV2Page extends PluginWebPage
{
    //列表
	public function main()
	{
	    $this->securitycode_list();
	}
	public function securitycode_list(){
        global $_GPC,$_W;

        // 	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
        // 	    $keyword = $_GPC['keyword'];
        $big_code  = $_GPC['big_code'];
        $middle_code  = $_GPC['middle_code'];
        $small_code  = $_GPC['small_code'];
        $small_securitycode  = $_GPC['small_securitycode'];
        $status  = !empty($_GPC['status']) ? $_GPC['status'] : '';
        // 	    $orderby  = $_GPC['orderby'];

        $pindex = max(1, intval($_GPC['page']));
        $psize = 100;

        $uniacid=$_W['uniacid']?$_W['uniacid']:1;

        $replace=array();
        $where = " securitycode.uniacid = '{$uniacid}' ";
        // 	    $type!='' && ($where.=" AND type=:type") && ($replace=array(':type'=>$type));
        $big_code!='' && ($where.=" AND big_code=:big_code") && ($replace+=array(':big_code'=>$big_code));
        $middle_code!='' && ($where.=" AND middle_code=:middle_code") && ($replace+=array(':middle_code'=>$middle_code));
        $small_code!='' && ($where.=" AND small_code=:small_code") && ($replace+=array(':small_code'=>$small_code));
        $small_securitycode!='' && ($where.=" AND small_securitycode=:small_securitycode") && ($replace+=array(':small_securitycode'=>$small_securitycode));
        $status!='' && ($where.=" AND status=:status") && ($replace+=array(':status'=>$status));
        $list = pdo_fetchall("SELECT securitycode.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_goods_securitycode') . " securitycode ".
            "WHERE $where ORDER BY id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $replace, 'id');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_goods_securitycode') . " securitycode WHERE $where");
        $pager = pagination($total, $pindex, $psize);

        foreach ($list as $key => &$value) {
            $value['goods_name']=pdo_fetchcolumn('select title from ims_ewei_shop_goods where id='.$value['goods_id']);
        }
        unset($value);
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;

        //pdo_debug();
        $_W['isajax'] && die(json_encode($result));
        $_GPC['isdebug']&& print_r($result);
        include($this->template('yoxwechatbusiness/securitycode/securitycode_list'));
    }
	public function edit()
	{
	    global $_GPC,$_W;

	    $id=$_GPC['id'];
// 	    $type    = !empty($_GPC['type']) ? $_GPC['type'] : '';
	    $big_code  = $_GPC['big_code'];
	    $middle_code  = $_GPC['middle_code'];
	    $small_code  = $_GPC['small_code'];
	    $small_securitycode  = $_GPC['small_securitycode'];
	    $status  = !empty($_GPC['status']) ? $_GPC['status'] : '';

	    $uniacid=$_W['uniacid']?$_W['uniacid']:1;

	    if($status!=''||$big_code||$middle_code||$small_code){
	        $data=array();
	        $uniacid && $data['uniacid']=$uniacid;
	        $big_code && $data['big_code']=$big_code;
	        $middle_code && $data['middle_code']=$middle_code;
	        $small_code && $data['small_code']=$small_code;
	        $small_securitycode && $data['small_securitycode']=$small_securitycode;
	        $status!='' && $data['status']=$status;

	        !$id && pdo_insert('ewei_shop_goods_securitycode', $data);
	        $id && pdo_update("ewei_shop_goods_securitycode", $data, array("id" => $id));
	        show_json(1,'成功');
	    }
	    $info = pdo_fetch("SELECT securitycode.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_goods_securitycode') . " securitycode ".
	        "WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $uniacid));

	    $result['status']=1;
	    $result['data']=$info;
	    $_W['isajax'] && die(json_encode($result));
	    $_GPC['isdebug']&& print_r($result);
	    include($this->template());
	}
	/**
	 * 导入
	 */
	public function import(){
	    global $_GPC,$_W;

	    $uniacid=$_W['uniacid'];
	    //die('aaa');
	    if ($_FILES["file"]["error"] > 0)
	    {
	        print_r($_FILES);
	        echo "Error: " . $_FILES["file"]["error"] . "<br>";
	        die('a');
	    }
	    if($_FILES){
	        $str=file_get_contents($_FILES['file']['tmp_name']);
	        $array = explode("\r\n",$str);
	        //print_r($array);
	        $count=count($array);
	        if($count>200){
	            show_json(0,'最多导入200条记录，如服务器已升级，请联系开发者修改最多导入数量');
	        }
	        //print_r($_FILES);
	        foreach($array as $line){
	            $line_arr=explode(',', $line);
	            //var_dump($line);
	            //var_dump($line_arr);
	            if(!is_numeric($line_arr[0]))continue;
	            $data['uniacid']=$_W['uniacid'];
	            $data['big_code']=$line_arr[0];
	            $data['middle_code']=$line_arr[1];
	            $data['small_code']=$line_arr[2];
	            $data['small_securitycode']=$line_arr[3];
	            $data['goods_id']=$line_arr[4];
	            $data['add_time']=time();
	            $list[]=$data;
	            $info = pdo_fetch("SELECT securitycode.*,FROM_UNIXTIME(add_time) AS add_time_format FROM " . tablename('ewei_shop_goods_securitycode') . " securitycode ".
	                "WHERE big_code = :big_code AND middle_code=:middle_code AND small_code=:small_code AND uniacid = :uniacid", array(':big_code' => $data['big_code'],':middle_code'=>$data['middle_code'],':small_code'=>$data['small_code'],':uniacid' => $uniacid));
	            //pdo_debug();die();
	            if(empty($info)){
	                pdo_insert('ewei_shop_goods_securitycode', $data);
	            }
	        }

	        //print_r($list);
	        show_json(1,'成功');
	    }
	    //print_r($_FILES);
	    //die('c');
	    include($this->template());

	}
	public function delete()
	{
	    global $_GPC,$_W;
	    $uniacid=$_W['uniacid'];
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_goods_securitycode") . " WHERE id in( " . $id . " ) AND uniacid=" .$uniacid);
	    foreach($items as $item){
	        pdo_delete('ewei_shop_goods_securitycode', array('id' => $id,'uniacid'=>$uniacid));
	    }
	    show_json(1,'删除成功');
	}
	public function displayorder()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    $displayorder = intval($_GPC["value"]);
	    $item = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_goods_securitycode") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    if( !empty($item) )
	    {
	        pdo_update("ewei_shop_goods_securitycode", array( "displayorder" => $displayorder ), array( "id" => $id ));
	        plog("ewei_shop_goods_securitycode.edit", "修改排序 ID: " . $item["id"] . " 标题: " . $item["id"] . " 排序: " . $displayorder . " ");
	    }
	    show_json(1);
	}
	public function status()
	{
	    global $_W;
	    global $_GPC;
	    $id = intval($_GPC["id"]);
	    if( empty($id) )
	    {
	        $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
	    }
	    $items = pdo_fetchall("SELECT id FROM " . tablename("ewei_shop_goods_securitycode") . " WHERE id in( " . $id . " ) AND uniacid=" . $_W["uniacid"]);
	    foreach( $items as $item )
	    {
	        pdo_update("ewei_shop_goods_securitycode", array( "status" => intval($_GPC["status"]) ), array( "id" => $item["id"] ));
	        plog("ewei_shop_goods_securitycode.edit", ("修改状态<br/>ID: " . $item["id"] . "<br/>热搜: " . $item["keyword"] . "<br/>状态: " . $_GPC["status"] == 1 ? "开启" : "关闭"));
	    }
	    show_json(1, array( "url" => referer() ));
	}
}
?>