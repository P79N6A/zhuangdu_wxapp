<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
.multi-img-details .multi-item{max-height:none;max-width: none;position: relative;float: none;margin-right: 18px;height:60px;width:100%;padding:5px 0;}
.multi-img-details .multi-item img{width:50px;height:50px;float:left;margin-right:5px;}
.multi-img-details .img-nickname{width:300px;position: inherit;float:left;background:none;color:#666;text-align: left;}
</style>
<div class="form-group">
	<label class="col-lg control-label">完成订单X天内允许退款</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<div class="input-group fixsingle-input-group">
			<input type="text" name="data[refundday]" class="form-control" value="<?php  echo $data['refundday'];?>" />
			<div class="input-group-addon">天</div>
		</div>
		<span class='help-block'>设置0则表示商品都不允许退款</span>
		<?php  } else { ?>
		<div class='form-control-static'>
			<?php  if(empty($data['refundday'])) { ?>不允许退款<?php  } else { ?><?php  echo $data['refundday'];?> 天<?php  } ?>
		</div>
		<?php  } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">不允许退退款的商品</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<div class="input-group" style="width: 500px;">
<!--			<?php  echo tpl_selector('goodsid',array(
			'preview'=>true,
			'readonly'=>true,
			'multi'=>1,
			'value'=>$item['title'],
			'url'=>webUrl('groups/set/query'),
			'items'=>$goods,
			'buttontext'=>'选择商品',
			'placeholder'=>'请选择商品')
			)
			?>-->

			<?php  echo tpl_goods_selector('goodsid',$goods,array('type'=>'group','option'=>false));?>

			<!--<div class="input-group multi-img-details container ui-sortable">
				<?php  if(is_array($goods)) { foreach($goods as $item) { ?>
				<div data-name="goodsid" data-id="426" class="multi-item">
					<img src="<?php  echo tomedia($item['thumb'])?>" class="img-responsive img-thumbnail">
					<div class="img-nickname"><?php  echo $item['title'];?></div>
					<div style="clear:both;"></div>
				</div>
				<?php  } } ?>
			</div>-->

		</div>
		<span class='help-block'>当前列表中存在的商品不允许退款</span>
		<?php  } else { ?>
			<?php  if(!empty($goods)) { ?>
				<?php  if(is_array($goods)) { foreach($goods as $item) { ?>
				<a href="<?php  echo tomedia($item['thumb'])?>" target='_blank'>
					<img src="<?php  echo tomedia($item['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
				</a>
				<?php  } } ?>
			<?php  } else { ?>
			暂无商品
			<?php  } ?>
		<?php  } ?>
	</div>
</div>