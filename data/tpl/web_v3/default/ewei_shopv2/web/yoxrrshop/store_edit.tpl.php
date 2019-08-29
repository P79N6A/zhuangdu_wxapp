<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='page-header'>
    当前位置：<span class="text-primary">人人店</span>
</div>
<div class="page-content">
    <form id="setform"  action="" method="post" class="form-horizontal form-validate">
        <div class='form-group-title'>店铺管理</div>


        <div class="form-group">
            <label class="col-lg control-label">店铺名称</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="name" class="form-control" value="<?php  echo $info['name'];?>" placeholder="店铺名称" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">店铺编号</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="store_number" class="form-control" value="<?php  echo $info['store_number'];?>" placeholder="店铺编号" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">电话</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="tel" class="form-control" value="<?php  echo $info['tel'];?>" placeholder="电话" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">营业时间</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="business_time" class="form-control" value="<?php  echo $info['business_time'];?>" placeholder="营业时间" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">店长微信</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="manager_wechat" class="form-control" value="<?php  echo $info['manager_wechat'];?>" placeholder="店长微信" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">评分</label>
            <div class="col-sm-9 col-xs-12">
                <select name="star">
                <option <?php  if($info['star']==1) { ?>selected<?php  } ?> value="0">请选择评分</option>
                <option <?php  if($info['star']==1) { ?>selected<?php  } ?> value="1">1</option>
                <option <?php  if($info['star']==2) { ?>selected<?php  } ?> value="2">2</option>
                <option <?php  if($info['star']==3) { ?>selected<?php  } ?> value="3">3</option>
                <option <?php  if($info['star']==4) { ?>selected<?php  } ?> value="4">4</option>
                <option <?php  if($info['star']==5) { ?>selected<?php  } ?> value="5">5</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">省市区</label>
            <div class="col-sm-9 col-xs-12">
               <?php  echo tpl_form_field_district('area',array('province'=>$info['province'],'city'=>$info['city'],'district'=>$info['district']));?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">详细地址</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" name="address" class="form-control" value="<?php  echo $info['address'];?>" placeholder="详细地址" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">位置</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo tpl_form_field_coordinate('lal',array('lat'=>$info['lal']['1'], 'lng'=>$info['lal']['0']));?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">店内装潢</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo tpl_form_field_multi_image('thumbs',$info['thumbs'])?>
                <span class="help-block">仅限上传5张图片</span>
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
