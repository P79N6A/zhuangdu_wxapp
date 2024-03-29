<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('mmanage/common', TEMPLATE_INCLUDEPATH)) : (include template('mmanage/common', TEMPLATE_INCLUDEPATH));?>

<div class='fui-page fui-page-current'>
    <div class="fui-header fui-header-success">
        <div class="fui-header-left"></div>
        <div class="title">工作台(<?php  echo $account['username'];?>)</div>
        <div class="fui-header-right"></div>
    </div>
    <div class='fui-content navbar'>

        <div class="headinfo">
            <div class="userinfo">
                <a class="fui-list external" href="<?php  echo mobileUrl()?>">
                    <div class="fui-list-media">
                        <img src="<?php  echo tomedia($shopset['logo'])?>">
                    </div>
                    <div class="fui-list-info">
                        <div class="title"><?php echo !empty($shopset['name'])?$shopset['name']:'未设置商城名称'?></div>
                        <div class="text"><?php echo !empty($shopset['description'])?$shopset['description']:'未设置商城简介'?></div>
                    </div>
                </a>
                <?php if(cv('sysset')) { ?>
                    <a class="setbtn" href="<?php  echo mobileUrl('mmanage/shop')?>">
                        <i class="icon icon-settings"></i>
                    </a>
                <?php  } ?>
            </div>
        </div>

        <?php  if(!empty($notice)) { ?>
            <div class="fui-cell-group">
                <div class="fui-cell index-notice">
                    <div class="fui-cell-icon"><i class="icon icon-notice"></i></div>
                    <div class="fui-cell-text">
                        <ul>
                            <?php  if(is_array($notice)) { foreach($notice as $notice_item) { ?>
                                <li>
                                    <a  data-nocache="true" href="<?php  echo mobileUrl('mmanage/system/notice/detail', array('id'=>$notice_item['id']))?>"><?php  echo $notice_item['title'];?></a>
                                </li>
                            <?php  } } ?>
                        </ul>
                    </div>
                    <?php  if(count($notice)>1) { ?>
                        <a class="fui-cell-remark" data-nocache="true" href="<?php  echo mobileUrl('mmanage/system/notice')?>">更多</a>
                    <?php  } ?>
                </div>
            </div>
        <?php  } ?>

        <div class="fui-menu-group">
            <a class="fui-menu-item noactive">
                <p id="today_count">--</p>
                <small>今日订单</small>
            </a>
            <a class="fui-menu-item noactive">
                <p id="today_price">--</p>
                <small>今日成交</small>
            </a>
            <a class="fui-menu-item noactive ">
                <p id="today_member">--</p>
                <small>新增会员</small>
            </a>
        </div>

        <?php if(cv('order.list.status1|order.list.status0|order.list.status4|order.list.status5')) { ?>
            <div class="fui-cell-group">
                <a class="fui-cell external" href="<?php  echo mobileUrl('mmanage/order', array('status'=>1))?>">
                    <div class="fui-cell-icon"><i class="icon icon-rejectedorder"></i></div>
                    <div class="fui-cell-text">订单管理</div>
                    <div class="fui-cell-remark">全部</div>
                </a>
            </div>
            <div class="fui-block-group col-3">
                <?php if(cv('order.list.status1')) { ?>
                    <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/order', array('status'=>1))?>">
                        <div class="icon text-blue"><i class="icon icon-deliver"></i></div>
                        <div class="title">待发货</div>
                        <div class="text"><span id="status_1">--</span> 单</div>
                    </a>
                <?php  } ?>
                <?php if(cv('order.list.status0')) { ?>
                    <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/order', array('status'=>0))?>">
                        <div class="icon text-yellow"><i class="icon icon-dollar"></i></div>
                        <div class="title">待付款</div>
                        <div class="text"><span id="status_0">--</span> 笔</div>
                    </a>
                <?php  } ?>
                <?php if(cv('order.list.status4|order.list.status5')) { ?>
                    <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/order', array('status'=>4))?>">
                        <div class="icon text-orange">
                            <i class="icon icon-rejectedorder"></i>
                        </div>
                        <div class="title">维权订单</div>
                        <div class="text"><span id="status_4">--</span> 笔</div>
                    </a>
                <?php  } ?>
            </div>
        <?php  } ?>

        <div class="fui-cell-group">
            <div class="fui-cell">
                <div class="fui-cell-icon">
                    <i class="icon icon-shop"></i>
                </div>
                <div class="fui-cell-text">商城管理</div>
            </div>
        </div>
        <div class="fui-block-group col-3">
            <?php if(cv('goods')) { ?>
                <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/goods')?>">
                    <div class="icon text-yellow">
                        <i class="icon icon-goods"></i>
                    </div>
                    <div class="title">商品管理</div>
                    <div class="text"><span id="goods_count">--</span> 个</div>
                </a>
            <?php  } ?>
            <?php if(cv('member')) { ?>
                <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/member')?>">
                    <div class="icon text-orange">
                        <i class="icon icon-group"></i>
                    </div>
                    <div class="title">会员管理</div>
                    <div class="text"><span id="member_count">--</span> 个</div>
                </a>
            <?php  } ?>
            <?php if(cv('finance')) { ?>
                <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/finance')?>">
                    <div class="icon text-blue">
                        <i class="icon icon-recharge"></i>
                    </div>
                    <div class="title">财务管理</div>
                    <div class="text"></div>
                </a>
            <?php  } ?>
            <?php if(cv('sale')) { ?>
                <!--
                <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/sale')?>">
                    <div class="icon text-orange">
                        <i class="icon icon-goods1"></i>
                    </div>
                    <div class="title">营销设置</div>
                    <div class="text"></div>
                </a>-->
            <?php  } ?>
            <?php if(cv('statistics')) { ?>
                <!--
                <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/statistics')?>">
                    <div class="icon text-blue">
                        <i class="icon icon-rank"></i>
                    </div>
                    <div class="title">数据统计</div>
                    <div class="text"></div>
                </a>-->
            <?php  } ?>
            <?php if(cv('sysset')) { ?>
                <a class="fui-block-child" href="<?php  echo mobileUrl('mmanage/shop')?>">
                    <div class="icon text-orange">
                        <i class="icon icon-shop"></i>
                    </div>
                    <div class="title">店铺设置</div>
                    <div class="text"></div>
                </a>
            <?php  } ?>
        </div>

        <div class="fui-title center">更多设置请至PC端后台</div>
    </div>
    <script language="javascript">
        require(['../addons/ewei_shopv2/plugin/mmanage/static/js/base.js'],function(modal){
            modal.initHome();
        });
    </script>
</div>
<?php  $this->footerMenus(null, $texts)?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--青岛易联互动网络科技有限公司-->