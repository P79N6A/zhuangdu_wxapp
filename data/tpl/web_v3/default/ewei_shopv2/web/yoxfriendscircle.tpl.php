<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">精选朋友圈管理 </span>
</div>
<div class="page-content">
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="yoxfriendscircle">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-4">
			<span class="">
				 <?php if(cv('yoxfriendscircle.edit')) { ?>
						 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxfriendscircle/edit')?>"><i class="fa fa-plus"></i> 添加</a>
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

<!-- 文章列表 -->
<?php  if(count($result['data']['list'])>0) { ?>
	<div class="page-table-header">
		<input type="checkbox">
		<div class="btn-group ">
			<?php if(cv('yoxfriendscircle.edit')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxfriendscircle/status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxfriendscircle/status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<?php  } ?>
			<?php if(cv('yoxfriendscircle.delete')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxfriendscircle/delete')?>">
				<i class='icow icow-shanchu1'></i> 删除
			</button>
			<?php  } ?>
		</div>
	</div>
	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th style="width:25px;"></th>
				<th style="width:100px;">排序</th>
				<th>标题</th>
				<th>描述</th>
				<th style="width:100px;">精选</th>
				<th style="width:100px;">前端显示</th>
				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>
	
			<?php  if(is_array($result['data']['list'])) { foreach($result['data']['list'] as $yoxfriendscircle) { ?>
			<tr>
				<td>
					<input type='checkbox' value="<?php  echo $yoxfriendscircle['id'];?>" />
				</td>
				<td>
					<?php if(cv('yoxfriendscircle.edit')) { ?>
						<a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('yoxfriendscircle/displayorder',array('id'=>$yoxfriendscircle['id']))?>"><?php  echo $yoxfriendscircle['displayorder'];?></a> 
					<?php  } else { ?> 
						<?php  echo $yoxfriendscircle['displayorder'];?> 
					<?php  } ?>
				</td>
				<td><?php  echo $yoxfriendscircle['name'];?></td>
				<td><?php  echo $yoxfriendscircle['description'];?></td>
				<td>
					<span class='label <?php  if($yoxfriendscircle['is_featured']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>' 
						<?php if(cv('yoxhotsearch.page.edit')) { ?> 
							data-toggle="ajaxSwitch" 
							data-confirm = "确认<?php  if($yoxfriendscircle['is_featured']==1) { ?>取消精选<?php  } else { ?>设为精选<?php  } ?>吗？"
							data-switch-value="<?php  echo $yoxfriendscircle["is_featured"];?>" 
							data-switch-value0="0|关闭|label label-default|<?php  echo webUrl('yoxfriendscircle/is_featured',array('is_featured'=>1,'id'=>$yoxfriendscircle['id']))?>" 
							data-switch-value1="1|开启|label label-primary|<?php  echo webUrl('yoxfriendscircle/is_featured',array('is_featured'=>0,'id'=>$yoxfriendscircle['id']))?>" 
						<?php  } ?>>
						
						<?php  if($yoxfriendscircle['status']==1) { ?>开启<?php  } else { ?>关闭<?php  } ?>
					</span>
				</td>
				<td>
					<span class='label <?php  if($yoxfriendscircle['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>' 
						<?php if(cv('yoxhotsearch.page.edit')) { ?> 
							data-toggle="ajaxSwitch" 
							data-confirm = "确认<?php  if($yoxfriendscircle['status']==1) { ?>关闭<?php  } else { ?>开启<?php  } ?>吗？"
							data-switch-value="<?php  echo $yoxfriendscircle["yoxhotsearch_status"];?>" 
							data-switch-value0="0|关闭|label label-default|<?php  echo webUrl('yoxfriendscircle/status',array('status'=>1,'id'=>$yoxfriendscircle['id']))?>" 
							data-switch-value1="1|开启|label label-primary|<?php  echo webUrl('yoxfriendscircle/status',array('status'=>0,'id'=>$yoxfriendscircle['id']))?>" 
						<?php  } ?>>
						
						<?php  if($yoxfriendscircle['status']==1) { ?>开启<?php  } else { ?>关闭<?php  } ?>
					</span>
				</td>
				<td>
					<?php if(cv('yoxfriendscircle.edit')) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxfriendscircle/edit',array('id'=>$yoxfriendscircle['id']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>
					<?php  } ?> 
					<?php if(cv('yoxhotsearch.delete')) { ?>
						<a data-toggle="ajaxRemove" class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxfriendscircle/delete',array('id'=>$yoxfriendscircle['id']))?>" data-confirm="确认要删除此热搜?">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                <i class='icow icow-shanchu1'></i>
                           </span>
						</a>
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
						<?php if(cv('yoxfriendscircle.edit')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxfriendscircle/status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxfriendscircle/status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<?php  } ?>
						<?php if(cv('yoxhotsearch.delete')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxfriendscircle/delete')?>">
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
			暂时没有任何朋友圈文章!
		</div>
	</div>
<?php  } ?>
</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+4-->