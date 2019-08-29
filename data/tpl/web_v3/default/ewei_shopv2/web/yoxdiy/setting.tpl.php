<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    <span class="text-primary">入口设置</span>
</div>
<div class="page-content">
<form class="form-horizontal form-validate" role="form" method="post" novalidate="novalidate">
    <div class="form-group">
        <label class="col-sm-2 control-label">直接链接</label>
        <div class="col-sm-9 col-xs-12">
            <p class='form-control-static'>
                <a href='javascript:;' class="js-clip" title='点击复制链接' data-url="<?php  echo mobileUrl('yoxdiy.index.template_list',array(),1)?>" >
                    <?php  echo mobileUrl('yoxdiy.index.template_list',array(),1)?>
                </a>
                <span style="cursor: pointer;" data-toggle="popover" data-trigger="hover" data-html="true"
                      data-content="<img src='<?php  echo m('qrcode')->createQrcode(mobileUrl('yoxdiy.index.template_list',array(),1))?>'
                      width='130' alt='链接二维码'>" data-placement="auto right">
                    <i class="glyphicon glyphicon-qrcode"></i>
                </span>
            </p>
        </div>
    </div>
</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>