<?php 

class queue {
	private $islock = array('value'=>0,'expire'=>0);
	private $expiretime = 900; //锁过期时间，秒
	
	//初始赋值
	public function __construct(){
		$lock = Util::getCache('queuelock','first');
		if(!empty($lock)) $this->islock = $lock;
	}
	
	//加锁
	private function setLock(){
		$array = array('value'=>1,'expire'=>time());
		Util::setCache('queuelock','first',$array);
		$this->islock = $array;
	}
	
	//删除锁
	private function deleteLock(){
		Util::deleteCache('queuelock','first');
		$this->islock = array('value'=>0,'expire'=>time());
	}	
	
	//检查是否锁定
	public function checkLock(){
		$lock = $this->islock;	
		if($lock['value'] == 1 && $lock['expire'] < (time() - $this->expiretime )){ //过期了，删除锁
			$this->deleteLock();
			return false;
		}
		if(empty($lock['value'])){
			return false;
		}else{
			return true;
		}
	}
	
	public function queueMain(){
		set_time_limit(0); //解除超时限制
		if(self::checkLock()){
			return false; //锁定的时候直接返回
		}else{
			self::setLock(); //没锁的话锁定
		}
		//self::auto_notice(); //发送过期通知
		self::auto_cash(); //自动处理提现
		self::autoDealOrder(); //自动处理订单
		self::deleteLock(); //执行完删除锁
	}
	
	//自动发送提醒
	static function auto_notice(){
		global $_W;
		$now = time();
		$uid = $_W['uniacid'];
		$noticeorders = pdo_getall('fx_activity_records',array('uniacid' => $uid,'status'=>2,'is_hexiao'=>1,'limitnotice'=>0));
		if($noticeorders){
			foreach ($noticeorders as $key => $v) {
				$goods = pdo_get('fx_activity_records',array('id' => $v['activityid']),array('gname','noticetime','hexiaolimittime','hexiaolimittimetype','g_type'));
				if($goods['hexiaolimittime'] && $goods['hexiaolimittimetype'] && ($goods['g_type'] == 1)){
					if($goods['hexiaolimittimetype'] == 1){
						$cutofftime = $goods['hexiaolimittime'];
					}else if($goods['hexiaolimittimetype'] == 2){
						$cutofftime = $v['ptime'] + $goods['hexiaolimittime']*24*3600;
					}
					if($goods['noticetime']){
						if($now > ($cutofftime - $goods['noticetime']*24*3600)){
							$flag = 1;
						}
					}else {
						if($now > ($cutofftime - 604800)){
							$flag = 1;
						}
					}
					if($flag){
						$msg = '';
						$msg .= "【到期提醒】您购买的商品即将过期，请速去商家自提核销。". "\n";
						$msg .= "商品名称：".$goods['gname']. "\n";
						$msg .= "截止时间：".date('Y-m-d H:i:s',$cutofftime). "\n";
						$msg .= "";	
						$url = app_url('order/order/detail',array('id'=>$v['id']));
						sendCustomNotice($v['openid'],$msg,$url,'');
						pdo_update('tg_order',array('limitnotice' => 1),array('id' => $v['id']));
					}
				}
			}
		}
	}

	//自动处理订单
	static function autoDealOrder(){
		global $_W;
		$config =  $_W['_config'];
		//自动取消订单
		if(in_array('cancle', $config['task'])){
			$config['cancle_time'] = !empty($config['cancle_time'])?$config['cancle_time']:1;
			$canceltime = time() - $config['cancle_time']*3600;
			$orderdata = pdo_fetchall("select status,id from".tablename("fx_activity_records")."where uniacid={$_W['uniacid']} and paytype<>'delivery' and status=0 and UNIX_TIMESTAMP(jointime) < '{$canceltime}'");		
			foreach($orderdata as $k=>$v){
				model_records::cancelDoNotPayOrder($v);
			}
		}
	}
	
	//自动处理提现
	static function auto_cash(){
		global $_W;
		$config =  $_W['_config'];
		if(in_array('cash', $config['task'])){
			$config['cash_time'] = !empty($config['cash_time'])?$config['cash_time']:1;
			$cashtime = time() - $config['cash_time']*3600;
			$time = TIMESTAMP;
			
			$recorddata = pdo_fetchall("select * from".tablename("fx_merchant_record")."where uniacid={$_W['uniacid']} and status <> 3 and createtime < '{$cashtime}'");
			foreach($recorddata as $k=>$v){
				$merchant = model_merchant::getSingleMerchant($v['merchantid'], 'openid');
				if(!empty($merchant['openid'])){
					$money = $v['money'];//实扣金额（元）
					$get_money = $v['get_money'];//实到金额（元）
					$commission = sprintf("%.2f",$v['commission']);//手续费
					
					$result = model_merchant::finance($merchant['openid'], $get_money * 100, '主办方提现');  //结算操作
					if ($result['result_code'] == 'SUCCESS'){
						pdo_update('fx_merchant_account',array('no_money_doing'=>0),array('merchantid'=>$v['merchantid']));
						pdo_insert("fx_merchant_money_record",array('merchantid'=>$v['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$get_money,'recordsid'=>'','createtime'=>$time,'type'=>4,'detail'=>$v['orderno']));
						if($commission>0)
						pdo_insert("fx_merchant_money_record",array('merchantid'=>$v['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>0-$commission,'recordsid'=>'','createtime'=>$time,'type'=>7,'detail'=>$v['orderno']));
						$res = model_merchant::updateNoSettlementMoney(0-$money, $v['merchantid']);
						if($res){
							pdo_update('fx_merchant_record',array('status'=>3,'updatetime'=>$time,'type'=>1),array('id'=>$v['id']));
						}							
					}
				}
			}
		}
	}
	
}

