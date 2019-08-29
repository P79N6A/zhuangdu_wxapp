<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type='text/css'>
    .ordertable { width:100%;position: relative;margin-bottom:10px}
    .ordertable tr td:first-child { text-align: right }
    .ordertable tr td {padding:10px 5px 0;vertical-align: top}
    .ordertable1 tr td { text-align: right; }
    .ops .btn { padding:5px 10px;}
    .order-container{
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }
    .order-container-left{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .order-container-static{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        padding: 30px 50px 0;
    }
    .font20{
        font-size:20px;
        font-weight:bold;;
    }
    .trbagpack span{
        margin: 0 10px;
        display: inline-block;
        vertical-align: middle;
    }
    .trbagpack span.address{
        width:150px;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }
    tfoot .price{
        float: right;
    }
    tfoot .price-inner{
        display: inline-block;
        vertical-align: middle;
        width:100px;
        text-align: right;
    }
    .packbag-group{
        border:1px solid #efefef;
    }
    .packbag{
        padding: 0 30px;
    }
    .packbag-title{
        line-height: 33px;
    }
    .packbag-group .packbag-list{
        padding: 20px 33px;
        border-bottom: 1px solid #efefef;
        display: flex;
        align-items: center;
    }
    .packbag-list .packbag-media{
        width:100px;
    }
    .packbag-list .packbag-inner{
        border-left:1px solid #efefef;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .packbag-goods-list{
        display: flex;
        flex-wrap: wrap;
        width:100%;
    }
    .packbag-goods{
        width:25%;
        display: flex;
        display: -webkit-flex;
        margin: 10px 0 5px;
    }
    .packbag-goods-media{
        width:52px;
        height: 52px;
        margin-right: 10px;
    }
    .packbag-goods-media img{
        width:52px;
        height: 52px;
        border: 1px solid #efefef;
    }
    .packbag-goods-inner{
        flex:1;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
    }
    .packbag-goods-inner p{
        color: #999;
    }
    .packbag-goods-inner .title{
        width:100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .table .trorder td{
        border-right:1px solid #efefef;
    }
    .table .trorder td:nth-of-type(1){
        border:none;
    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">订单详情</span>
</div>
<div class="page-content">
    <?php  if($order['status']!=-1) { ?>
    <div class="step-region" >
        <ul class="ui-step ui-step-4" >
            <li <?php  if($order['status']>=0) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number" >1</div>
            <div class="ui-step-title" >买家下单</div>
            <div class="ui-step-meta" ><?php  if(0<=$order['status']) { ?><?php  echo date('Y-m-d',$order['createtime'])?><br/><?php  echo date('H:i:s',$order['createtime'])?><?php  } ?></div>
            </li>
            <li <?php  if($order['status']>=1) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number">2</div>
            <div class="ui-step-title">买家付款</div>
            <div class="ui-step-meta"><?php  if(1<=$order['status']) { ?><?php  echo date('Y-m-d',$order['paytime'])?><br/><?php  echo date('H:i:s',$order['paytime'])?><?php  } ?></div>
            </li>
            <li <?php  if($order['status']>=2) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number" >3</div>
            <div class="ui-step-title">卖家发货</div>
            <div class="ui-step-meta" ><?php  if(2<=$order['status']) { ?><?php  echo date('Y-m-d',$order['sendtime'])?><br/><?php  echo date('H:i:s',$order['sendtime'])?><?php  } ?></div>
            </li>
            <li <?php  if($order['status']>=3) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number" >4</div>
            <div class="ui-step-title">订单完成</div>
            <div class="ui-step-meta"><?php  if(3<=$order['status']) { ?><?php  echo date('Y-m-d',$order['finishtime'])?><br/><?php  echo date('H:i:s',$order['finishtime'])?><?php  } ?></div>
            </li>
        </ul>
    </div>
    <?php  } ?>
    <form class="form-horizontal form" action="" method="post">
        <input type="hidden" name="id" value="<?php  echo $order['id'];?>" />
        <input type="hidden" name="dispatchid" value="<?php  echo $dispatch['id'];?>" />
        <div  class='row order-container'>
            <div class="order-container-left"  style="border-right: 1px solid #efefef;">
                <div class='panel-body' >
                    <div class="form-group" style='padding:0 10px;'>
                        <table class='ordertable' style='table-layout:fixed'>
                            <tr>
                                <td style='width:80px'>订单编号：</td>
                                <td><?php  echo $order['orderno'];?></td>
                            </tr>
                            <tr>
                                <td>订单金额：</td>
                                <td>¥ <?php  echo number_format($order['price']+$order['freight'],2)?> &nbsp;&nbsp;
                                </td>
                            </tr>
                            <?php  if(!empty($coupon)) { ?>
                            <tr>
                                <td>优惠券：</td>
                                <td><a href="<?php  echo webUrl('sale/coupon/edit',array('id'=>$coupon['id']))?>" target='_blank'><?php  echo $coupon['couponname'];?></a> &nbsp;&nbsp;<a data-toggle='popover' data-html='true' data-placement='right'
                                                                                                                                                                          data-content="<table style='width:100%;'>
                    <tr>
                        <td style='border:none;text-align:right;'>优惠方式：</td>
                        <td style='border:none;text-align:right;'>
                        <?php  if($coupon['backtype']==0) { ?>
                            立减 <?php  echo $coupon['deduct'];?> 元
                        <?php  } else if($coupon['backtype']==1) { ?>
                            打 <?php  echo $coupon['discount'];?> 折
                        <?php  } else if($coupon['backtype']==2) { ?>
                            <?php  if($coupon['backmoney']>0) { ?>返 <?php  echo $coupon['backmoney'];?> 余额<?php  } ?>
                            <?php  if($coupon['backcredit']>0) { ?>返 <?php  echo $coupon['backcredit'];?> 积分<?php  } ?>
                            <?php  if($coupon['backredpack']>0) { ?>返 <?php  echo $coupon['backredpack'];?> 红包<?php  } ?>
                        <?php  } ?>
                        </td>
                    </tr>
                    <?php  if($coupon['backtype']==2) { ?>
                        <tr>
                            <td style='border:none;text-align:right;'>返利方式：</td>
                            <td style='border:none;text-align:right;'>
                            <?php  if($order['backwhen']==0) { ?>
                                交易完成后(过退款期限)
                            <?php  } else if($order['backwhen']==1) { ?>
                                订单完成后(收货后)
                            <?php  } else { ?>
                                订单付款后
                            <?php  } ?>
                            </td>
                        </tr>
                        <tr>
                            <td style='border:none;text-align:right;'>返利情况：</td>
                            <td style='border:none;text-align:right;'>
                            <?php  if(empty($coupon['back'])) { ?>
                                未返利
                            <?php  } else { ?>
                                已返利
                            <?php  } ?>
                            </td>
                        </tr>
                        <?php  if(!empty($coupon['back'])) { ?>
                        <tr>
                            <td style='border:none;text-align:right;'>返利时间：</td>
                            <td style='border:none;text-align:right;'><?php  echo date('Y-m-d H:i',$coupon['backtime'])?></td>
                        </tr>
                        <?php  } ?>
                    <?php  } ?>
                </table>
    "><i class='fa fa-question-circle'></i></a></td>
                            </tr>
                            <?php  } ?>
                            <tr>
                                <td style='width:80px'>付款方式：</td>
                                <td>
                                    <?php  if($order['pay_type'] == '') { ?>
                                    未付款
                                    <?php  } else { ?>
                                    <?php  if($order['pay_type'] == 'credit') { ?>
                                    余额支付
                                    <?php  } else if($order['pay_type'] == 'wechat') { ?>
                                    微信支付
                                    <?php  } else if($order['pay_type'] == 'alipay') { ?>
                                    支付宝支付
                                    <?php  } else if($order['pay_type'] == 'system') { ?>
                                    <span class='label label-default'>系统虚拟</span>
                                    <?php  } else { ?>
                                    其他方式
                                    <?php  } ?>
                                    <?php  } ?>
                                    <?php  if($order['refundtime']) { ?>
                                    <span class='label label-default'>维权已完成</span>
                                    <?php  } ?>
                                </td>
                            </tr>
                            <?php  if($order['pay_type'] == 'system') { ?>
                            <?php  } else { ?>
                            <tr>
                                <td>买家：</td>
                                <td><a href="<?php  echo webUrl('member/list/detail',array('id'=>$member['id']))?>" target='_blank'><?php  echo $member['nickname'];?></a> &nbsp;&nbsp;<a data-toggle='popover' data-trigger="hover" data-html='true' data-placement='right'
                                                                                                                                                                          data-content="<table style='width:100%;'>
                    <tr>
                        <td  style='border:none;text-align:right;' colspan='2'><img src='<?php  echo $member['avatar'];?>' onerror=this.src='../addons/ewei_shopv2/plugin/groups/template/mobile/default/images/avatar.png' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' /></td>
                    </tr>
                    <tr>
                        <td  style='border:none;text-align:right;'>ID：</td>
                        <td  style='border:none;text-align:right;'><?php  echo $member['id'];?></td>
                    </tr>
                    <tr>
                        <td  style='border:none;text-align:right;'>昵称：</td>
                        <td  style='border:none;text-align:right;'><?php  echo $member['nickname'];?></td>
                    </tr>
                    <?php  if($member['realname']) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>姓名：</td>
                        <td  style='border:none;text-align:right;'><?php  echo $member['realname'];?></td>
                    </tr>
                    <?php  } ?>
                    <?php  if($member['mobile']) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>手机号：</td>
                        <td  style='border:none;text-align:right;'><?php  echo $member['mobile'];?></td>
                    </tr>
                    <?php  } ?>
                    <?php  if($member['weixin']) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>微信号：</td>
                        <td  style='border:none;text-align:right;'><?php  echo $member['weixin'];?></td>
                    </tr>
                    <?php  } ?>
                    </table>
    "><i class='icow icow-help' style="font-size: 13px;color: #2f3434;margin-left: -3px"></i></a></td>
                            </tr>
                            <?php  } ?>
                            <?php  if(!empty($order['invoicename'])) { ?>
                            <tr>
                                <td style='width:80px'>发票抬头：</td>
                                <td><?php  echo $order['invoicename'];?></td>
                            </tr>
                            <?php  } ?>
                            <tr>
                                <td style='width:80px'>配送方式：</td>
                                <td>
                                    <?php  if($order['isverify'] == 1) { ?>
                                    线下核销
                                    <?php  } else if(!empty($order['addressid'])) { ?>
                                    快递
                                    <?php  } else if(!empty($order['isvirtualsend']) || !empty($order['virtual'])) { ?>
                                    自动发货<?php  if(!empty($order['isvirtualsend'])) { ?>(虚拟物品)<?php  } else { ?>(虚拟卡密)<?php  } ?>
                                    <?php  } else if($order['dispatchtype']) { ?>
                                    自提
                                    <?php  } else { ?>
                                    其他
                                    <?php  } ?>
                                </td>
                            </tr>
                            <?php  if($order['isverify']==1) { ?>
                            <tr>
                                <td style='width:80px'>核销方式：</td>
                                <td><?php  if($order['verifytype']==0) { ?>
                                    整单核销
                                    <?php  } else if($order['verifytype']==1) { ?>
                                    按次核销
                                    <?php  } else if($order['verifytype']==2) { ?>
                                    按消费码核销
                                    <?php  } ?>
                                </td>
                            </tr>
                            <?php  if($order['verifytype']==0) { ?>
                            <tr>
                                <td style='width:80px'>消费码：</td>
                                <td><?php  echo $order['verifycode'];?></td>
                            </tr>
                            <?php  if($verify['isverify']) { ?>
                            <tr>
                                <td style='width:80px'>核销时间：</td>
                                <td><?php  echo date('Y-m-d H:i:s', $verify['verifytime'])?></td>
                            </tr>
                            <?php  if(!empty($saler)) { ?>
                            <tr>
                                <td style='width:80px'>核销人：</td>
                                <td><?php  echo $saler['nickname'];?>( <?php  echo $saler['salername'];?> )</td>
                            </tr>
                            <?php  } ?>
                            <?php  if(!empty($store)) { ?>
                            <tr>
                                <td style='width:80px'>核销门店：</td>
                                <td><?php  echo $store['storename'];?></td>
                            </tr>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  } else if($order['verifytype']==1) { ?>
                            <tr>
                                <td style='width:80px'>消费记录：</td>
                                <td>
                                    <a href='javascript:;' onclick='$("#verify-modal").modal()'><i class="fa fa-question-circle"></i> 查看</a>
                                    <div id="verify-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" style='width:850px'>
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                    <h3>核销记录</h3>
                                                </div>
                                                <div class="modal-body" >
                                                    <div style='max-height:500px;overflow:auto;min-width:800px;'>
                                                        <table style='width:100%;' class='table'>
                                                            <tr><td style='width:150px'>时间</td><td style='width:100px'>核销员</td><td>门店</td></tr>
                                                            <?php  if(is_array($verifyinfo)) { foreach($verifyinfo as $v) { ?>
                                                            <?php  if(!empty($v['id'])) { ?>
                                                            <tr><td><?php  echo date('Y-m-d H:i',$v['verifytime'])?></td><td><?php  echo $v['salername'];?><br/><small><?php  echo $v['nickname'];?></small></td><td><?php  echo $v['storename'];?></td></tr>
                                                            <?php  } ?>
                                                            <?php  } } ?>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php  } else if($order['verifytype']==2) { ?>
                            <tr>
                                <td style='width:80px'>消费码：</td>
                                <td><?php  echo $order['verifycode'];?></td>
                            </tr>
                            <?php  if(is_array($verifyinfo)) { foreach($verifyinfo as $v) { ?>
                            <?php  if($v['verified']) { ?>
                            <tr>
                                <td style='width:80px'><?php  echo $v['verifycode'];?></td>
                                <td>
                                    <a data-toggle='popover' data-html='true' data-placement='right'
                                       data-content="<table style='width:100%;'>
                                <tr>
                                    <td  style='border:none;text-align:right;'>核销员：</td>
                                    <td  style='border:none;text-align:right;'><?php  echo $v['salername'];?>/<?php  echo $v['nickname'];?></td>
                                </tr>
                                <tr>
                                    <td  style='border:none;text-align:right;'>门店：</td>
                                    <td  style='border:none;text-align:right;'><?php  echo $v['storename'];?></td>
                                </tr>
                                <tr>
                                    <td  style='border:none;text-align:right;'>时间：</td>
                                    <td  style='border:none;text-align:right;'><?php  echo date('Y-m-d H:i',$v['verifytime'])?></td>
                                </tr>
                                </table>" ><i class="fa fa-question-circle"></i> 使用信息</a>
                                </td>
                            </tr>
                            <?php  } ?>
                            <?php  } } ?>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  if(!empty($order['addressid'])) { ?>
                            <tr>
                                <td style='width:80px'>收货人：</td>
                                <td style='word-break: break-all;white-space: normal'>
                                    <?php  echo $user['realname'];?><br /><?php  echo $user['mobile'];?><br /><?php  echo $user['address'];?> <a class='js-clip' data-url="<?php  echo $user['address'];?>, <?php  echo $user['realname'];?>, <?php  echo $user['mobile'];?>">[复制]</a></td>
                            </tr>
                            <?php  } else if($order['isverify']==1 || !empty($order['virtual']) ||!empty($order['isvirtual'])) { ?>
                            <?php  if($order['status']>=2 && !empty($order['virtual']) ) { ?>
                            <tr>
                                <td style='width:80px'>发货信息：</td>
                                <td style='word-break: break-all;white-space: normal'><?php  echo str_replace("\n","<br/>", $order['virtual_str'])?></td>
                            </tr>
                            <?php  } ?>
                            <tr>
                                <td style='width:80px'>联系人：</td>
                                <td style='word-break: break-all;white-space: normal'><?php  echo $order['realname'];?>, <?php  echo $order['mobile'];?></td>
                            </tr>
                            <?php  } else { ?>
                            <?php  if($order['pay_type'] == 'system') { ?><?php  } else { ?>
                            <tr>
                                <td style='width:80px'>自提码：</td>
                                <td><?php  echo $order['verifycode'];?></td>
                            </tr>
                            <tr>
                                <td style='width:80px'>自提人：</td>
                                <td style='word-break: break-all;white-space: normal'><?php  echo $user['address'];?>,  <?php  echo $user['realname'];?>, <?php  echo $user['mobile'];?></td>
                            </tr>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  if(!empty($order['message'])) { ?>
                            <tr>
                                <td style='width:80px'>买家备注：</td>
                                <td><?php  echo $order['message'];?></td>
                            </tr>
                            <?php  } ?>
                            <?php  if(!empty($order['addressid'])) { ?>
                            <tr>
                                <td style='width:80px'></td>
                                <td style='word-break: break-all;white-space: normal'>
                                    <?php if(cv('groups.order.changeaddress')) { ?>
                                    <a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/changeaddress', array('id' => $order['id']))?>">编辑收货信息</a>
                                    <?php  } ?>
                                </td>
                            </tr>
                            <?php  } ?>
                        </table>
                        <?php  if(!empty($order_data)) { ?>
                        <table class='ordertable' style='table-layout:fixed;border-top:1px dotted #ccc'>
                            <tr>
                                <td style='width:120px'><h4>统一下单信息</h4></td>
                                <td></td>
                            </tr>
                            <?php  $datas = $order_data?>
                            <?php  $ii = 0;?>
                            <?php  if(is_array($order_fields)) { foreach($order_fields as $key => $value) { ?>
                            <tr <?php  if($ii>1) { ?>class="diymore2" style="display:none;"<?php  } ?>>
                                <td style='width:80px'><?php  echo $value['tp_name']?>：</td>
                                <td>
                                    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('diyform/diyform', TEMPLATE_INCLUDEPATH)) : (include template('diyform/diyform', TEMPLATE_INCLUDEPATH));?>
                                </td>
                            </tr>
                            <?php  if($ii==2) { ?>
                            <tr class="diymore22">
                                <td colspan="2"><a href="javascript:void(0);" style="padding-right: 100px;" id="showdiymore2">查看完整信息</a></td>
                            </tr>
                            <?php  } ?>
                            <?php  $ii++;?>
                            <?php  } } ?>
                        </table>
                        <?php  } ?>
                    </div>
                </div>
            </div>
            <div class="order-container-static" >
                <div class='panel-body' style='height:380px;' >
                    <div class=' status'>
                        <span class="">订单状态： </span>
                                <?php  if($order['status'] == 0) { ?>
                                <span class="text-warning font20">待付款</span>
                                <?php  } ?>
                                <?php  if($order['status'] == 1) { ?>
                                <span class="text-warning font20">待发货</span>
                                <?php  } ?>
                                <?php  if($order['status'] == 2) { ?><span class="text-warning font20">待收货</span><?php  } ?>
                                <?php  if($order['status'] == 3) { ?><span class="text-success font20">交易完成</span><?php  } ?>
                                <?php  if($order['status'] == -1) { ?>
                                <span class="text-default font20">已关闭</span>
                                <?php  } ?>
                                <span class="form-control-static">
                                    <?php  if($order['status'] == 0) { ?>
                                    （等待买家付款）
                                    <?php  } ?>
                                    <?php  if($order['status'] == 1) { ?>
                                    <!--（买家已经付款，请商家尽快发货）-->
                                    <?php  } ?>
                                    <?php  if($order['status'] == 1 && $order['success']==1) { ?>
                                    （买家已经付款，请商家尽快发货）
                                    <?php  } ?>
                                    <?php  if($order['status'] == 2) { ?>
                                    （ 商家已发货，等待买家收货并交易完成）
                                    <?php  } ?>
                                    <?php  if($order['status'] == -1) { ?>
                                    <?php  if(!empty($refund) && $refund['status']==1) { ?>
                                    <span class="label label-default">已退款</span> <?php  if(!empty($refund['refundtime'])) { ?>退款时间: <?php  echo date('Y-m-d H:i:s',$refund['refundtime'])?><?php  } ?>
                                    <?php  } ?>
                                    <?php  } ?>
                                </span>
                    </div>
                    <div class="">
                            <?php  if(!empty($order['expresssn']) && $order['status']>=2 && !empty($order['addressid'])) { ?>
                        <ul>
                            <li class="text">
                                快递公司: <span class="text-default"><?php  if(empty($order['expresscom'])) { ?>其他快递<?php  } else { ?><?php  echo $order['expresscom'];?><?php  } ?></span>
                            </li>
                            <li class="text">
                                快递单号: <span class="text-default"><?php  echo $order['expresssn'];?></span> <a class='op text-primary' data-toggle="ajaxModal" href="<?php  echo webUrl('util/express', array('id' => $order['id'],'express'=>$order['express'],'expresssn'=>$order['expresssn']))?>">查看物流</a>
                            </li>
                            <li class="text">
                                发货时间: <span class="text-default"><?php  echo date('Y-m-d H:i:s', $order['sendtime'])?></span>
                            </li>
                        </ul>
                            <?php  } ?>
                    </div>
                    <div class="">
                            <p class="form-control-static ops">
                                <?php  if($order['status'] == 1 && ($order['success'] == 1 || $order['groupnum'] ==1)) { ?>
                                <?php if(cv('groups.order.send')) { ?>
                                <a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/send', array('id' => $order['id']))?>">确认发货</a>
                                <?php  } ?>
                                <?php  } ?>
                                <?php  if($order['status'] == 2 && !empty($order['pay_type'])) { ?>
                                <?php if(cv('groups.order.sendcancel')) { ?>
                                <a class="text-primary" data-toggle='ajaxModal'  href="<?php  echo webUrl('groups/order/sendcancel', array('id' => $order['id']))?>" >取消发货</a>
                                <?php  } ?>
                                <?php  } ?>
                                <?php  if($order['status'] == 0) { ?>
                                <?php if(cv('groups.order.pay')) { ?>
                                <a class="btn btn-primary btn-xs" data-toggle="ajaxPost" href="<?php  echo webUrl('groups/order/pay', array('id' => $order['id']))?>" data-confirm="确认此订单已付款吗？">确认付款</a>
                                <?php  } ?>
                                <?php  } ?>
                                <?php if(cv('groups.order.remarksaler')) { ?>
                                <a  data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/remarksaler', array('id' => $order['id']))?>" <?php  if(!empty($order['remark'])) { ?>style='color:red'<?php  } ?> >
                                <?php  if(!empty($order['remark'])) { ?>查看备注<?php  } else { ?>添加备注<?php  } ?></a>
                                <?php  } ?>
                            </p>
                    </div>
                    <?php  if($order['status'] >0) { ?>
                    <div class='order-tips'>
                        <span class='order-tips-title'>友情提醒：</span>
                        <?php  if($order['status'] == 0) { ?>
                        <?php  if($order['paytype']==3) { ?>
                        <span class=' order-tips-row'>订单为货到付款，请您务必联系买家确认后再进行发货</span>
                        <?php  } else { ?>
                        <span class=' order-tips-row'>您可以联系买家进行付款，否则订单会根据设置自动关闭</span>
                        <?php  } ?>
                        <?php  } ?>
                        <?php  if($order['status'] == 1) { ?>
                        <span class=' order-tips-row'>如果无法进行发货，请及时联系买家进行妥善处理;</span>
                        <?php  } ?>
                        <?php  if($order['status'] == 2) { ?>
                        <span class=' order-tips-row'>请及时关注物流状态，确保买家及时收到商品;</span>
                        <span class=' order-tips-row'>如果买家未收到货物或有退换货请求，请及时联系买家妥善处理</span>
                        <?php  } ?>
                        <?php  if($order['status']==3) { ?>
                        <span class=' order-tips-row'>交易成功，如买家有售后申请，请与买家进行协商，妥善处理</span>
                        <?php  } ?>
                    </div>
                    <?php  } ?>
                </div>
            </div>
        </div>
        <br />
        <h3 class="order-title">商品信息</h3>

                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr class="trorder">
                        <th style="width:70px">商品</th>
                        <th></th>
                        <th style="text-align: center;">单价(元)</th>
                        <th style="text-align: center;">数量</th>
                        <th style="text-align: center;">金额(元)</th>
                        <?php  if(!empty($goods['diyformdata'])) { ?>
                        <th style="width:80px;"></th>
                        <?php  } ?>
                        <th style="">规格</th>
                    </tr>
                    </thead>
                    <tr class="trorder">
                        <td class='full'>
                            <img src="<?php  echo tomedia($order['thumb'])?>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" style='width:50px;height:50px;border:1px solid #ccc; padding:1px;'>
                        </td>
                        <td>
                            <?php  echo $order['title'];?>
                        </td>
                        <td style="text-align: center;">
                            <?php  if($order['is_team']==1) { ?>
                            ¥ <?php  echo $order['groupsprice'];?>
                            <?php  } else { ?>
                            ¥ <?php  echo $order['singleprice'];?>
                            <?php  } ?>
                        </td>
                        <td style="text-align: center;">1</td>
                        <td style='text-align: center;'>
                            ¥ <?php  echo number_format($order['price'] + $order['freight'],2)?>
                        </td>
                        <?php  if(!empty($goods['diyformdata'])) { ?>
                        <td>
                            <a href='javascript:;' class=btn-xs' hide="1"  data="<?php  echo $goods['id'];?>" onclick="showDiyInfo(this)">自定义信息</a>
                        </td>
                        <?php  } ?>
                        <td style="padding: 10px 20px">
                            <p style="white-space:normal;">
                                规格：<?php  if(!empty($order['optiontitle'])) { ?><span class="label label-primary" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php  echo $goods['optionname'];?>"><?php  echo $order['optiontitle'];?></span><?php  } else { ?>无<?php  } ?>
                            </p>
                        </td>
                    </tr>
                    <?php  if(!empty($goods['diyformdata'])) { ?>
                    <tr>
                        <td colspan='5'>
                            <table class='ordertable' style='table-layout:fixed;display: none;' id="diyinfo_<?php  echo $goods['id'];?>">
                                <?php  $datas = $goods['diyformdata']?>
                                <?php  if(is_array($goods['diyformfields'])) { foreach($goods['diyformfields'] as $key => $value) { ?>
                                <tr>
                                    <td style='width:80px'><?php  echo $value['tp_name']?>：</td>
                                    <td>
                                        <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('diyform/diyform', TEMPLATE_INCLUDEPATH)) : (include template('diyform/diyform', TEMPLATE_INCLUDEPATH));?>
                                    </td>
                                </tr>
                                <?php  } } ?>
                            </table>
                        </td>
                    </tr>
                    <?php  } ?>
                    <tfoot>
                    <tr class="trorder">
                        <td colspan="5">
                            <div class="price">
                                <p> <span class="price-inner">  订单金额：</span><span style="font-weight: bold"> ¥ <?php  echo number_format($order['price']+$order['freight'],2)?></span></p>
                                <p><span class="price-inner">商品小计：</span><span  style="font-weight: bold">¥ <?php  echo number_format($order['price'] + $order['discount'] ,2)?></span></p>
                                <p><span class="price-inner">运费：</span><span>¥ <?php  echo number_format( $order['freight'],2)?></span></p>
                                <?php  if($order['discount']>0) { ?>
                                <p><span class="price-inner">团长优惠：</span><span class="text-danger">- ¥<?php  echo $order['discount'];?></span></p>
                                <?php  } ?>
                                <?php  if($order['discountprice']>0) { ?>
                                <p><span class="price-inner">会员折扣：</span><span class="text-danger">-¥ <?php  echo number_format( $order['discountprice'],2)?></span></p>
                                <?php  } ?>
                                <?php  if($order['creditmoney']>0) { ?>
                                <p><span class="price-inner">积分抵扣：</span><span class="text-danger">-¥ <?php  echo number_format( $order['creditmoney'],2)?></span></p>
                                <?php  } ?>
                                <?php  if($order['deductcredit2']>0) { ?>
                                <p><span class="price-inner">余额抵扣：</span ><span class="text-danger">-¥ <?php  echo number_format( $order['deductcredit2'],2)?></span></p>
                                <?php  } ?>
                                <?php  if($order['deductenough']>0) { ?>
                                <p><span class="price-inner">满额立减：</span><span class="text-danger">-¥ <?php  echo number_format( $order['deductenough'],2)?></span></p>
                                <?php  } ?>
                                <?php  if($order['couponprice']>0) { ?>
                                <p><span class="price-inner">优惠券优惠：</span><span class="text-danger">-¥ <?php  echo number_format( $order['couponprice'],2)?></span></p>
                                <?php  } ?>
                                <?php  if($order['isdiscountprice']>0) { ?>
                                <p><span class="price-inner">促销优惠：</span><span class="text-danger">-¥ <?php  echo number_format( $order['isdiscountprice'],2)?></span></p>
                                <?php  } ?>
                                <?php  if(intval($order['changeprice'])!=0) { ?>
                                <p><span class="price-inner">卖家改价：</span><span style='<?php  if(0<$order['changeprice']) { ?>color:green<?php  } else { ?>color:red<?php  } ?>'><?php  if(0<$order['changeprice']) { ?>+<?php  } else { ?>-<?php  } ?>¥ <?php  echo number_format(abs($order['changeprice']),2)?></span></p>
                                <?php  } ?>
                                <?php  if(intval($order['changedispatchprice'])!=0) { ?>
                                <p><span class="price-inner">卖家改运费：</span><span style='<?php  if(0<$order['changedispatchprice']) { ?>color:green<?php  } else { ?>color:red<?php  } ?>'><?php  if(0<$order['changedispatchprice']) { ?>+<?php  } else { ?>-<?php  } ?>¥ <?php  echo abs($order['changedispatchprice'])?></span></p>
                                <?php  } ?>
                                <p><span class="price-inner">实付款：</span><span style="font-size: 14px;font-weight: bold;color: #e4393c">¥ <?php  echo number_format($order['price'] - $order['creditmoney'] + $order['freight'],2)?></span></p>
                            </div>

                        </td>
                    </tr>
                    </tfoot>

                </table>
    </form>
</div>
<script language='javascript'>
    $(function () {
        $("#showdiymore1").click(function () {
            $(".diymore1").show();
            $(".diymore11").hide();
        });
        $("#showdiymore2").click(function () {
            $(".diymore2").show();
            $(".diymore22").hide();
        });
    });
    function showDiyInfo(obj){
        var data = $(obj).attr('data');
        var id = "diyinfo_" + data;
        var hide = $(obj).attr('hide');
        if(hide=='1'){
            $("#"+id).slideDown();
        }
        else{
            $("#"+id).slideUp();
        }
        $(obj).attr('hide',hide=='1'?'0':'1');
    }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>