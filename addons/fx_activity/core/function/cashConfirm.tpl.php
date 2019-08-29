<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/header', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-default">
	<div class="table-responsive">
		<table class="table table-hover" style="min-width: 300px;">
			<thead class="navbar-inner">
				<tr>
					<th>提现订单号</th>
					<th style="width:95px;">商户名称</th>
					<th class="">申请金额<br>实际到账</th>
					<th class="">佣金百分比</th>
					<th class="">提现方式</th>
					<th class="">申请时间</th>
					<th class="">操作时间</th>
					<th class="">状态</th>
                    <?php  if(!MERCHANTID) { ?><th class="text-center">操作</th><?php  } ?>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $merchantid => $merchant) { ?>
				<tr>
					<td><?php  echo $merchant['orderno'];?></td>
					<td><?php  echo $merchant['merchant']['name'];?></td>
					<td><?php  echo currency_format($merchant['money'])?><br><?php  echo currency_format($merchant['get_money'])?></td>
					<td><?php  if(!empty($merchant['percent'])) { ?><?php  echo $merchant['percent'];?>%<?php  } else { ?>0.00%<?php  } ?></td>
					<td><?php  if($merchant['type']==1) { ?><label class="label label-default label-success">微信零钱<?php  } else if($merchant['type']==2) { ?><label class="label label-default label-info">手动处理<?php  } ?></label></td>
					<td><?php  echo date('Y-m-d',$merchant['createtime'])?><br><?php  echo date('H:i:s',$merchant['createtime'])?></td>
					<td><?php  echo date('Y-m-d',$merchant['updatetime'])?><br><?php  echo date('H:i:s',$merchant['updatetime'])?></td>
					<td><?php  if($merchant['status']==1) { ?><label class="label label-default label-default">待确认</label><?php  } ?>
						<?php  if($merchant['status']==2) { ?><label class="label label-default label-danger">待打款</label><?php  } ?>
						<?php  if($merchant['status']==3) { ?><label class="label label-default label-success">已打款</label><?php  } ?></td>
                    <?php  if(!MERCHANTID) { ?>
					<td style="text-align: center;">
						<?php  if($merchant['status']<3) { ?>
                            <?php  if($merchant['status']==1) { ?><a  href="<?php  echo web_url('application/merchant/merchantApply', array( 'id'=>$merchant['id'],'type'=>1));?>" title="确认" class="btn btn-default btn-sm">确认</a> - <?php  } ?>
                            <a   href="<?php  echo web_url('application/merchant/merchantApply', array( 'id'=>$merchant['id'],'type'=>2));?>" title="确认并打款" class="btn btn-default btn-sm" onclick="return confirm('确认？');return false;">确认并打款</a> - 
                            <a href="<?php  echo web_url('application/merchant/merchantApply', array( 'id'=>$merchant['id'],'type'=>3));?>" title="手动处理" class="btn btn-default btn-sm" onclick="return confirm('手动处理系统将仅修改状态，不做打款处理！确认？');return false;">手动处理</a>
						<?php  } else { ?>
						<a  href="#" title="已完成" class="btn btn-default btn-sm">已完成</a>
						<?php  } ?>
					</td>
                    <?php  } ?>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
		<?php  if(empty($list)) { ?>
		    <div class="panel-body" style="text-align: center;padding:30px;">
		        暂时没有任何数据!
		    </div>
		<?php  } ?>
	</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/footer', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/footer', TEMPLATE_INCLUDEPATH));?>