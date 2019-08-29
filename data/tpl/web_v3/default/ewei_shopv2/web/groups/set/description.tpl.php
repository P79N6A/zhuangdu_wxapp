<?php defined('IN_IA') or exit('Access Denied');?>
	<div class="form-group">
		<label class="col-lg control-label">是否显示统一描述</label>
		<div class="col-sm-9 col-xs-12">
			<?php if(cv('groups.set.edit')) { ?>
			<label class='radio radio-inline'>
				<input type='radio' value='1' name='data[description]'  <?php  if($data['description']==1) { ?>checked<?php  } ?> /> 显示
			</label>
			<label class='radio radio-inline'>
				<input type='radio' value='0' name='data[description]' <?php  if($data['description']==0) { ?>checked<?php  } ?> /> 关闭
			</label>
			<?php  } else { ?>
			<div class='form-control-static'><?php  if($data['description']==1) { ?>显示<?php  } else { ?>关闭<?php  } ?></div>
			<?php  } ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg control-label">统一描述</label>
		<div class="col-sm-9 col-xs-12">
			<?php if( ce('groups.set' ,$data) ) { ?>
			<?php  echo tpl_ueditor('groups_description',$data['groups_description'],array('height'=>'200'))?>
			<?php  } else { ?>
			<textarea id='groups_description' name='groups_description' style='display:none;'><?php  echo $data['groups_description'];?></textarea>
			<a href='javascript:preview_html("#groups_description")' class="btn btn-default">查看内容</a>
			<?php  } ?>
		</div>
	</div>
<!--913702023503242914-->