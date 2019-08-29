<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary">微商用户 </span>
</div>
<div class="page-content">
<form action="./index.php" method="get" class="form-horizontal" role="form">
	<input type="hidden" name="c" value="site">
	<input type="hidden" name="a" value="entry">
	<input type="hidden" name="m" value="ewei_shopv2">
	<input type="hidden" name="do" value="web">
	<input type="hidden" name="r" value="yoxwechatbusiness.index">
	<div class="page-toolbar m-b-sm m-t-sm">
		<div class="col-sm-10">
			<span class="">
				 <?php if(cv('yoxwechatbusiness.edit')) { ?>
						 <!--<a class='btn btn-primary btn-sm' href="<?php  echo webUrl('user')?>"><i class="fa fa-plus"></i> 添加用户</a>-->
				 <?php  } ?>
			</span>
          <div class="input-group" style="float:left;">
            <select name="level" class="form-control">
		      <option value="">选择等级..</option>
		      <?php  if(is_array($result['data']['level_list'])) { foreach($result['data']['level_list'] as $level_info) { ?>
		      <option <?php  if($level_info['level']==$_GPC['level']) { ?>selected<?php  } ?> value="<?php  echo $level_info['level'];?>"><?php  echo $level_info['name'];?>(<?php  echo $level_info['level'];?>)</option>
		      <?php  } } ?>
		     </select>
          </div>
          <div class="input-group" style="float:left;">
            <input type="text" name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder="姓名" class="form-control">
          </div>
          <div class="input-group" style="float:left;">
            <input type="text" name="mobile" value="<?php  echo $_GPC['mobile'];?>" placeholder="手机" class="form-control">
          </div>
          <div class="input-group" style="float:left;">
            <input type="text" name="invite_uid" value="<?php  echo $_GPC['invite_uid'];?>" placeholder="上级UID" class="form-control">
          </div>
          <div class="input-group" style="float:left;">
            <input type="text" name="invite_realname" value="<?php  echo $_GPC['invite_realname'];?>" placeholder="上级姓名" class="form-control">
          </div>
		<!--<div class="form-group col-sm-4">
          <div class="input-group">
            <input type="text" name="invitationcode" value="<?php  echo $_GPC['invitationcode'];?>" placeholder="邀请码" class="form-control">
          </div>
        </div>-->


		<div class="input-group pull-left">
			<div class="input-group">
				<!--<input type="text" class=" form-control" name='keyword' value="<?php  echo $_GPC['name'];?>" placeholder="请输入关键词"> <span class="input-group-btn">-->
				<button class="btn btn-primary" type="submit"> 搜索</button> </span>
			</div>
		</div>
	</div>
 </div>
</form>

<!-- 列表 -->
<?php  if(count($result['data']['list'])>0) { ?>
	<div class="page-table-header">
		<input type="checkbox">
		<div class="btn-group ">
			<?php if(cv('yoxwechatbusiness.edit')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/status',array('status'=>1))?>">
				<i class='icow icow-qiyong'></i> 开启
			</button>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/status',array('status'=>0))?>">
				<i class='icow icow-jinyong'></i> 关闭
			</button>
			<?php  } ?>
			<?php if(cv('yoxwechatbusiness.delete')) { ?>
			<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/delete')?>">
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
				<th>用户UID</th>
				<th>用户</th>
				<th>邀请人真实姓名</th>
				<th>邀请人昵称</th>
				<th>余额</th>
				<th>积分</th>
				<th>微商等级</th>
				<th>状态</th>
				<th>时间</th>
				<th>库存</th>
				<th style="width: 150px;">操作</th>
			</tr>
		</thead>
		<tbody>

			<?php  if(is_array($result['data']['list'])) { foreach($result['data']['list'] as $info) { ?>
			<tr>
				<td>
					<input type='checkbox' value="<?php  echo $yoxwechatbusiness['id'];?>" />
				</td>
				<!--<td>
					<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('yoxwechatbusiness/displayorder',array('id'=>$yoxwechatbusiness['id']))?>"><?php  echo $yoxwechatbusiness['displayorder'];?></a>
					<?php  } else { ?>
						<?php  echo $yoxwechatbusiness['displayorder'];?>
					<?php  } ?>
				</td>-->
				<td><?php  echo $info['uid'];?></td>
				<td><img width="50" src="<?php  echo $info['avatar'];?>"><?php  echo $info['nickname'];?></td>
				<td><?php  echo $info['invite_realname'];?></td>
				<td><?php  echo $info['invite_nickname'];?></td>
				<td><?php  echo $info['credit1'];?></td>
				<td><?php  echo $info['credit2'];?></td>
				<td><?php  echo $info['level'];?>:<?php  echo $info['level_name'];?></td>
				<td>
					<?php  if($info['status']<1) { ?> <span style="color: red;">未激活</span> <?php  } ?>
					<?php  if($info['status']==1) { ?><span style="color: green;">已激活</span><?php  } ?>
					<?php  if($info['status']==4) { ?><span style="color: black;">黑名单</span><?php  } ?>
				</td>

				<td><?php  echo $info['add_time_format'];?></td>
				<td><a href="<?php  echo webUrl('yoxwechatbusiness/user_stock',array('uid'=>$info['uid']))?>">查看库存</a></td>
				<td>
					<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('yoxwechatbusiness/edit',array('id'=>$info['id']))?>">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                             <i class="icow icow-bianji2"></i>
                         </span>
						</a>
					<?php  } ?>
					<?php if(cv('yoxhotsearch.delete')) { ?>
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
						<?php if(cv('yoxwechatbusiness.edit')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/status',array('status'=>1))?>">
							<i class='icow icow-qiyong'></i> 开启
						</button>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('yoxwechatbusiness/status',array('status'=>0))?>">
							<i class='icow icow-jinyong'></i> 关闭
						</button>
						<?php  } ?>
						<?php if(cv('yoxhotsearch.delete')) { ?>
						<button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('yoxwechatbusiness/delete')?>">
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