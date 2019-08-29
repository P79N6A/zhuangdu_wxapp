<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">微商利润/佣金 </span>
</div>
<div class="page-content">
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="user">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-4">
			<span class="">
				 <?php if(cv('yoxwechatbusiness.commission.edit')) { ?>
						 <!--<a class='btn btn-primary btn-sm' href="<?php  echo webUrl('user')?>"><i class="fa fa-plus"></i> 添加用户</a>-->
				 <?php  } ?>
			</span>
		</div>
		<div class="col-sm-6 pull-right">
			<div class="input-group">
				<input type="text" class=" form-control" name='keyword' value="<?php  echo $_GPC['name'];?>" placeholder="请输入关键词"> <span class="input-group-btn">
				<button class="btn btn-primary" type="submit"> 搜索</button> </span>
			</div>

		</div>
	</div>
</form>

<!-- 佣金列表 -->
<?php  if(count($result['data']['list'])>0) { ?>
	<div class="page-table-header">
		<input type="checkbox">
		<div class="btn-group ">
			<?php if(cv('yoxwechatbusiness.commission.edit')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/commission/status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/commission/status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<?php  } ?>
			<?php if(cv('yoxwechatbusiness.commission.delete')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/commission/delete')?>">
				<i class='icow icow-shanchu1'></i> 删除
			</button>
			<?php  } ?>
		</div>
	</div>
	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th style="width:25px;"></th>
				<!--<th style="width:100px;">排序</th>-->
				<th>订单ID</th>
				<th>下单者</th>
				<th>商品</th>
				<th style="width:300px;">类型</th>
				<th>数量</th>
				<th style="width:200px;">获佣者</th>
				<th>利润/佣金</th>
				<th>状态</th>
				<th style="width:250px;">备注</th>
				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>
	
			<?php  if(is_array($result['data']['list'])) { foreach($result['data']['list'] as $info) { ?>
			<tr>
				<td>
					<input type='checkbox' value="<?php  echo $info['id'];?>" />
				</td>
				<!--<td>
					<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('yoxwechatbusiness/displayorder',array('id'=>$yoxwechatbusiness['id']))?>"><?php  echo $yoxwechatbusiness['displayorder'];?></a> 
					<?php  } else { ?> 
						<?php  echo $yoxwechatbusiness['displayorder'];?> 
					<?php  } ?>
				</td>-->
				<td><?php  echo $info['order_id'];?></td>
				<td><?php  echo $info['nickname'];?></td>
				<td><?php  if($info['goods_thumb']) { ?><img width="50" src="/attachment/<?php  echo $info['goods_thumb'];?>"><?php  } ?><?php  echo $info['goods_title'];?><?php  echo $info['goods_option_title'];?></td>
				<td><?php  echo $info['type_name'];?>(<?php  echo $info['type'];?>)</td>
				<td><?php  echo $info['total'];?></td>
				<td><?php  if($info['from_uid']) { ?><span class="label">转单</span><?php  } ?><?php  echo $info['commission_nickname'];?></td>
				<td><?php  echo $info['commission'];?></td>
				<td>
				<span class='label <?php  if($info['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>' 
							data-toggle="ajaxSwitch" 
							data-confirm = "确认<?php  if($info['status']==1) { ?>设置未分佣<?php  } else { ?>设置为已分佣<?php  } ?>吗？"
							data-switch-value="<?php  echo $info['status'];?>" 
							data-switch-value0="0|未分佣|label label-default|<?php  echo webUrl('yoxwechatbusiness/commission/status',array('status'=>1,'id'=>$info['id']))?>" 
							data-switch-value1="1|已分佣|label label-primary|<?php  echo webUrl('yoxwechatbusiness/commission/status',array('status'=>0,'id'=>$info['id']))?>" 
						<?php  echo $info['status_name'];?>
					</span>
				</td>
				<td><?php  echo $info['remark'];?></td>
				<td>
					<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/commission/edit',array('id'=>$info['id']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>
					<?php  } ?> 
					<?php if(cv('yoxwechatbusiness.commission.delete')) { ?>
						<!--<a data-toggle="ajaxRemove" class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/delete',array('id'=>$yoxwechatbusiness['id']))?>" data-confirm="确认要删除?">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                <i class='icow icow-shanchu1'></i>
                           </span>
						</a>-->
					<?php  } ?>
				</td>
			</tr>
			<?php  } } ?>
		</tbody>
		<tfoot>
			<tr>
				<td>
					<input type="checkbox">
				</td>
				<td colspan="3">
					<div class="btn-group ">
						<?php if(cv('yoxwechatbusiness.commission.edit')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/commission/status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/commission/status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<?php  } ?>
						<?php if(cv('yoxwechatbusiness.commission.delete')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/commission/delete')?>">
							<i class='icow icow-shanchu1'></i> 删除
						</button>
						<?php  } ?>
					</div>
				</td>
				<td colspan="5" class="text-right">	<?php  echo $result['data']['pager'];?> </td>
			</tr>
		</tfoot>
	</table>

<?php  } else { ?>
	<div class='panel panel-default'>
		<div class='panel-body' style='text-align: center;padding:30px;'>
			暂无数据!
		</div>
	</div>
<?php  } ?>
</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+4-->