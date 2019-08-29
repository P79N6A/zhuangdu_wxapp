<?php
	//1：积分，微擎表,2：tg表
	function credit_get_by_uid($uid = '' ,$credit_type=1) {
		global $_W;
		if($credit_type==1){
			load()->model('mc');
			$result = mc_fetch($uid, array('credit1','credit2'));
		}
		if($credit_type==2){
			$result = pdo_fetch("select credit1,credit2 from".tablename('tg_member')."where uid=:uid and uniacid=:uniacid",array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
		}
		return $result;
	} 
	
	 function credit_update_credit1($uid, $credit1 = 0, $remark = '', $store_id = 0, $clerk_type = 1) {
		global $_W;
		load()->model('mc');
		$result = mc_credit_update($uid, 'credit1', $credit1, array($uid, $remark, MODULE_NAME, '', $store_id, $clerk_type));
		if($result){
			$openid = mc_uid2openid($uid);
			mc_notice_credit1($openid, $uid, $credit1, $remark, '', '谢谢您的参与!');
			return TRUE;
		}
		return FALSE;			
	}
	
	function credit_update_credit2($uid ,$credit2=0,$credit_type=1,$remark='') {
		global $_W;
		if($credit_type==1){
			load()->model('mc');
			$f = mc_credit_update($uid, 'credit2', $credit2, array($uid, '报名余额操作',MODULE_NAME));
			if($f){
				$data=array(
					'uid'=>$uid,
					'uniacid'=>$_W['uniacid'],
					'openid'=>'',
					'num'=>$credit2,
					'createtime'=>TIMESTAMP,
					'status'=>1,
					'type'=>2,
					'paytype'=>2,
					'table'=>1,
					'remark'=>$remark
				);	
				//pdo_insert("tg_credit_record",$data);	
				return TRUE;
			}
			return FALSE;
		}
		if($credit_type==2){
			$info = pdo_fetch("select credit1,credit2 from".tablename('tg_member')."where uid=:uid and uniacid=:uniacid",array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
			if(pdo_update("tg_member",array('credit2'=>$info['credit2']+$credit2),array('uid'=>$uid,'uniacid'=>$_W['uniacid']))){
				$data=array(
					'uid'=>$uid,
					'uniacid'=>$_W['uniacid'],
					'openid'=>'',
					'num'=>$credit2,
					'createtime'=>TIMESTAMP,
					'status'=>1,
					'type'=>2,
					'paytype'=>2,
					'table'=>2,
					'remark'=>$remark
				);	
				pdo_insert("tg_credit_record",$data);	
				return TRUE;
			}
			return FALSE;
		}
		
	}
	
