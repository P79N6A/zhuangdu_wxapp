<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">微商</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>微商库存销量</div>
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
            <label class="col-lg control-label">用户</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.user_stock.edit')) { ?>
                <img height="50" src="<?php  echo $result['data']['avatar'];?>">
                <?php  echo $result['data']['nickname'];?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">商品</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.user_stock.edit')) { ?>
                <img height="50" src="/attachment/<?php  echo $result['data']['goods_thumb'];?>"><?php  echo $result['data']['goods_title'];?>-<?php  echo $result['data']['goods_option_title'];?>
                <input type="hidden" name="goods_id" class="form-control" value="<?php  echo $result['data']['goods_id'];?>" placeholder="商品id" />
                <input type="hidden" name="goods_option_id" class="form-control" value="<?php  echo $result['data']['goods_option_id'];?>" placeholder="规格id" />
                <?php  } ?>
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">库存</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="stock" class="form-control" value="<?php  echo $result['data']['stock'];?>" placeholder="库存" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">销量</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.user_stock.edit')) { ?>
                <input type="text" name="user_sales" class="form-control" value="<?php  echo $result['data']['user_sales'];?>" placeholder="销量" />
                <?php  } ?>
            </div>
        </div>
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
                <?php if(cv('yoxwechatbusiness.user_stock.edit')) { ?>
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
