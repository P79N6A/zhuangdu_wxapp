<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">微商</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>编辑</div>
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
            <label class="col-lg control-label">大码</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="big_code" class="form-control" value="<?php  echo $result['data']['big_code'];?>" placeholder="大码" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">中码</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="middle_code" class="form-control" value="<?php  echo $result['data']['middle_code'];?>" placeholder="中码" />
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">小码</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="small_code" class="form-control" value="<?php  echo $result['data']['small_code'];?>" placeholder="小码" />
            </div>
        </div>
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
                <input type="submit" value="提交" class="btn btn-primary" />
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
