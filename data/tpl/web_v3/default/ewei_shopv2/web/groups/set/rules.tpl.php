<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
	<label class="col-lg control-label">拼团规则</label>
	<div class="col-sm-9 col-xs-12">
		<?php if( ce('groups.set' ,$data) ) { ?>
		<?php  echo tpl_ueditor('rules',$data['rules'],array('height'=>'200'))?>
		<?php  } else { ?>
		<textarea id='rules' name='rules' style='display:none;'><?php  echo $data['rules'];?></textarea>
		<a href='javascript:preview_html("#rules")' class="btn btn-default">查看内容</a>
		<?php  } ?>
	</div>
</div>
<!--青岛易联互动网络科技有限公司-->