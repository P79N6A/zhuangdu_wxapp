<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group" style="display: none;">
	<label class="col-lg control-label">显示拼团按钮</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<label class='radio radio-inline'>
			<input type='radio' value='0' name='data[groups]' <?php  if($data['groups']==0) { ?>checked<?php  } ?> /> 关闭
		</label>
		<label class='radio radio-inline'>
			<input type='radio' value='1' name='data[groups]'  <?php  if($data['groups']==1) { ?>checked<?php  } ?> /> 显示
		</label>
		<?php  } else { ?>
		<div class='form-control-static'><?php  if($data['groups']==1) { ?>显示<?php  } else { ?>关闭<?php  } ?></div>
		<?php  } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">线下核销关键词</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<input type="text" name="data2[exchangekeyword]" class="form-control" value="<?php  echo $data2['exchangekeyword'];?>" />
		<span class='help-block'>店员线下兑换使用，使用方法: 回复关键词后系统会提示输入核销码</span>
		<?php  } else { ?>
		<div class='form-control-static'><?php  if(empty($data2['exchangekeyword'])) { ?><?php  } else { ?><?php  echo $data2['exchangekeyword'];?><?php  } ?></div>
		<?php  } ?>
	</div>
</div>
<div class='form-group-title'>关注设置</div>
<div class="form-group">
	<label class="col-lg control-label">关注引导页</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<input type="text" name="data[followurl]" class="form-control" value="<?php  echo $data['followurl'];?>" />
		<span class='help-block'>
			<p>用户未关注的引导页面，建议使用短链接：<a target="_blank" href="http://www.dwz.cn">短网址</a></p>
			<p>如果设置关注二维码则<span class="text-danger">优先弹出二维码</span></p>
		</span>
		<?php  } else { ?>
		<input type="hidden" name="data[followurl]" value="<?php  echo $data['followurl'];?>" />
		<div class='form-control-static'><?php  echo $data['followurl'];?></div>
		<?php  } ?>
	</div>
</div>

<div class="form-group">
	<label class="col-lg control-label">关注二维码</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<?php  echo tpl_form_field_image2('data[followqrcode]', $data['followqrcode']);?>
		<span class='help-block'></span>
		<?php  } else { ?>
		<input type="hidden" name="data[followqrcode]" value="<?php  echo $data['followqrcode'];?>" />
		<div class='form-control-static'><?php  echo $data['followqrcode'];?></div>
		<?php  } ?>
	</div>
</div>

<div class="form-group">
	<label class="col-lg control-label">拼团顶部关注条</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<label class='radio radio-inline'>
			<input type='radio' value='1' name='data[followbar]'  <?php  if($data['followbar']==1) { ?>checked<?php  } ?> /> 显示
		</label>
		<label class='radio radio-inline'>
			<input type='radio' value='0' name='data[followbar]' <?php  if($data['followbar']==0) { ?>checked<?php  } ?> /> 关闭
		</label>
		<?php  } else { ?>
		<div class='form-control-static'><?php  if($data['followbar']==1) { ?>显示<?php  } else { ?>关闭<?php  } ?></div>
		<?php  } ?>
	</div>
</div>

<div class="form-group" style="display: none;">
	<label class="col-lg control-label">拼团说明页面</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<input type="text" name="data[groupsurl]" class="form-control" value="<?php  echo $data['groupsurl'];?>"  />
		<?php  } else { ?>
		<div class='form-control-static'><?php  echo $data['groupsurl'];?></div>
		<?php  } ?>
	</div>
</div>
<div class='form-group-title'>分享设置</div>
<div class="form-group">
	<label class="col-lg control-label">分享标题</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<input type="text" name="data[share_title]" class="form-control" value="<?php  echo $data['share_title'];?>" />
		<span class="help-block">不填写默认商城名称</span>
		<?php  } else { ?>
		<input type="hidden" name="data[share_title]" value="<?php  echo $data['share_title'];?>" />
		<div class='form-control-static'><?php  echo $data['share_title'];?></div>
		<?php  } ?>

	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">分享图标</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>

		<?php  echo tpl_form_field_image2('data[share_icon]', $data['share_icon']);?>
		<span class="help-block">不选择默认商城LOGO</span>
		<?php  } else { ?>
		<input type="hidden" name="data[share_icon]" value="<?php  echo $data['share_icon'];?>" />
		<?php  if(!empty($data['share_icon'])) { ?>
		<a href='<?php  echo tomedia($data['share_icon'])?>' target='_blank'>
		<img src="<?php  echo tomedia($data['share_icon'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
		</a>
		<?php  } ?>
		<?php  } ?>

	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">分享描述</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<textarea style="height:100px;" name="data[share_desc]" class="form-control" cols="60"><?php  echo $data['share_desc'];?></textarea>
		<?php  } else { ?>
		<textarea style="height:100px;display: none" name="data[share_desc]" class="form-control" cols="60"><?php  echo $data['share_desc'];?></textarea>
		<div class='form-control-static'><?php  echo $data['share_desc'];?></div>
		<?php  } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">分享链接</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>

		<div class="input-group form-group" style="margin: 0;">
			<input type="text" name="data[share_url]" class="form-control" value="<?php  echo $data['share_url'];?>" id="shareurl" />
			<span data-input="#shareurl" data-toggle="selectUrl" data-full="true" class="input-group-addon btn btn-default">选择链接</span>
		</div>

		<span class='help-block'>用户分享出去的链接，默认为首页</span>
		<?php  } else { ?>
		<input type="hidden" name="data[share_url]" value="<?php  echo $data['share_url'];?>" />
		<div class='form-control-static'><?php  echo $data['share_url'];?></div>
		<?php  } ?>
	</div>
</div>