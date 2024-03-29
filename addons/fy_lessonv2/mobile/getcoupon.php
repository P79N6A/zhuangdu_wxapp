<?php
/**
 * 优惠券中心
 * ============================================================================
 * 版权所有 2015-2017 风影随行，并保留所有权利。
 * 网站地址: http://www.5kym.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！不允许对程序代码以任何形式任何目的的再发布，作者将保留
 * 追究法律责任的权力和最终解释权。
 */

$login_visit = json_decode($setting['login_visit']);
if(!empty($login_visit) && in_array('getcoupon', $login_visit)){
	checkauth();
}

$title = "优惠券中心";
$pindex = max(1, intval($_GPC['page']));
$psize = 10;

if($op=='display'){
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid AND status=:status AND is_exchange=:is_exchange ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid,':status'=>1, ':is_exchange'=>1));
	foreach($list as $k=>$v){
		$list[$k]['conditions'] = $v['conditions']>0 ? "满".$v['conditions']."可用" : "无金额门槛";
		$list[$k]['integral'] = $v['exchange_integral']>0 ? "需".$v['exchange_integral']."积分" : "免费";
		$list[$k]['already_per'] = round($v['already_exchange']/$v['total_exchange'], 2)*100<=100 ? round($v['already_exchange']/$v['total_exchange'], 2)*100 : 100;
		
		$list[$k]['is_end'] = 0;
		if($v['already_exchange']>=$v['total_exchange'] || $v['status']==0){
			$list[$k]['is_end'] = 1;
		}

		$category = pdo_fetch("SELECT name FROM " .tablename($this->table_category). " WHERE id=:id", array(':id'=>$v['category_id']));
		$list[$k]['category_name'] = $category['name'] ? "适用".$category['name']."分类的课程" : "适用全部分类的课程";
		unset($category);
	}

	$market = pdo_fetch("SELECT coupon_desc FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
	$coupon_desc = $market['coupon_desc'] ? explode("\n", $market['coupon_desc']) : "";

}elseif($op=='getcoupon'){
	checkauth();
	$uid = $_W['member']['uid'];
	$member = pdo_fetch("SELECT credit1 FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$uid));

	$id = $_GPC['id'];
	$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid AND id=:id AND status=:status AND is_exchange=:is_exchange", array(':uniacid'=>$uniacid,':id'=>$id,':status'=>1,'is_exchange'=>1));
	if(empty($coupon)){
		message("该优惠券不存在", "", "error");
	}
	if($coupon['already_exchange']>$coupon['total_exchange']){
		message("该优惠券已被抢光", "", "error");
	}

	$already = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND coupon_id=:coupon_id AND uid=:uid", array(':uniacid'=>$uniacid,':coupon_id'=>$id,':uid'=>$uid));	
	if($already>=$coupon['max_exchange']){
		message("您已经兑换{$already}张了，留点给别人吧~", "", "error");
	}
	if($member['credit1']<$coupon['exchange_integral']){
		message("您的积分不足于兑换", "", "error");
	}

	load()->model('mc');
	if(mc_credit_update($uid, 'credit1', '-'.$coupon['exchange_integral'], array(0, '微课堂模块兑换优惠券:'.$id))){
		$memberCoupon = array(
			'uniacid'		=> $uniacid,
			'uid'			=> $uid,
			'amount'		=> $coupon['amount'],
			'conditions'	=> $coupon['conditions'],
			'validity'		=> $coupon['validity_type']==1 ? $coupon['days1'] : time()+$coupon['days2']*86400,
			'category_id'	=> $coupon['category_id'],
			'status'		=> 0,
			'source'		=> 5,
			'coupon_id'		=> $id,
			'addtime'		=> time(),
		);
		if(pdo_insert($this->table_member_coupon, $memberCoupon)){
			pdo_update($this->table_mcoupon, array('already_exchange'=>$coupon['already_exchange']+1), array('id'=>$id));
			message("领取成功", $this->createMobileUrl('getcoupon'), "success");
		}else{
			message("领取优惠券失败", "", "error");
		}
	}else{
		message("扣除用户积分失败", "", "error");
	}
}

if(!$_W['isajax'] && $op=='display'){
	include $this->template('getcoupon');
}
if($_W['isajax'] && $op=='display'){
	echo json_encode($list);
}

?>