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
            <label class="col-lg control-label">用户</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <img width="50" src="<?php  echo $result['data']['avatar'];?>"><?php  echo $result['data']['nickname'];?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">用户等级</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.user.edit')) { ?>
                <select name="level">
                <option <?php  if($result['data']['level']==0 ) { ?>selected<?php  } ?> value="1">没有等级</option>
                <option <?php  if($result['data']['level']==1 ) { ?>selected<?php  } ?> value="1">等级一</option>
                <option <?php  if($result['data']['level']==2 ) { ?>selected<?php  } ?> value="2">等级二</option>
                <option <?php  if($result['data']['level']==3 ) { ?>selected<?php  } ?> value="3">等级三</option>
                <option <?php  if($result['data']['level']==4 ) { ?>selected<?php  } ?> value="4">等级四</option>
                <option <?php  if($result['data']['level']==5 ) { ?>selected<?php  } ?> value="5">等级五</option>
                <option <?php  if($result['data']['level']==6 ) { ?>selected<?php  } ?> value="6">等级六</option>
                <option <?php  if($result['data']['level']==7 ) { ?>selected<?php  } ?> value="7">等级七</option>
                </select>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">上级</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <img width="50" src="<?php  echo $result['data']['invite_avatar'];?>"><?php  echo $result['data']['invite_nickname'];?>
                <?php  } ?>
            </div>
        </div>
		<div class="form-group">
            <label class="col-lg control-label">上等级</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('yoxwechatbusiness.edit')) { ?>
                <img width="50" src="<?php  echo $result['data']['up_level_avatar'];?>"><?php  echo $result['data']['up_level_nickname'];?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">店招</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo tpl_form_field_multi_image('banner', $result['data']['banner'])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">业务员</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo tpl_selector('docking_people_uid',array('required'=>false,'preview'=>false,'key'=>'uid','text'=>'nickname','type'=>'text','placeholder'=>'昵称/姓名/手机号','buttontext'=>'选择业务员', 'items'=>$result['data']['shop_member_info'],'url'=>webUrl('member/query') ))?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">状态</label>
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline"><input type="radio" name="status" value="0" <?php  if(empty($result['data']['status'])) { ?>checked="true"<?php  } ?>/>未激活</label>
                <label class="radio-inline"><input type="radio" name="status" value="1" <?php  if($result['data']['status'] == 1) { ?>checked="true"<?php  } ?>   /> 已激活</label>
                <label class="radio-inline"><input type="radio" name="status" value="4" <?php  if($result['data']['status'] == 4) { ?>checked="true"<?php  } ?>   /> 黑名单</label>

            </div>
        </div>
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
