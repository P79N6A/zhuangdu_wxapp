<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo FX_URL;?>web/resource/css/common.min.css?v=20170826" rel="stylesheet">
<link href="<?php echo FX_URL;?>web/resource/css/util.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo FX_URL;?>web/resource/js/util.min.js?v=20170912"></script>
<style>
.multi-img-details .multi-item{height:auto;}
#member.multi-img-details .multi-item{text-align:center; max-width:100px;}
#member.multi-img-details .multi-item img{ max-width:90px; max-height:90px;}
#member.multi-img-details .multi-item .title{ overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
</style>
<ul class="nav nav-tabs" id="myTab">
	<li class="active"><a href="#tab_basic">基本设置</a></li>
	<li><a href="#tab_param0">信息设置</a></li>
    <li><a href="#tab_param1">分享设置</a></li>
    <li><a href="#tab_param2">会员设置</a></li>
    <li><a href="#tab_param3">通知设置</a></li>
    <li><a href="#tab_param4">支付设置</a></li>
    <li><a href="#tab_param5">分类设置</a></li>
    <li><a href="#tab_param6">短信设置</a></li>
    <li><a href="#tab_param7">商户设置</a></li>
    <li><a href="#tab_param8">其它设置</a></li>
</ul>
<div class="main">
	<form class="form-horizontal form" action="" method="post">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_basic">
            	<div class="panel panel-default">
            		<div class="panel-heading">系统设置</div>
           			<div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页开启</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[homewitch]" value="1" <?php  if($settings['homewitch']==1 || $settings['homewitch']=='') { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[homewitch]" value="0" <?php  if($settings['homewitch']=='0') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">底部导航</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[navswitch]" value="1" <?php  if($settings['navswitch']==1 || $settings['navswitch']=='') { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[navswitch]" value="0" <?php  if($settings['navswitch']=='0') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">报名留言</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[joinmsg]" value="1" <?php  if($settings['joinmsg']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[joinmsg]" value="0" <?php  if(!$settings['joinmsg']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">单图模式</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[bigimg]" value="1" <?php  if($settings['bigimg']==1) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[bigimg]" value="0" <?php  if($settings['bigimg']==0 || $settings['bigimg']=='') { ?>checked="checked"<?php  } ?>> 关闭【发现列表单图模式展示】
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">附件尺寸</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[image][ratio]" value="1/1" <?php  if($settings['image']['ratio']=='1/1' || $settings['image']['bigimg']=='') { ?>checked="checked"<?php  } ?>> 1:1
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[image][ratio]" value="4/3" <?php  if($settings['image']['ratio']=='4/3') { ?>checked="checked"<?php  } ?>> 4:3
                                </label>
                                 <label class="radio radio-inline">
                                    <input type="radio" name="module[image][ratio]" value="16/9" <?php  if($settings['image']['ratio']=='16/9') { ?>checked="checked"<?php  } ?>> 16:9
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[image][ratio]" value="NaN" <?php  if($settings['image']['ratio']=='NaN') { ?>checked="checked"<?php  } ?>> 自由调整【只适用报名信息图片附件】
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">数据导出</label>
                            <div class="col-xs-12 col-sm-8">
                            	<div class="input-group">
                                	<span class="input-group-addon">每个文件</span>
                                    <input type="text" name="module[output][pagesize]" class="form-control" value="<?php  echo $settings['output']['pagesize'];?>" placeholder="默认：200条数据">
                                    <span class="input-group-addon">条【数值太大容易超时，建议 200~1000 条】</span>
                                </div>
                            </div>
                        </div>
                	</div>
                </div>
                
                <div class="panel panel-default">
            		<div class="panel-heading">详情设置</div>
           			<div class="panel-body">
                    	<div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页按钮</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[homebtn]" value="1" <?php  if($settings['homebtn'] || $settings['homebtn']=='') { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[homebtn]" value="0" <?php  if($settings['homebtn']=='0') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                		<div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">漂浮按钮</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[fbtn]" value="1" <?php  if($settings['fbtn'] || $settings['fbtn']=='') { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[fbtn]" value="0" <?php  if($settings['fbtn']=='0') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">抢购标语</label>
                            <div class="col-xs-12 col-sm-4">
                            	<div class="input-group">
                                    <input type="text" name="module[slogan]" class="form-control" value="<?php  echo $settings['slogan'];?>" placeholder="默认：大牌快抢">
                                    <span class="input-group-addon">最多4个字</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">客服设置</label>
                            <div class="col-md-8 js-kefu">
                            	<label class="checkbox-inline">
                                    <input type="checkbox" value="1" name="module[kefu][switch]" <?php  if($settings['kefu']['switch']) { ?>checked<?php  } ?>> 开启
                                </label><p></p>
                                <label class="radio-inline">
                                    <input type="radio" value="1" name="module[kefu][type]" <?php  if($settings['kefu']['type']=='1' || $settings['kefu']['type']=='') { ?>checked="checked"<?php  } ?>> 微信二维码
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" value="2" name="module[kefu][type]" <?php  if($settings['kefu']['type']=='2') { ?>checked="checked"<?php  } ?>> 第三方接口
                                </label>
                                <div class="kefu-qrcode" style="margin-top:15px;<?php  if($settings['kefu']['type']=='2') { ?>display:none<?php  } ?>">
                                    <?php  echo tpl_form_field_image('module[kefu][qrcode]', $settings['kefu']['qrcode']);?>
                                    <span class="help-block">建议640×870像素</span>
                                </div>
                                <div class="input-group kefu-url" style="margin-top:15px;<?php  if($settings['kefu']['type']!='2') { ?>display:none<?php  } ?>">
                                    <span class="input-group-addon">客服连接</span>
                                    <input type="text" name="module[kefu][url]" class="form-control" value="<?php  echo $settings['kefu']['url'];?>">
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-default">
            		<div class="panel-heading">城市定位</div>
           			<div class="panel-body">
                		<div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">开启位置</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[location]" value="1" <?php  if($settings['location']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[location]" value="0" <?php  if(!$settings['location']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">开启城市</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[citys]" value="1" <?php  if($settings['citys']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[citys]" value="0" <?php  if(!$settings['citys']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">初始区域</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[countrie]" value="1" <?php  if($settings['countrie']==1 || empty($settings['countrie'])) { ?>checked="checked"<?php  } ?>> 全国
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[countrie]" value="2" <?php  if($settings['countrie']==2) { ?>checked="checked"<?php  } ?>> 系统定位
                                </label>
                            </div>
                    	</div>
                    </div>
                </div>
                
                <div class="panel panel-default">
            		<div class="panel-heading">关注设置</div>
           			<div class="panel-body">
                		<div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">强制关注</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[guanzhu_join]" value="2" <?php  if($settings['guanzhu_join']==2) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[guanzhu_join]" value="1" <?php  if($settings['guanzhu_join']==1 || $settings['guanzhu_join']=='') { ?>checked="checked"<?php  } ?>> 关闭【认证服务号】
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">关注引导</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[guanzhu]" value="1" <?php  if($settings['guanzhu']==1) { ?>checked="checked"<?php  } ?>> 显示
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[guanzhu]" value="2" <?php  if($settings['guanzhu']==2 || $settings['guanzhu']=='') { ?>checked="checked"<?php  } ?>> 关闭【认证服务号】
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">关注二维码</label>
                            <div class="col-sm-8">
                                <?php  echo tpl_form_field_image('module[followed_image]', $settings['followed_image']);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_param0">
            	<div class="panel panel-default">
            		<div class="panel-heading">基本信息</div>
           			<div class="panel-body">
                    	<div class="alert alert-info">
                            <p style="text-indent: 18px;">注：当默认官方活动的时，主办方信息应用此处设置</p>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">官方名称</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[sname]" class="form-control" value="<?php  echo $settings['sname'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">官方图标</label>
                            <div class="col-sm-8">
                                <?php  echo tpl_form_field_image('module[slogo]', $settings['slogo']);?>		
                                <span class="help-block">建议150×150像素</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟关注</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[followno]" class="form-control" value="<?php echo $settings['followno']?$settings['followno']:0;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">官方介绍</label>
                            <div class="col-xs-12 col-sm-8">
                                 <textarea style="height:150px;" name="module[detail]" class="form-control" cols="60"><?php  echo $settings['detail'];?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">管理设置</label>
                            <div class="col-xs-12 col-sm-8">
                                <div class='input-group'>
                                    <input type="text" name="" maxlength="30" value="" class="form-control" readonly placeholder='可指定多个管理员【用于移动端管理，和接收全局通知】' />
                                    <div class='input-group-btn'>
                                        <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus').modal();">选择管理员</button>
                                        <button class="btn btn-danger" type="button" onclick="reset_admin();">重置</button>
                                    </div>
                                </div>
                                <div class="input-group multi-img-details" id="member">
                                    <?php  if(is_array($settings['openids'])) { foreach($settings['openids'] as $openid) { ?>
                                    <?php  $member = getMember($openid)?>
                                    <div class="multi-item">
                                    <img src="<?php  echo $member['avatar'];?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail">
                                    <input type="hidden" name="module[openids][]" value="<?php  echo $openid;?>">
                                    <em class="close" title="删除管理员" onclick="deleteMultiImage(this)">×</em>
                                    <p class="help-block title"><?php  echo $member['nickname'];?></p>
                                    </div>
                                    <?php  } } ?>
                                </div>
                                <div id="modal-module-menus"  class="modal fade" tabindex="-1">
                                    <div class="modal-dialog" style='width: 660px;'>
                                        <div class="modal-content">
                                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择管理员</h3></div>
                                            <div class="modal-body" >
                                                <div class="row">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="keyword" value="" id="member-kwd" placeholder="请输入粉丝昵称或openid" />
                                                        <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                                    </div>
                                                </div>
                                                <div id="module-member" style="padding-top:5px;"></div>
                                            </div>
                                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">场地名称</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[storename]" class="form-control" value="<?php  echo $settings['storename'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">位置坐标</label>
                            <div class="col-xs-12 col-sm-8">
                            <?php  echo tpl_form_field_position('module',array('lng'=>$settings['lng'],'lat'=>$settings['lat'],'adinfo'=>$settings['adinfo']))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">详细地址</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[address]" class="form-control" value="<?php  echo $settings['address'];?>" placeholder="活动详细地址" id="address"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">联系人员</label>
                            <div class="col-xs-12 col-sm-8">
                                <input name="module[linkman_name]" class="form-control" value="<?php  echo $settings['linkman_name'];?>" type="text" placeholder="联系人姓名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">联系电话</label>
                            <div class="col-xs-12 col-sm-8">
                                <input name="module[mobile]" class="form-control" value="<?php  echo $settings['mobile'];?>" type="text" placeholder='只可填写一个'>
                            </div>
                        </div>
                	</div>
                </div>
                <div class="panel panel-default">
            		<div class="panel-heading">客服信息</div>
                    <div class="panel-body">
                    	<div class="alert alert-info">
                            <p style="text-indent: 18px;">注：目前显示在移动端：我的->主办方->帮助</p>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">客服电话</label>
                            <div class="col-xs-12 col-sm-8">
                                <input name="module[kefutel]" class="form-control" value="<?php  echo $settings['kefutel'];?>" type="text" placeholder='多个用","号分开，不可填写中文'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">客服微信</label>
                            <div class="col-xs-12 col-sm-8">
                                <input name="module[kefuwixin]" class="form-control" value="<?php  echo $settings['kefuwixin'];?>" type="text">
                            </div>
                        </div>
                	</div>
                </div>
                <div class="panel panel-default">
            		<div class="panel-heading">版权信息</div>
                    <div class="panel-body">
                    	<div class="alert alert-info">
                            <p style="text-indent: 18px;">注：显示在移动端部分页面最下方，留空不显示</p>
                        </div>
                        <?php  $copyright = $settings['copyright']?>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">版权信息</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[copyright][title]" class="form-control" value="<?php echo !empty($copyright['title'])?$copyright['title']:'';?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">版权连接</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[copyright][link]" class="form-control" value="<?php echo !empty($copyright['link'])?$copyright['link']:'';?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">版权标志</label>
                            <div class="col-sm-8">
                                <?php echo tpl_form_field_image('module[copyright][logo]', !empty($copyright['logo'])?$copyright['logo']:'');?>
                                <span class="help-block">建议150×95像素</span>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param1">
            	<div class="panel panel-default">
            		<div class="panel-heading">系统设置</div>
           			<div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">分享标题</label>
                            <div class="col-sm-8 col-xs-12">
                                <input type="text" name="module[share][title]" class="form-control" value="<?php  echo $settings['share']['title'];?>" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">分享图片</label>
                            <div class="col-sm-8">
                                <?php  echo tpl_form_field_image('module[share][pic]', $settings['share']['pic']);?>
                                (建议150*150)
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">分享描述</label>
                            <div class="col-sm-8 col-xs-12">
                                <input type="text" name="module[share][desc]" class="form-control" value="<?php  echo $settings['share']['desc'];?>" placeholder="">
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param2">
            	<div class="panel panel-default">
            		<div class="panel-heading">系统设置</div>
           			<div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">积分</label>
                            <div class="col-xs-12 col-sm-8">
                            	<div class="input-group">
                                    <span class="input-group-addon">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="module[creditstatus]" value="1"<?php  if($settings['creditstatus']==1) { ?> checked="checked"<?php  } ?>>显示
                                        </label>
                                    </span>
                                    <input type="text" name="module[credit1link]" class="form-control" value="<?php  echo $settings['credit1link'];?>" placeholder="自定义积分跳转网址，不填写系统默认.">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">余额</label>
                            <div class="col-xs-12 col-sm-8">
                            	<div class="input-group">
                                    <span class="input-group-addon">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="module[credit2]" value="1"<?php  if($settings['credit2']==1) { ?> checked="checked"<?php  } ?>>显示
                                        </label>
                                    </span>
                                	<input type="text" name="module[credit2link]" class="form-control" value="<?php  echo $settings['credit2link'];?>" placeholder="自定义余额跳转网址，不填写系统默认.">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">查看权限</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[allowswitch]" value="1" <?php  if($settings['allowswitch']==1) { ?>checked="checked"<?php  } ?>> 开启【允许查看他人信息】
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[allowswitch]" value="0" <?php  if($settings['allowswitch']==0 || $settings['allowswitch']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param3">
            	<div class="panel panel-default">
            		<div class="panel-heading">系统设置</div>
           			<div class="panel-body">
                    	<div class="form-group">
                            <label class="col-md-2 control-label">配置说明</label>
                            <div class="col-md-8">
                                <div class="alert alert-warning" style="margin-bottom:0;">
                                    <h5 style="text-indent: 18px;">注: 仅限 <b>认证服务号</b> 添加消息模板后才可以使用模板消息功能（不设置，文本消息提醒）。</h5>
                                    <ol>
                                        <li>登陆<a href="https://mp.weixin.qq.com/">【微信公众平台】</a>-【功能】-【模板消息】</li>
                                        <li>选择行业为："IT科技 - 互联网|电子商务"</li>
                                        <li>在【模板库】搜索对应模板标题找到对应模板,点击详情保存添加,重复执行。</li>
                                        <li>把【我的模板】下对应标题复制模板ID到本页设置对应位置。</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    	<div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">通知开启</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[mmsg]" value="1" <?php  if($settings['mmsg']==1 || $settings['mmsg']=='') { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[mmsg]" value="0" <?php  if($settings['mmsg']=='0') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">报名成功通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_join]" class="form-control" value="<?php  echo $settings['m_join'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM406157086[名称：报名成功通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">报名付费通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_pay]" class="form-control" value="<?php  echo $settings['m_pay'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM205704547[名称：成员报名付费通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">报名取消通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_cancle]" class="form-control" value="<?php  echo $settings['m_cancle'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM406447723[名称：报名取消通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">消费成功通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_hexiao]" class="form-control" value="<?php  echo $settings['m_hexiao'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM410000708[名称：消费成功通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">审核结果通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_review]" class="form-control" value="<?php  echo $settings['m_review'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM409913268[名称：审核结果通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">提现申请提醒模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_cash]" class="form-control" value="<?php  echo $settings['m_cash'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM205459480[名称：提现申请提醒]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">活动状态通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_status]" class="form-control" value="<?php  echo $settings['m_status'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——TM00574[名称：报名状态通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">认证审核通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_mcert]" class="form-control" value="<?php  echo $settings['m_mcert'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务——OPENTM408469323[名称：认证审核通知]</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">订单退款提醒ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[m_refund]" class="form-control" value="<?php  echo $settings['m_refund'];?>">
                                <span class="help-block">公众平台模板消息编号：IT科技 - 互联网|电子商务OPENTM200565278[名称：订单退款提醒]</span>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param4">
            	<div class="panel panel-default">
            		<div class="panel-heading">支付配置</div>
           			<div class="panel-body">
                        <div class="alert alert-info">
                            <p style="text-indent: 18px;">注: 以下设置只有 <b>"认证公众号"</b> 设置有效，<b>"订阅号"</b> 请借用 <b>"服务号"</b> 的支付权限。</p>
                            <ol style="margin-bottom:0px;">
                                <li>非认证公众号请保留默认。</li>
                            </ol>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">微信支付</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[wechatstatus]" value="2" <?php  if($settings['wechatstatus']==2) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[wechatstatus]" value="1" <?php  if($settings['wechatstatus']==1 || $settings['wechatstatus']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">余额支付</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[creditpay]" value="1" <?php  if($settings['creditpay']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[creditpay]" value="0" <?php  if(!$settings['creditpay']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">线下付款</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[deliverystatus]" value="2" <?php  if($settings['deliverystatus']==2) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[deliverystatus]" value="1" <?php  if($settings['deliverystatus']==1 || $settings['deliverystatus']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                	</div>
                </div>
                
                <div class="panel panel-default">
            		<div class="panel-heading">退款配置</div>
           			<div class="panel-body">
                        <div class="form-group" style="display:none">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">选择退款方式</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[sys_refund]" value="1" <?php  if($settings['sys_refund']==1 || $settings['sys_refund']=='') { ?>checked="checked"<?php  } ?>> 旧版
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[sys_refund]" value="2" <?php  if($settings['sys_refund']==2) { ?>checked="checked"<?php  } ?>> 新版【微擎版本V1.5+，微擎平台：支付参数 - 退款配置】
                                </label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">商户支付证书</label>
                            <div class="col-sm-8 col-xs-12">
                                <textarea class="form-control" name="cert" rows="8" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入"></textarea>
                                <span class="help-block">从商户平台上下载支付证书, 解压并取得其中的 <mark>apiclient_cert.pem</mark> 用记事本打开并复制文件内容, 填至此处</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">支付证书私钥</label>
                            <div class="col-sm-8 col-xs-12">
                                <textarea class="form-control" name="key" rows="8" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入"></textarea>
                                <span class="help-block">从商户平台上下载支付证书, 解压并取得其中的 <mark>apiclient_key.pem</mark> 用记事本打开并复制文件内容, 填至此处</span>
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">rootca证书</label>
                            <div class="col-sm-8 col-xs-12">
                                <textarea class="form-control" name="rootca" rows="8" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入"></textarea>
                                <span class="help-block">从商户平台上下载支付证书, 解压并取得其中的 <mark>rootca.pem</mark> 用记事本打开并复制文件内容, 填至此处</span>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param5">
            	<div class="panel panel-default">
            		<div class="panel-heading">分类设置</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">开启分类</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[cateswitch]" value="1" <?php  if($settings['cateswitch']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[cateswitch]" value="0" <?php  if(!$settings['cateswitch']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页分类</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[catesindex]" value="1" <?php  if($settings['catesindex']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[catesindex]" value="0" <?php  if(!$settings['catesindex']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">全部图标</label>
                            <div class="col-sm-8">
                            	<label class="radio radio-inline">
                                    <input type="radio" name="module[catemore]" value="1" <?php  if($settings['catemore'] || $settings['catemore']=='') { ?>checked="checked"<?php  } ?>> 显示
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[catemore]" value="0" <?php  if($settings['catemore']=='0') { ?>checked="checked"<?php  } ?>> 隐藏
                                </label>
                                <p></p>
                                <?php echo tpl_form_field_image('module[catemoreico]', empty($settings['catemoreico'])?FX_URL.'app/resource/images/icon-class-all.png':$settings['catemoreico']);?>		
                                <span class="help-block">建议150×150像素</span>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param6">
            	<div class="panel panel-default">
            		<div class="panel-heading">系统设置</div>
           			<div class="panel-body">
                        <div class="alert alert-info">
                            <p style="text-indent: 18px;">注：<a target="_blank" href="https://www.aliyun.com/" class="product-grey-font">【点此申请阿里短信】</a>  <a target="_blank" href="https://www.alidayu.com/" class="product-grey-font">【旧接口登录】</a>，以下为短信模板变量：</p>
                            <ol style="margin-bottom:0px;">
                                <li>1、验证码模板变量：${code} = 验证码</li>
                                <li>2、通知模板变量：${product} = 当前公众号名称，${item} = 活动名称，${name} = 用户姓名，${timestr} = 活动开始时间，${idcode} = 核销码</li>
                                <li>3、通知模板请在活动编辑中设置</li>
                            </ol>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">手机验证</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[smsswitch]" value="1" <?php  if($settings['smsswitch']==1) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[smsswitch]" value="0" <?php  if($settings['smsswitch']==0 || $settings['smsswitch']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">接口类型</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[sms_type]" value="0" <?php  if($settings['sms_type']==0 || $settings['sms_type']=='') { ?>checked="checked"<?php  } ?>> 阿里大于
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[sms_type]" value="1" <?php  if($settings['sms_type']==1) { ?>checked="checked"<?php  } ?>> 阿里云通信【2017年6月28之后申请的】
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">KeyID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[sms_appkey]" class="form-control" value="<?php  echo $settings['sms_appkey'];?>" placeholder="应用管理 - 应用列表 - appkey">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">KeySecret</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[sms_appsecret]" class="form-control encrypt" value="<?php  echo $settings['sms_appsecret'];?>" placeholder="应用管理 - 应用列表 - 【设置】 查看">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">短信签名</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[sms_signname]" class="form-control encrypt" value="<?php  echo $settings['sms_signname'];?>" placeholder="短信签名，传入的短信签名必须是审核通过的签名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">验证码模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[sms_code]" class="form-control encrypt" value="<?php  echo $settings['sms_code'];?>" placeholder='"SMS_" 开头的字串'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">通知模板ID</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="module[sms_notify]" class="form-control encrypt" value="<?php  echo $settings['sms_notify'];?>" placeholder='"SMS_" 开头的字串'>
                            </div>
                        </div>
                	</div>
                </div>                
            </div>
            
            <div class="tab-pane" id="tab_param7">
            	<div class="panel panel-default">
            		<div class="panel-heading">系统设置</div>
           			<div class="panel-body">
                    	<div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">商户平台</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[merch]" value="1" <?php  if($settings['merch']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[merch]" value="0" <?php  if(!$settings['merch']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">管理入口</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[merch_entry]" value="1" <?php  if($settings['merch_entry']==1 || $settings['merch_entry']=='') { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[merch_entry]" value="0" <?php  if($settings['merch_entry']=='0') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                                <label class="radio radio-inline" style="padding-left:0;">【移动端个人中心主办方入口】</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">发布按钮</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[proswitch]" value="1" <?php  if($settings['proswitch']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[proswitch]" value="0" <?php  if(!$settings['proswitch']) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                                <label class="radio radio-inline" style="padding-left:0;">【单个用户开启请到主办方权限中设置】</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">绑定手机</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[bond]" value="1" <?php  if($settings['bond']) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[bond]" value="0" <?php  if(!intval($settings['bond'])) { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">发布免审</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[proreview]" value="1" <?php  if($settings['proreview']==1) { ?>checked="checked"<?php  } ?>> 所有
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[proreview]" value="2" <?php  if($settings['proreview']==2) { ?>checked="checked"<?php  } ?>> 免费
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[proreview]" value="0" <?php  if($settings['proreview']==0 || $settings['proreview']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                                <label class="radio radio-inline" style="padding-left:0;">【活动发布免审核】</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">强制认证</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[certswitch]" value="1" <?php  if($settings['certswitch']==1) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[certswitch]" value="0" <?php  if($settings['certswitch']==0 || $settings['certswitch']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                                <label class="radio radio-inline" style="padding-left:0;">【开启后商户必须认证才可提现】</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">商户佣金</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="input-group">
                                	<span class="input-group-addon">佣金比例</span>
                                    <input type="text" name="module[percent]" class="form-control" value="<?php  echo $settings['percent'];?>" placeholder='默认佣金比：0.6'>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">商户提现</label>
                            <div class="col-xs-12 col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">最低限额</span>
                                    <input type="text" name="module[merch_amount]" class="form-control" value="<?php  echo $settings['merch_amount'];?>" placeholder='系统默认最低：1'>
                                    <span class="input-group-addon">元，最大限额</span>
                                    <input type="text" name="module[merch_maximum]" class="form-control" value="<?php  echo $settings['merch_maximum'];?>" placeholder='默认：10000，最大：20000'>
                                    <span class="input-group-addon">元</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">平台图标</label>
                            <div class="col-sm-8">
                                <?php  echo tpl_form_field_image('module[merch_logo]', $settings['merch_logo']);?>
                                <span class="help-block">建议110×35像素，位于主办方登录平台左上角</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">主页封面</label>
                            <div class="col-sm-8">
                                <?php  echo tpl_form_field_image('module[merch_thumb]', $settings['merch_thumb']);?>
                                <span class="help-block">建议900×900像素，主办方主页默认封面背景</span>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab_param8">
            	<div class="panel panel-default">
            		<div class="panel-heading">计划任务</div>
                    <div class="panel-body">
                    	<div class="alert alert-info">
                            <p style="text-indent: 18px;">配制说明：阿里云控制台->产品服务->云监控->站点管理->创建监控点，参数设置默认即可。<span class="text-danger">【仅支持付费活动】</span></p>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">取消报名</label>
                            <div class="col-xs-12 col-sm-5">
                                <div class="input-group">
                                	<span class="input-group-addon">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="module[task][]" value="cancle" <?php  if($settings['task']['0']=='cancle') { ?>checked="checked"<?php  } ?>>开启
                                        </label>
                                    </span>
                                    <input type="text" name="module[cancle_time]" class="form-control" value="<?php  echo $settings['cancle_time'];?>" placeholder='默认为1小时'>
                                    <span class="input-group-addon">小时</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">提现处理</label>
                            <div class="col-xs-12 col-sm-5">
                                <div class="input-group">
                                	<span class="input-group-addon">
                                    	<label class="checkbox-inline">
                                            <input type="checkbox" name="module[task][]" value="cash" <?php  if($settings['task']['1']=='cash') { ?>checked="checked"<?php  } ?>>开启
                                        </label>
                                    </span>
                                    <input type="text" name="module[cash_time]" class="form-control" value="<?php  echo $settings['cash_time'];?>" placeholder='默认为1小时'>
                                    <span class="input-group-addon">小时</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">任务接口</label>
                            <div class="col-xs-12 col-sm-10">
                                <div class="bs-example bs-example-images" data-example-id="image-shapes" style="position:relative;">
                                    <a href="javascript:;" data-url="<?php  echo app_url('home/auto_task');?>" class="js-clip" id="js-copy"><span class="btn btn-success">点击复制接口连接</span></a>
                                </div>
                            </div>
                        </div>
                	</div>
                </div>
            	<div class="panel panel-default">
            		<div class="panel-heading">协议设置</div>
           			<div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">协议开启</label>
                            <div class="col-xs-12 col-sm-8">
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[agreement]" value="1" <?php  if($settings['agreement']==1) { ?>checked="checked"<?php  } ?>> 开启
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="module[agreement]" value="0" <?php  if($settings['agreement']==0 || $settings['agreement']=='') { ?>checked="checked"<?php  } ?>> 关闭
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">报名协议</label>
                            <div class="col-xs-12 col-sm-8">
                            <?php  echo tpl_ueditor('module[joinagreement]', $settings['joinagreement']);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">发布协议</label>
                            <div class="col-xs-12 col-sm-8">
                            <?php  echo tpl_ueditor('module[proagreement]', $settings['proagreement']);?>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
            
        </div>
		<div class="form-group col-sm-12">
			<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			<input type="submit" class="btn btn-primary col-lg-1" name="submit" value="提交" />
		</div>
	</form>
</div>
<script type="text/javascript">
require(['jquery', 'util'], function($, util){
	$('.js-copy').click(function(){
		util.clip(".js-copy", $(this).attr('data-url'));
	});
});
$('.js-clip').each(function(){
	util.clip(this, $(this).data('url'));
});
//微擎0.8显示switch
require(['bootstrap.switch'],function($){
	$('.js-flag:checkbox').bootstrapSwitch({onText: '开启', offText: '关闭'});
	$('.js-flag:checkbox').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = $(this).data('id');
		var ban = state ? 1 : 0;
	});
});
//微擎1.0+显示switch
$('.switch').click(function(e){
	var id = $(this).data('id');
	var state = $(this).hasClass("switchOff");
	var show = state ? 1 : 0;	
	$(this).toggleClass("switchOff");
	$(this).toggleClass("switchOn");

});
$(".js-kefu input[name='module[kefu][type]']").click(function(){
	var obj = $(this);
	if (obj.val()!='2'){
		$(".kefu-url").hide();
		$(".kefu-qrcode").show();
	}else{
		$(".kefu-url").show();
		$(".kefu-qrcode").hide();
	}
});
//管理员通知配制
function select_member(o) {
	var hh = '', istrue = true, modalobj = $('#modal-module-menus');
	$("input[name='module[openids][]']").each(function(){
		if ($(this).val()==o.openid){
			util.tips('管理员不能重复');
			istrue = false;
			return false;
		}
	});
	if (!istrue) return false;
	hh += '<div class="multi-item"><img src="'+o.avatar+'" onerror="this.src=\'./resource/images/nopic.jpg\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail">'
	+'	<input type="hidden" name="module[openids][]" value="'+o.openid+'">'
	+'	<em class="close" title="删除管理员" onclick="deleteMultiImage(this)">×</em>'
	+'	<p class="help-block title">'+o.nickname+'</p>'
	+'</div>';
	$("#member").append(hh);
	modalobj.modal('hide');
}
function reset_admin(){
	$("#member").html('');
}
function deleteMultiImage(obj){
	$(obj).parents(".multi-item").remove();
}
$(function () {
	window.optionchanged = false;
	$('#myTab a').click(function (e) {
		e.preventDefault();//阻止a链接的跳转行为
		$(this).tab('show');//显示当前选中的链接及关联的content
	})
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/footer', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/footer', TEMPLATE_INCLUDEPATH));?>