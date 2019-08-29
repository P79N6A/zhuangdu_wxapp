<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">微商-成分社-化妆品 </span>
</div>
<div class="page-content">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-5">
			<span class="">
					 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/hotsearch')?>"><i class="fa"></i>热搜设置</a>
			</span>
			<span class="">
					 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/ingredients.product_list')?>"><i class="fa"></i>所有化妆品</a>
			</span>
			<span class="">
					 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/ingredients.ingredient_list')?>"><i class="fa"></i>所有成分</a>
			</span>
			<span class="">
					 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/ingredients.product_edit')?>"><i class="fa fa-plus"></i> 添加化妆品</a>
			</span>
			<span class="">
					 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/ingredients.ingredient_edit')?>"><i class="fa fa-plus"></i> 添加成分</a>
			</span>&nbsp;&nbsp;
			<span class="">
					 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/ingredients.collect_product')?>"><i class="fa fa-plus"></i>点击采集产品(cosdna)</a>
			</span>
		</div>
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="yoxwechatbusiness.ingredients">
		<div class="col-sm-6 pull-right">
			<div class="input-group">
				<input type="text" class=" form-control" name='name' value="<?php  echo $_GPC['name'];?>" placeholder="请输入关键词"> <span class="input-group-btn">
				<button class="btn btn-primary" type="submit"> 搜索</button> </span>
			</div>

		</div>
	</div>
</form>
<!-- 等级列表 -->
<?php  if(count($result['data']['list'])>0) { ?>
	<div class="page-table-header">
		<input type="checkbox">
		<div class="btn-group ">
			<?php if(cv('yoxwechatbusiness.ingredients.edit')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<?php  } ?>
			<?php if(cv('yoxwechatbusiness.ingredients.delete')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_delete')?>">
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
				<th style="width: 340px;">产品名称</th>
				<th>添加时间</th>
				<th>成分</th>
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
						<a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('yoxwechatbusiness/displayorder',array('id'=>$info['id']))?>"><?php  echo $info['displayorder'];?></a> 
					<?php  } else { ?> 
						<?php  echo $info['displayorder'];?> 
					<?php  } ?>
				</td>-->
				<td><?php  echo $info['name'];?></td>
				<td><?php  echo $info['add_time_format'];?></td>
				<td><a href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_ingredient_list',array('product_id'=>$info['id']))?>">查看成分</a></td>
				<td>
					<?php if(cv($info['url'])) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/ingredients/collect_ingredient',array('product_id'=>$info['id']))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="采集该产品成分">
						<i class="icow">采集该产品成</i>
						</span>
				</a>
					<?php  } ?> 
					<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_edit',array('id'=>$info['id']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>
					<?php  } ?> 
					<?php if(cv('yoxwechatbusiness.delete')) { ?>
						<a data-toggle="ajaxRemove" class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_delete',array('id'=>$info['id']))?>" data-confirm="确认要删除?">
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
						<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<?php  } ?>
						<?php if(cv('yoxwechatbusiness.delete')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/ingredients/product_delete')?>">
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