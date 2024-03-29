<?php defined('IN_IA') or exit('Access Denied');?><div id="tab-sale-3" class="tab-pane">
    <div class='form-group-title'>积分抵扣</div>
    <div class="form-group">
        <label class="col-lg control-label">
            积分抵扣
        </label>
        <div class="col-sm-9 col-xs-12">
            <?php if( ce('groups.goods' ,$item) ) { ?>
            <div class='input-group fixsingle-input-group'>
                <span class="input-group-addon">最多抵扣</span>
                <input type="text" name="deduct" value="<?php  echo $item['deduct'];?>" class="form-control"/>
                <span class="input-group-addon">元</span>
            </div>
            <span class="help-block">如果设置0，则不支持积分抵扣</span>
            <?php  } else { ?>
            <div class='form-control-static'><?php  echo $item['deduct'];?> 元</div>
            <?php  } ?>
        </div>
    </div>
    <div class='form-group-title'>
        团长优惠
        <?php if( ce('groups.goods' ,$item) ) { ?>
        <div class="pull-right" style="text-align: right;padding-right: 28px;">
            是否单独设置团长优惠 <input class="js-switch small" id="discount" name="discount" value="<?php  echo $item['discount'];?>" type="checkbox" <?php  if(!empty($item['discount'])) { ?>checked<?php  } ?>/>
        </div>
        <?php  } ?>
    </div>
    <div class="form-group">
        <label class="col-lg control-label">
            <?php if( ce('groups.goods' ,$item) ) { ?>
            <input type='radio' value='0' name='headstype' <?php  if($item['headstype']==0) { ?>checked<?php  } ?> />
            <?php  } else { ?>
            <?php  if($item['headstype']==0) { ?>
            优惠金额
            <?php  } ?>
            <?php  } ?>
        </label>
        <div class="col-sm-9 col-xs-12">
            <?php if( ce('groups.goods' ,$item) ) { ?>
            <div class='input-group fixsingle-input-group'>
                <span class="input-group-addon">优惠金额</span>
                <input type="text" name="headsmoney" value="<?php  echo $item['headsmoney'];?>" class="form-control"/>
                <span class="input-group-addon">元</span>
            </div>
            <span class="help-block">团长开团立减金额 </span>
            <?php  } else { ?>
            <?php  if($item['headstype']==0) { ?>
            <div class='form-control-static'> <?php  echo $item['headsmoney'];?> 元</div>
            <?php  } ?>
            <?php  } ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg control-label">
            <?php if( ce('groups.goods' ,$item) ) { ?>
            <input type='radio' value='1' name='headstype' <?php  if($item['headstype']==1) { ?>checked<?php  } ?> />
            <?php  } else { ?>
            <?php  if($item['headstype']==1) { ?>
            优惠折扣
            <?php  } ?>
            <?php  } ?>
        </label>
        <div class="col-sm-9 col-xs-12">
            <?php if( ce('groups.goods' ,$item) ) { ?>
            <div class='input-group fixsingle-input-group'>
                <span class="input-group-addon">优惠折扣</span>
                <input type="text" name="headsdiscount" value="<?php  echo $item['headsdiscount'];?>" class="form-control"/>
                <span class="input-group-addon">%</span>
            </div>
            <span class="help-block">折扣为0-100整数 ,价格=拼团价*折扣，如果设置0，则团长没有优惠金额</span>
            <?php  } else { ?>
            <?php  if($item['headstype']==1) { ?>
            <div class='form-control-static'> <?php  echo $item['headsdiscount'];?> 元</div>
            <?php  } ?>
            <?php  } ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg control-label">是否显示团长优惠</label>
        <div class="col-xs-12 col-sm-8">
            <?php if( ce('groups.goods' ,$item) ) { ?>
            <label class="radio radio-inline">
                <input type="radio" name="isdiscount" value="1" <?php  if(intval($item['isdiscount']) ==1 ) { ?>checked="checked"<?php  } ?>> 是
            </label>
            <label class="radio radio-inline">
                <input type="radio" name="isdiscount" value="0" <?php  if(intval($item['isdiscount']) ==0) { ?>checked="checked"<?php  } ?>> 否
            </label>
            <?php  } else { ?>
            <div class='form-control-static'><?php  if(intval($item['isdiscount']) ==1 ) { ?>是<?php  } else { ?>否<?php  } ?></div>
            <?php  } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(":checkbox[name='discount']").click(function () {
            if ($(this).prop('checked')) {
                $(this).val(1);
            }else {
                $(this).val(0);
            }
        })
    })
</script>