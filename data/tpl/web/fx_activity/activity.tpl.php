<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/header', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/header', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo FX_URL;?>web/resource/css/util.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo FX_URL;?>web/resource/js/util.min.js?v=20170912"></script>
<style>
.label-default{background-color:#d1dade;color:#5e5e5e;}
.input-group-addon .radio-inline, .input-group-addon .checkbox-inline{padding-top:0; line-height:0.95;}
.dropdown-menu{ min-width:145px; }
.multi-img-details .multi-item{height:auto;}
#member.multi-img-details .multi-item{text-align:center; max-width:100px;}
#member.multi-img-details .multi-item img{ max-width:90px; max-height:90px;}
#member.multi-img-details .multi-item .title{ overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
#myTab2.nav-tabs{border-color: #1ab394;}
#myTab2.nav-tabs>li>a{border-radius:0!important;padding: 7px 15px;}
#myTab2.nav-tabs>li>a:hover{ color:#333}
#myTab2.nav-tabs>li.active>a, #myTab2.nav-tabs>li.active>a:hover, #myTab2.nav-tabs>li.active>a:focus{color: #FFF;border-radius:0;background-color: #1ab394;border-color: #1ab394;}
#options.markethide .optmarket, .markethide.market{display:none}
</style>
<link href="<?php  echo $_W['siteroot'];?>web/resource/components/select2/select2.min.css" rel="stylesheet">
<?php  if($op == 'post') { ?>
<ol class="breadcrumb we7-breadcrumb" style="display:none">
	<a href="./index.php?c=site&amp;a=article&amp;"><i class="wi wi-back-circle"></i> </a>
	<li><a href="./index.php?c=site&amp;a=article&amp;">文章管理</a></li>
	<li><a href="./index.php?c=site&amp;a=article&amp;do=post&amp;">文章编辑</a></li>
</ol>
<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#tab_basic">基本设置</a></li>
    <li><a href="#tab_info">活动信息</a></li>
    <li><a href="#tab_detail">活动描述</a></li>
    <?php  if($_W['allow']['activity']['falseinfo'] || !$_W['allow']) { ?><li><a href="#tab_false">虚拟信息</a></li><?php  } ?>
    <li><a href="#tab_spec">规格库存</a></li>
    <li><a href="#tab_share">分享设置</a></li>
    <li><a href="#tab_prize">优惠返利</a></li>
    <li><a href="#tab_form">自定义表单</a></li>
    <li><a href="#tab_msg">通知客服</a></li>
    <li><a href="#tab_agreement">报名协议</a></li>
</ul>

<div class="main">
<form action="" method="post" onsubmit="return check(this)" class="form-horizontal form" enctype="multipart/form-data">
    <input type="hidden" name="activityid" value="<?php  echo $activity['id'];?>"/>
    <div class="tab-content">
		<div class="tab-pane in active" id="tab_basic">
			<div class="panel panel-default">
				<div class="panel-heading">基本设置</div>
                <div class="panel-body">
                	<div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">活动状态</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[show]" value="1"<?php  if($activity['show'] || empty($activity['show'])) { ?> checked<?php  } ?>> 上线
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[show]" value="0"<?php  if($activity['show']=='0') { ?> checked<?php  } ?>> 关闭
                            </label>
                        </div>
					</div>
                    <div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">退款申请</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][refund]" value="1"<?php  if($activity['switch']['refund']) { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][refund]" value="0"<?php  if(!$activity['switch']['refund']) { ?> checked<?php  } ?>> 关闭
                            </label>
                        </div>
                    </div>
					<div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">报名审核</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][joinreview]" value="1"<?php  if($activity['switch']['joinreview']) { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][joinreview]" value="0"<?php  if(!$activity['switch']['joinreview']) { ?> checked<?php  } ?>> 关闭【默认关闭无需审核】
                            </label>
                        </div>
					</div>
                    <div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">现场签到</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][signin]" value="1"<?php  if($activity['switch']['signin']) { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][signin]" value="0"<?php  if(!$activity['switch']['signin']) { ?> checked<?php  } ?>> 关闭【用户现场扫描活动二维码签到】
                            </label>
                        </div>
					</div>
                    <div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">显示头像</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][avatar]" value="1"<?php  if($activity['switch']['avatar'] || $activity['switch']['avatar']=='') { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][avatar]" value="0"<?php  if($activity['switch']['avatar']=='0') { ?> checked<?php  } ?>> 关闭【详情页显示报名用户头像列表】
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">已报人数</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][joinnum]" value="1"<?php  if($activity['switch']['joinnum'] || $activity['switch']['joinnum']=='') { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][joinnum]" value="0"<?php  if($activity['switch']['joinnum']=='0') { ?> checked<?php  } ?>> 关闭【开启时手机端显示已报名人数】
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">取消报名</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][joincancel]" value="1"<?php  if($activity['switch']['joincancel']) { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][joincancel]" value="0"<?php  if(!$activity['switch']['joincancel']) { ?> checked<?php  } ?>> 关闭【已支付用户不可取消】
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">重复报名</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][rejoin]" value="1"<?php  if($activity['switch']['rejoin']) { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[switch][rejoin]" value="0"<?php  if(!$activity['switch']['rejoin']) { ?> checked<?php  } ?>> 关闭【开启后可重复报名】
                            </label>
                        </div>
                    </div>
                    
				</div>
			</div>
		</div>
        
        <div class="tab-pane" id="tab_info">
        	<div class="panel panel-default">
				<div class="panel-heading">基本信息</div>
                <div class="panel-body">
                	<div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">活动类型</label>
                        <div class="col-md-6 js-atype">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[atype]" value="1" <?php  if(intval($activity['atype']) ==1 || empty($activity['atype'])) { ?>checked="checked"<?php  } ?>> 常规活动
                            </label>
                            <label class="radio radio-inline"<?php  if(!$_W['plugin']['card']['config']['card_enable']) { ?> style="display:none"<?php  } ?>>
                                <input type="radio" name="activity[atype]" value="2" <?php  if(intval($activity['atype']) ==2) { ?>checked="checked"<?php  } ?>> 支持年卡
                            </label>
                            <label class="radio radio-inline"<?php  if(!$_W['plugin']['card']['config']['card_enable']) { ?> style="display:none"<?php  } ?>>
                                <input type="radio" name="activity[atype]" value="3" <?php  if(intval($activity['atype']) ==3) { ?>checked="checked"<?php  } ?>> 仅限年卡
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[atype]" value="4" <?php  if(intval($activity['atype']) ==4) { ?>checked="checked"<?php  } ?>> 仅供浏览
                            </label>
                        </div>
                    </div>
                	<div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动分类</label>
                        <div class="col-md-6">
                         <?php  echo tpl_form_field_category_2level('activity', $category['0'], $category['1'], $activity['parentid'], $activity['childid'])?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">主办单位</label>
                        <div class="col-md-6">
                        	<?php  if(MERCHANTID) { ?>
                            <span class="uneditable-input form-control"><?php  echo $merchant['name'];?></span>
                            <input name="activity[merchantid]" value="<?php  echo $merchant['id'];?>" type="hidden">
                            <?php  } else { ?>
                            <select name="activity[merchantid]" class="form-control s1">
                            <?php  if($_W['user']['uid']) { ?>
                                <option value="0" <?php  if(empty($activity['merchantid'])) { ?>selected="selected"<?php  } ?>><?php  echo $_W['_config']['sname'];?></option>
                            <?php  } ?>
                            <?php  if(is_array($merchants)) { foreach($merchants as $row) { ?>
                                <option value="<?php  echo $row['id'];?>" <?php  if($activity['merchantid']==$row['id']) { ?>selected="selected"<?php  } ?>><?php  echo $row['name'];?></option>
                            <?php  } } ?>
                            </select>
                            <?php  } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">设置场地</label>
                        <div class="col-sm-6 col-xs-12 chks">
                            <div class='input-group'>
                                <input type="text" name="saler" maxlength="30" value="<?php  if(is_array($salers)) { foreach($salers as $saler) { ?> <?php  echo $saler['nickname'];?>; <?php  } } ?>" id="saler" class="form-control" readonly placeholder="如果不选择场地，则适用当前主办方所有场地"/>
                                <div class='input-group-btn'>
                                    <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus').modal();">选择场地</button>
                                </div>
                            </div>
                    
                            <div style="margin-top:.5em;" class="input-group multi-audio-details clear-fix" id='stores'>
                                <?php  if(is_array($stores)) { foreach($stores as $store) { ?>
                                <div style="height: 40px; position:relative; float: left; margin-right: 18px;" class="multi-audio-item" storeid="<?php  echo $store['id'];?>">
                                    <div class="input-group">
                                        <input type="hidden" value="<?php  echo $store['id'];?>" name="storeids[]">
                                        <input type="text" value="<?php  echo $store['storename'];?>" readonly="" class="form-control">
                                        <div class="input-group-btn">
                                            <button type="button" onclick="remove_store(this)" class="btn btn-default"><i class="fa fa-remove"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <?php  } } ?>
                            </div>
                    
                            <div id="modal-module-menus" class="modal fade" tabindex="-1">
                                <div class="modal-dialog" style='width: 920px;'>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                            <h3>选择场地</h3>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="输入场地名称、如不填写，搜索后显示当前主办方所有场地" />
                                                    <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_stores();">搜索</button></span>
                                                </div>
                                            </div>
                                            <div id="module-menus" style="padding-top:5px;"></div>
                                        </div>
                                        <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                    </div>
                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="activity[hasstore]" id="hasstore" value="1" <?php  if($activity['hasstore']==1) { ?>checked<?php  } ?>>内部定义
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="activity[hasonline]" value="1" <?php  if($activity['hasonline']==1) { ?>checked<?php  } ?>>线上活动
                            </label>
                        </div>
                    </div>
                    <script language='javascript'>
						$('select[name="activity[merchantid]"]').on('change', function(e){
							$('#module-menus').html(''),$('#stores').html('');
						});
                        function search_stores() {
							var merchantid = $("select[name='activity[merchantid]']").val();
							$("#module-menus").html("正在搜索....")
							$.get("<?php  echo web_url('application/hexiao/selectstore')?>", {keyword: $.trim($('#search-kwd').val()), merchantid: merchantid}, function(dat){
								$('#module-menus').html(dat);
							});
						}
                        function remove_store(obj){
							var storeid = $(obj).closest('.multi-audio-item').attr('storeid');
								$('.multi-audio-item[storeid="' + storeid +'"]').remove();
						}
                        function select_store(o) {
							if($('.multi-audio-item[storeid="' + o.id +'"]').length>0){
								return;
							}
							var html ='<div style="height: 40px; position:relative; float: left; margin-right: 18px;" class="multi-audio-item" storeid="' + o.id +'">';
								html+='<div class="input-group">';
								html+='<input type="hidden" value="' + o.id +'" name="storeids[]">';
								html+='<input type="text" value="' + o.storename +'" readonly="" class="form-control">';
								html+='<div class="input-group-btn"><button type="button" onclick="remove_store(this)" class="btn btn-default"><i class="fa fa-remove"></i></button></div>';
								html+='</div></div>';
							$('#stores').append(html);
						}
                    </script>
                    <div id="tbstore" style="<?php  if($activity['hasstore']!=1) { ?>display:none<?php  } ?>">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="input-group">
                                	<span class="input-group-addon">场地名称</span>
                                	<input type="text" name="activity[addname]" class="form-control" value="<?php  echo $activity['addname'];?>" placeholder="例如XXX大厦"/>
                                </div>
                                <div class="input-group" style="margin-top:10px">
                                	<span class="input-group-addon">联系电话</span>
                                	<input type="text" name="activity[tel]" class="form-control" value="<?php  echo $activity['tel'];?>" placeholder="不填写不显示"/>
                                </div>
                                <div class="input-group" style="margin-top:10px">
                                	<span class="input-group-addon">详细地址</span>
                                	<input type="text" name="activity[address]" class="form-control" value="<?php  echo $activity['address'];?>" placeholder="活动详细地址" id="address"/>
                                </div>
                                <div class="input-group" style="margin-top:10px">
                                	<span class="input-group-addon">场地坐标</span>
                                	<?php  echo tpl_form_field_position('activity',array('lng'=>$activity['lng'],'lat'=>$activity['lat'],'adinfo'=>$activity['adinfo']))?>
                                </div>
                                
                            </div>
                            <div class="col-xs-12 col-sm-4">
                            	<div class="help-block" style="padding-top:0px;">
                                    提醒：开启内部定义场地，选择的场地、核销员将失效，也不再有核销权限，需要设置当前主办方全局核销员。
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
        	<div class="panel panel-default">
				<div class="panel-heading">活动信息</div>
                <div class="panel-body">
                	
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动名称</label>
                        <div class="col-sm-6 col-xs-12">
                            <input type="text" name="activity[title]" class="form-control" value="<?php  echo $activity['title'];?>" placeholder="活动名称"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">顶部标题</label>
                        <div class="col-sm-6 col-xs-12">
                            <input type="text" name="activity[pagetitle]" class="form-control" value="<?php  echo $activity['pagetitle'];?>" placeholder="默认：活动详情；位于移动端顶部标题栏"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">活动单价</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">￥</span>
                                <input type="text" name="activity[aprice]" class="form-control" value="<?php  echo $activity['aprice'];?>" placeholder="不设置，视为免费">
                                <span class="input-group-addon">免费标签</span>
                                <input type="text" name="activity[freetitle]" class="form-control" value="<?php  echo $activity['freetitle'];?>" placeholder="默认：免费活动">
                            </div>
                        </div>
                    </div>
                    <?php  if($_W['plugin']['card']['config']['card_enable']) { ?>
                    <div class="form-group market<?php  if($activity['atype']!=2) { ?> markethide<?php  } ?>">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">年卡价</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">￥</span>
                                <input type="text" name="activity[marketprice]" class="form-control" value="<?php  echo $activity['marketprice'];?>" placeholder="不设置，视为免费">
                            </div>
                        </div>
                    </div>
                    <?php  } ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">消耗积分</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="number" name="activity[costcredit]" class="form-control" value="<?php  echo $activity['costcredit'];?>" placeholder="不设置，不消耗积分">
                                <span class="input-group-addon">分</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">报名时间</label>
                        <div class="col-sm-6 col-xs-12">
                        <?php  echo tpl_form_field_daterange('joinTime', array('start' =>$activity['joinstime'],'end' =>$activity['joinetime']), true);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动时间</label>
                        <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_daterange('activityTime', array('start' =>$activity['starttime'],'end' =>$activity['endtime']), true);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">缩略图片</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="hidden" name="thumb_old" value="<?php  echo $activity['thumb'];?>">
                        	<?php  echo tpl_form_field_image('activity[thumb]', $activity['thumb']);?>
                            <span class="help-block">图片建议大小：640×380像素</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">详情图集</label>
                        <div class="col-sm-9">
                        	<?php  echo tpl_form_field_multi_image('atlas',$activity['atlas']);?>
                            <span class="help-block">图片建议大小：640×380像素</span>
                        </div>
                    </div>
				</div>
			</div>
        </div>
        
		<div class="tab-pane" id="tab_detail">
			<div class="panel panel-default">
				<div class="panel-heading">简介详情</div>
				<div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">简要概述</label>
                        <div class="col-sm-9 col-xs-12">
                        <textarea class="form-control" name="activity[intro]" rows="3" placeholder="活动简要概述，建议30个字符左右"><?php  echo $activity['intro'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">报名须知<span class="help-block" style="padding-left:10px;font-size:12px;">PS：(这里填写一些报名规则细节，不支持图片)</span></label>
                        <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_ueditor('activity[info]', $activity['info']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动详情</label>
                        <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_ueditor('activity[detail]', $activity['detail'], array('height'=>300));?>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        
        <div class="tab-pane" id="tab_false">
			<div class="panel panel-default">
				<div class="panel-heading">虚拟信息</div>
				<div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟阅读</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="activity[falsedata][read]" class="form-control" value="<?php  echo $activity['falsedata']['read'];?>" placeholder="在此填写虚拟阅读量"/>
                                <span class="input-group-addon">次</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟转发</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="activity[falsedata][share]" class="form-control" value="<?php  echo $activity['falsedata']['share'];?>" placeholder="在此填写虚拟转发量"/>
                                <span class="input-group-addon">次</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟收藏</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="activity[falsedata][favorite]" class="form-control" value="<?php  echo $activity['falsedata']['favorite'];?>" placeholder="在此填写虚拟收藏量"/>
                                <span class="input-group-addon">次</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟报名</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="activity[falsedata][num]" class="form-control" value="<?php  echo $activity['falsedata']['num'];?>" placeholder="请填写虚拟报名人数"/>
                                <span class="input-group-addon">人【开启规格后，请在规格内设置】</span>
                            </div>
                            <!--<input type="checkbox" name="scratch[only_others]" id="only_others" value="1"  />仅送给未中奖的用户-->
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟昵称</label>
                        <div class="col-md-9">
                                <input type="text" name="activity[falsedata][nickname]" class="form-control" value="<?php  echo $activity['falsedata']['nickname'];?>" placeholder='虚拟用户昵称，多个用"，"号分开'/>
                            <!--<input type="checkbox" name="scratch[only_others]" id="only_others" value="1"  />仅送给未中奖的用户-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟头像</label>
                        <div class="col-sm-9">
                        	<?php  echo tpl_form_field_multi_image('head',$activity['falsedata']['head']);?>
                            <span class="help-block">虚拟用户头像，建议：65X65</span>
                        </div>
                    </div>
				</div>
			</div>
		</div>
                
		<div class="tab-pane" id="tab_spec">
			<div class="panel panel-default">
				<div class="panel-heading">规格库存</div>
                <div class="panel-body">
                	<div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">单位类型</label>
                        <div class="col-md-6">
                        	<input type="text" name="activity[unitstr]" class="form-control" value="<?php echo $activity['unitstr']?$activity['unitstr']:'';?>" placeholder="如：‘张、份’，默认‘名额’"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动库存</label>
                        <div class="col-md-6">
                            <div class="input-group">
                            	<span class="input-group-addon">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" <?php  if($activity['gnumshow']==1) { ?>checked<?php  } ?> value="1" onclick='setSwitch(this)'>显示
                                        <input type="hidden" name="activity[gnumshow]" VALUE="<?php echo $activity['gnumshow'] ? 1 : 0;?>" />
                                    </label>
                                </span>
                                <input type="text" name="activity[gnum]" class="form-control" value="<?php echo $activity['gnum']?$activity['gnum']:'';?>" placeholder="不填写名额不限"/>
                                <span class="input-group-addon">名额</span>
                            </div>
                            <!--<input type="checkbox" name="scratch[only_others]" id="only_others" value="1"  />仅送给未中奖的用户-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">限购设置</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="activity[limitnum]" class="form-control" value="<?php echo $activity['limitnum']?$activity['limitnum']:'';?>"/><span class="input-group-addon">名额</span>				
                            </div>
                            <span class="help-block">单次购买数量上限，默认不填或填 0 则不限制。（填 1 则不显示数量输入框）</span>
                            <!--<input type="checkbox" name="scratch[only_others]" id="only_others" value="1"  />仅送给未中奖的用户-->
                        </div>
                    </div>
                    <?php  include fx_template('activity/spec_option');?>
				</div>
			</div>
		</div>
                
		<div class="tab-pane" id="tab_share">
			<div class="panel panel-default">
				<div class="panel-heading">分享设置</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">分享标题</label>
                        <div class="col-sm-8 col-xs-12">
                            <input type="text" name="activity[sharetitle]" class="form-control" value="<?php  echo $activity['sharetitle'];?>" placeholder="如果不填写，系统默认">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享图标</label>
                        <div class="col-sm-8 col-xs-12">
                        <?php  echo tpl_form_field_image('activity[sharepic]', $activity['sharepic']);?>
                            <div class="help-block">
                                图片建议为正方形，如果不选择，默认为活动缩略图片
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">分享描述</label>
                        <div class="col-sm-8 col-xs-12">
                            <input type="text" name="activity[sharedesc]" class="form-control" value="<?php  echo $activity['sharedesc'];?>" placeholder="如果不填写，系统默认">
                        </div>
                    </div>
				</div>
			</div>
		</div>
                
		<div class="tab-pane" id="tab_prize">
			<div class="panel panel-default">
				<div class="panel-heading">奖励设置</div>
				<div class="panel-body">
                    <div class="form-group" style=" display:none">
                        <label class="col-md-2 control-label">配置说明</label>
                        <div class="col-md-8">
                            <div class="alert alert-warning">
                                <p style="text-indent: 18px;">
                                    注: 只有 <b>认证公众号</b> 设置有效，借用权限不支持。
                                </p>
                                <ol style="margin-bottom:0px;">
                                    <li>此栏目默认不生效，需要在参数设置 <b>会员设置</b> 中开启/关闭。</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <?php  if($_W['allow']['activity']['credit'] || !$_W['allow']) { ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">积分奖励</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">报名赠送</span>
                                <input type="text" name="prize[credit]" class="form-control" value="<?php  if($activity['prize']['credit']=='') { ?>0<?php  } else { ?><?php  echo $activity['prize']['credit'];?><?php  } ?>">
                                <span class="input-group-addon">分，取消扣除</span>
                                <input type="text" name="prize[creditoff]" class="form-control" value="<?php  if($activity['prize']['creditoff']=='') { ?>0<?php  } else { ?><?php  echo $activity['prize']['creditoff'];?><?php  } ?>">
                                <span class="input-group-addon">分</span>
                            </div>
                            <span class="help-block">会员报名当前活动所获得的积分,如果不填或者填0，则默认为不奖励积分。</span>
                            <p></p>
                            <div class="input-group">
                                <span class="input-group-addon">分享赠送</span>
                                <input type="text" name="prize[share_credit]" class="form-control" value="<?php  if($activity['prize']['share_credit']=='') { ?>0<?php  } else { ?><?php  echo $activity['prize']['share_credit'];?><?php  } ?>">
                                <span class="input-group-addon">分，每天奖励</span>
                                <input type="text" name="prize[share_times]" class="form-control" value="<?php  if($activity['prize']['share_times']=='') { ?>0<?php  } else { ?><?php  echo $activity['prize']['share_times'];?><?php  } ?>">
                                <span class="input-group-addon">次</span>
                            </div>
                            <span class="help-block">会员分享当前活动所获得的积分,如果不填或者填0，则默认为不奖励积分。</span>
                            <p></p>
                            <div class="input-group">
                                <span class="input-group-addon">签到赠送</span>
                                <input type="text" name="prize[sign_credit]" class="form-control" value="<?php  if($activity['prize']['sign_credit']=='') { ?>0<?php  } else { ?><?php  echo $activity['prize']['sign_credit'];?><?php  } ?>">
                                <span class="input-group-addon">分</span>
                                <!--
                                <input type="text" name="prize[sign_times]" class="form-control" value="<?php  if($activity['prize']['sign_times']=='') { ?>0<?php  } else { ?><?php  echo $activity['prize']['sign_times'];?><?php  } ?>">
                                <span class="input-group-addon">次</span>
                                -->
                            </div>
                            <span class="help-block">会员签到当前活动所获得的积分,如果不填或者填0，则默认为不奖励积分。</span>
                        </div>
                    </div>
                    <?php  } ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">抽奖设置</label>
                        <div class="col-md-8">
                        	<label class="radio-inline">
                                <input type="radio" value="1" name="prize[lottery][enable]" <?php  if($activity['prize']['lottery']['enable']) { ?>checked<?php  } ?>> 开启
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="0" name="prize[lottery][enable]" <?php  if(!$activity['prize']['lottery']['enable']) { ?>checked<?php  } ?>> 关闭
                            </label>
                        	<div class="input-group" style=" margin-top:15px">
                            	<span class="input-group-addon">抽奖连接</span>
                                <input type="text" name="prize[lottery][url]" class="form-control" value="<?php  echo $activity['prize']['lottery']['url'];?>">
                            </div>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="1" name="prize[lottery][fee]" <?php  if($activity['prize']['lottery']['fee']) { ?>checked<?php  } ?>> 仅限付费用户
                            </label>
                        </div>
                    </div>
                    <?php  if($_W['plugin']['card']['config']['card_enable']) { ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">年卡折扣</label>
                        <div class="col-md-8">
                        	<label class="radio-inline">
                                <input type="radio" value="1" name="prize[cardper][enable]" <?php  if($activity['prize']['cardper']['enable']) { ?>checked<?php  } ?>> 开启
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="0" name="prize[cardper][enable]" <?php  if(!$activity['prize']['cardper']['enable']) { ?>checked<?php  } ?>> 关闭
                            </label>
                        	<div class="input-group" style=" margin-top:15px">
                            	<span class="input-group-addon">专享折扣</span>
                                <input type="text" name="prize[cardper][percent]" class="form-control" value="<?php  echo $activity['prize']['cardper']['percent'];?>" placeholder="系统默认：<?php echo empty($_W['plugin']['card']['config']['percent'])?'8.8':$_W['plugin']['card']['config']['percent']?> 折">
                                <span class="input-group-addon">折</span>
                            </div>
                        </div>
                    </div>
                    <?php  } ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">优惠设置</label>
                        <div class="col-md-8">
                            <ul class="nav nav-tabs" id="myTab2">
                                <li class="active"><a href="#tab_discount">折扣</a></li>
                                <li class=""><a href="#tab_enough">满减</a></li>
                                <li class=""><a href="#tab_deduction">积分抵扣</a></li>
                                <li class=""><a href="#tab_mcgroup">会员组</a></li>
                            </ul>
                            <div class="tab-content">
                            	<div class="tab-pane active" id="tab_discount">
                                    <div class="recharge-items">
                                    	<?php  if(is_array($marketing['0'])) { foreach($marketing['0'] as $key => $v) { ?>
                                    	<div class="input-group recharge-item" style="margin-top:5px"><span class="input-group-addon">单次报名满</span><input type="text" class="form-control" name="discount[<?php  echo $key;?>][meet]" value="<?php  echo $v['meet'];?>"><span class="input-group-addon">人 优惠</span><input type="text" class="form-control" name="discount[<?php  echo $key;?>][give]" value="<?php  echo $v['give'];?>"><span class="input-group-addon">折</span><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="removeConsumeItem(this)"><i class="fa fa-remove"></i></button></div></div>
                                        <?php  } } ?>
                                    </div>
                                    <div style="margin-top:5px">
                                        <button type="button" class="btn btn-default" onclick="addConsumeItem(this,'discount')" style="margin-bottom:5px"><i class="fa fa-plus"></i> 增加优惠项</button>
                                    </div>
                                    <span class="help-block">同时设置折扣、满减，默认折扣生效【注：开启规格时建议选择折扣类型】</span>
                                </div>
                                <div class="tab-pane" id="tab_enough">
                                    <div class="recharge-items">
                                    	<?php  if(is_array($marketing['1'])) { foreach($marketing['1'] as $key => $v) { ?>
                                    	<div class="input-group recharge-item" style="margin-top:5px"><span class="input-group-addon">单次报名满</span><input type="text" class="form-control" name="enough[<?php  echo $key;?>][meet]" value="<?php  echo $v['meet'];?>"><span class="input-group-addon">人 立减</span><input type="text" class="form-control" name="enough[<?php  echo $key;?>][give]" value="<?php  echo $v['give'];?>"><span class="input-group-addon">元</span><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="removeConsumeItem(this)"><i class="fa fa-remove"></i></button></div></div>
                                        <?php  } } ?>
                                    </div>
                                    <div style="margin-top:5px">
                                        <button type="button" class="btn btn-default" onclick="addConsumeItem(this,'enough')" style="margin-bottom:5px"><i class="fa fa-plus"></i> 增加优惠项</button>
                                    </div>
                                    <span class="help-block">同时设置折扣、满减，默认折扣生效【注：开启规格时建议选择折扣类型】</span>
                                </div>
                                
                                <div class="tab-pane" id="tab_deduction">
                                	<?php  include fx_template('activity/marketing/deduction');?>
                                </div>
                                
                                <div class="tab-pane" id="tab_mcgroup">
                                    <div class="recharge-items">
                                    	<?php  if(is_array($marketing['2'])) { foreach($marketing['2'] as $key => $v) { ?>
                                    	<div class="input-group recharge-item" style="margin-top:5px">
                                            <span class="input-group-addon">折扣</span>
                                            <input type="text" class="form-control" name="mcgroup[<?php  echo $key;?>][discount]" value="<?php  echo $v['discount'];?>">
                                            <span class="input-group-addon">折 立减</span>
                                            <input type="text" class="form-control" name="mcgroup[<?php  echo $key;?>][money]" value="<?php  echo $v['money'];?>">
                                            <span class="input-group-addon">元</span>
                                            <div class="input-group-btn">
                                            	<input type="hidden" name="mcgroup[<?php  echo $key;?>][grouptitle]" value="<?php  echo $v['grouptitle'];?>">
                                                <select name="mcgroup[<?php  echo $key;?>][groupid]" class="btn btn-default" style="padding-right:45px;">
                                                    <?php  if(is_array($mcgroups)) { foreach($mcgroups as $g) { ?>
                                                    <option value="<?php  echo $g['groupid'];?>"<?php  if($v['groupid']==$g['groupid']) { ?> selected<?php  } ?>><?php  echo $g['title'];?></option>
                                                    <?php  } } ?>
                                                </select>
                                                <button class="btn btn-danger" type="button" onclick="removeConsumeItem(this)"><i class="fa fa-remove"></i></button>
                                            </div>
                                        </div>
                                        <?php  } } ?>
                                    </div>
                                    <div style="margin-top:5px">
                                        <button type="button" class="btn btn-default" onclick="addConsumeItem(this,'mcgroup')" style="margin-bottom:5px"><i class="fa fa-plus"></i> 增加优惠项</button>
                                    </div>
                                </div>
                            </div>
                            <script>
							$(function () {
								window.optionchanged = false;
								$('#myTab2 a').click(function (e) {
									e.preventDefault();//阻止a链接的跳转行为
									$(this).tab('show');//显示当前选中的链接及关联的content
								})
							});
							</script>
                        </div>
                    </div>
					<script type="text/javascript">
                        function addConsumeItem(obj,type){
							var key = $("#tab_"+type+" .recharge-item").length;
							var mcgroups = <?php  echo json_encode($mcgroups);?>;
                            var html = '';
                            html = '<div class="input-group recharge-item" style="margin-top:5px">'
								+'<span class="input-group-addon">单次报名满</span>'
								+'<input type="text" class="form-control" name="'+type+'['+key+'][meet]" value="">'
								+'<span class="input-group-addon">人 '+(type=='discount'?'优惠':'立减')+'</span>'
								+'<input type="text" class="form-control" name="'+type+'['+key+'][give]" value="">'
								+'<span class="input-group-addon">'+(type=='discount'?'折':'元')+'</span>'
								+'<div class="input-group-btn">'
								+'	<button class="btn btn-danger" type="button" onclick="removeConsumeItem(this)"><i class="fa fa-remove"></i></button>'
								+'</div>'
							+'</div>';
							if (type == 'mcgroup'){
								var option;
								$.each(mcgroups, function(i, o) {
									option+='<option value="'+o.groupid+'">'+o.title+'</option>';
								});
								html = '<div class="input-group recharge-item" style="margin-top:5px">'
									+'<span class="input-group-addon">折扣</span>'
									+'<input type="text" class="form-control" name="'+type+'['+key+'][discount]" value="">'
									+'<span class="input-group-addon">折 立减</span>'
									+'<input type="text" class="form-control" name="'+type+'['+key+'][money]" value="">'
									+'<span class="input-group-addon">元</span>'
									+'<div class="input-group-btn">'
									+'	<input type="hidden" name="mcgroup['+key+'][grouptitle]" value="">'
									+'	<select name="mcgroup['+key+'][groupid]" class="btn btn-default" style="padding-right:45px;">'
									+'	'+option
									+'	</select>'
									+'	<button class="btn btn-danger" type="button" onclick="removeConsumeItem(this)"><i class="fa fa-remove"></i></button>'
									+'</div>'
								+'</div>';
							}
                        	$(obj).parent().siblings('.recharge-items').append(html);
                        }
                        function removeConsumeItem(obj){
                            $(obj).parent().parent().remove();
                        }
						$("#tab_mcgroup").delegate("select","change",function(e){
							$(this).prev('input').val($(this).find('option:selected').text());
						});
                    </script>
				</div>
			</div>
		</div>
                
		<div class="tab-pane" id="tab_form">
            <div class="panel panel-default">
                <div class="panel-heading">自定义表单</div>
                <div class="panel-body">
                    <?php  include fx_template('activity/form_option');?>
                </div>
            </div>
		</div>
        
        <div class="tab-pane" id="tab_msg">
            <div class="panel panel-default">
                <div class="panel-heading">通知设置</div>
                <div class="panel-body">
                	<?php  if($_W['allow']['activity']['sms'] || !$_W['allow']) { ?>
        			<div class="form-group">
                    	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">短信通知</label>
                        <div class="col-xs-12 col-sm-5">
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[smsswitch]" value="1"<?php  if($activity['smsswitch']) { ?> checked<?php  } ?>> 开启
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" name="activity[smsswitch]" value="0"<?php  if(!$activity['smsswitch']) { ?> checked<?php  } ?>> 关闭
                            </label>
                        </div>
                    </div>
                    <?php  if(!MERCHANTID) { ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                        <div class="col-md-9">
                        	<div class="input-group">
                            	<span class="input-group-addon">模板ID</span>
                        		<input type="text" name="activity[smsnotify]" class="form-control encrypt" value="<?php  echo $activity['smsnotify'];?>" placeholder='"SMS_" 开头的字串【不设置应用全局】'>
                            </div>
                        </div>
                    </div>
                    <?php  } ?>
                    <?php  } ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">管理通知</label>
                        <div class="col-xs-12 col-sm-9">
                            <div class='input-group'>
                                <input type="text" name="module[admin_nickname]" maxlength="30" value="<?php  echo $settings['admin_nickname'];?>" class="form-control" readonly placeholder="可指定多个微信用户【不设置应用全局】" />
                                <div class='input-group-btn'>
                                    <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-member').modal();">选择管理员</button>
                                    <button class="btn btn-danger" type="button" onclick="reset_admin();">重置</button>
                                </div>
                            </div>
                            <div class="input-group multi-img-details" id="member">
                            	<?php  if(is_array($activity['openids'])) { foreach($activity['openids'] as $openid) { ?>
                                <?php  $member = getMember($openid)?>
                                <div class="multi-item">
                                <img src="<?php  echo $member['avatar'];?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail">
                                <input type="hidden" name="activity[openids][]" value="<?php  echo $openid;?>">
                                <em class="close" title="删除管理员" onclick="deleteMultiImage(this)">×</em>
                                <p class="help-block title"><?php  echo $member['nickname'];?></p>
                                </div>
                                <?php  } } ?>
                            </div>
                            <div id="modal-module-member"  class="modal fade" tabindex="-1">
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
                    <!--
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">报名成功消息设置</label>
                        <div class="col-md-9">
                        	<input type="text" name="activity[tmplmsg][jointitle]" class="form-control encrypt" value="<?php  echo $activity['tmplmsg']['jointitle'];?>" placeholder='标题名称'>
                            <p></p>
							<textarea class="form-control" name="activity[tmplmsg][joinremark]" rows="3" placeholder="备注内容"><?php  echo $activity['tmplmsg']['joinremark'];?></textarea>
                        </div>
                    </div>-->
                    
        		</div>
        	</div>
            
            <div class="panel panel-default">
                <div class="panel-heading">客服设置</div>
                <div class="panel-body">
                	<div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">内部定义</label>
                        <div class="col-md-8">
                        	<label class="checkbox-inline">
                                <input type="checkbox" value="1" name="activity[kefu][switch]" <?php  if($activity['kefu']['switch']) { ?>checked<?php  } ?>> 开启
                            </label>
                        </div>
                    </div>
                	<div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">客服配制</label>
                        <div class="col-md-8 js-kefu">
                        	<div style="<?php  if(MERCHANTID) { ?>display:none<?php  } ?>">
                            <label class="radio-inline">
                                <input type="radio" value="1" name="activity[kefu][type]" <?php  if($activity['kefu']['type']=='1' || $activity['kefu']['type']=='') { ?>checked="checked"<?php  } ?>> 微信二维码
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="2" name="activity[kefu][type]" <?php  if($activity['kefu']['type']=='2') { ?>checked="checked"<?php  } ?>> 第三方接口
                            </label>
                            </div>
                            <div class="kefu-qrcode" style="<?php  if(!MERCHANTID) { ?>margin-top:15px;<?php  } ?><?php  if($activity['kefu']['type']=='2') { ?>display:none<?php  } ?>">
                                <?php  echo tpl_form_field_image('activity[kefu][qrcode]', $activity['kefu']['qrcode']);?>
                                <span class="help-block">建议640×870像素</span>
                            </div>
                            <div class="input-group kefu-url" style="margin-top:15px;<?php  if($activity['kefu']['type']!='2' || MERCHANTID) { ?>display:none<?php  } ?>">
                                <span class="input-group-addon">客服连接</span>
                                <input type="text" name="activity[kefu][url]" class="form-control" value="<?php  echo $activity['kefu']['url'];?>">
                            </div>
                        </div>
                    </div>
                    
        		</div>
        	</div>
        </div>
        
        <div class="tab-pane" id="tab_agreement">
			<div class="panel panel-default">
				<div class="panel-heading">报名协议</div>
				<div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">协议内容</label>
                        <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_ueditor('activity[agreement]', $activity['agreement']);?>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        
	</div>
    
    <div class="form-group" style="text-align:center;">
        <div class="col-xs-12">
        	<input name="submit" type="submit" value="　　保存　　" class="btn btn-primary"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </div>
    </div>
</form>
</div>
<script type="text/javascript">
$(function () {
	window.optionchanged = false;
	$('#myTab a').click(function (e) {
		e.preventDefault();//阻止a链接的跳转行为
		$(this).tab('show');//显示当前选中的链接及关联的content
	})
	$("#hasstore").click(function(){
		var obj = $(this);
		if (obj.get(0).checked){
			$("#tbstore").show();
		}else{
			$("#tbstore").hide();
		}
	});
	//
	$(".js-atype input[name='activity[atype]']").click(function(){
		var obj = $(this);
		if (obj.val()!='2'){
			$("#options").addClass('markethide');
			$(".market").addClass('markethide');
		}else{
			$("#options").removeClass('markethide');
			$(".market").removeClass('markethide');
		}
	});
	//
	$(".js-kefu input[name='activity[kefu][type]']").click(function(){
	var obj = $(this);
	if (obj.val()!='2'){
		$(".kefu-url").hide();
		$(".kefu-qrcode").show();
	}else{
		$(".kefu-url").show();
		$(".kefu-qrcode").hide();
	}
});
});
function check(form) {
	if (!form['activity[title]'].value) {
		util.tips('请输入活动名称');
		$("input[name='activity[title]']").focus();
		return false;
	}
	if (form['joinTime[start]'].value == form['joinTime[end]'].value) {
		util.tips('请设置合理的报名时间段');
		return false;
	}
	if (form['activityTime[start]'].value==form['activityTime[end]'].value) {
		util.tips('请设置合理的活动开始时间段');
		$(".mui-calendar-picker3").trigger('tap');
		return false;
	}
	var id="<?php  echo $activityid;?>",mid="<?php echo MERCHANTID;?>",proreview="<?php echo $_W['_config']['proreview']==1 || ($_W['_config']['proreview']==2 && $activity['aprice']==0)?1:0;?>";
	if (id!='' && proreview=='0' && mid!=''){
		if(!confirm('修改后将进入审核状态，是否确认？')) {
			return false;
		}
	}else{
		return true;
	}
}
function setSwitch(obj){
	var ischecked = $(obj).get(0).checked ? '1' : '0';
	$(obj).parent('label').find('input').next().val(ischecked);
}
//管理员通知配制
function select_member(o) {
	var hh = '', istrue = true, modalobj = $('#modal-module-member');
	$("input[name='activity[openids][]']").each(function(){
		if ($(this).val()==o.openid){
			alert('管理员不能重复');
			istrue = false;
			return false;
		}
	});
	if (!istrue) return false;
	hh += '<div class="multi-item"><img src="'+o.avatar+'" onerror="this.src=\'./resource/images/nopic.jpg\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail">'
	+'	<input type="hidden" name="activity[openids][]" value="'+o.openid+'">'
	+'	<em class="close" title="删除管理员" onclick="deleteMultiImage(this)">×</em>'
	+'	<p class="help-block title">'+o.nickname+'</p>'
	+'</div>';
	$("#member").append(hh);
	modalobj.modal('hide');
}
function reset_admin(){
	$("#member").html('');
}
</script>
<?php  } else if($op == 'display') { ?>
<ul class="nav nav-tabs">
	<li<?php  if($status==0) { ?> class="active"<?php  } ?>>
        <a href="<?php  echo web_url('activity/activity/display',array('status'=>0,'merchantid'=>$merchantid,'keyword'=>$keyword))?>">全部活动 <span class="label label-warning pull-right" style="margin-left: 10px;"><?php  echo $totals['0'];?></span></a>
    </li>
	<li<?php  if($status==1) { ?> class="active"<?php  } ?>>
        <a href="<?php  echo web_url('activity/activity/display',array('status'=>1,'merchantid'=>$merchantid,'keyword'=>$keyword))?>">进行中 <span class="label label-warning pull-right" style="margin-left: 10px;"><?php  echo $totals['1'];?></span></a>
    </li>
    <li<?php  if($status==2) { ?> class="active"<?php  } ?>>
        <a href="<?php  echo web_url('activity/activity/display',array('status'=>2,'merchantid'=>$merchantid,'keyword'=>$keyword))?>">已结束 <span class="label label-warning pull-right" style="margin-left: 10px;"><?php  echo $totals['2'];?></span></a>
    </li>
	<li<?php  if($status==3) { ?> class="active"<?php  } ?>>
        <a href="<?php  echo web_url('activity/activity/display',array('status'=>3,'merchantid'=>$merchantid,'keyword'=>$keyword))?>">待审核 <span class="label label-warning pull-right" style="margin-left: 10px;"><?php  echo $totals['3'];?></span></a>
    </li>
    <li<?php  if($status==4) { ?> class="active"<?php  } ?>>
        <a href="<?php  echo web_url('activity/activity/display',array('status'=>4,'merchantid'=>$merchantid,'keyword'=>$keyword))?>">已拒审 <span class="label label-warning pull-right" style="margin-left: 10px;"><?php  echo $totals['4'];?></span></a>
    </li>
</ul>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="get" class="form-horizontal" role="form" id="form1">
            <?php  if(!MERCHANTID) { ?>
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="<?php echo MODULE_NAME;?>">
            <?php  } ?>
            <input type="hidden" name="do" value="activity">
            <input type="hidden" name="ac" value="activity">
            <input type="hidden" name="op" value="display">
            <div class="form-group" style="margin-bottom:0px;">
            	<?php  if(!MERCHANTID) { ?>
                <div class="col-md-2">
                    <select name="merchantid" class="form-control s1">
                    	<option value="-1" <?php  if($merchantid=='-1') { ?>selected="selected"<?php  } ?>>所有主办方</option>
                    <?php  if($_W['user']['uid']) { ?>
                        <option value="0" <?php  if($merchantid=='0') { ?>selected="selected"<?php  } ?>><?php  echo $_W['_config']['sname'];?></option>
                    <?php  } ?>
                    <?php  if(is_array($merchants)) { foreach($merchants as $row) { ?>
                        <option value="<?php  echo $row['id'];?>" <?php  if($merchantid==$row['id']) { ?>selected="selected"<?php  } ?>><?php  echo $row['name'];?></option>
                    <?php  } } ?>
                    </select>
                </div>
                <?php  } ?>
            	<div class="col-md-2">
                    <select name="keywordtype" class="form-control">
                        <option value="">关键字类型</option>
                        <option value="1" <?php  if($_GPC['keywordtype']==1) { ?>selected="selected"<?php  } ?>>活动名称</option>
                        <option value="2" <?php  if($_GPC['keywordtype']==2) { ?>selected="selected"<?php  } ?>>活动ID</option>
                        <?php  if(!MERCHANTID) { ?>
                        <option value="3" <?php  if($_GPC['keywordtype']==3) { ?>selected="selected"<?php  } ?>>主办方名称</option>
                        <?php  } ?>
                    </select>
                </div>                
                <div class="col-md-<?php  if(!MERCHANTID) { ?>8<?php  } else { ?>10<?php  } ?>">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键字">
                        <span class="input-group-addon btn" id="search">搜索</span>
                    </div>
                </div>
                <div class="col-md-2" style="display:none">
                    <a href="<?php  echo web_url('activity/activity/post');?>" class="btn btn-primary we7-padding-horizontal">+新建活动</a>
                </div>
            </div>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </form>
    </div>
</div>
<script type="text/javascript">
	$("#search").click(function(){
		$('#form1')[0].submit();
	});
</script>
<form class="form-horizontal" action="" method="post">
<div class="panel panel-default">
	<div class="panel-heading">
    	<div class="panel-title">活动列表<span class="color-default pull-right">【点击活动标题复制连接】</span></div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="navbar-inner">
                <tr>
                    <th class="text-center" style="width:40px;">
                        <input type="checkbox" name="checkall" value="" id="checkall" onClick="var ck = this.checked; $(':checkbox').each(function(){this.checked = ck});">
                    </th>
                    <th class="text-center" style="width:40px;">ID</th>
                    <th class="text-left">活动名称</th>
                    <th class="text-center" style="width:90px;">已报名</th>
                    <th class="text-center" style="width:80px;">已收款</th>
                    <th class="text-center" style="width:<?php  if(IMS_VERSION=='0.8') { ?>120<?php  } else { ?>57<?php  } ?>px;">上线</th>
                    <th class="text-center" style="width:65px;">排序</th>
                    <th class="text-center" style="width:65px;">签到码</th>
                    <th class="text-center" style="width:330px;">操作</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
            <?php  if(is_array($activity)) { foreach($activity as $row) { ?>
            <?php  $sumpay = pdo_fetchcolumn("SELECT sum(payprice) FROM ".tablename('fx_activity_records') . " WHERE (status = 1 or status = 3) and activityid = ".$row['id']);?>
                <tr>
                    <td class="text-center"><input type="checkbox" name="check" value="<?php  echo $row['id'];?>" class="items"></td>
                    <td><?php  echo $row['id'];?></td>
                    <td class="text-left">
                    <span class="line-feed"><a href="javascript:;" data-url="<?php  echo app_url('activity/detail/display',array('activityid'=>$row['id']))?>" style="margin-right:0px;" class="js-clip" title="点击复制当前活动入口"><?php  echo str_replace('请编辑！','<font color="#FF0000"><i>请编辑！</i></font>',$row['title'])?></a></span>
                    <?php  if($_W['allow']['activity']['recommend'] || !$_W['allow']) { ?>
                    <span data="<?php  if($row['recommend']==1) { ?>0<?php  } else { ?>1<?php  } ?>" class="label <?php  if($row['recommend']==1) { ?>label-success<?php  } else { ?>label-success label-default<?php  } ?> pull-left" onclick="setProperty(this,<?php  echo $row['id'];?>,'recommend');" style="cursor:pointer"><?php  if($row['recommend']==1) { ?>已推荐<?php  } else { ?>点此推荐<?php  } ?></span>
                    <?php  } else { ?>
                    <span class="label <?php  if($row['recommend']==1) { ?>label-success<?php  } else { ?>label-success label-default<?php  } ?> pull-left"><?php  if($row['recommend']==1) { ?>已推荐<?php  } else { ?>未推荐<?php  } ?></span>
                    <?php  } ?>
                    <?php  if(TIMESTAMP < strtotime($row['endtime'])) { ?><span class="label label-info pull-right">进行中</span><?php  } else { ?><span class="label label-warning pull-right">已结束</span><?php  } ?>
                    </td>
                    <td class="small">
                    <?php  $joinnum1 = pdo_fetchcolumn('SELECT SUM(buynum) FROM ' . tablename('fx_activity_records') . " WHERE activityid = ".$row['id']." and (status = 1 or status = 2 or status=3)")?>
                    <?php  $joinnum2 = pdo_fetchcolumn('SELECT SUM(buynum) FROM ' . tablename('fx_activity_records') . " WHERE activityid = ".$row['id']." and status = 0")?>
                    <?php  $joinnum3 = pdo_fetchcolumn('SELECT SUM(buynum) FROM ' . tablename('fx_activity_records') . " WHERE activityid = ".$row['id']." and status = 5")?>
                    <font color="coral"><?php echo $joinnum1?$joinnum1:0;?></font><font color="red"></font><font color="orange"></font>/<?php  if($row['gnum'] == 0) { ?>不限<?php  } else { ?><?php  echo $row['gnum'];?><?php  } ?></td>
                    <td><span class="text-muted small"><?php  if($row['aprice'] >0) { ?>￥<?php  echo number_format(floatval($sumpay),2)?><?php  } else { ?>免费<?php  } ?></span></td>
                    <td class="text-center">
                    <?php  if(IMS_VERSION=='0.8') { ?>
                    <input class="js-flag" type="checkbox" data-id="<?php  echo $row['id'];?>" <?php  if($row['show']) { ?>checked<?php  } ?>/>
                    <?php  } else { ?>
                    <div class="switch switch<?php echo $row['show'] ? 'On' : 'Off'?>" data-id="<?php  echo $row['id'];?>"></div>
                    <?php  } ?>
                    </td>
                    <td class="text-center">
                    <input type="text" class="form-control input-sm js-displayorder" name="displayorder[]" value="<?php  echo $row['displayorder'];?>" data-id="<?php  echo $row['id'];?>"> 
                    <input type="hidden" name="ids[]" value="<?php  echo $row['id'];?>"></td>
                    <td><a class="btn btn-default btn-sm" href="<?php  echo web_url('activity/url2qr/display', array('activityid' => $row['id']))?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="适用大型活动现场，用户自主签到，请谨慎保管">查看</a></td>
                    <td class="text-center">
                    <div class="btn-group">
                    	<?php  if(!MERCHANTID) { ?>
                        <a href="javascript:;" class="btn btn-default btn-sm" data-id="<?php  echo $row['id'];?>" data-toggle="modal" data-target="#check-msg" data-whatever="@getbootstrap">通知</a>
                        <?php  } ?>
                        <a href="javascript:;" class="btn btn-default btn-sm js-copy-data" data-id="<?php  echo $row['id'];?>" data-placement="left" title="点击新建一个相同活动">复制</a>
                        <a href="<?php  echo web_url('records/records/display', array('activityid' => $row['id']))?>" class="btn btn-default btn-sm">报名记录</a>
                        <?php  if(!MERCHANTID) { ?>
                        <div class="btn-group">
                            <button type="button" class="btn<?php  if($row['review']==0) { ?> btn-warning<?php  } else if($row['review']==1) { ?> btn-default<?php  } else { ?> btn-danger<?php  } ?> dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><font><?php  if($row['review']==0) { ?>待审<?php  } else if($row['review']==1) { ?>通过<?php  } else { ?>拒审<?php  } ?></font><span class="caret"></span></button>
                            <ul class="dropdown-menu" id="menu_<?php  echo $row['id'];?>">
                                <li class="js-review" data-id="<?php  echo $row['id'];?>" data-review="1"><a href="javascript:;">通过</a></li>
                                <li class="js-review" data-id="<?php  echo $row['id'];?>" data-review="2"><a href="javascript:;">拒审</a></li>
                            </ul>
                        </div>
                        <?php  } else { ?>
                        <a href="javascript:;" class="btn<?php  if($row['review']==0) { ?> btn-warning<?php  } else if($row['review']==1) { ?> btn-default<?php  } else { ?> btn-danger<?php  } ?> btn-sm"><?php  if($row['review']==0) { ?>待审<?php  } else if($row['review']==1) { ?>通过<?php  } else { ?>拒审<?php  } ?></a>
                        <?php  } ?>
                        <a href="<?php  echo web_url('activity/activity/post', array('activityid' => $row['id']))?>" class="btn btn-success btn-sm">编辑</a>
                        <?php  if($_W['allow']['activity']['delete'] || !$_W['allow']) { ?>
                        <a href="javascript:void(0);" class="js-delete btn btn-danger btn-sm" data-id="<?php  echo $row['id'];?>">删除</a>
                        <?php  } ?>
                        </div>
                        
                    </td>
                </tr>
            <?php  } } ?>
            <thead>
            <tr>
                <td colspan="9">
                <?php  if($_W['allow']['activity']['displayorder'] || !$_W['allow']) { ?>
                <input name="submit" type="submit" class="btn btn-primary min-width" value="保存排序">
                <?php  } ?>
                <?php  if(!MERCHANTID) { ?>
                <a href="javascript:;" class="btn btn-success min-width js-review-list js-on-review">通过审核</a>
                <a href="javascript:;" class="btn btn-warning min-width js-review-list js-off-review">拒绝审核</a>
                <?php  } ?>
                <a href="javascript:;" class="btn btn-danger min-width js-batch js-delete" style="display:none">删除</a>
                </td>
            </tr>
            </thead>
            </tbody>
        </table>
    </div>
	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
</div>
<div class="pull-right"><?php  echo $pager;?></div>
</form>
<div class="modal fade" id="check-msg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">通知发送</h4>
            </div>
            <div class="modal-body we7-form">
                <div class="form-group" style="display:none;">
                    <div class="col-xs-12 col-sm-5">
                        <label class="radio radio-inline">
                            <input type="radio" name="messge_type" value="1" checked> 模板通知
                        </label>
                        <label class="radio radio-inline">
                            <input type="radio" name="messge_type" value="2"> 短信通知
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                    <select class="form-control" name="status">
                        <option value="0">请选择活动状态</option>
                        <option value="1">活动发布</option>
                        <option value="2">活动开始</option>
                        <option value="3">活动结束</option>
                    </select>
                    </div>
                    <div class="col-sm-6">
                    <select class="form-control" name="fans_group">
                        <option value="0">请选择粉丝组</option>
                        <option value="1">全部粉丝</option>
                        <option value="2">主办方粉丝</option>
                        <option value="3">仅限参与用户</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                	<div class="col-sm-12">
                    <input type="text" name="messge_title" class="form-control" value="" placeholder="标题内容，不填写系统默认"/>
                    </div>
                </div>
                <div class="form-group">
                	<div class="col-sm-12">
                    <input type="text" name="messge_url" class="form-control" value="" placeholder="跳转连接，不填写系统默认"/>
                    </div>
                </div>
                <div class="form-group">
                	<div class="col-sm-12">
                    <textarea name="messge_remark" rows="3" class="form-control" placeholder="备注内容，不填写系统默认"></textarea>
                    </div>
                </div>
                <input id="aid" type="hidden" name="id" value=""/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary js-send-msg">发送</button>
            </div>
        </div>
    </div>
</div>
<script>
function setProperty(obj,id,type){
	$(obj).html($(obj).html() + "...");
	$.post("<?php  echo web_url('activity/activity/recommend')?>",{id:id, data: obj.getAttribute("data")},function(d){
			console.log(d);
			$(obj).html($(obj).html().replace("...",""));
			if(type=='recommend'){
				$(obj).html( d.data=='1'?'点此推荐':'已推荐');
			}
			$(obj).attr("data",d.data);
			if(d.result==1){
				$(obj).toggleClass("label-default");
			}
		}
		,"json"
	);
}
function sendmsg(id, status, messge_type, fans_group, messge_title, messge_url, messge_remark, thispage){
	$.post("<?php  echo web_url('activity/activity/sendmsg')?>",
	{	activityid:id,
		status:status,
		messge_type:messge_type,
		fans_group:fans_group,
		messge_title:messge_title, 
		messge_url:messge_url,
		messge_remark:messge_remark,
		page:thispage
	}, function(data){
		console.log(data);
		if (data.tpage == 0){
			util.tips('暂无数据');
		}else if(thispage < data.tpage){
			if (thispage==1) util.loading("共 "+data.total+" 条记录，执行进度 1/" + data.total);
			thispage++;
			$('#tips-container span').html("共 "+data.total+" 条记录，执行进度 " + thispage + "/" + data.total);
			setTimeout(function() {
				sendmsg(id, status, messge_type, fans_group, messge_title, messge_url, messge_remark, thispage);
			}, 500);
		}else{
			util.tips(data.message);
			$('#check-msg').modal('hide');
		}
	},"json");
}
$(function(){
	//发送通知
	$('#check-msg').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var recipient = button.data('id');
		var modal = $(this)
		modal.find('.modal-body #aid').val(recipient)
	});
	$('.js-send-msg').click(function() {
		var messge_type   = $('#check-msg').find("input[name='messge_type']").val(),
			status        = $('#check-msg').find("select[name='status']").val(),
			fans_group    = $('#check-msg').find("select[name='fans_group']").val(),
			id            = $('#check-msg').find("input[name='id']").val(),
			messge_title  = $('#check-msg').find("input[name='messge_title']").val(),
			messge_url    = $('#check-msg').find("input[name='messge_url']").val(),
			messge_remark = $('#check-msg').find("textarea[name='messge_remark']").val(),
			thispage      = 1;
			
		if ($('#check-msg').find("select[name='status']").val()==0){
			util.tips('请选择活动状态', 1000);
			return false;
		}
		if ($('#check-msg').find("select[name='fans_group']").val()==0){
			util.tips('请选择粉丝组', 1000);
			return false;
		}
		sendmsg(id, status, messge_type, fans_group, messge_title, messge_url, messge_remark, thispage);		
	});
	//微擎0.8显示switch
	require(['bootstrap.switch'],function($){
		$('.js-flag:checkbox').bootstrapSwitch({onText: '上线', offText: '关闭'});
		$('.js-flag:checkbox').on('switchChange.bootstrapSwitch', function(event, state) {
			var id = $(this).data('id');
			var show = state ? 1 : 0;
			$.getJSON("<?php  echo web_url('activity/activity/show')?>", {id:id, show:show}, function(data) {
				util.tips(data.message, 2000);
			});
		});
	});
	//微擎1.0+显示switch
	$('.switch').click(function(e){
		var id = $(this).data('id');
		var state = $(this).hasClass("switchOff");
		var show = state ? 1 : 0;
		$.getJSON("<?php  echo web_url('activity/activity/show')?>", {id:id, show:show}, function(data) {
			util.tips(data.message, 2000);
		});
		
		$(this).toggleClass("switchOff");
		$(this).toggleClass("switchOn");
	
	});
	//审核
	$('.js-review').click(function(e){
		e.stopPropagation();
		var $this = $(this),id = $this.data('id'),review=parseInt($this.attr('data-review'));
		if (review==1) {
			html = '确认通过审核?';
		}else{
			html = '确认拒绝审核?';
		}
		util.nailConfirm(this, function(state) {
			$this.parents('.btn-group').trigger('click');
			if(!state) return;
			$.post("<?php  echo web_url('activity/activity/review')?>", {id:id,review:review}, function(data){
				if(!data.errno){
					$this.parents('.btn-group').find('font').html(review==1?'通过':'拒审');
					if(review==1){
						$this.parent().siblings('button').removeClass('btn-warning btn-danger').addClass('btn-default');
					}else{
						$this.parent().siblings('button').removeClass('btn-default btn-warning').addClass('btn-danger');
					}
					util.tips(data.message);
				};
			}, 'json');
		}, {html:html,placement: 'left'});
	});
	
	//批量审核操作
	$('.js-review-list').click(function(e){
		e.stopPropagation();
		var ids = [];
		var $checkboxes = $('.items:checkbox:checked');
		$checkboxes.each(function() {
			if (this.checked) {
				ids.push(this.value);
			};
		});
	
		if (ids.length == 0) {
			util.tips('请选择要操作的信息!', 2000);
			return false;
		}
		var op = '';
		var html = '';
		if ($(this).hasClass('js-on-review')) {
			html = '确认通过审核?';
			review = 1;
		}else if($(this).hasClass('js-off-review')){
			html = '确认取消审核?';
			review = 2;
		}
		var $this = $(this);
		util.nailConfirm(this, function(state) {
			if(!state) return;
			$.post("<?php  echo web_url('activity/activity/review')?>", {id:ids,review:review}, function(data){
				if(!data.errno){
					util.tips(data.message);
				};
				window.location.reload();
			}, 'json');
		}, {html: html,placement: $this.data('placement')});
	});
	$('.js-clip').each(function(){
		util.clip(this, $(this).attr('data-url'));
	});
	//复制活动
	$(".js-copy-data").click(function(e){
		e.stopPropagation();
		var $this = $(this);
		var activity_id = $this.data('id');
		var displayorder = parseInt($this.val());
		util.nailConfirm(this, function(state) {
			if (!state) return;
			$.post("<?php  echo web_url('activity/activity/copy')?>", {id : activity_id}, function(data) {
				if(!data.errno){
					util.message(data.message,"refresh","success");
					//location.reload();
				}else{
					util.tips(data.message);	
				}
			}, 'json');
		}, {html:"确定复制当前活动吗？",placement: $this.data('placement')});
	});
	//排序
	$(".js-displayorder").blur(function(e){
		e.stopPropagation();
		var $this = $(this);
		var good_id = $this.data("id");
		var displayorder = parseInt($this.val());
		if (isNaN(displayorder)) {
			$this.parent().addClass('has-error');
			util.tips('必须为数字', 2000);
			return false;
		}else{
			$this.parent().removeClass('has-error');
			return true;
		}
	});
	//批量删除效果b
	$('.js-batch').click(function(e){
		e.stopPropagation();
		var ids = [];
		var $checkboxes = $('.items:checkbox:checked');
		$checkboxes.each(function() {
			if (this.checked) {
				ids.push(this.value);
			};
		});
	
		if (ids.length == 0) {
			util.tips('请选择要操作的信息!', 2000);
			return false;
		}
		var op = '';
		var html = '';
		if ($(this).hasClass('id1')) {
			op = 'on_shelves';
			html = '待定?';
		} else if ($(this).hasClass('id2')) {
			op = 'off_shelves';
			html = '待定?';
		} else if ($(this).hasClass('js-deletes')) {
			op = 'deleteArr';
			html = '确认删除?';
		} else if ($(this).hasClass('js-remove')) {
			op = 'remove';
			html = '确认选中彻底删除?';
		}
		var $this = $(this);
		util.nailConfirm(this, function(state) {
			if(!state) return;
			$.post("<?php  echo web_url('activity/activity/delete')?>", {id : ids}, function(data){
				if(!data.errno){
					$checkboxes.each(function() {
						$(this).parent().parent().remove();
					});
				};
				util.tips(data.message);
			}, 'json');
		}, {html: html,placement: $this.data('placement')});
	});

	//删除效果b，单条操作
	$('.js-delete').click(function(e) {
		e.stopPropagation();
		var $this = $(this);
		var activity_id = $this.data('id');
		util.nailConfirm(this, function(state) {
			if (!state) return;
			$.post("<?php  echo web_url('activity/activity/delete')?>", {activityid : activity_id}, function(data) {
				if(!data.errno){
					$this.parents('tr').remove();
				}
				util.tips(data.message);
			}, 'json');
		}, {html:"确定删除？",placement: $this.data('placement')});
	});
});
</script>
<?php  } ?>
<script>
require(['../../components/select2/select2.min'], function(){
	$(".s1").select2();
	$(".select2").css('width','100%');
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/footer', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/footer', TEMPLATE_INCLUDEPATH));?>
