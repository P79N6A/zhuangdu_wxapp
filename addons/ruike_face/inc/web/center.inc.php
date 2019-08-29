<?php



defined('IN_IA') or exit('Access Denied');

global $_W,$_GPC;

load()->func('tpl');
$path=IA_ROOT.'/addons/'.$_GPC['m'].'/project.json';
// echo $path;exit;
if(checksubmit()){
	if($_GPC['api_key'] && $_GPC['api_secret']){
		$data['api_key']=$_GPC['api_key'];
		$data['api_secret']=$_GPC['api_secret'];
		$res=file_put_contents($path, json_encode($data));
		if($res){
			message('配置成功！',$this->createWebUrl('center'),'success');
		}else{
			message('配置失败','','error');
		}
	}else{
		message('配置项不能为空！','refresh','error');
	}
}

$faces=file_get_contents($path);
$conf=json_decode($faces,true);
include $this->template('webapps/center');



