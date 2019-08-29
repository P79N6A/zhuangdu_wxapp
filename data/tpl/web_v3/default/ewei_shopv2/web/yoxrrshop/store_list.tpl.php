<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">人人店 </span>
</div>
<div class="page-content">
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="yoxrrshop">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-4">
			<span class="">
				 <?php if(cv('yoxrrshop.edit')) { ?>
						 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxrrshop/store_edit')?>"><i class="fa fa-plus"></i> 添加</a>
				 <?php  } ?>
			</span>
		</div>
		<div class="col-sm-6 pull-right">
			<div class="input-group">
				<input type="text" class=" form-control" name='name' value="<?php  echo $_GPC['name'];?>" placeholder="请输入关键词"> <span class="input-group-btn">
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
			<?php if(cv('yoxrrshop.edit')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxrrshop/store_status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxrrshop/store_status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<?php  } ?>
			<?php if(cv('yoxrrshop.delete')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxrrshop/store_delete')?>">
				<i class='icow icow-shanchu1'></i> 删除
			</button>
			<?php  } ?>
		</div>
	</div>
	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th style="width:50px;"></th>
				<th style="width:100px;">排序</th>
				<th style="width:180px;">店铺名</th>
				<th style="width:180px;">店铺编号</th>
				<th style="width:100px;">评分</th>
				<th style="width:180px;">电话</th>
				<th style="width:100px;">状态</th>

				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>

			<?php  if(is_array($result['data']['list'])) { foreach($result['data']['list'] as $row) { ?>
			<tr>
				<td>
					<input type='checkbox' value="<?php  echo $row['id'];?>" />
				</td>
			    <td>
                    <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('yoxrrshop/index/displayorder',array('id'=>$row['id']))?>" ><?php  echo $row['displayorder'];?></a>
                </td>
				<td><?php  echo $row['name'];?></td>
				<td><?php  echo $row['store_number'];?></td>
				<td><?php  echo $row['star'];?></td>
				<td><?php  echo $row['tel'];?></td>
			    <td>
                    <span class='label <?php  if($row['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
                          data-toggle='ajaxSwitch'
                          data-switch-value='<?php  echo $row['status'];?>'
                          data-switch-value0='0|隐藏|label label-default|<?php  echo webUrl('yoxrrshop/index/status',array('status'=>1,'id'=>$row['id']))?>'
                          data-switch-value1='1|显示|label label-primary|<?php  echo webUrl('yoxrrshop/index/status',array('status'=>0,'id'=>$row['id']))?>'
                          >
                          <?php  if($row['status']==1) { ?>显示<?php  } else { ?>隐藏<?php  } ?></span>
                </td>

				<td>
					<?php if(cv('yoxrrshop.edit')) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxrrshop/store_edit',array('id'=>$row['id']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>
					<?php  } ?>
					<?php if(cv('yoxrrshop.delete')) { ?>
						<a data-toggle="ajaxRemove" class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxrrshop/store_delete',array('id'=>$row['id']))?>" data-confirm="确认要删除?">
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
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxrrshop/store_status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxrrshop/store_status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxrrshop/store_delete')?>">
							<i class='icow icow-shanchu1'></i> 删除
						</button>
					</div>
				</td>
				<td colspan="5" class="text-right">	<?php  echo $result['data']['pager'];?> </td>
			</tr>
		</tfoot>
	</table>

<?php  } else { ?>
	<div class='panel panel-default'>
		<div class='panel-body' style='text-align: center;padding:30px;'>
			暂时没有数据!
		</div>
	</div>
<?php  } ?>
</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+4-->