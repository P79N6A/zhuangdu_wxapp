<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .trhead td {  background:#efefef;text-align: center}
    .trbody td {  text-align: center; vertical-align:top;border-left:1px solid #f2f2f2;overflow: hidden; font-size:12px;}
    .trorder { background:#f8f8f8;border:1px solid #f2f2f2;text-align:left;}
    .ops { border-right:1px solid #f2f2f2; text-align: center;}
    .ops a,.ops span{
        margin: 3px 0;
    }
    .table-top .op:hover{
        color: #000;
    }
    .tables{
        border:1px solid #e5e5e5;
        font-size: 12px;
        line-height: 18px;
    }
    .tables:hover{
        border:1px solid #b1d8f5;
    }
    .table-row,.table-header,.table-footer,.table-top{
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        justify-content: center;
        -webkit-justify-content: center;
        -webkit-align-content: space-around;
        align-content: space-around;
    }
    .tables  .table-row>div{
        padding: 14px 0;
    }
    .tables  .table-row.table-top>div{
        padding: 11px 0;
    }
    .tables    .table-row .ops.list-inner{
        border-right:none;
    }
    .tables .list-inner{
        border-right: 1px solid #efefef;
        vertical-align: middle;
    }
    .table-row .goods-des .title{
        width:180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .table-row .goods-des{
        width:300px;
        border-right: 1px solid #efefef;
        vertical-align: middle;
    }
    .table-row .list-inner{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        text-align: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-align-items: center;
        align-items: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-flex-direction: column;
        flex-direction: column;
    }
    .saler>div{
        width:130px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .table-row .list-inner.ops,  .table-row .list-inner.paystyle{
        -webkit-flex-direction: column;
        flex-direction: column;
        -webkit-justify-content: center;
        justify-content: center;
    }
    .table-header .others{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        text-align: center;
    }
    .table-footer{
        border-top: 1px solid #efefef;
    }
    .table-footer>div, .table-top>div{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        height:100%;
    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">订单管理 </span>
</div>
<div class="page-content">
    <div class="main">
        <form action="./index.php" method="get" class="form-horizontal table-search" role="form">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="ewei_shopv2" />
            <input type="hidden" name="do" value="web" />
            <input type="hidden" name="r"  value="groups.order" />
            <input type="hidden" name="status"  value="<?php  echo $_GPC['status'];?>" />
            <div class="page-toolbar m-b-sm m-t-sm">
                <div class="col-sm-5">
                    <div class="btn-group btn-group-sm" style='float:left'>
                        <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d H:i', $endtime)),true);?>
                    </div>
                </div>
                <div class="col-sm-7 pull-right">
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
                            <select name='searchtime' class='form-control  input-sm select-md' style="width:100px"  >
                                <option value=''>不按时间</option>
                                <option value='create' <?php  if($_GPC['searchtime']=='create') { ?>selected<?php  } ?>>下单时间</option>
                                <option value='pay' <?php  if($_GPC['searchtime']=='pay') { ?>selected<?php  } ?>>付款时间</option>
                                <option value='send' <?php  if($_GPC['searchtime']=='send') { ?>selected<?php  } ?>>发货时间</option>
                                <option value='finish' <?php  if($_GPC['searchtime']=='finish') { ?>selected<?php  } ?>>完成时间</option>
                            </select>
                        </div>
                        <div class="input-group-select">
                            <select name='searchfield'  class='form-control  input-sm select-md'   style="width:110px;"  >
                                <option value='orderno' <?php  if($_GPC['searchfield']=='orderno') { ?>selected<?php  } ?>>订单号</option>
                                <option value='member' <?php  if($_GPC['searchfield']=='member') { ?>selected<?php  } ?>>会员信息</option>
                                <option value='address' <?php  if($_GPC['searchfield']=='address') { ?>selected<?php  } ?>>收件人信息</option>
                                <option value='expresssn' <?php  if($_GPC['searchfield']=='expresssn') { ?>selected<?php  } ?>>快递单号</option>
                                <option value='goodstitle' <?php  if($_GPC['searchfield']=='goodstitle') { ?>selected<?php  } ?>>商品名称</option>
                                <option value='goodssn' <?php  if($_GPC['searchfield']=='goodssn') { ?>selected<?php  } ?>>商品编码</option>
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
        <div class="table-row"><div style='height:20px;padding:0;border-top:none;'>&nbsp;</div></div>
        <table class='table table-responsive' style='table-layout: fixed;'>
            <tr style='background:#f8f8f8'>
                <td style='width:60px;border-left:1px solid #f2f2f2;'>商品</td>
                <td style='width:150px;'></td>
                <td style='width:70px;text-align: right;;'>单价/数量</td>
                <td  style='width:100px;text-align: center;'>买家</td>
                <td style='width:100px;text-align: center;'>支付/配送/退款</td>
                <td style='width:100px;text-align: center;'>金额</td>
                <td style='width:100px;text-align: center;'>操作</td>
                <td style='width:90px;text-align: center'>状态</td>
            </tr>
            <?php  if(is_array($list)) { foreach($list as $item) { ?>
            <tr ><td colspan='8' style='height:20px;padding:0;border-top:none;'>&nbsp;</td></tr>
            <tr class='trorder'>
                <td colspan='4' >
                    <span style="font-weight: bold;margin-right: 10px;color: #2d2d31"> <?php  echo date('Y-m-d',$item['createtime'])?> <?php  echo date('H:i:s',$item['createtime'])?></span>
                    订单编号:  <?php  echo $item['orderno'];?>

                </td>
                <td colspan='4' style='text-align:right;font-size:12px;' class='aops'>


                    <?php if(cv('groups.order.delete')) { ?>
                    <a class='op'  data-toggle='ajaxPost' href="<?php  echo webUrl('groups/order/delete',array('id'=>$item['id']))?>" data-confirm="删除后无法恢复，确认删除订单吗？" >
                        <i class="icow icow-shanchu1"  style="color: #999;display: inline-block;vertical-align: middle;"></i>
                        删除订单
                        &nbsp
                    </a>
                    <?php  } ?>

                    <?php if(cv('groups.order.remarksaler')) { ?>

                    <a class='op'  data-toggle="ajaxModal" href="<?php  echo webUrl('groups/order/remarksaler', array('id' => $item['id']))?>">
                        <?php  if(!empty($item['remark'])) { ?>
                        <i class="icow icow-flag-o" style="color: #df5254;display: inline-block;vertical-align: middle" title="  查看备注"></i>
                        备注
                        &nbsp
                        <?php  } else { ?>
                        <i class="icow icow-yibiaoji" style="color: #999;display: inline-block;vertical-align: middle" title="  添加备注" ></i>
                        备注
                        &nbsp
                        <?php  } ?>
                    </a>
                    <?php  } ?>
                    <?php  if(!empty($item['expresssn']) && $item['status']>=2 && !empty($item['addressid'])) { ?>
                    <a class='op' data-toggle="ajaxModal" href="<?php  echo webUrl('util/express', array('id' => $item['id'],'express'=>$item['express'],'expresssn'=>$item['expresssn']))?>">
                        <i class="icow icow-wuliu" style="color: #999;display: inline-block;vertical-align: middle;"></i>
                        查看物流
                        &nbsp
                    </a>
                    <?php  } ?>
                    <?php  if($item['status'] == 0 ) { ?>
                    <?php if(cv('groups.order.close')) { ?>
                    <a class='op'  data-toggle='ajaxModal' href="<?php  echo webUrl('groups/order/close',array('id'=>$item['id']))?>" >
                        <i class="icow icow-shutDown"  style="color: #999;display: inline-block;vertical-align: middle;"></i>
                        关闭订单
                        &nbsp
                    </a>
                    <?php  } ?>
                    <?php  } ?>
                </td>
            </tr>
            <tr class='trbody'>
                <td style='overflow:hidden;'><img src="<?php  echo tomedia($item['thumb'])?>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" style='width:50px;height:50px;border:1px solid #ccc; padding:1px;'></td>
                <td style='text-align: left;overflow:hidden;border-left:none;'><?php  echo $item['title'];?><br/>
                    <span style="color: #999"> <?php  if(empty($item['optiontitle'])) { ?><?php  echo $item['optiontitle'];?><?php  } ?></span>
                </td>
                <td style='text-align:right;border-left:none;'>
                    <?php  if(!$item['title'] && !$item['thumb']) { ?>
                    <?php  echo number_format($item['price'],2)?>
                    <?php  } else { ?>
                    ¥ <?php  if($item['is_team']==1) { ?><?php  echo $item['groupsprice'];?><?php  } else { ?><?php  echo $item['singleprice'];?><?php  } ?>
                    <?php  } ?>
                    <br/>x1
                </td>
                <td  style='text-align: center;' >
                    <?php  if($item['pay_type']=='system') { ?>系统虚拟<?php  } ?>
                    <?php if(cv('member.member.edit')) { ?>
                    <a href="<?php  echo webUrl('member/list/detail',array('id'=>$item['mid']))?>"> <?php  echo $item['nickname'];?></a>
                    <?php  } else { ?>
                    <?php  echo $item['nickname'];?>
                    <?php  } ?>
                    <br/>
                    <?php  echo $item['mrealname'];?><br/><?php  echo $item['mmobile'];?>
                </td>
                <td style='text-align:center;' >
                    <?php  if($item['pay_type']=='wechat') { ?>
                    <span> <i class="icow icow-weixin text-success" style="font-size: 17px"></i>微信支付</span>
                    <?php  } else if($item['pay_type']=='credit') { ?>
                    <span> <i class="icow icow-yue text-warning" style="font-size: 17px;display:inline-block;height: 17px;width: 17px;padding-top: 3px"></i><span>余额支付</span></span>
                    <?php  } else if($item['pay_type']=='alipay') { ?>
                    <span><i class="icow icow-zhifubao text-primary" style="font-size: 17px"></i>支付宝支付</span>
                    <?php  } else if($item['pay_type']=='system') { ?>
                    <span class='label label-default'>系统虚拟</span>
                    <?php  } else if($item['pay_type']=='other') { ?>
                    <span class='label label-default'>其他</span>
                    <?php  } else if($item['pay_type']=='') { ?>
                    <span class='label label-default'>未付款</span>
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
                    <span class='text-default'>已退款</span>
                    <?php  } ?>
                </td>
                <td style='text-align:center' >
                    ¥ <?php  echo number_format($item['price']-$item['creditmoney']+$item['freight'],2)?>
                    <a data-toggle='popover'  data-trigger="hover" data-html='true' data-placement='right' data-content="<table style='width:100%;'>
                    <tr>
                        <td  style='border:none;text-align:right;'>商品小计：</td>
                        <td  style='border:none;text-align:right;;'>
                            ¥ <?php  echo number_format($item['price'] + $item['discount'],2)?>
                        </td>
                    </tr>
                    <tr>
                        <td  style='border:none;text-align:right;'>运费：</td>
                        <td  style='border:none;text-align:right;;'>¥ <?php  echo number_format( $item['freight'],2)?></td>
                    </tr>
                    <?php  if($item['discount']>0) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>团长优惠：</td>
                        <td  class='text-danger' style='border:none;text-align:right;'>- ¥<?php  echo $item['discount'];?></td>
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
                    <?php  if($item['deductenough']>0) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>满额立减：</td>
                        <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['deductenough'],2)?></td>
                    </tr>
                    <?php  } ?>
                    <?php  if($item['couponprice']>0) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>优惠券优惠：</td>
                        <td class='text-danger'  style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['couponprice'],2)?></td>
                    </tr>
                    <?php  if($item['isdiscountprice']>0) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>促销优惠：</td>
                        <td  class='text-danger' style='border:none;text-align:right;;'>-¥ <?php  echo number_format( $item['isdiscountprice'],2)?></td>
                    </tr>
                    <?php  } ?>
                    <?php  } ?>
                    <?php  if(intval($item['changeprice'])!=0) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>卖家改价：</td>
                        <td  style='border:none;text-align:right;;'>
                        <span class='<?php  if(0<$item['changeprice']) { ?>text-success<?php  } else { ?>text-danger<?php  } ?>'>
                        <?php  if(0<$item['changeprice']) { ?>+<?php  } else { ?>-<?php  } ?>¥ <?php  echo number_format(abs($item['changeprice']),2)?>
                        </span>
                        </td>
                    </tr>
                    <?php  } ?>
                    <?php  if(intval($item['changedispatchprice'])!=0) { ?>
                    <tr>
                        <td  style='border:none;text-align:right;'>卖家改运费：</td>
                        <td  style='border:none;text-align:right;;'>
                            <span class='<?php  if(0<$item['changedispatchprice']) { ?>text-success<?php  } else { ?>text-danger<?php  } ?>'>
                            <?php  if(0<$item['changedispatchprice']) { ?>+<?php  } else { ?>-<?php  } ?>¥ <?php  echo abs($item['changedispatchprice'])?>
                            </span>
                        </td>
                    </tr>
                    <?php  } ?>
                    <tr>
                        <td style='border:none;text-align:right;'>应收款：</td>
                        <td style='border:none;text-align:right;font-weight:bold'>¥ <?php  echo number_format($item['price']-$item['creditmoney']+$item['freight'],2)?></td>
                    </tr>
                </table>">
                        <i class='fa fa-question-circle'></i></a>
                    <?php  if($item['freight']>0) { ?>
                    <br/>(含运费:¥ <?php  echo number_format( $item['freight'],2)?>)
                    <?php  } ?>
                </td>
                <td style='text-align:center' >
                    <a class='op text-primary'  href="<?php  echo webUrl('groups/order/detail', array('orderid' => $item['id']))?>" >查看详情</a>
                    <br/>
                    <?php  if(!empty($item['refundid'])) { ?>
                    <a class='op  text-primary'  href="<?php  echo webUrl('groups/refund/detail', array('id' => $item['id']))?>" >维权<?php  if($item['refundstate']>0) { ?>处理<?php  } else { ?>详情<?php  } ?></a>
                    <?php  } ?>
                </td>
                <td  class='ops' style='line-height:20px;text-align:center' >
                    <?php  if($item['status'] == 0) { ?><span class='text-warning'>待付款</span><?php  } ?>
                    <?php  if($item['is_team']==1) { ?>
                    <?php  if($item['status'] == 1 && $item['success'] == 0) { ?><span class='text-success'>已付款</span><?php  } ?>
                    <?php  if($item['status'] == 1 && $item['success'] == 1) { ?>
                    <?php  if($item['isverify'] == 1) { ?>
                    <span class='text-warning'>待核销</span><?php  } else { ?>
                    <span class='text-warning'>待发货</span>
                    <?php  } ?><?php  } ?>
                    <?php  if($item['status'] == 1 && $item['success'] == -1) { ?><span class='text-warning'>待退款</span><?php  } ?>
                    <?php  } else { ?>
                    <?php  if($item['status'] == 1) { ?>
                    <?php  if($item['isverify'] == 1) { ?><span class='text-warning'>待核销</span><?php  } else { ?>
                    <span class='text-warning'>待发货</span>
                    <?php  } ?>
                    <?php  } ?>
                    <?php  } ?>
                    <?php  if($item['deleted'] == 1) { ?>
                    <span class='text-default'>用户已删除</span>
                    <?php  } else { ?>
                    <?php  if($item['status'] == 2) { ?><span class='text-warning'>待收货</span><?php  } ?>
                    <?php  if($item['status'] == 3) { ?><span class='text-success'>已完成</span><?php  } ?>
                    <?php  if($item['status'] == -1) { ?><span class='text-default'>已取消</span><?php  } ?>
                    <?php  } ?>
                    <br />
                    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/order/ops', TEMPLATE_INCLUDEPATH)) : (include template('groups/order/ops', TEMPLATE_INCLUDEPATH));?>
                </td>
            </tr>
            <tr style="border-bottom:none;background:#f9f9f9;">
                <td colspan='8' style='text-align:left;height:0;padding:0'>
                    <?php  if(!empty($item['message'])) { ?> <span style="color: #ff5555">买家备注: <?php  echo $item['message'];?></span><?php  } ?>
                </td>
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