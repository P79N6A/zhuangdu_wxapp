<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .pd0{padding:0;}
    .trhead td {  background:#efefef;text-align: center}
    .trbody td {  text-align: center; vertical-align:top;border-left:1px solid #f2f2f2;overflow: hidden; font-size:12px;}
    .trorder { background:#f8f8f8;border:1px solid #f2f2f2;text-align:left;}
    .ops { border-right:1px solid #f2f2f2; text-align: center;}
</style>
<div class="page-header">
    当前位置：<span class="text-primary">订单管理</span>
</div>
<div class="page-content">
<div class="main">
    <form action="./index.php" method="get" class="form-horizontal table-search" role="form">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="groups.refund" />
        <input type="hidden" name="status"  value="<?php  echo $_GPC['status'];?>" />
        <div class="page-toolbar m-b-sm m-t-sm">
            <div class="col-sm-6">
                <div class="btn-group btn-group-sm" style='float:left'>
                    <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d H:i', $endtime)),true);?>
                </div>
            </div>
            <div class="col-sm-6 pull-right">
                <div class="input-group">
                    <div class="input-group-select">
                        <select name="paytype" class="form-control input-sm select-md" style="width:100px;">
                            <option value="" <?php  if($_GPC['paytype']=='') { ?>selected<?php  } ?>>支付方式</option>
                            <?php  if(is_array($paytype)) { foreach($paytype as $key => $type) { ?>
                            <option value="<?php  echo $key;?>" <?php  if($_GPC['paytype'] == "$key") { ?> selected="selected" <?php  } ?>><?php  echo $type;?></option>
                            <?php  } } ?>
                        </select>
                    </div>
                    <div class="input-group-select">
                        <select name='searchtime' class='form-control  input-sm select-md' style="width:100px;"  >
                            <option value=''>不按时间</option>
                            <option value='apply' <?php  if($_GPC['searchtime']=='apply') { ?>selected<?php  } ?>>申请时间</option>
                            <option value='operate' <?php  if($_GPC['searchtime']=='operate') { ?>selected<?php  } ?>>同意退换货时间</option>
                            <option value='send' <?php  if($_GPC['searchtime']=='send') { ?>selected<?php  } ?>>买家发货时间</option>
                            <option value='return' <?php  if($_GPC['searchtime']=='return') { ?>selected<?php  } ?>>卖家发货时间</option>
                            <option value='end' <?php  if($_GPC['searchtime']=='end') { ?>selected<?php  } ?>>完成时间</option>
                        </select>
                    </div>
                    <div class="input-group-select">
                        <select name='searchfield'  class='form-control  input-sm select-md'   style="width:100px;"  >
                            <option value='orderno' <?php  if($_GPC['searchfield']=='orderno') { ?>selected<?php  } ?>>订单号</option>
                            <option value='refundno' <?php  if($_GPC['searchfield']=='refundno') { ?>selected<?php  } ?>>维权编号</option>
                            <option value='member' <?php  if($_GPC['searchfield']=='member') { ?>selected<?php  } ?>>会员信息</option>
                            <option value='address' <?php  if($_GPC['searchfield']=='address') { ?>selected<?php  } ?>>收件人信息</option>
                            <option value='expresssn' <?php  if($_GPC['searchfield']=='expresssn') { ?>selected<?php  } ?>>快递单号</option>
                            <option value='goodstitle' <?php  if($_GPC['searchfield']=='goodstitle') { ?>selected<?php  } ?>>商品名称</option>
                        </select>
                    </div>
                    <input type="text" class="form-control input-sm"  name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键词"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"> 搜索</button>
                    <button type="submit" name="export" value="1" class="btn btn-success">导出</button>
                </span>
                </div>
            </div>
        </div>
    </form>
    <?php  if(count($list)>0) { ?>
    <table class='table table-responsive' style='table-layout: fixed;'>
        <tr style='background:#f8f8f8'>
            <td style='width:60px;border-left:1px solid #f2f2f2;'>商品</td>
            <td style='width:150px;'></td>
            <td style='width:70px;text-align: right;;'>单价/数量</td>
            <td  style='width:100px;text-align: center;'>买家</td>
            <td style='width:90px;text-align: center;'>支付/配送</td>
            <td style='width:80px;text-align: center;'>金额</td>
            <td style='width:90px;text-align: center;'>申请时间</td>
            <td style='width:120px;text-align: center'>状态</td>
        </tr>
        <?php  if(is_array($list)) { foreach($list as $item) { ?>
        <tr ><td colspan='8' style='height:20px;padding:0;border-top:none;'>&nbsp;</td></tr>
        <tr class='trorder'>
            <td colspan='4' >
                <span style="font-weight: bold;"><?php  echo date('Y-m-d',$item['applytime'])?> <?php  echo date('H:i:s',$item['applytime'])?></span>
                维权编号:  <?php  echo $item['refundno'];?>
                <?php  if(!empty($item['refundstate'])) { ?><label class='label label-danger'><?php  echo $r_type[$item['rtype']];?>申请</label><?php  } ?>
                <?php  if(!empty($item['refundstate']) && $item['refundstatus'] == 4) { ?><label class='label label-default'>客户退回物品</label><?php  } ?>
            </td>
            <td colspan='4' style='text-align:right;font-size:12px;' class=''>
                <?php if(cv('groups.refund.note')) { ?>
                <a class='op'  data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/remarksaler', array('id' => $item['id']))?>" >
                    <?php  if(!empty($item['remarksaler'])) { ?>
                        <i class="icow icow-flag-o" style="color: #df5254;display: inline-block;vertical-align: middle" title="  查看备注"></i>
                        备注
                        <?php  } else { ?>
                        <i class="icow icow-yibiaoji" style="color: #999;display: inline-block;vertical-align: middle" title="  添加备注" ></i>
                         备注
                    <?php  } ?>
                </a>
                <?php  } ?>
                <?php  if(empty($item['statusvalue'])) { ?>
                <?php if(cv('groups.refund.close')) { ?>
                <a class='op'  data-toggle='ajaxModal' href="<?php  echo webUrl('groups/order/close',array('id'=>$item['orderid']))?>" >
                    <i class="icow icow-shutDown" title=""  style="color: #999;margin-right: 3px;display: inline-block;vertical-align: middle"></i> 关闭订单
                </a>
                <?php  } ?>
                <?php  } ?>
                <?php  if(!empty($item['rexpresssn'])) { ?>
                <?php if(cv('groups.refund.express')) { ?>
                <a class="op" data-toggle="ajaxModal"  href="<?php  echo webUrl('groups/order/changeexpress', array('id' => $item['id']))?>">
                    <i class="icow icow-wuliu" style="color: #999;margin-right: 3px;display: inline-block;vertical-align: middle;"></i>修改物流
                </a>
                <?php  } ?>
                <?php  } ?>
            </td>
        </tr>
        <tr class='trbody'>
            <td style='overflow:hidden;'><img src="<?php  echo tomedia($item['thumb'])?>" style='width:50px;height:50px;border:1px solid #ccc; padding:1px;' onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'"></td>
            <td style='text-align: left;overflow:hidden;border-left:none;'><?php  echo $item['title'];?></td>
            <td style='text-align:right;border-left:none;'>
                ¥ <?php  if($item['is_team']==1) { ?><?php  echo $item['groupsprice'];?><?php  } else { ?><?php  echo $item['singleprice'];?><?php  } ?>
                <br/>x1
            </td>
            <td style='text-align: center;' >
                <?php if(cv('member.member.edit')) { ?>
                <a href="<?php  echo webUrl('member/list/detail',array('id'=>$item['mid']))?>"> <?php  echo $item['nickname'];?></a>
                <?php  } else { ?>
                <?php  echo $item['nickname'];?>
                <?php  } ?>
                <br/>
                <?php  echo $item['mrealname'];?><br/><?php  echo $item['mmobile'];?></td>
            <td style='text-align:center;' >
                <?php  if($item['pay_type']=='wechat') { ?>
                <span> <i class="icow icow-weixinzhifu text-success" style="font-size: 17px"></i>微信支付</span>
                <?php  } else if($item['pay_type']=='credit') { ?>
                <span> <i class="icow icow-yue text-warning" style="font-size: 17px;"></i><span>余额支付</span></span>
                <?php  } else if($item['pay_type']=='alipay') { ?>
                <span><i class="icow icow-zhifubaozhifu text-primary" style="font-size: 17px"></i>支付宝支付</span>
                <?php  } else if($item['pay_type']) { ?>
                <span class='label label-default'><?php  echo $item['pay_type'];?></span>
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
            </td>
            <td style='text-align:center' >
                ¥ <?php  echo number_format($item['applyprice'],2)?>
            </td>
            <td style='text-align:center' >
                <?php  if(!empty($item['refundid'])) { ?>
                <a class='op text-primary' style="display: block"  href="<?php  echo webUrl('groups/refund/detail', array('id' => $item['orderid']))?>" >维权<?php  if($item['refundstate']>0) { ?>处理<?php  } else { ?>详情<?php  } ?></a>
                <?php  } ?>
                <a  style="display: block" class='op text-primary'  href="<?php  echo webUrl('groups/order/detail', array('orderid' => $item['orderid']))?>" >订单详情</a>
                <?php  if(!empty($item['expresssn'])) { ?>
                <a  style="display: block" class='op text-primary'  data-toggle="ajaxModal" href="<?php  echo webUrl('util/express', array('id' => $item['id'],'express'=>$item['express'],'expresssn'=>$item['expresssn']))?>"   >物流信息</a>
                <?php  } ?>
            </td>
            <td  class='ops' style='line-height:20px;text-align:center' >
                <?php  if($item['refundstatus'] == -2) { ?><span class="text-default">客户取消<?php  echo $r_type[$refund['rtype']];?></span>
                <?php  } else if($item['refundstatus'] == -1) { ?><span class="text-default">已拒绝<?php  echo $r_type[$refund['rtype']];?></span>
                <?php  } else if($item['refundstatus'] == 0) { ?><span class="text-warning">等待商家处理申请</span>
                <?php  } else if($item['refundstatus'] == 1) { ?><span class="text-default"><?php  echo $r_type[$refund['rtype']];?>完成</span>
                <?php  } else if($item['refundstatus'] == 3) { ?><span class="text-warning">等待客户退回物品</span>
                <?php  } else if($item['refundstatus'] == 4) { ?><span class="text-warning">客户退回物品，等待商家重新发货</span>
                <?php  } else if($item['refundstatus'] == 5) { ?><span class="text-warning">等待客户收货</span><?php  } ?>
                <br />
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/order/ops', TEMPLATE_INCLUDEPATH)) : (include template('groups/order/ops', TEMPLATE_INCLUDEPATH));?>
            </td>
        </tr>
        <tr style="border-bottom:none;background:#f9f9f9;">
            <td colspan='8' style='text-align:left;height:0;padding:0'></td>
        </tr>
        <?php  } } ?>
    </table>
    <div style="text-align:right;width:100%;">
        <?php  echo $pager;?>
    </div>
    <?php  } else { ?>
    <div class='panel panel-default'>
        <div class='panel-body' style='text-align: center;padding:30px;'>
            暂时没有任何订单!
        </div>
    </div>
    <?php  } ?>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>