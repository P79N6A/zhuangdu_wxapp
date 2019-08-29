<?php
/**
 * 颜值测试
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
class yoxfaceModel extends PluginModel 
{
    private $is_from_wechat='';
    public function __construct(){
        parent::__construct();
        global $_GPC, $_W;
        $this->is_from_wechat=$_GPC['comefrom']=='wxapp'?1:0;
    }
    public function add_face($data){
        if(empty($data)){
            return;
        }
        $is_insert=pdo_insert('ewei_shop_yoxface', $data);
        
        if(empty($is_insert)){
            return;
        }
        $data['id']=$is_insert;
        return $data;
    }
    public function page_face_list(){
        global $_GPC, $_W;
        
        $uid=$_GPC['uid'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:20;
        
//         $uid=$_W['uid'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        $where = " face.uniacid = '{$uniacid}' ";
        $uid && ($where.=" AND face.uid = '{$uid}' ");
        
        $left_join_member="LEFT JOIN " . tablename('mc_members') . " `members` ON (`members`.uid=face.uid)";
        $member_field=" `members`.nickname,`members`.avatar";
        $list = pdo_fetchall("SELECT face.*,FROM_UNIXTIME(face.add_time) AS add_time_format,{$member_field} FROM " . tablename('ewei_shop_yoxface') . " face ".
            $left_join_member.
            " WHERE $where ORDER BY face.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxface') . " face WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        return $result;
    }
    /**
     * 建议列表
     * @return string
     */
    public function page_face_value_suggest_list(){
        global $_GPC, $_W;
        
        $uid=$_GPC['uid'];
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = $_GPC['psize']?$_GPC['psize']:20;
        
        //$uid=$_W['uid'];
        
        $uniacid=$_W['uniacid']?$_W['uniacid']:0;
        $merchid=$_W['merchid']?$_W['merchid']:0;
        
        $where = " suggest.uniacid = '{$uniacid}' ";
        
        $list = pdo_fetchall("SELECT suggest.*,FROM_UNIXTIME(suggest.add_time) AS add_time_format FROM " . tablename('ewei_shop_yoxface_suggest') . " suggest ".
            " WHERE $where ORDER BY suggest.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_yoxface_suggest') . " suggest WHERE $where");
        $pager = pagination($total, $pindex, $psize);
        
        foreach($list as &$info){
            $goods_ids=array_merge((array)$goods_ids,(array)explode(',',$info['goods_ids']));
            $package_ids=array_merge((array)$package_ids,(array)explode(',',$info['package_ids']));
            $info['goods_ids_arr']=$goods_ids;
            $info['package_ids_arr']=$package_ids;
        }
        $goods_ids_str=implode(',', $goods_ids);
        $package_ids_str=implode(',', $package_ids);
        unset($info);
        $goods_list = pdo_fetchall("SELECT goods.id,goods.title,goods.marketprice FROM " . tablename('ewei_shop_goods') . " goods ".
            " WHERE id in($goods_ids_str) ORDER BY goods.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        $package_list = pdo_fetchall("SELECT package.id,package.title,price FROM " . tablename('ewei_shop_package') . " package ".
            " WHERE id in($package_ids_str)  ORDER BY package.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id DESC');
        foreach($list as &$info){
            foreach($goods_list as $goods_info){
                if(in_array($goods_info['id'],$info['goods_ids_arr'])){
                    $info['goods_list'][]=$goods_info;
                }
            }
            foreach($package_list as $package_info){
                if(in_array($package_info['id'],$info['package_ids_arr'])){
                    $info['package_list'][]=$package_info;
                }
            }
        }
        unset($info);
        //print_r($list);
        //pdo_debug();
        $result['status']=1;
        $result['data']['list']=$list;
        $result['data']['pager']=$pager;
        
        return $result;
    }
    public function suggest_info($id){
        
        $info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxface_suggest') . " WHERE id = :id", array(':id' => $id));
        
        return $info;
    }
    function curlPost($url,$data,$method){
        $ch = curl_init();   //1.初始化
        curl_setopt($ch, CURLOPT_URL, $url); //2.请求地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);//3.请求方式
        //4.参数如下
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');//模拟浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept-Encoding: gzip, deflate'));//gzip解压内容
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        
        if($method=="POST"){//5.post方式的时候添加数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);//6.执行
        if (curl_errno($ch)) {//7.如果出错
            return curl_error($ch);
        }
        curl_close($ch);//8.关闭
        return $tmpInfo;
    }
    //========================以下是页面================================//
    public function page_face_result(){
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        
        $account_api = WeAccount::create();
//         $info = $account_api->fansQueryInfo($_W['openid']);
        //if(checksubmit('submit')) {
            if(empty($_GPC['image'][0])){
                message('请选择一张照片！','refresh','error');
            }
            $filename=tomedia($_GPC['image'][0]);
            $path=IA_ROOT.'/addons/'.$_GPC['m'].'/plugin/yoxface/project.json';
            $faces=file_get_contents($path);
            $conf=json_decode($faces,true);
            $request_data=array
            (
                "api_key"=>$conf['api_key'],
                "api_secret"=>$conf['api_secret'],
                "return_attributes"    => "gender,emotion,age,facequality,smiling",
                "image_url"=>$filename,
            );
            $url="https://api-cn.faceplusplus.com/facepp/v3/detect";
            $method="POST";
            $files=$this->curlPost($url,$request_data,$method);
            $files=json_decode($files,true);
            if(!empty($files['error_message'])){
                message('当前使用人数比较多，请稍后再试！','refresh','error');
            }
            $cache=uni_account_default($uniacid);
            
            foreach($files['faces'] as &$face){
                $face_data=json_encode($face);
                $gender=$face['attributes']['gender']['value']=='Female'?2:1;
                $this->add_face(array('uniacid'=>$uniacid,'uid'=>$_W['member']['uid'],'face_url'=>$filename,'age'=>$face['attributes']['age']['value'],'facequality'=>$face['attributes']['facequality']['threshold'],'face_data'=>$face_data,'gender'=>$gender,'add_time'=>time()));
                $suggest_info = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_yoxface_suggest') . " WHERE face_value_lt<:face_value_lt AND uniacid = :uniacid ORDER BY face_value_lt DESC ", 
                    array(':face_value_lt' =>$face['attributes']['facequality']['value'], ':uniacid' => $uniacid));
                $suggest_info['goods_ids'] && ($suggest_info['goods_list'] = pdo_fetchall("SELECT goods.id,goods.title,goods.marketprice FROM " . tablename('ewei_shop_goods') . " goods ".
                    " WHERE id in({$suggest_info['goods_ids']}) ORDER BY goods.id DESC ", array(), 'id DESC'));
                $suggest_info['package_ids'] && ($suggest_info['package_list'] = pdo_fetchall("SELECT package.id,package.title,price FROM " . tablename('ewei_shop_package') . " package ".
                    " WHERE id in({$suggest_info['package_ids']})  ORDER BY package.id DESC ", array(), 'id DESC'));
                $face['suggest_info']=$suggest_info;
            }
            
            
    	    $data['files']=$files;
    	    $data['member_info']=$_W['member'];
    	    $data['filename']=$filename;
    	    $data['cache']=$cache;
    	    
    	    $result['status']=1;
    	    $result['message']=$files['error_message'];
    	    $result['data']=$data;
	    //pdo_debug();
    	    //print_r($result);die('Yoper');
    	    return $result;
        //}
    }
    //===========================以上是页面==========================================//
    /*
 [files] => Array
                (
                    [image_id] => lR5b5fM2JzYgpaFCoPyu3Q==
                    [request_id] => 1551668747,e63ff7d6-2344-46b3-b66b-f311839e8fc2
                    [time_used] => 559
                    [faces] => Array
                        (
                            [0] => Array
                                (
                                    [attributes] => Array
                                        (
                                            [emotion] => Array
                                                (
                                                    [sadness] => 61.134
                                                    [neutral] => 32.734
                                                    [disgust] => 0.088
                                                    [anger] => 4.964
                                                    [surprise] => 0.088
                                                    [fear] => 0.676
                                                    [happiness] => 0.315
                                                )

                                            [gender] => Array
                                                (
                                                    [value] => Female
                                                )

                                            [age] => Array
                                                (
                                                    [value] => 22
                                                )

                                            [facequality] => Array
                                                (
                                                    [threshold] => 70.1
                                                    [value] => 21.636
                                                )

                                            [smile] => Array
                                                (
                                                    [threshold] => 50
                                                    [value] => 0.181
                                                )

                                        )

                                    [face_rectangle] => Array
                                        (
                                            [width] => 207
                                            [top] => 27
                                            [left] => 184
                                            [height] => 207
                                        )

                                    [face_token] => ad6889d6758c9ff3f326b8d091741ce7
                                    [suggest_info] => 
                                )

                        )

                )     
     */
}