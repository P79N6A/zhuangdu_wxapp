<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">微商</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>查看</div>
        <!--<div class="form-group">
            <label class="col-lg control-label">排序</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <input type="text" name="displayorder" class="form-control" value="<?php  echo $result['data']['displayorder'];?>" placeholder="排序" />
                <span class="help-block">排序</span>
                <?php  } ?>
            </div>
        </div>-->
        <div class="form-group">
            <label class="col-lg control-label">订单ID</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.commission.edit')) { ?>
                <?php  echo $result['data']['order_id'];?>
                <?php  } ?>
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">下单者</label>
            <div class="col-sm-9 col-xs-12">
				<img width="50" src="<?php  echo $result['data']['avatar'];?>">
                <?php  echo $result['data']['nickname'];?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">商品</label>
            <div class="col-sm-9 col-xs-12">
                <img width="50" src="/attachment/<?php  echo $result['data']['goods_thumb'];?>"><?php  echo $result['data']['goods_title'];?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">类型</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo $result['data']['type_name'];?>(<?php  echo $result['data']['type'];?>)
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">数量</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo $result['data']['total'];?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">获佣者</label>
            <div class="col-sm-9 col-xs-12">
                <img width="50" src="<?php  echo $result['data']['commission_avatar'];?>"><?php  echo $result['data']['commission_nickname'];?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">利润/佣金</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo $result['data']['commission'];?>
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">状态</label>
            <div class="col-sm-9 col-xs-12">
            <select name="status">
            <option <?php  if($result['data']['status']==0 ) { ?>selected<?php  } ?> value="0">未分佣</option>
            <option <?php  if($result['data']['status']==1 ) { ?>selected<?php  } ?> value="1">分佣</option>
            </select>
                设置为已分佣，将会把佣金发放到余额
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">备注</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="remark" class="form-control" readonly value="<?php  echo $result['data']['remark'];?>" placeholder="备注" />
            </div>
        </div>
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
                <?php if(cv('yoxwechatbusiness.commission.edit')) { ?>
                <input type="submit" value="提交" class="btn btn-primary" />
                <?php  } ?>
            </div>
        </div>
    </form>
</div>
<script>
$(function(){
        $(".js-switch").not(".checkhi").click(function () {
            var next = $(this).next();
            if(next.hasClass('checked')){
                $(this).val("1").prev().val("1");
            }else{
                $(this).val("0").prev().val("0");
            }
        });
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
