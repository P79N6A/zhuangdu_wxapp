<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">当前位置：<span class="text-primary">基础设置</span> </div>
<div class="page-content">
    <div class="tabs-container">
        <form id="setform" action="" method="post" class="form-horizontal form-validate">
        <input type="hidden" id="tab" name="tab" value="<?php  echo $_GPC['tab'];?>" />
        <ul class="nav nav-tabs" id="myTab">
            <li  <?php  if(empty($_GPC['tab']) || $_GPC['tab']=='basic') { ?>class="active"<?php  } ?>><a href="#tab_basic">关注及分享</a></li>
            <li  <?php  if($_GPC['tab']=='description') { ?>class="active"<?php  } ?> ><a href="#tab_description">统一描述</a></li>
            <li  <?php  if($_GPC['tab']=='trade') { ?>class="active"<?php  } ?> ><a href="#tab_trade">交易管理</a></li>
            <li  <?php  if($_GPC['tab']=='rights') { ?>class="active"<?php  } ?> ><a href="#tab_rights">维权设置</a></li>
            <li  <?php  if($_GPC['tab']=='rules') { ?>class="active"<?php  } ?> ><a href="#tab_rules">拼团规则</a></li>
            <li  <?php  if($_GPC['tab']=='just_up_level') { ?>class="active"<?php  } ?> ><a href="#tab_just_up_level">仅显示上级</a></li>
        </ul>
        <div class="tab-content ">
            <div class="tab-pane <?php  if(empty($_GPC['tab']) || $_GPC['tab']=='basic') { ?>active<?php  } ?>" id="tab_basic">
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/set/basic', TEMPLATE_INCLUDEPATH)) : (include template('groups/set/basic', TEMPLATE_INCLUDEPATH));?>
            </div>
            <div class="tab-pane <?php  if($_GPC['tab']=='description') { ?>active<?php  } ?>" id="tab_description">
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/set/description', TEMPLATE_INCLUDEPATH)) : (include template('groups/set/description', TEMPLATE_INCLUDEPATH));?>
            </div>
            <div class="tab-pane <?php  if($_GPC['tab']=='trade') { ?>active<?php  } ?>" id="tab_trade">
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/set/trade', TEMPLATE_INCLUDEPATH)) : (include template('groups/set/trade', TEMPLATE_INCLUDEPATH));?>
            </div>
            <div class="tab-pane <?php  if($_GPC['tab']=='rights') { ?>active<?php  } ?>" id="tab_rights">
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/set/rights', TEMPLATE_INCLUDEPATH)) : (include template('groups/set/rights', TEMPLATE_INCLUDEPATH));?>
            </div>
            <div class="tab-pane <?php  if($_GPC['tab']=='rules') { ?>active<?php  } ?>" id="tab_rules">
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/set/rules', TEMPLATE_INCLUDEPATH)) : (include template('groups/set/rules', TEMPLATE_INCLUDEPATH));?>
            </div>
            <div class="tab-pane <?php  if($_GPC['tab']=='just_up_level') { ?>active<?php  } ?>" id="tab_just_up_level">
                <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('groups/set/just_up_level', TEMPLATE_INCLUDEPATH)) : (include template('groups/set/just_up_level', TEMPLATE_INCLUDEPATH));?>
            </div>
        </div>
        <?php if(cv('groups.set.edit')) { ?>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9 col-xs-12">
                <input type="submit"  value="提交" class="btn btn-primary" />
            </div>
        </div>
        <?php  } ?>
        </form>
    </div>
</div>
<script language='javascript'>
    require(['bootstrap'], function () {
        $('#myTab a').click(function (e) {
            $('#tab').val($(this).attr('href'));
            e.preventDefault();
            $(this).tab('show');
        })
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--913702023503242914-->