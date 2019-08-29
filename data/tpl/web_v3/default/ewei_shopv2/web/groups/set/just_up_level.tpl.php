<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-lg control-label">仅显示上级发起的拼团</label>
    <div class="col-sm-9 col-xs-12">
            <label class='radio radio-inline'>
                <input type='radio' value='1' name='data[just_up_level]'  <?php  if($data['just_up_level']==1) { ?>checked<?php  } ?> /> 开启
            </label>
            <label class='radio radio-inline'>
                <input type='radio' value='0' name='data[just_up_level]' <?php  if($data['just_up_level']==0) { ?>checked<?php  } ?> /> 不开启
            </label>

    </div>
</div>