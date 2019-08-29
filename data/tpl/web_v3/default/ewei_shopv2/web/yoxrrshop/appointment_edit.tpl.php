<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">人人店</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>预约</div>

        <div class="form-group">
            <label class="col-lg control-label">所属门店</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="appointment" class="form-control" value="<?php  echo $info['appointment'];?>" placeholder="预约需求" readonly="readonly" />
            </div>
        </div>
                <div class="form-group">
            <label class="col-lg control-label">预约人</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="appointment" class="form-control" value="<?php  echo $info['appointment'];?>" placeholder="预约人" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">预约人手机号</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="appointment" class="form-control" value="<?php  echo $info['appointment'];?>" placeholder="预约人手机号" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">预约时间</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="appointment" class="form-control" value="<?php  echo $info['appointment'];?>" placeholder="预约时间" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">进店需求</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="appointment" class="form-control" value="<?php  echo $info['appointment'];?>" placeholder="进店需求" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">进店需求</label>
            <div class="col-sm-9 col-xs-12">
                <select name="star">
                <option <?php  if($info['star']==1) { ?>selected<?php  } ?> value="0">请选择进店需求</option>
                <option <?php  if($info['star']==1) { ?>selected<?php  } ?> value="1">1</option>
                <option <?php  if($info['star']==2) { ?>selected<?php  } ?> value="2">2</option>
                <option <?php  if($info['star']==3) { ?>selected<?php  } ?> value="3">3</option>
                <option <?php  if($info['star']==4) { ?>selected<?php  } ?> value="4">4</option>
                <option <?php  if($info['star']==5) { ?>selected<?php  } ?> value="5">5</option>
                </select>
            </div>
        </div>

        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9">
                <input type="submit" value="提交" class="btn btn-primary" />
            </div>
        </div>
    </form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
