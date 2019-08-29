<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">微商</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>微商等级</div>
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
            <label class="col-lg control-label">等级</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <select name="level">
                <option <?php  if($result['data']['level']==1) { ?>selected<?php  } ?> value="1">等级一</option>
                <option <?php  if($result['data']['level']==2) { ?>selected<?php  } ?> value="2">等级二</option>
                <option <?php  if($result['data']['level']==3) { ?>selected<?php  } ?> value="3">等级三</option>
                <option <?php  if($result['data']['level']==4) { ?>selected<?php  } ?> value="4">等级四</option>
                <option <?php  if($result['data']['level']==5) { ?>selected<?php  } ?> value="5">等级五</option>
                <option <?php  if($result['data']['level']==6) { ?>selected<?php  } ?> value="6">等级六</option>
                <option <?php  if($result['data']['level']==7) { ?>selected<?php  } ?> value="7">等级七</option>
                </select>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">激活方式</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <select name="get_alive_type">
                <option <?php  if($result['data']['get_alive_type']==1) { ?>selected<?php  } ?> value="1">由公司激活</option>
                <option <?php  if($result['data']['get_alive_type']==2) { ?>selected<?php  } ?> value="2">由老大激活</option>

                </select>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">等级名称</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <input type="text" name="name" class="form-control" value="<?php  echo $result['data']['name'];?>" placeholder="等级名称" />
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">返点/返金额</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                上等级返:<input type="text" name="up_return" class="form-control" value="<?php  echo $result['data']['up_return'];?>" placeholder="返上等级多少钱或多少比例" />大于0是返多少钱，小于0是返多少比例，等于0不返
                <br/><br/>
				邀请人返:<input type="text" name="invite_return" class="form-control" value="<?php  echo $result['data']['invite_return'];?>" placeholder="返邀请人多少钱或多少比例" />大于0是返多少钱，小于0是返多少比例，等于0不返
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">升级达标价格</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="growth_value_money" class="form-control" value="<?php  echo $result['data']['growth_value_money'];?>" placeholder="购买达到多少钱升级" />
                <p>微商->基本设置-><a style="color: green;" href="/web/index.php?c=site&a=entry&m=ewei_shopv2&do=web&r=yoxwechatbusiness.setting.edit&name=UPGRADE">升级设置</a>，设置为"购买达到指定金额升级"有效</p>
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">升级达标套餐</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <!--套餐包含商品<input type="text" name="package_setting[goods][0][goods_id]" class="form-control" value="<?php  echo $result['data']['package_setting']['goods'][0]['goods_id'];?>" placeholder="商品id" />
                套餐价格<input type="text" name="package_setting[price]" class="form-control" value="<?php  echo $result['data']['package_setting']['price'];?>" placeholder="成为此等级的套餐价格" />
                套餐<input type="text" name="package_setting[package][id]" class="form-control" value="<?php  echo $result['data']['package_setting']['package']['id'];?>" placeholder="成为此等级的套餐" />-->
                <input type="text" name="package_id" class="form-control" value="<?php  echo $result['data']['package_id'];?>" placeholder="成为此等级的套餐" />
				-><a style="color: green;" href="/web/index.php?c=site&a=entry&m=ewei_shopv2&do=web&r=sale.package">去添加套餐</a>
				<p>微商->基本设置-><a style="color: green;" href="/web/index.php?c=site&a=entry&m=ewei_shopv2&do=web&r=yoxwechatbusiness.setting.edit&name=UPGRADE">升级设置</a>，设置为"购买套餐升级"有效</p>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">升级达标邀请人数</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="growth_value_invite_num" class="form-control" value="<?php  echo $result['data']['growth_value_invite_num'];?>" placeholder="邀请达到多少人升级" />
                <p>微商->基本设置-><a style="color: green;" href="/web/index.php?c=site&a=entry&m=ewei_shopv2&do=web&r=yoxwechatbusiness.setting.edit&name=UPGRADE">升级设置</a>，设置为"邀请人数达标升级"有效</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">等级可邀请数量</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="invite_num" class="form-control" value="<?php  echo $result['data']['invite_num'];?>" placeholder="等级可邀请数量" />
                <p>微商->基本设置-><a style="color: green;" href="/web/index.php?c=site&a=entry&m=ewei_shopv2&do=web&r=yoxwechatbusiness.setting.edit&name=UPGRADE">升级设置</a>，设置为"邀请人数达标升级"，此项无效</p>
            </div>
        </div>
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
                <?php if(cv('result.edit')) { ?>
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
