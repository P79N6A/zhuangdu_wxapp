<?php defined('IN_IA') or exit('Access Denied');?><?php  if(count($packages)>0) { ?>
<?php  if(is_array($packages)) { foreach($packages as $item) { ?>
<tr>
    <td>
        <input type='checkbox'  value="<?php  echo $item['id'];?>"/>
    </td>
    <td style='text-align:center;'>
        <?php if(cv('sale.package.edit')) { ?>
        <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('sale/package/change',array('typechange'=>'displayorder','id'=>$item['id']))?>" ><?php  echo $item['displayorder'];?></a>
        <?php  } else { ?>
        <?php  echo $item['displayorder'];?>
        <?php  } ?>
    </td>
    <td>
        <img src="<?php  echo tomedia($item['thumb'])?>" style="width:40px;height:40px;padding:1px;border:1px solid #ccc;"   onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'"/>
    </td>
    <td class='full' style="overflow-x: hidden">
        <?php if(cv('sale.package.edit')) { ?>
        <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('sale/package/change',array('typechange'=>'title','id'=>$item['id']))?>" ><?php  echo $item['title'];?></a>
        <?php  } else { ?>
        <?php  echo $item['title'];?>
        <?php  } ?>
    </td>
    <td>
        <?php if(cv('sale.package.edit')) { ?>
        &yen;<a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('sale/package/change',array('typechange'=>'price','id'=>$item['id']))?>" ><?php  echo $item['price'];?></a>
        <?php  } else { ?>
        <?php  echo $item['price'];?>
        <?php  } ?>
    </td>
    <td  style="overflow:visible;">
        <span class='label <?php  if($item['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
        <?php if(cv('sale.package.edit')) { ?>
        data-toggle='ajaxSwitch'
        data-confirm = "确认是<?php  if($item['status']==1) { ?>关闭<?php  } else { ?>开启<?php  } ?>？"
        data-switch-refresh='true'
        data-switch-value='<?php  echo $item['status'];?>'
        data-switch-value0='0|关闭|label label-default|<?php  echo webUrl('sale/package/status',array('status'=>1,'id'=>$item['id']))?>'
        data-switch-value1='1|开启|label label-primary|<?php  echo webUrl('sale/package/status',array('status'=>0,'id'=>$item['id']))?>'
        <?php  } ?>>
        <?php  if($item['status']==1) { ?>开启<?php  } else { ?>关闭<?php  } ?></span>
    </td>
    <td  style="overflow:visible;position:relative;">
        <?php if(cv('sale.package.edit|sale.package.view')) { ?>
        <a  class='btn  btn-op btn-operation' href="<?php  echo webUrl('sale/package/edit', array('type'=>$_GPC['type'],'id' => $item['id'],'page'=>$page))?>">
            <span data-toggle="tooltip" data-placement="top" data-original-title="<?php if(cv('sale.package.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>"><i class="icow icow-bianji2"></i></span>
        </a>
        <?php  } ?>
        <?php if(cv('sale.package.delete1')) { ?>
        <a  class='btn  btn-op btn-operation' data-toggle='ajaxRemove' href="<?php  echo webUrl('sale/package/delete1', array('id' => $item['id']))?>" data-confirm='如果此活动存在购买记录，会无法关联到商品, 确认要彻底删除吗?？'>
            <span data-toggle="tooltip" data-placement="top" data-original-title="删除"><i class="icow icow-shanchu1"></i></span>
        </a>
        <?php  } ?>
        <a href="javascript:;" class='btn  btn-op btn-operation js-clip' data-url="<?php  echo mobileUrl('goods/package/detail', array('pid' => $item['id']),true)?>">
            <span data-toggle="tooltip" data-placement="top" data-original-title="复制链接"><i class="icow icow-lianjie2"></i></span>
        </a>
        <a href="javascript:void(0);" class="btn  btn-op btn-operation" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<img src='<?php  echo $item['qrcode'];?>' width='130' alt='链接二维码'>" data-placement="auto right">
            <i class="icow icow-erweima3"></i>
        </a>
    </td>
</tr>
<?php  if(!empty($item['merchname']) && $item['merchid'] > 0) { ?>
<tr>
    <td colspan='3' style='text-align: left;border-top:none;padding:5px 0;' class='aops'>
        <span class="text-default" style="margin-left: 95px;">商户名称:</span><span class="text-info"><?php  echo $item['merchname'];?></span>
    </td>

    <td colspan='4' style='text-align: right;border-top:none;padding:5px 0;' class='aops'></td>
</tr>
<?php  } ?>
<?php  } } ?>
<?php  } else { ?>
<td colspan="7" style="text-align: center;">暂时没有任何套餐</td>
<?php  } ?>
<!--913702023503242914-->