<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
.tabs-container .panel-body{border:none;}
.kf_span{height:34px;float:left;line-height: 34px;padding:0 5px;}
</style>
<div class="panel panel-default" >
	<div class="panel-body">
		<div class="col-sm-9 col-xs-12">
			<h4 class="set_title">积分抵扣</h4>
			<span> 开启积分抵扣, 商品最多抵扣的数目需要在商品【营销】中单独设置</span>
		</div>
		<div class="col-lg pull-right" style="padding-top:10px;text-align: right" >
			<?php if(cv('groups.set.edit')) { ?>
			<input type="checkbox" class="js-switch" name="data[creditdeduct]" value="1" <?php  if($data['creditdeduct']==1) { ?>checked<?php  } ?> />
			<?php  } else { ?>
			<?php  if($data['creditdeduct']==1) { ?>
			<span class='text-success'>开启</span>
			<?php  } else { ?>
			<span class='text-default'>关闭</span>
			<?php  } ?>
			<?php  } ?>
		</div>
	</div>

	<div class="form-group"  id='creditdeduct' <?php  if(empty($data['creditdeduct'])) { ?>style="display:none"<?php  } ?>>
		<label class="col-lg control-label">设置</label>
		<div class="col-sm-5">
			<?php if(cv('groups.set.edit')) { ?>
			<label class='radio radio-inline'>
				<input type='radio' value='0' name='data[groupsdeduct]' <?php  if($data['groupsdeduct']==0) { ?>checked<?php  } ?> /> 同步商城积分比例
			</label>
			<div class='input-group fixsingle-input-group'>
				<?php  echo $sys_data['credit'];?>积分 抵扣 <?php  echo $sys_data['money'];?> 元
				<span class='help-block'>
					<a href="<?php  echo webUrl('sale/deduct')?>">修改商城积分抵扣比例</a>
				</span>
			</div>
			<?php  } else { ?>
			<?php  if($data['groupsdeduct']==0) { ?>
			<div class='form-control-static'><?php  echo $data['credit'];?>积分 抵扣 <?php  echo $sys_data['money'];?> 元</div>
			<?php  } ?>
			<?php  } ?>


			<?php if(cv('groups.set.edit')) { ?>
			<label class='radio radio-inline'>
				<input type='radio' value='1' name='data[groupsdeduct]'  <?php  if($data['groupsdeduct']==1) { ?>checked<?php  } ?> /> 使用拼团积分比例
			</label>
			<div class='input-group fixsingle-input-group'>
				<input type="hidden" name="data[credit]" value="1" class="form-control" />
				<span class='input-group-addon'>1个积分 抵扣</span>
				<input type="text" name="data[groupsmoney]"  value="<?php  echo $data['groupsmoney'];?>" class="form-control" />
				<span class='input-group-addon'>元</span>
			</div>
			<span class='help-block'>积分抵扣比例设置</span>
			<?php  } else { ?>
			<?php  if($data['groupsdeduct']==1) { ?>
			<div class='form-control-static'><?php  echo $data['credit'];?>积分 抵扣 <?php  echo $data['groupsmoney'];?> 元</div>
			<?php  } ?>
			<?php  } ?>
		</div>
	</div>
	<div class="panel-body">
		<div class="col-sm-9 col-xs-12">
			<h4 class="set_title">团长优惠</h4>
			<span> 开启所有商品团长优惠, 如需单独设置请到商品管理【营销】中单独设置</span>
		</div>
		<div class="col-lg pull-right" style="padding-top:10px;text-align: right" >
			<?php if(cv('groups.set.edit')) { ?>
			<input type="checkbox" class="js-switch" name="data[discount]" value="1" <?php  if($data['discount']==1) { ?>checked<?php  } ?> />
			<?php  } else { ?>
			<?php  if($data['discount']==1) { ?>
			<span class='text-success'>开启</span>
			<?php  } else { ?>
			<span class='text-default'>关闭</span>
			<?php  } ?>
			<?php  } ?>
		</div>
	</div>
<div class="form-group"  id='discount' <?php  if(empty($data['discount'])) { ?>style="display:none"<?php  } ?>>
	<div class="form-group">
		<label class="col-lg control-label">
			<?php if(cv('groups.set.edit')) { ?>
			<input type='radio' value='0' name='headstype' <?php  if($data['headstype']==0) { ?>checked<?php  } ?> />
			<?php  } ?>
		</label>
		<div class="col-sm-9 col-xs-12">
			<?php if(cv('groups.set.edit')) { ?>
			<div class='input-group fixsingle-input-group'>
				<span class="input-group-addon">优惠金额</span>
				<input type="text" name="headsmoney" value="<?php  echo $data['headsmoney'];?>" class="form-control"/>
				<span class="input-group-addon">元</span>
			</div>
			<span class="help-block">团长开团立减金额</span>
			<?php  } else { ?>
			<?php  if($data['headstype']==0) { ?>
			<div class='form-control-static'>优惠金额 <?php  echo $data['headsmoney'];?>元</div>
			<?php  } ?>
			<?php  } ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg control-label">
			<?php if(cv('groups.set.edit')) { ?>
			<input type='radio' value='1' name='headstype' <?php  if($data['headstype']==1) { ?>checked<?php  } ?> />
			<?php  } ?>
		</label>
		<div class="col-sm-9 col-xs-12">
			<?php if(cv('groups.set.edit')) { ?>
			<div class='input-group fixsingle-input-group'>
				<span class="input-group-addon">优惠折扣</span>
				<input type="text" name="headsdiscount" value="<?php  echo $data['headsdiscount'];?>" class="form-control"/>
				<span class="input-group-addon">%</span>
			</div>
			<span class="help-block">折扣为0-100整数，价格=拼团价*折扣，如果设置0，则团长没有优惠金额</span>
			<?php  if($data['headstype']==0) { ?>
			<div class='form-control-static'>优惠折扣 <?php  echo $data['headsdiscount'];?>%</div>
			<?php  } ?>
			<?php  } ?>
		</div>
	</div>
</div>
</div>
<div class='form-group-title'>自动退款</div>
<div class="form-group">
	<label class="col-lg control-label">拼团失败X小时后</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<div class="input-group fixsingle-input-group">
			<input type="text" name="data[refund]" id="refund" class="form-control"  value="<?php  echo $data['refund'];?>" />
			<div class="input-group-addon">小时</div>
		</div>
		<span class='help-block'>拼团失败后 ，系统会在X小时后自动退款，设置0小时为不自动退款。</span>
		<?php  } else { ?>
		<?php  if($data['refund'] > 0) { ?>
		<div class='form-control-static'><?php  echo $data['refund'];?>小时</div>
		<?php  } else { ?>
		<div class='form-control-static'>不自动退款</div>
		<?php  } ?>
		<?php  } ?>
	</div>
</div>
<div class='form-group-title'>自动收货</div>
<div class="form-group ">
	<label class="col-lg control-label">发货几天后</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('groups.set.edit')) { ?>
		<div class="input-group fixsingle-input-group">
			<input type="text" name="data[receive]" class="form-control" value="<?php  echo $data['receive'];?>" />
			<div class="input-group-addon">天</div>
		</div>
		<span class='help-block'>订单发货后，用户收货的天数，如果在期间未确认收货，系统自动完成收货，空为不自动收货</span>
		<?php  } else { ?>
		<input type="hidden" name="data[receive]" value="<?php  echo $data['receive'];?>"/>
		<div class='form-control-static'>
			<?php  if(empty($data['receive'])) { ?>9<?php  } else { ?><?php  echo $data['receive'];?><?php  } ?> 天
		</div>
		<?php  } ?>
	</div>
</div>


<script language="javascript">
	$(function () {
		$(":checkbox[name='data[creditdeduct]']").click(function () {
			if ($(this).prop('checked')) {
				$("#creditdeduct").show();
			}
			else {
				$("#creditdeduct").hide();
			}
		})
		$(":checkbox[name='data[discount]']").click(function () {
			if ($(this).prop('checked')) {
				$("#discount").show();
			}
			else {
				$("#discount").hide();
			}
		})

		$(":checkbox[name='data[moneydeduct]']").click(function () {
			if ($(this).prop('checked')) {
				$("#moneydeduct").show();
			}
			else {
				$("#moneydeduct").hide();
			}
		})
		$("#refund").keyup(function(){
			var val = parseInt(Math.abs($(this).val()));
			if(val >= 1){
				return true;
			}else{
				val = 0;
			}
			$(this).val(val);
		})

	})
</script>