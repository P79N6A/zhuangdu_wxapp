<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-lg control-label">购买强制关注</label>
    <div class="col-sm-9">
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <label class="radio-inline"><input type="radio" name="followneed" value="1" <?php  if($item['followneed'] == 1) { ?>checked="true"<?php  } ?>   /> 必须关注</label>
        <label class="radio-inline"><input type="radio" name="followneed" value="0" <?php  if(empty($item['followneed']) ) { ?>checked="true"<?php  } ?>  /> 不需关注</label>
        <?php  } else { ?>
        <div class='form-control-static'><?php  if(empty($item['followneed'])) { ?>不需关注<?php  } else { ?>必须关注<?php  } ?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label">未关注提示</label>
    <div class="col-sm-9">
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <input type='text' class="form-control" name='followtext' value='<?php  echo $item['followtext'];?>' />
        <span  class='help-block'>购买商品必须关注，如果未关注，弹出的提示，如果为空默认为“如果您想要购买此商品，需要您关注我们的公众号，点击【确定】关注后再来购买吧~”</span>
        <?php  } else { ?>
        <div class='form-control-static'><?php  echo $item['followtext'];?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label">关注引导</label>
    <div class="col-sm-9">
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <input type='text' class="form-control" name='followurl' value='<?php  echo $item['followurl'];?>' />
        <span  class='help-block'>购买商品必须关注，如果未关注，跳转的链接，如果为空默认为系统关注页</span>
        <?php  } else { ?>
        <div class='form-control-static'><?php  echo $item['followurl'];?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group splitter"></div>
<div class="form-group">
    <label class="col-lg control-label">分享标题</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <input type="text" name="share_title" id="share_title" class="form-control" value="<?php  echo $item['share_title'];?>" />
        <span class='help-block'>如果不填写，默认为商品名称</span>
        <?php  } else { ?>
        <div class='form-control-static'><?php  echo $item['share_title'];?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label">分享图标</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <?php  echo tpl_form_field_image2('share_icon', $item['share_icon'])?>
        <span class='help-block'>如果不选择，默认为商品缩略图片</span>
        <?php  } else { ?>
        <?php  if(!empty($item['share_icon'])) { ?>
        <a href='<?php  echo tomedia($item['share_icon'])?>' target='_blank'>
        <img src="<?php  echo tomedia($item['share_icon'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
        </a>
        <?php  } ?>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label">分享描述</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <textarea name="share_desc" class="form-control" rows="5" ><?php  echo $item['share_desc'];?></textarea>
        <span class='help-block'>如果不填写，则使用商品副标题，如商品副标题为空则使用店铺名称</span>
        <?php  } else { ?>
        <div class='form-control-static'><?php  echo $item['share_desc'];?></div>
        <?php  } ?>
    </div>
</div>
<!--4000097827-->