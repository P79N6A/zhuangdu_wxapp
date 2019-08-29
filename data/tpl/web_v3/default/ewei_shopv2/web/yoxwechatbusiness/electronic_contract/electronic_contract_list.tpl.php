<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">电子合同</span>
</div>
<div class="page-content">
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="yoxwechatbusiness.electronic_contract.electronic_contract_list">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-4">
			<span class="">
				 <?php if(cv('electronic_contract.edit')) { ?>
						 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/edit',array('type'=>'WECHAT_BUSINESS'))?>"><i class="fa fa-plus"></i> 添加微商电子合同</a>
				 <?php  } ?>
			</span>
		</div>
		<div class="col-sm-6 pull-right">
			<div class="input-group">
				<input type="text" class=" form-control" name='uid' value="<?php  echo $_GPC['type'];?>" placeholder="请输入type"> <span class="input-group-btn">
				<button class="btn btn-primary" type="submit"> 搜索</button> </span>
			</div>
		</div>
	</div>
</form>

<!-- 列表 -->
<?php  if(count($result['data']['list'])>0) { ?>
	<div class="page-table-header">
		<input type="checkbox">
		<div class="btn-group ">
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/delete')?>">
				<i class='icow icow-shanchu1'></i> 删除
			</button>
		</div>
	</div>
	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th style="width:25px;"></th>
				<th style="width:100px;">ID</th>
				<th style="width:208px;">type</th>
				<th style="width:208px;">标题</th>
				<th style="width:190px;">时间</th>
				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>
	
			<?php  if(is_array($result['data']['list'])) { foreach($result['data']['list'] as $info) { ?>
			<tr>
				<td>
					<input type='checkbox' value="<?php  echo $info['id'];?>" />
				</td>
				<td><?php  echo $info['id'];?></td>
				<td><?php  echo $info['type_name'];?></td>
				<td><?php  echo $info['title'];?></td>
				<td><?php  echo $info['add_time_format'];?></td>
				<td>
					<?php if(cv('electronic_contract.edit')) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/edit',array('id'=>$info['id']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>
					<?php  } ?>  
						<a data-toggle="ajaxRemove" class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/delete',array('id'=>$info['id']))?>" data-confirm="确认要删除?">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                <i class='icow icow-shanchu1'></i>
                           </span>
						</a>
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
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness.electronic_contract/delete')?>">
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