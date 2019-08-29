<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">微商 设置</span>
</div>
<div class="page-content">
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="yoxwechatbusiness.setting">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-4">
			<!--<span class="">
				 <?php if(cv('yoxwechatbusiness.edit')) { ?>
						 <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('yoxwechatbusiness/setting.edit')?>"><i class="fa fa-plus"></i>添加设置</a>
				 <?php  } ?>
			</span>-->
		</div>
		<div class="col-sm-6 pull-right">
			<div class="input-group">
				<input type="text" class=" form-control" name='name' value="<?php  echo $_GPC['name'];?>" placeholder="请输入关键词"> <span class="input-group-btn">
				<button class="btn btn-primary" type="submit"> 搜索</button> </span>
			</div>

		</div>
	</div>
</form>

<!-- 列表 -->
<?php  if(count($result['data']['list'])>0) { ?>
<!-- 	<div class="page-table-header">
		<input type="checkbox">
		<div class="btn-group ">
			<?php if(cv('yoxwechatbusiness.level.edit')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<?php  } ?>
			<?php if(cv('yoxwechatbusiness.level.delete')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/delete')?>">
				<i class='icow icow-shanchu1'></i> 删除
			</button>
			<?php  } ?>
		</div>
	</div> -->
	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th>名称</th>
				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>
			<tr>
<!-- 				<td>
					<input type='checkbox' value="" />
				</td> -->
				<td>授权证书</td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/setting/edit',array('name'=>'CERTIFICATE'))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icow icow-bianji2"></i></span>
					</a>
				</td>
			</tr>
			<tr>
<!-- 				<td>
					<input type='checkbox' value="" />
				</td> -->
				<td>升级设置</td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/setting/edit',array('name'=>'UPGRADE'))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icow icow-bianji2"></i></span>
					</a>
				</td>
			</tr>

			<tr>
				<td>邀请设置</td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/setting/edit',array('name'=>'INVITE'))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icow icow-bianji2"></i></span>
					</a>
				</td>
			</tr>

			<tr>
				<td>等级规则</td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/setting/edit',array('name'=>'level_rule'))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icow icow-bianji2"></i></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>合约内容</td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/setting/edit',array('name'=>'contract'))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icow icow-bianji2"></i></span>
					</a>
				</td>
			</tr>
			<!--<tr>
				<td>
					<input type='checkbox' value="" />
				</td>
				<td>业务员</td><td><?php  echo $result['data']['list']['DOCKING_PEOPLE']['value_name'];?></td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/setting/edit',array('name'=>'UPGRADE'))?>">
						<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icow icow-bianji2"></i></span>
					</a>
				</td>
			</tr>-->
		</tbody>
<!-- 		<tfoot>
			<tr>
				<td>
					<input type="checkbox">
				</td>
				<td colspan="3">
					<div class="btn-group ">
						<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/setting/status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/setting/status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<?php  } ?>
						<?php if(cv('yoxwechatbusiness.delete')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/setting/delete')?>">
							<i class='icow icow-shanchu1'></i> 删除
						</button>
						<?php  } ?>
					</div>
				</td>
				<td colspan="5" class="text-right">	<?php  echo $result['data']['pager'];?> </td>
			</tr>
		</tfoot> -->
	</table>

<?php  } else { ?>
	<div class='panel panel-default'>
		<div class='panel-body' style='text-align: center;padding:30px;'>
			请设置等级!
		</div>
	</div>
<?php  } ?>
</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+4-->