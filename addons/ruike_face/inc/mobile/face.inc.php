<?php



defined('IN_IA') or exit('Access Denied');

global $_W,$_GPC;

load()->func('tpl');



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



    $account_api = WeAccount::create();

    $info = $account_api->fansQueryInfo($_W['openid']);

    if(checksubmit('submit')) {

        if(empty($_GPC['image'][0])){

            message('请选择一张照片！','refresh','error');

        }

        $filename=tomedia($_GPC['image'][0]);

        $path=IA_ROOT.'/addons/'.$_GPC['m'].'/project.json';
        $faces=file_get_contents($path);
        $conf=json_decode($faces,true);

        $data=array

        (

            "api_key"=>$conf['api_key'],

            "api_secret"=>$conf['api_secret'],

            "return_attributes"    => "gender,emotion,age,facequality,smiling",

            "image_url"=>$filename,

        );

        $url="https://api-cn.faceplusplus.com/facepp/v3/detect";

        $method="POST";

        $files=curlPost($url,$data,$method);

        $files=json_decode($files,true);

        if(!empty($files['error_message'])){

            message('当前使用人数比较多，请稍后再试！','refresh','error');

        }
        $cache=uni_account_default($_W['uniacid']);


        include $this->template('face_rec');

        exit();

        //        print_r($files);

    }

    include $this->template('face');



