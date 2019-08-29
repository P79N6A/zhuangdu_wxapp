<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="btn-group we7-btn-group">
	<a href="<?php  echo url('mc/member/base_information', array('uid' => $uid))?>" class="btn <?php  if($do == 'base_information') { ?>active<?php  } ?>">基本信息</a>
	<a href="<?php  echo url('mc/member/member_credits', array('uid' => $uid))?>" class="btn <?php  if($do == 'member_credits') { ?>active<?php  } ?>"><?php  echo $creditnames['credit1']['title'];?>/<?php  echo $creditnames['credit2']['title'];?></a>
	<a href="<?php  echo url('mc/member/credit_statistics', array('uid' => $uid, 'type' => 1))?>" class="btn <?php  if($do == 'credit_statistics') { ?>active<?php  } ?>">数据统计</a>
</div>
<?php  if($do == 'credit_statistics') { ?>
<style>
	.account-stat{overflow:hidden; color:#666;}
	.account-stat .account-stat-btn{width:100%; overflow:hidden;}
	.account-stat .account-stat-btn > div{text-align:center; margin-bottom:5px;margin-right:2%; float:left;width:48%; padding-top:10px;font-size:16px; border-left:1px #DDD solid;}
	.account-stat .account-stat-btn > div:first-child{border-left:0;}
	.account-stat .account-stat-btn .stat{width:80%;margin:10px auto;font-size:15px}
</style>
<div class="panel panel-default" style="padding:1em;margin-top:2em">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin: -1em -1em 1em -1em;">
		<div class="navbar-header">
			<a class="navbar-brand" href="javascript:;">数据统计</a>
			<ul class="nav navbar-nav navbar-right">
				<li <?php  if($_GPC['type'] == 1) { ?>class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_statistics', array('uid' => $uid, type => 1));?>">今日</a></li>
				<li <?php  if($_GPC['type'] == -1) { ?>class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_statistics', array('uid' => $uid, type => -1));?>">昨日</a></li>
				<form class="navbar-form navbar-left" role="search" id="form1">
					<input name="c" value="mc" type="hidden" />
					<input name="a" value="member" type="hidden" />
					<input name="do" value="credit_statistics" type="hidden" />
					<input name="uid" value="<?php  echo $uid;?>" type="hidden" />
					<?php  echo tpl_form_field_daterange('datelimit', array('start' => date('Y-m-d', $starttime),'end' => date('Y-m-d', $endtime)), '')?>
				</form>
			</ul>
		</div>
	</nav>
	<div class="account-stat">
		<div class="account-stat-btn">
			<?php  if(is_array($credits)) { foreach($credits as $key => $li) { ?>
			<div>
				<strong><?php  echo $li;?></strong>
				<div id="rule" class="stat">
					<div>增加 <strong><span class="text-success"><?php  echo $data[$key]['add'];?></span></strong></div>
					<div>减少 <strong><span class="text-danger"><?php  echo $data[$key]['del'];?></span></strong></div>
				</div>
			</div>
			<?php  } } ?>
		</div>
	</div>
</div>
<script>
	require(['jquery', 'daterangepicker'], function($,c) {
		$(function() {
			$('.daterange').on('apply.daterangepicker', function(ev, picker) {
				$('#form1')[0].submit();
			});
		})
	});
</script>
<?php  } ?>
<?php  if($do == 'member_credits') { ?>
<div class="main">
	<form action="" class="we7-form form" method="post" enctype="multipart/form-data">
		<?php  if(!empty($credits)) { ?>
		<!--积分、余额-->
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">会员<?php  echo $creditnames['credit1']['title'];?>/<?php  echo $creditnames['credit2']['title'];?>操作</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $creditnames['credit1']['title'];?></td>
				<td><?php  echo $credits['credit1'];?></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" title="<?php  echo $creditnames['credit1']['title'];?>" class="modal-trade-credit1" data-type="credit1" data-uid="<?php  echo $uid;?>" data-title="<?php  echo $creditnames['credit1']['title'];?>
">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $creditnames['credit2']['title'];?></td>
				<td><?php  echo $credits['credit2'];?></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" title="<?php  echo $creditnames['credit2']['title'];?>" class="modal-trade-credit2" data-type="credit2" data-uid="<?php  echo $uid;?>" data-title="<?php  echo $creditnames['credit2']['title'];?>
">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<col />
			<col />
			<col />
			<tr>
				<th colspan="6">
					<div class="btn-group we7-btn-group">
						<a href="<?php  echo url('mc/member/member_credits', array('uid' => $uid, 'type' => 'credit1'))?>" class="btn <?php  if($type == 'credit1') { ?>active<?php  } ?>"><?php  echo $creditnames['credit1']['title'];?>记录</a>
						<a href="<?php  echo url('mc/member/member_credits', array('uid' => $uid, 'type' => 'credit2'))?>" class="btn <?php  if($type == 'credit2') { ?>active<?php  } ?>"><?php  echo $creditnames['credit2']['title'];?>记录</a>
					</div>
				</th>
			</tr>
			<tr>
				<th>账户类型</th>
				<th class="text-center">操作员</th>
				<th class="text-center"><?php  echo $creditnames[$type]['title'];?>增减</th>
				<th class="text-center">模块</th>
				<th class="text-center">操作时间</th>
				<th class="text-center">备注</th>
			</tr>
			<?php  if(!empty($records)) { ?>
				<?php  if(is_array($records)) { foreach($records as $record) { ?>
				<tr>
					<td class="text-center"><?php  if($record['credittype'] == 'credit2') { ?><?php  echo $creditnames['credit2']['title'];?><?php  } else { ?><?php  echo $creditnames['credit1']['title'];?><?php  } ?></td>
					<td class="text-center"><?php  echo $record['username'];?></td>
					<td class="text-center"><?php  echo $record['num'];?></td>
					<td class="text-center"><?php  echo $record['system'];?></td>
					<td class="text-center"><?php  echo date("Y-m-d H:i:s", $record['createtime'])?></td>
					<td class="text-center"><?php  echo $record['remark'];?></td>
				</tr>
				<?php  } } ?>
			<?php  } ?>
		</table>
		<div class="text-right">
			<?php  echo $pager;?>
		</div>
		<!--end积分、余额-->
		<?php  } ?>
	</form>
</div>
<script type="text/javascript">
require(['trade'], function(trade){
	trade.init();
});
</script>
<?php  } ?>
<?php  if($do == 'base_information') { ?>
<div class="main" id="js-base-information" ng-controller="baseInformation" ng-cloak>
	<form action="" class="we7-form form" method="post" enctype="multipart/form-data">
		<?php  if(!empty($profile)) { ?>
		<!--基本信息-->
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col width="500px">
			<tr>
				<th class="text-left" colspan="3">会员信息</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['nickname']['title'];?></td>
				<td ng-bind="profile.nickname"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;"></a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label">密码</td>
				<td>*****</td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#password">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">基本信息</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['avatar']['title'];?></td>
				<td><img ng-src="{{profile.avatarUrl}}" alt="" class="img-circle" style="width: 65px; height: 65px;"/></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" ng-click="changeImage('avatar')">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label">用户UID</td>
				<td colspan="2"><?php  echo $uid;?></td>
			</tr>
			<tr>
				<td class="table-label">所在会员组</td>
				<td ng-bind="groups[profile.groupid].title"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#groupid" ng-click="editInfo('groupid', profile.groupid)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['nickname']['title'];?></td>
				<td ng-bind="profile.nickname"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#nickname" ng-click="editInfo('nickname', profile.nickname)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['realname']['title'];?></td>
				<td ng-bind="profile.realname"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#realname" ng-click="editInfo('realname', profile.realname)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['gender']['title'];?></td>
				<td ng-bind="sexes[profile.gender].name"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#gender" ng-click="editInfo('gender', profile.gender)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['birthyear']['title'];?></td>
				<td ng-bind="profile.births"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#births" ng-click="editInfo('births', profile.births)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['nationality']['title'];?></td>
				<td ng-bind="profile.resides"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#resides" ng-click="editInfo('resides', profile.resides)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['resideprovince']['title'];?></td>
				<td ng-bind="profile.address"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#address" ng-click="editInfo('address', profile.address)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['mobile']['title'];?></td>
				<td ng-bind="profile.mobile"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#mobile" ng-click="editInfo('mobile', profile.mobile)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['qq']['title'];?></td>
				<td ng-bind="profile.qq"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#qq" ng-click="editInfo('qq', profile.qq)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['email']['title'];?></td>
				<td ng-bind="profile.email"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#email" ng-click="editInfo('email', profile.email)">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">联系方式</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['telephone']['title'];?></td>
				<td ng-bind="profile.telephone"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#telephone" ng-click="editInfo('telephone', profile.telephone)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['msn']['title'];?></td>
				<td ng-bind="profile.msn"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#msn" ng-click="editInfo('msn', profile.msn)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['taobao']['title'];?></td>
				<td ng-bind="profile.taobao"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#taobao" ng-click="editInfo('taobao', profile.taobao)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['alipay']['title'];?></td>
				<td ng-bind="profile.alipay"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#alipay" ng-click="editInfo('alipay', profile.alipay)">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col width="100px">
			<tr>
				<th class="text-left"><?php  echo $uniacid_fields['address']['title'];?></th>
				<th class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#address-add">添加收货地址</a></div>
				</th>
			</tr>
			<tr ng-repeat="address in addresses track by address.id">
				<td class="table-label" ng-bind="address.pcda">
				</td>
				<td class="text-right">
					<div class="link-group">
						<a href="javascript:;" ng-if="!address.isdefault" ng-click="setDefaultAdd(address.id)">设为默认</a>
						<a href="javascript:;" data-toggle="modal" data-target="#address-edit" ng-click="choseEditAdd(address.id)">编辑</a>
						<a href="javascript:;" class="del" ng-click="delAdd(address.id)">删除</a>
					</div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">教育情况</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['studentid']['title'];?></td>
				<td ng-bind="profile.studentid"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#studentid" ng-click="editInfo('studentid', profile.studentid)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['grade']['title'];?></td>
				<td ng-bind="profile.grade"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#grade" ng-click="editInfo('grade', profile.grade)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['graduateschool']['title'];?></td>
				<td ng-bind="profile.graduateschool"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#graduateschool" ng-click="editInfo('graduateschool', profile.graduateschool)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['education']['title'];?></td>
				<td ng-bind="profile.education"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#education" ng-click="editInfo('education', profile.education)">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">工作情况</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['company']['title'];?></td>
				<td ng-bind="profile.company"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#company" ng-click="editInfo('company', profile.company)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['occupation']['title'];?></td>
				<td ng-bind="profile.occupation"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#occupation" ng-click="editInfo('occupation', profile.occupation)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['position']['title'];?></td>
				<td ng-bind="profile.position"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#position" ng-click="editInfo('position', profile.position)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['revenue']['title'];?></td>
				<td ng-bind="profile.revenue"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#revenue" ng-click="editInfo('revenue', profile.revenue)">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">个人情况</th>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['constellation']['title'];?></td>
				<td ng-bind="profile.constellation"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#constellation" ng-click="editInfo('constellation', profile.constellation)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['zodiac']['title'];?></td>
				<td ng-bind="profile.zodiac"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#zodiac" ng-click="editInfo('zodiac', profile.zodiac)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['nationality']['title'];?></td>
				<td ng-bind="profile.nationality"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#nationality" ng-click="editInfo('nationality', profile.nationality)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['height']['title'];?></td>
				<td ng-bind="profile.height"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#height" ng-click="editInfo('height', profile.height)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['weight']['title'];?></td>
				<td ng-bind="profile.weight"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#weight" ng-click="editInfo('weight', profile.weight)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['bloodtype']['title'];?></td>
				<td ng-bind="profile.bloodtype"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#bloodtype" ng-click="editInfo('bloodtype', profile.bloodtype)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['idcard']['title'];?></td>
				<td ng-bind="profile.idcard"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#idcard" ng-click="editInfo('idcard', profile.idcard)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['zipcode']['title'];?></td>
				<td ng-bind="profile.zipcode"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#zipcode" ng-click="editInfo('zipcode', profile.zipcode)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['site']['title'];?></td>
				<td ng-bind="profile.site"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#site" ng-click="editInfo('site', profile.site)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['affectivestatus']['title'];?></td>
				<td ng-bind="profile.affectivestatus"><?php  echo $profile['affectivestatus'];?></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#affectivestatus" ng-click="editInfo('affectivestatus', profile.affectivestatus)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['lookingfor']['title'];?></td>
				<td ng-bind="profile.lookingfor"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#lookingfor" ng-click="editInfo('lookingfor', profile.lookingfor)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['bio']['title'];?></td>
				<td ng-bind="profile.bio"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#bio" ng-click="editInfo('bio', profile.bio)">修改</a></div>
				</td>
			</tr>
			<tr>
				<td class="table-label"><?php  echo $uniacid_fields['interest']['title'];?></td>
				<td ng-bind="profile.interest"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#interest" ng-click="editInfo('interest', profile.interest)">修改</a></div>
				</td>
			</tr>
		</table>
		<table class="table we7-table table-hover table-form">
			<col width="140px">
			<col />
			<col width="100px">
			<tr>
				<th class="text-left" colspan="3">其他信息</th>
			</tr>
			<tr ng-repeat="field in custom_fields">
				<td class="table-label" ng-bind="uniacid_fields[field]['title']"></td>
				<td ng-bind="profile[field]"></td>
				<td class="text-right">
					<div class="link-group"><a href="javascript:;" data-toggle="modal" data-target="#other_field" ng-click="editInfo('other_field', field)">修改</a></div>
				</td>
			</tr>
		</table>
		<!-- 模态框开始 -->
		<div class="modal fade" id="groupid" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改会员组</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<select class="we7-select" ng-model="userOriginal.groupid" ng-options="group.groupid as group.title for group in groups"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('groupid')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="gender" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改性别</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<select class="we7-select" ng-model="userOriginal.gender" ng-options="sex.id as sex.name for sex in sexes"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('gender')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="nickname" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改昵称</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.nickname" class="form-control" placeholder="昵称" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('nickname')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="realname" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改真实姓名</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.realname" class="form-control" placeholder="真实姓名" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('realname')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="births" role="dialog">
			<div class="we7-modal-dialog modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改出生年月日</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<?php  echo tpl_fans_form('birth',$profile['birth']);?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" ng-click="httpChange('births')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="resides" role="dialog">
			<div class="we7-modal-dialog modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改户籍</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<?php  echo tpl_fans_form('reside',$profile['resides']);?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" ng-click="httpChange('resides')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="address" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改详细地址</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.address" class="form-control" placeholder="详细地址" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('address')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="mobile" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改手机号</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.mobile" class="form-control" placeholder="手机号码" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('mobile')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="qq" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改QQ号</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.qq" class="form-control" placeholder="QQ号" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('qq')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="email" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改Email</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.email" class="form-control" placeholder="email" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('email')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="password" role="dialog">
			<div class="we7-modal-dialog modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改密码</div>
					</div>
					<div class="modal-body text-center">
						<div class="we7-form" style="width: 450px; margin: 0 auto;">
							<div class="form-group">
								<label for="" class="control-label col-sm-2">新密码</label>
								<div class="form-controls col-sm-10">
									<input type="password" value="" class="form-control new-password">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="control-label col-sm-2">确认新密码</label>
								<div class="form-controls col-sm-10">
									<input type="password" value="" class="form-control renew-password">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" ng-click="httpChange('password')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="telephone" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">修改固定电话</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.telephone" class="form-control" placeholder="固定电话" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('telephone')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="msn" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">MSN</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.msn" class="form-control" placeholder="MSN" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('msn')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="taobao" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">阿里旺旺</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.taobao" class="form-control" placeholder="淘宝账号" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('taobao')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="alipay" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">支付宝账号</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.alipay" class="form-control" placeholder="淘宝账号" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('alipay')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="studentid" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">学号</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.studentid" class="form-control" placeholder="学号" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('studentid')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="grade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">班级</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.grade" class="form-control" placeholder="班级" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('grade')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="graduateschool" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">毕业院校</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.graduateschool" class="form-control" placeholder="毕业院校" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('graduateschool')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="education" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">学历</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<select class="we7-select" ng-model="userOriginal.education" ng-options="education for education in educations"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('education')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="company" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">公司</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.company" class="form-control" placeholder="公司" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('company')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="occupation" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">职业</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.occupation" class="form-control" placeholder="职业" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('occupation')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="position" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">职位</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.position" class="form-control" placeholder="职位" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('position')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="revenue" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">年收入</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.revenue" class="form-control" placeholder="年收入" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('revenue')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="interest" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">兴趣爱好</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.interest" class="form-control" placeholder="兴趣爱好" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('interest')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="bio" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">自我介绍</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.bio" class="form-control" placeholder="自我介绍" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('bio')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="lookingfor" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">交友目的</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.lookingfor" class="form-control" placeholder="交友目的" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('lookingfor')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="affectivestatus" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">情感状态</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.affectivestatus" class="form-control" placeholder="情感状态" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('affectivestatus')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="site" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">个人主页</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.site" class="form-control" placeholder="个人主页" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('site')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="zipcode" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">邮编</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.zipcode" class="form-control" placeholder="邮编" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('zipcode')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="idcard" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">身份证号</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.idcard" class="form-control" placeholder="身份证号" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('idcard')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="weight" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">体重</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.weight" class="form-control" placeholder="体重" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('weight')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="height" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">身高</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.height" class="form-control" placeholder="身高" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('height')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="nationality" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">国籍</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal.nationality" class="form-control" placeholder="国籍" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('nationality')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="constellation" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">星座</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<select class="we7-select" ng-model="userOriginal.constellation" ng-options="constellation for constellation in constellations"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('constellation')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="zodiac" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">生肖</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<select class="we7-select" ng-model="userOriginal.zodiac" ng-options="zodiac for zodiac in zodiacs"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('zodiac')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="bloodtype" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title">血型</div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<select class="we7-select" ng-model="userOriginal.bloodtype" ng-options="bloodtype for bloodtype in bloodtypes"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('bloodtype')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="other_field" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="we7-modal-dialog modal-dialog we7-form">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<div class="modal-title" ng-bind="other_field_name"></div>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" ng-model="userOriginal[other_field_title]" class="form-control" placeholder="{{other_field_name}}" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="httpChange('other_field')">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="address-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="address-add">添加收货地址</h4>
					</div>
					<div class="modal-body">
						<div class="addaddress">
							<div class="form-group">
								<label class="col-sm-2 control-label">姓名</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="addAddress.name" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">手机号</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="addAddress.phone" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">邮编</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="addAddress.code" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">收货地址</label>
								<div class="col-sm-9 col-xs-12">
									<?php  echo tpl_form_field_district("addaddress");?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">详细地址</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="addAddress.detail" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-9 col-xs-12">
									<a href="javascript:"  ng-click="addAdd()" class="btn btn-default btn-primary">提交</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="address-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="address-edit">修改收货地址</h4>
					</div>
					<div class="modal-body">
						<div class="addaddress">
							<div class="form-group">
								<label class="col-sm-2 control-label">姓名</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="editAddress.name" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">手机号</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="editAddress.phone" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">邮编</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="editAddress.code" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">收货地址</label>
								<div class="col-sm-9 col-xs-12">
									<?php  echo tpl_form_field_district("addaddress");?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">详细地址</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" type="text" ng-model="editAddress.detail" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-9 col-xs-12">
									<a href="javascript:"  ng-click="editAdd(editAddress.id)" class="btn btn-default btn-primary">提交</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 模态框结束 -->
		<!--end编辑会员-->
		<?php  } ?>
		
	</form>
</div>
<script type="text/javascript">
	angular.module('memberAPP').value('config', {
		profile:<?php echo !empty($profile) ? json_encode($profile) : 'null'?>,
		custom_fields:<?php echo !empty($custom_fields) ? json_encode($custom_fields) : 'null'?>,
		all_fields:<?php echo !empty($all_fields) ? json_encode($all_fields) : 'null'?>,
		uniacid_fields:<?php echo !empty($uniacid_fields) ? json_encode($uniacid_fields) : 'null'?>,
		groups:<?php echo !empty($groups) ? json_encode($groups) : 'null'?>,
		addresses:<?php  echo json_encode($addresses)?>,
		uid:<?php  echo $uid;?>,
		links: {
			basePost: "<?php  echo url('mc/member/base_information', array('uid' => $uid))?>",
			addAddressUrl: "<?php  echo url('mc/member/address', array('op' =>'addaddress', 'uid' => $uid))?>",
			editAddressUrl: "<?php  echo url('mc/member/address', array('op' =>'editaddress', 'uid' => $uid))?>",
			delAddressUrl: "<?php  echo url('mc/member/address', array('op' =>'deladdress','uid' => $uid))?>",
			setDefaultAddressUrl: "<?php  echo url('mc/member/address', array('op' => 'isdefault', 'uid' => $uid))?>",
		},
	});
	angular.bootstrap($('#js-base-information'), ['memberAPP']);
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>