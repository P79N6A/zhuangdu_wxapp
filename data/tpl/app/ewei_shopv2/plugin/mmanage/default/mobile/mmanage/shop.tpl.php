<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('mmanage/common', TEMPLATE_INCLUDEPATH)) : (include template('mmanage/common', TEMPLATE_INCLUDEPATH));?>

<div class='fui-page fui-page-current'>
    <div class="fui-header fui-header-success">
        <div class="fui-header-left">
            <a class="back"></a>
        </div>
        <div class="title">店铺设置</div>
        <div class="fui-header-right"></div>
    </div>
    <div class='fui-content navbar'>

        <div class="fui-list-group">
            <div class="fui-list">
                <input type="file"  name="file-shoplogo" id="file-shoplogo" />
                <input type="hidden" id="shoplogo" value="<?php  echo $shopset['shop']['logo'];?>" />
                <div class="fui-list-inner">
                    <div class="title">商城logo</div>
                </div>
                <div class="fui-list-media">
                    <img src="<?php  echo tomedia($shopset['shop']['logo'])?>" class="round" id="showlogo" />
                </div>
            </div>
        </div>

        <div class="fui-cell-group">
            <div class="fui-cell">
                <div class="fui-cell-label">商城名称</div>
                <div class="fui-cell-info">
                    <input type="text" placeholder="请输入商城名称" class="fui-input" value="<?php  echo $shopset['shop']['name'];?>" id="shopname" />
                </div>
            </div>
            <div class="fui-cell fui-cell-textarea">
                <div class="fui-cell-label " style="margin-top: 0;">商城简介</div>
                <div class="fui-cell-info">
                    <textarea rows="3" placeholder="请输入商城简介" id="shopdesc"><?php  echo $shopset['shop']['description'];?></textarea>
                </div>
            </div>
        </div>

        <div class="fui-cell-group fui-cell-click">
            <div class="fui-cell noactive">
                <div class="fui-cell-label ">关闭商城</div>
                <div class="fui-cell-info">
                    <input type="checkbox" class="fui-switch fui-switch-small fui-switch-success pull-right" <?php  if(!empty($shopset['shop']['close'])) { ?>checked=""<?php  } ?> id="shopclose" />
                </div>
            </div>
        </div>

        <?php if(cv('sysset.shop.edit')) { ?>
            <div class="btn btn-success block" id="btn-submit">保存设置</div>
        <?php  } ?>
    </div>

    <script language="javascript">
        require(['../addons/ewei_shopv2/plugin/mmanage/static/js/base.js'],function(modal){
            modal.initShop();
        });
    </script>
</div>
<?php  $this->footerMenus(null, $texts)?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+454mI5p2D5omA5pyJ-->