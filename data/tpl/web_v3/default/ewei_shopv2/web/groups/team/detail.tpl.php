<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style>
   table td{
       border:none !important;
    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">拼团详情</span>
</div>
<div class="page-content">
<div class="tab-content">
    <?php  if($teaminfo['success']!=-1 && $teaminfo['status'] > 0 ) { ?>
    <div class="step-region" >
        <ul class="ui-step ui-step-3" >
            <li <?php  if(0<=$teaminfo['status']) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number" >1</div>
            <div class="ui-step-title" >团长开团</div>
            <div class="ui-step-meta" ><?php  if(0<=$teaminfo['status']) { ?><?php  echo date('Y-m-d',$teaminfo['paytime'])?><br/><?php  echo date('H:i:s',$teaminfo['paytime'])?><?php  } ?></div>
            </li <?php  if(1<=$teaminfo['status']) { ?>class="ui-step-done"<?php  } ?>>
            <li <?php  if(!empty($teaminfo['paytime'])) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number">2</div>
            <div class="ui-step-title">拼团进行中</div>
            <div class="ui-step-meta"><?php  if($teaminfo['status']>0 && $teaminfo['success']==0) { ?>正在召集小伙伴<?php  } ?></div>
            </li>

            <li <?php  if($teaminfo['status'] >= 1 && $teaminfo['success'] == 1 ) { ?>class="ui-step-done"<?php  } ?>>
            <div class="ui-step-number" >3</div>
            <div class="ui-step-title"><?php  if($teaminfo['success'] == 1) { ?>拼团成功<?php  } else if($teaminfo['success'] == -1) { ?>拼团失败<?php  } else { ?>拼团完成<?php  } ?></div>
            <div class="ui-step-meta"></div>
            </li>
        </ul>
    </div>
    <?php  } ?>
    <?php  if(!empty($teaminfo['gid'])) { ?>
    <div class="panel panel-default">
        <div class="panel-default">
            <div class="table-responsive">
                <table class="table table-hover" style="margin:0;">
                    <thead>
                    <tr>
                        <th style="width:80px;">商品图</th>
                        <th style="width:180px;">商品名</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr data-toggle="popover" data-trigger="hover" data-placement="left" class="js-goods-img">
                        <td>
                            <img class="scrollLoading" src="<?php  echo tomedia($teaminfo['thumb'])?>" height="50" width="50" style="border:1px solid #ddd;" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'">
                        </td>
                        <td class="line-feed"><?php  echo $teaminfo['title'];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php  } else { ?>
    <div style="padding:0 0 10px 0;"><strong>商品不存在或已删除</strong></div>
    <?php  } ?>
    <div class="panel panel-default">
        <div class="panel-body col-xs-6 col-sm-6 col-md-6">
            <div class="form-group" style="margin:0;height:30px;">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">团状态:</label>
                <div class="col-sm-9 col-xs-12">
                    <p class="">
                        <?php  if($teaminfo['success']== 0 && $teaminfo['status']> 0) { ?><span class="label label-info">拼团中</span><?php  } ?>
                        <?php  if($teaminfo['success']== 1) { ?><span class="label label-success">拼团成功</span><?php  } ?>
                        <?php  if($teaminfo['success']== -1) { ?><span class="label label-warning">拼团失败</span><?php  } ?>
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="form-group" style="margin:0;height:30px;">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">团ID :</label>
                <div class="col-sm-9 col-xs-12">
                    <p class=""><?php  echo $teaminfo['teamid'];?></p>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="form-group" style="margin:0;height:30px;">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">团购进度 :</label>
                <div class="col-sm-9 col-xs-12">
                    <p class=""><span style="color:red;"><?php  echo $total;?></span>/<?php  echo $teaminfo['groupnum'];?></p>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div class="panel-body col-xs-6 col-sm-6 col-md-6">
            <div class="form-group" style="margin:0;height:60px;">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">团长头像:</label>
                <div class="col-sm-9 col-xs-12">
                    <p class="">
                        <img class="scrollLoading" src="<?php  echo tomedia($member['avatar'])?>" onerror="this.src='../addons/ewei_shopv2/plugin/groups/template/mobile/default/images/avatar.png'"
                             height="50" width="50" style="border:1px solid #ddd;">
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="form-group" style="margin:0;height:30px;">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">团长昵称 :</label>
                <div class="col-sm-9 col-xs-12">
                    <p class=""><?php  echo $member['nickname'];?></p>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="form-group" style="margin:0;height:30px;">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">团长优惠 :</label>
                <div class="col-sm-9 col-xs-12">
                    <p class="text-danger">
                        <?php  if($teaminfo['discount']) { ?>- ¥<?php  echo $teaminfo['discount'];?><?php  } else { ?>暂无优惠<?php  } ?>
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class='table table-responsive' style='table-layout: fixed;overflow: hidden'>
                <tr style='background:#f8f8f8'>
                    <td style='width:60px;border-left:1px solid #f2f2f2;'>买家</td>
                    <td style='width:150px;'></td>
                    <td style='width:100px;text-align: center;'>支付/配送/退款</td>
                    <td style='width:100px;text-align: center;'>金额</td>
                    <td style='width:100px;text-align: center;'>操作</td>
                    <td style='width:90px;text-align: center'>状态</td>
                </tr>
                <?php  if(is_array($orders)) { foreach($orders as $item) { ?>
                <tr ><td colspan='6' style='height:20px;padding:0;border-top:none;'>&nbsp;</td></tr>
                <tr class='trorder'>
                    <td colspan='6' >
                       <span style="font-weight: bold"><?php  echo date('Y-m-d',$item['createtime'])?> <?php  echo date('H:i:s',$item['createtime'])?></span> 订单编号:  <?php  echo $item['orderno'];?>
                    </td>
                </tr>
                <tr class='trbody'>
                    <td style='overflow:hidden;'>
                        <img src="<?php  echo tomedia($item['avatar'])?>" onerror="this.src='../addons/ewei_shopv2/plugin/groups/template/mobile/default/images/avatar.png'" style='width:50px;height:50px;border:1px solid #ccc; padding:1px;'>
                    </td>
                    <td style='text-align: left;overflow:hidden;border-left:none;'>
                        <?php  if($item['pay_type']=='system') { ?>系统虚拟<?php  } ?>
                        <a href="<?php  echo webUrl('member/list/detail',array('id'=>$item['mid']))?>"> <?php  echo $item['nickname'];?></a>
                        <br/><?php  echo $item['addressdata']['mobile'];?>
                    </td>
                    <td style='text-align:center;' >
                        <?php  if($item['pay_type']=='wechat') { ?>
                        <span> <i class="icow icow-weixinzhifu text-success" style="font-size: 17px"></i>微信支付</span>
                        <?php  } else if($item['pay_type']=='credit') { ?>
                        <span> <i class="icow icow-yue text-warning" style="font-size: 17px;"></i><span>余额支付</span></span>
                        <?php  } else if($item['pay_type']=='alipay') { ?>
                        <span><i class="icow icow-zhifubaozhifu text-primary" style="font-size: 17px"></i>支付宝支付</span>
                        <?php  } else if($item['pay_type']=='other') { ?>
                        <span> <i class="text-primary icow icow-icon" style="font-size: 17px"></i>其他</span>
                        <?php  } else if($item['pay_type']=='system') { ?>
                        <span> <i class="icow icow-kuajingzhifuiconfukuan text-danger" style="font-size: 17px"></i>系统虚拟</span>
                        <?php  } else if($item['pay_type']=='') { ?>
                        <span> <i class="icow icow-shibai text-warning" style="font-size: 17px;"></i><span>未支付</span></span>
                        <?php  } ?>
                        <br />
                        <span style="margin-top:5px;display:block;">
                            <?php  if($item['isverify'] == 1) { ?>
                            线下核销
                            <?php  } else if(!empty($item['addressid'])) { ?>
                            快递
                            <?php  } else if(!empty($item['isvirtualsend']) || !empty($item['virtual'])) { ?>
                            自动发货<?php  if(!empty($item['isvirtualsend'])) { ?>(虚拟物品)<?php  } else { ?>(虚拟卡密)<?php  } ?>
                            <?php  } else if($item['dispatchtype']) { ?>
                            自提
                            <?php  } else { ?>
                            其他
                            <?php  } ?>
                        </span>
                        <?php  if($item['refundtime']) { ?>
                        <span class='label label-default'>已退款</span>
                        <?php  } ?>
                    </td>
                    <td style='text-align:center' >
                        ¥ <?php  echo number_format($item['price']+$item['freight'],2)?>
                        <a data-toggle='popover' data-html='true' data-placement='right' data-trigger="hover" data-content="<table style='width:100%;'>
                <tr>
                    <td  style='border:none;text-align:right;'>商品小计：</td>
                    <td  style='border:none;text-align:right;'>¥ <?php  echo number_format( $item['price'] + $item['discount'] ,2)?></td>
                </tr>
                <tr>
                    <td  style='border:none;text-align:right;'>运费：</td>
                    <td  style='border:none;text-align:right;;'>¥ <?php  echo number_format( $item['freight'],2)?></td>
                </tr>
                <?php  if($item['discount']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>团长优惠：</td>
                    <td class='text-danger'  style='border:none;text-align:right;'>- ¥<?php  echo $item['discount'];?></td>
                </tr>
                <?php  } ?>
                <?php  if($item['discountprice']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>会员折扣：</td>
                    <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['discountprice'],2)?></td>
                </tr>
                <?php  } ?>
                <?php  if($item['creditmoney']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>积分抵扣：</td>
                    <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['creditmoney'],2)?></td>
                </tr>
                <?php  } ?>
                <?php  if($item['deductcredit2']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>余额抵扣：</td>
                    <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['deductcredit2'],2)?></td>
                </tr>
                <?php  } ?>
                <?php  if($item['deductenough']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>满额立减：</td>
                    <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['deductenough'],2)?></td>
                </tr>
                <?php  } ?>
                <?php  if($item['couponprice']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>优惠券优惠：</td>
                    <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['couponprice'],2)?></td>
                </tr>
                <?php  if($item['isdiscountprice']>0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>促销优惠：</td>
                    <td class='text-danger'  style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['isdiscountprice'],2)?></td>
                </tr>
                <?php  } ?>
                <?php  } ?>
                <?php  if(intval($item['changeprice'])!=0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>卖家改价：</td>
                    <td  style='border:none;text-align:right;;'>
                    <span class='<?php  if(0<$item['changeprice']) { ?>success<?php  } else { ?>danger<?php  } ?>'>
                    <?php  if(0<$item['changeprice']) { ?>+<?php  } else { ?>-<?php  } ?>¥ <?php  echo number_format(abs($item['changeprice']),2)?>
                    </span>
                    </td>
                </tr>
                <?php  } ?>
                <?php  if(intval($item['changedispatchprice'])!=0) { ?>
                <tr>
                    <td  style='border:none;text-align:right;'>卖家改运费：</td>
                    <td  style='border:none;text-align:right;;'>
                        <span class='<?php  if(0<$item['changedispatchprice']) { ?>success<?php  } else { ?>danger<?php  } ?>'>
                        <?php  if(0<$item['changedispatchprice']) { ?>+<?php  } else { ?>-<?php  } ?>¥ <?php  echo abs($item['changedispatchprice'])?>
                        </span>
                    </td>
                </tr>
                <?php  } ?>
                <tr>
                    <td style='border:none;text-align:right;'>应收款：</td>
                    <td  style='border:none;text-align:right;font-weight:bold'>¥ <?php  echo number_format($item['price']-$item['creditmoney']+$item['freight'],2)?></td>
                </tr>
            </table>">
                            <i class='fa fa-question-circle'></i></a>
                        <br/>(含运费:¥ <?php  echo number_format( $item['freight'],2)?>)
                    </td>
                    <td style='text-align:center' >
                        <a class='op text-primary'  href="<?php  echo webUrl('groups/order/detail', array('orderid' => $item['id']))?>" >查看详情</a>
                    </td>
                    <td  class='ops' style='line-height:20px;text-align:center' >
                        <?php  if($item['deleted'] == 1) { ?>
                        <span class='text-default'>用户已删除</span>
                        <?php  } else { ?>
                            <?php  if($item['status'] == 0) { ?><span class='text-danger'>待付款</span><?php  } ?>
                            <?php  if($item['status'] == 1 && $item['success'] == 0) { ?><span class='text-navy'>已付款</span><?php  } ?>
                            <?php  if($item['status'] == 1 && $item['success'] == -1) { ?><span class='text-navy'>待退款</span><?php  } ?>
                            <?php  if($item['status'] == 1 && $item['success'] == 1) { ?><span class='text-info'>待发货</span><?php  } ?>
                            <?php  if($item['status'] == 2) { ?><span class='text-warning'>待收货</span><?php  } ?>
                            <?php  if($item['status'] == 3) { ?><span class='text-success'>已完成</span><?php  } ?>
                            <?php  if($item['status'] == -1) { ?><span class='text-default'>已取消</span><?php  } ?>
                        <?php  } ?>
                        <br />
                        <?php  if($item['status'] == 1 && $item['success'] == 1) { ?>
                        <a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/send', array('id' => $item['id']))?>">确认发货</a>
                        <?php  } ?>
                        <?php  if($item['success'] == -1) { ?>
                        <!--<a class="btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/send', array('id' => $item['id']))?>">确认退款</a>-->
                        <?php  } ?>
                    </td>
                </tr>
                <tr style="border-bottom:none;background:#f9f9f9;">
                    <td colspan='6' style='text-align:left;height:0;padding:0'></td>
                </tr>
                <?php  } } ?>
            </table>
        </div>
    </div>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--OTEzNzAyMDIzNTAzMjQyOTE0-->