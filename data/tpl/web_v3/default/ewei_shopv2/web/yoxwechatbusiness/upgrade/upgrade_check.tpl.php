<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">微商</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>用户升级审核</div>

        <div class="form-group">
            <label class="col-lg control-label">用户</label>
            <div class="col-sm-9 col-xs-12">
                <img height="50" src="<?php  echo $result['avatar'];?>">
                <?php  echo $result['nickname'];?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">当前等级</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo $result['level_name'];?>
            </div>
        </div>
        <?php  if($result['upgrade_submit']==1) { ?>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
                <input type="submit" value="通过审核" class="btn btn-primary" />
            </div>
        </div>
        <?php  } ?>
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
