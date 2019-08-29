<?php

/**

 * 活动报名模块处理程序

 *

 * @author 悟空源码社区

 * @url http://www.5kym.cn/

 */

defined('IN_IA') or exit('Access Denied');

define("MODULE_NAME", "fx_activity");

require IA_ROOT . '/addons/' . MODULE_NAME . '/core/common/defines.php';

require FX_CORE . '/class/loader.class.php';

$autoload = FX_CORE . '/class/autoload.php';

if(file_exists($autoload)) require $autoload;

class Fx_activityModuleProcessor extends WeModuleProcessor {

	public function respond() {

		global $_W;

		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码

		$message = $this -> message;

		$openid = $this -> message['from'];

		$content = $this -> message['content'];

		$msgtype = strtolower($message['msgtype']);

		$event = strtolower($message['event']);

		if ($msgtype == 'text' || $event == 'click') {

			$saler = pdo_fetch('select * from ' . tablename('fx_saler') . " where INSTR(`openid`, '$openid') and status=:status and uniacid=:uniacid", array(':status' => 1, ':uniacid' => $_W['uniacid']));

			if (empty($saler)) {

				$this -> endContext();

				return $this -> respText('您无核销权限!');

				return $this -> salerEmpty();

			} 

			if (!$this -> inContext) {

				$this -> beginContext();

				return $this -> respText('请输入核销码:');

			} else if ($this -> inContext && is_numeric($content)) {

				$records = pdo_fetch('select * from ' . tablename('fx_activity_records') . ' where hexiaoma=:hexiaoma and uniacid=:uniacid', array(':hexiaoma' => $content, ':uniacid' => $_W['uniacid']));

				if (empty($records)) {

					return $this -> respText('未找到要核销的报名,请重新输入!');

				}

				if ($records['ishexiao'] == 1) {

					$this -> endContext();

					return $this -> respText('此报名已核销，无需重复核销!');

				} 

				if ($records['status'] != 1 && $records['status'] != 2) {

					$this -> endContext();

					return $this -> respText('报名状态错误，无法核销!');

				} 

				$storeids = array();

				$salerids = array();

				$activity  = model_activity::getSingleActivity($records['activityid'], '*');

				$storeids = array_merge($activity['storeids'], $storeids);

				$salerids = array_merge(explode(',', $saler['storeid']), $salerids);

				if (!empty($saler['storeid'])) {

					$inter = array_intersect($storeids, $salerids);

					if (empty($inter)) {

						return $this -> respText('您对当前活动无核销权限!');

					}

				}

				$data = array(

					'payprice' => $records['price'], 

					'status'=>3,

					'ishexiao'=>1,

					'veropenid' => $openid,

					'sendtime'=>date('Y-m-d H:i:s',TIMESTAMP)

				);

				$result = pdo_update('fx_activity_records', $data, array('id' => $records['id']));

				if(!empty($records['merchantid']) && !empty($records['price'])){

					pdo_insert("fx_merchant_money_record",array('merchantid'=>$records['merchantid'],'uniacid'=>$_W['uniacid'],'money'=>$records['price'],'recordsid'=>$records['id'],'createtime'=>TIMESTAMP,'type'=>2,'detail'=>'关键词核销<br>订单号：'.$records['orderno']));

					if($records['paytype']=='wechat' || $records['paytype']=='alipay')//只有微信、支付宝成功支付的才可更新结算金额

					model_merchant::updateNoSettlementMoney($records['price'], $records['merchantid']);//更新可结算金额

				}

				//积分奖励

				if ($_W['_config']['creditstatus'] == 1 && $activity['prize']['sign_credit'] > 0) {

					$credit = intval($activity['prize']['sign_credit']);//赠送积分额度

					$credit_type = $_W['_config']['credit_type']?$_W['_config']['credit_type']:1;

					fx_load()->model('credit');

					$result = credit_update_credit1($_W['member']['uid'], $credit, "核销获取" . $credit . '积分', $activity['merchantid']);

				}

				$this -> endContext();

				return $this -> respText('核销成功!');

			} 

		} 

	}

	private function salerEmpty() {

		ob_clean();

		ob_start();

		echo '';

		ob_flush();

		ob_end_flush();

		exit(0);

	}

}