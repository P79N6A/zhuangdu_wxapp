<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">微商-证书编辑</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>证书编辑</div>
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
            <label class="col-lg control-label">商户</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="merchname" readonly class="form-control" value="<?php  echo $result['data']['merchname'];?>" placeholder="名称" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">昵称</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="nickname" readonly class="form-control" value="<?php  echo $result['data']['nickname'];?>" placeholder="昵称" />
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">真实姓名</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="realname" readonly class="form-control" value="<?php  echo $result['data']['realname'];?>" placeholder="真实姓名" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">证书</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo tpl_form_field_multi_image('certificate', $result['data']['certificate'])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">审核状态</label>
            <div class="col-sm-9 col-xs-12">
                <select name="is_certificate_approved">
                <option <?php  if($result['data']['is_certificate_approved']==0) { ?>selected<?php  } ?> value="0">未审核</option>
                <option <?php  if($result['data']['is_certificate_approved']==1) { ?>selected<?php  } ?> value="1">审核通过</option>
                <option <?php  if($result['data']['is_certificate_approved']==2) { ?>selected<?php  } ?> value="2">不通过</option>
                </select>
            </div>
        </div>
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
            <input type="hidden" name="merchid" readonly class="form-control" value="<?php  echo $result['data']['merchid'];?>" placeholder="merchid" />
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
