<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<style>
    .select22{
        padding:0 0 0 10px;
        border:0;
    }
    .select22 .select2{
        margin:0;
        width:100%;
        height:34px;
        border-radius: 3px;
        border-color: rgb(229, 230, 231);
    }
    .select22 .select2 .select2-choice{
        height: 34px;
        line-height: 32px;
        border-radius: 3px;
        border-color: rgb(229, 230, 231);
    }
    .select22 .select2 .select2-choice .select2-arrow{
        background: #fff;
    }

    .select2-drop li {position: relative}
    .select2-drop li:before {
        content: "";
        height: 0;
        width: 100%;
        border-top:1px solid #eee; position: absolute;
        top:0;
        left:0;
    }
    .select2-drop li:first-child:before {border-top: 0}
    .tabs-container .panel-body{
        border: 0;
        padding: 0;
    }
    .panel {
        border: 0;
        padding: 5px;
    }
    .panel-inline{
        width: 49%;
        display: inline-block;
    }
    /*.help-block {*/
        /*margin-bottom: 0px;*/
    /*}*/
    .input-group .col-sm-6.col-xs-12{
        margin: 0;
        padding: 0;
    }
</style>
<div class="page-header">
    <span class="pull-right">
        <?php if(cv('groups.notice.edit')) { ?>
        <div class=pull-right" style="padding-right: 28px;">
            <strong>高级模式</strong>
            <input class="js-switch small" id="is_advanced" type="checkbox" <?php  if(!empty($data['is_advanced'])) { ?>checked<?php  } ?>/>
        </div>
        <?php  } ?>
    </span>
    当前位置：<span class="text-primary">消息提醒设置</span>
</div>
<div class="page-content">
    <div class="alert alert-primary">
        请将公众平台模板消息所在行业选择为： IT科技/互联网|电子商务 --- <span class="text-danger">如果你使用高级模式则使用   <a href="<?php  echo webUrl('sysset/tmessage')?>">模板库(点击进入)</a>&nbsp;&nbsp;&nbsp;  <b>拼团仅支持自定义拼团类的模板消息</b></span>
    </div>
    <form action="" method="post" class="form-horizontal  form-validate" enctype="multipart/form-data" >
        <input type="hidden" value="<?php  echo intval($data['is_advanced'])?>" name='data[is_advanced]' />
        <div class="tabs-container" id="normal">
            <div class="tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#normal-tab-1" aria-expanded="true"> 卖家通知</a></li>
                    <li class=""><a data-toggle="tab" href="#normal-tab-2" aria-expanded="false"> 买家通知</a></li>
                </ul>
                <div class="tab-content ">
                    <div id="normal-tab-1" class="tab-pane active">
                        <div class="panel panel-default" >
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="col-md">
                                    <h4>卖家发货通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 " style="padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_teamsend_close_normal'];?>" name='data[groups_teamsend_close_normal]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_teamsend_close_normal'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <div class="col-sm-10 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <input type="text" name="data[groups_teamsend]" class="form-control" value="<?php  echo $data['groups_teamsend'];?>" />
                                        <div class="help-block">通知公众平台模板库【待办项目更新通知】编号: OPENTM207273120 </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static"><?php  echo $data['groups_teamsend'];?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div id="normal_user">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="normal-tab-2" class="tab-pane ">


                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4 class="">拼团成功通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_success_close_normal'];?>" name='data[groups_success_close_normal]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_success_close_normal'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="">
                                    <div class="col-sm-12 col-xs-12" >
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <input type="text" name="data[groups_success]" class="form-control" value="<?php  echo $data['groups_success'];?>" />
                                        <div class="help-block">公众平台模板库【订单状态提醒】编号:  OPENTM206848054  </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static"><?php  echo $data['groups_success'];?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>拼团失败通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_error_close_normal'];?>" name='data[groups_error_close_normal]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_error_close_normal'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="">
                                    <div class="col-sm-12 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <input type="text" name="data[groups_error]" class="form-control" value="<?php  echo $data['groups_error'];?>" />
                                        <div class="help-block">公众平台模板库【订单状态提醒】编号:  OPENTM206848054  </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static"><?php  echo $data['groups_error'];?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>支付成功通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_pay_close_normal'];?>" name='data[groups_pay_close_normal]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_pay_close_normal'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <div class="col-sm-12 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <input type="text" name="data[groups_pay]" class="form-control" value="<?php  echo $data['groups_pay'];?>" />
                                        <div class="help-block">公众平台模板库【支付成功通知】编号:  OPENTM402074550  </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static"><?php  echo $data['groups_pay'];?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>订单发货提醒</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_send_close_normal'];?>" name='data[groups_send_close_normal]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_send_close_normal'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body" >
                                <div class="">
                                    <div class="col-sm-12 col-xs-12" >
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <input type="text" name="data[groups_send]" class="form-control" value="<?php  echo $data['groups_send'];?>" />
                                        <div class="help-block">公众平台模板库【订单发货提醒】编号:  OPENTM200565259  </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static"><?php  echo $data['groups_send'];?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tabs-container" id="advanced">
            <div class="tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#advanced-tab-1" aria-expanded="true"> 卖家通知</a></li>
                    <li class=""><a data-toggle="tab" href="#advanced-tab-2" aria-expanded="false"> 买家通知</a></li>
                </ul>
                <div class="tab-content ">
                    <div id="advanced-tab-1" class="tab-pane active">
                        <div class="panel panel-default" >
                            <div class="panel-body">
                                <div class="col-md">
                                    <h4>卖家发货通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2" >
                                    <input type="hidden" value="<?php  echo $data['groups_teamsend_close_advanced'];?>" name='data[groups_teamsend_close_advanced]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_teamsend_close_advanced'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <div class="col-sm-10 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <div class="input-group" style="width: 300px;margin-top: 20px">
                                            <div class="col-sm-11 col-xs-12" style="padding: 0;">
                                                <div class="input-group-addon select22" style="padding: 10px 0;">
                                                    <select class="select2" name="data[groups_teamsend_template]">
                                                        <option value=''>从模板消息库中选择</option>
                                                        <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                                        <option value="<?php  echo $template_val['id'];?>" <?php  if($data['groups_teamsend_template'] == $template_val['id'] ) { ?>selected<?php  } ?>><?php  echo $template_val['title'];?></option>
                                                        <?php  } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="help-block"></div>
                                        <?php  } else { ?>
                                        <div class="form-control-static">
                                            <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                            <?php  if($data['groups_send_template'] == $template_val['id'] ) { ?><?php  echo $template_val['title'];?><?php  } ?>
                                            <?php  } } ?>
                                        </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div id="advanced_user"></div>
                            </div>
                        </div>
                    </div>
                    <div id="advanced-tab-2" class="tab-pane">


                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body" style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>拼团成功通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_success_close_advanced'];?>" name='data[groups_success_close_advanced]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_success_close_advanced'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <div class="col-sm-12 col-xs-12" >
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <div class="input-group-addon select22" style="padding: 0;">
                                            <select class="select2" name="data[groups_success_template]">
                                                <option value=''>从模板消息库中选择</option>
                                                <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                                <option value="<?php  echo $template_val['id'];?>" <?php  if($data['groups_success_template'] == $template_val['id'] ) { ?>selected<?php  } ?>><?php  echo $template_val['title'];?></option>
                                                <?php  } } ?>
                                            </select>
                                        </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static">
                                            <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                            <?php  if($data['groups_send_template'] == $template_val['id'] ) { ?><?php  echo $template_val['title'];?><?php  } ?>
                                            <?php  } } ?>
                                        </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body"  style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>拼团失败通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_error_close_advanced'];?>" name='data[groups_error_close_advanced]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_error_close_advanced'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body" >
                                <div class="">
                                    <div class="col-sm-12 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <div class="input-group-addon select22"  style="padding: 0;">
                                            <select class="select2" name="data[groups_error_template]">
                                                <option value=''>从模板消息库中选择</option>
                                                <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                                <option value="<?php  echo $template_val['id'];?>" <?php  if($data['groups_error_template'] == $template_val['id'] ) { ?>selected<?php  } ?>><?php  echo $template_val['title'];?></option>
                                                <?php  } } ?>
                                            </select>
                                        </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static">
                                            <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                            <?php  if($data['groups_send_template'] == $template_val['id'] ) { ?><?php  echo $template_val['title'];?><?php  } ?>
                                            <?php  } } ?>
                                        </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body"  style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>付款通知</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_pay_close_advanced'];?>" name='data[groups_pay_close_advanced]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_pay_close_advanced'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <div class="col-sm-12 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <div class="input-group-addon select22"  style="padding: 0;">
                                            <select class="select2" name="data[groups_pay_template]">
                                                <option value=''>从模板消息库中选择</option>
                                                <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                                <option value="<?php  echo $template_val['id'];?>" <?php  if($data['groups_pay_template'] == $template_val['id'] ) { ?>selected<?php  } ?>><?php  echo $template_val['title'];?></option>
                                                <?php  } } ?>
                                            </select>
                                        </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static">
                                            <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                            <?php  if($data['groups_send_template'] == $template_val['id'] ) { ?><?php  echo $template_val['title'];?><?php  } ?>
                                            <?php  } } ?>
                                        </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-inline" >
                            <div class="panel-body"  style="margin-bottom: 20px">
                                <div class="col-sm-9 col-xs-12">
                                    <h4>发货提醒</h4>
                                </div>
                                <?php if(cv('groups.notice.edit')) { ?>
                                <div class="col-sm-2 pull-right" style="text-align: right;padding-right: 28px" >
                                    <input type="hidden" value="<?php  echo $data['groups_send_close_advanced'];?>" name='data[groups_send_close_advanced]' />
                                    <input class="js-switch small" type="checkbox" <?php  if(empty($data['groups_send_close_advanced'])) { ?>checked<?php  } ?>/>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <div class="col-sm-12 col-xs-12">
                                        <?php if(cv('groups.notice.edit')) { ?>
                                        <div class="input-group-addon select22">
                                            <select class="select2" name="data[groups_send_template]">
                                                <option value=''>从模板消息库中选择</option>
                                                <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                                <option value="<?php  echo $template_val['id'];?>" <?php  if($data['groups_send_template'] == $template_val['id'] ) { ?>selected<?php  } ?>><?php  echo $template_val['title'];?></option>
                                                <?php  } } ?>
                                            </select>
                                        </div>
                                        <?php  } else { ?>
                                        <div class="form-control-static">
                                            <?php  if(is_array($template_list)) { foreach($template_list as $template_val) { ?>
                                            <?php  if($data['groups_send_template'] == $template_val['id'] ) { ?><?php  echo $template_val['title'];?><?php  } ?>
                                            <?php  } } ?>
                                        </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('groups.notice.edit')) { ?>
                <input type="submit"  value="提交" class="btn btn-primary"  />

                <?php  } ?>
            </div>
        </div>
    </form>
</div>
<script>
    $(".js-switch").click(function () {
        $(this).prev().val( this.checked ?0:1);
        if (this.checked)
        {
            $(this).parents(".panel-body").next(".panel-body").show();
        }
        else
        {
            $(this).parents(".panel-body").next(".panel-body").hide();
        }
    })

    $(function () {
        $("#is_advanced").click(function () {
            $(":input[name='data[is_advanced]']").val( this.checked ?1:0);
            if (this.checked)
            {
                getNoticeUser('advanced');
                $("#advanced").show();
                $("#normal").hide();
            }
            else
            {
                getNoticeUser('normal');
                $("#advanced").hide();
                $("#normal").show();
            }
        })

        if($(":input[name='data[is_advanced]']").val() == 1)
        {
            getNoticeUser('advanced');
            $("#advanced").show();
            $("#normal").hide();
        }
        else
        {
            getNoticeUser('normal');
            $("#advanced").hide();
            $("#normal").show();
        }
        $(".js-switch").each(function (key,val) {
            if (val.checked)
            {
                $(val).parents(".panel-body").next(".panel-body").show();
            }
            else
            {
                $(val).parents(".panel-body").next(".panel-body").hide();
            }
        })
        $.each($(".selectAll").parents(".tab-pane"),function (key,val) {
            $(val).find(".selectAll").attr("checked","true");
            $(val).find(".selectAll").next().attr("checked","true");
            $(val).find(":checkbox").not(".selectAll").each(function (k,v) {
                if (!v.checked)
                {
                    $(val).find(".selectAll").removeAttr("checked");
                    $(val).find(".selectAll").prev().removeAttr("checked");
                }
            });
        })

    })

    $(".selectAll").click(function () {
        var s = $(this).parents(".tab-pane");
        if (this.checked)
        {
            s.find(":checkbox").not(this).each(function (key,val) {
                if (!val.checked)
                    $(val).next().click();
            });
        }
        else
        {
            s.find(":checkbox").not(this).each(function (key,val) {
                if (val.checked)
                    $(val).next().click();
            });
        }

    })

    function getNoticeUser(val) {
        $.get("<?php  echo webUrl('groups/notice_user')?>",function (data) {
            if(val == 'normal')
            {
                $("#normal_user").html(data);
                $("#advanced_user").html('');
            }
            else
            {
                $("#advanced_user").html(data);
                $("#normal_user").html('');
            }
        })

    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>     