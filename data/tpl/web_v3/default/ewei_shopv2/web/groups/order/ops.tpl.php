<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!$refund) { ?>
    <?php  if($item['status'] == 0) { ?>
    <?php if(cv('groups.order.pay')) { ?>
    <a class="btn btn-primary btn-xs" data-toggle="ajaxPost" href="<?php  echo webUrl('groups/order/pay', array('id' => $item['id']))?>" data-confirm="确认此订单已付款吗？">确认付款</a>
    <?php  } ?>
    <?php  } ?>
    <?php  if(($item['status'] == 1 && $item['success'] == 1) || ($item['status'] == 1 && $item['is_team'] == 0) ) { ?>
<?php  if($item['isverify'] == 1) { ?>
<?php  } else { ?>
<?php if(cv('groups.order.send')) { ?>
    <a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/send', array('id' => $item['id']))?>">确认发货</a><br />
<?php  } ?>
<?php  if($item['sendtype'] > 0) { ?>
    <?php if(cv('groups.order.sendcancel')) { ?>
        <a class="text-primary" data-toggle='ajaxModal'  href="<?php  echo webUrl('groups/order/sendcancel', array('id' => $item['id']))?>" >取消发货</a><br />
    <?php  } ?>
<?php  } ?>
<?php  } ?>
    <?php  } ?>
    <?php  if($item['status'] == -1 && $item['success'] == -1) { ?>
    <!--<a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/send', array('id' => $item['id']))?>" data-confirm="确认此订单要退款吗？">确认退款</a>-->
    <?php  } ?>
    <?php  if($item['status'] == 2) { ?>
<?php if(cv('groups.order.changeexpress')) { ?>
    <a class="btn btn-success btn-xs" data-toggle="ajaxModal"  href="<?php  echo webUrl('groups/order/changeexpress', array('id' => $item['id']))?>">修改物流</a><br />
<?php  } ?>
<?php if(cv('groups.order.finish')) { ?>
    <a class="btn btn-primary btn-xs" data-toggle='ajaxPost'  href="<?php  echo webUrl('groups/order/finish', array('id' => $item['id']))?>" data-confirm="确认订单收货吗？">确认收货</a>
<?php  } ?>
    <?php  } ?>
<?php  } else { ?>

    <?php  if($item['refundstatus'] == 0) { ?>
<?php if(cv('groups.refund.submit')) { ?>
    <a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/refund/submit', array('id' => $item['orderid'],'refundid' => $item['id']))?>">处理申请</a>
<?php  } ?>
    <?php  } ?>
    <?php  if($item['refundstatus'] == 3) { ?>
<?php if(cv('groups.refund.receipt')) { ?>
    <a class="btn btn-primary btn-xs" data-toggle='ajaxPost'  href="<?php  echo webUrl('groups/refund/submit', array('id' => $item['orderid'],'refundid' => $item['id']))?>" data-confirm="确认订单收货吗？">确认收货</a>
<?php  } ?>
    <?php  } ?>
    <?php  if($item['refundstatus'] == 4) { ?>
<?php if(cv('groups.refund.send')) { ?>
    <a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/refund/submit', array('id' => $item['orderid'],'refundid' => $item['id']))?>">确认发货</a>
<?php  } ?>
    <?php  } ?>
    <?php  if($item['refundstatus'] == 5) { ?>
<?php if(cv('groups.refund.express')) { ?>
    <a class="btn btn-success btn-xs" data-toggle="ajaxModal"  href="<?php  echo webUrl('groups/refund/submit', array('id' => $item['orderid'],'refundid' => $item['id']))?>">修改物流</a>
<?php  } ?>
    <?php  } ?>
<?php  } ?>



