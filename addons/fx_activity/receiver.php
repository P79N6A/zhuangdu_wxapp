<?php

/**

 * 活动报名模块订阅器

 *

 * @author 悟空源码社区

 * @url http://www.5kym.cn/

 */

defined('IN_IA') or exit('Access Denied');



class Fx_activityModuleReceiver extends WeModuleReceiver {

	public function receive() {

		$type = $this->message['type'];

		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码

	}

}