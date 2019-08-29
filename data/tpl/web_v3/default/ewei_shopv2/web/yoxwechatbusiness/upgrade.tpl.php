<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">用户升级 </span>
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

<!-- 等级列表 -->
<?php  if(count($result['data']['list'])>0) { ?>

	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th>用户名</th>
				<th>uid</th>
				<th>当前等级</th>
			<!-- 	<th>当前业绩</th>
				<th>当前团队</th> -->
				<th>申请等级</th>
			<!-- 	<th>等级所需业绩</th>
				<th>等级所需团队</th> -->
				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>

			<?php  if(is_array($result['data']['list'])) { foreach($result['data']['list'] as $info) { ?>
			<tr>

				<td><img width="50" src="<?php  echo $info['avatar'];?>"><?php  echo $info['nickname'];?></td>
				<td><?php  echo $info['uid'];?></td>
				<td><?php  echo $info['level_name'];?></td>
				<td><?php  echo $info['next_level_name'];?></td>
				<td>
					<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/upgrade/upgrade_check',array('uid'=>$info['uid']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>

				</td>
			</tr>
			<?php  } } ?>
		</tbody>

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
