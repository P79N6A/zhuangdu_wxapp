<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-cut" id="js-module-display" ng-controller="userModuleDisplayCtrl" ng-cloak>
	<div class="panel-heading">
		<span class="panel-heading-left"><i class="wi wi-apply" style="font-size: 24px; margin-right: 10px; vertical-align:middle;"></i>请选择您要操作的应用</span>
	</div>
	<div class="panel-body" >
		<div class="we7-page-search cut-header">
			<div class="cut-search">
				<div class="input-group pull-left">
					<input class="form-control" name="keyword" ng-model="searchKeyword" type="text" placeholder="请输入应用名称" ng-keypress="searchKeywordModule()">
					<span class="input-group-btn"><button class="btn btn-default button" ng-click="searchKeywordModule()"><i class="fa fa-search"></i></button></span>
				</div>
			</div>
		</div>
		<ul class="letters-list">
			<li ng-repeat="letter in alphabet" ng-style="{'background-color': letter == activeLetter ? '#ddd' : 'none'}" ng-class="{'active': letter == activeLetter}" ng-click="searchLetterModule(letter)">
				<a href="javascript:;" ng-bind="letter"></a>
			</li>
		</ul>
		<div class="cut-list" infinite-scroll='loadMore()'>
			<!--应用-->
			<div class="item module-list-item" ng-repeat="list in userModule" ng-if="userModule">
				<div class="content">
					<img ng-src="{{list.logo}}" class="icon" onerror="this.src='./resource/images/nopic-107.png'">
					<div class="name text-over" ng-bind="list.title"></div>
				</div>
				<div class="version">
					<span class="name">支持</span>
					<div class="version-detail">
						<span ng-if="list.<?php echo MODULE_SUPPORT_ACCOUNT_NAME;?> == 2">公众号</span>
						<span ng-if="list.<?php echo MODULE_SUPPORT_ACCOUNT_NAME;?> == 2 && list.wxapp_support == 2">、</span>
						<span ng-if="list.wxapp_support == 2">小程序</span>
					</div>
				</div>
				<div class="mask">
					<a ng-href="./index.php?c=module&a=display&do=switch&module_name={{list.name}}" class="entry" target="_blank">
						<div>进入应用 <i class="wi wi-angle-right"></i></div>
					</a>
					<a href="javascript:;" class="cut-btn" ng-click="showAccounts($event, list.name)">
						<i class="wi wi-changing-over"></i>
					</a>
					<a ng-href="./index.php?c=home&a=welcome&do=add_welcome&module={{list.name}}" onclick="return ajaxopen(this.href);" class="home-show" title="添加到首页常用功能">
						<i class="wi wi-eye"></i>
					</a>
					<a href="./index.php?c=module&a=display&do=rank&module_name={{list.name}}" class="stick" title="置顶">
						<i class="wi wi-stick-sign"></i>
					</a>
				</div>
				<div class="cut-select" ng-mouseleave="hideSelect($event)" ng-show="list.accounts">
					<div class="arrow-left"></div>
					<div class="cut-item">
						<a href="javascript:;">
							<div class="detail" ng-repeat="account in list.accounts" ng-if="list.accounts">
								<div class="text-over text-left" ng-if="account.app_name">
									<span ng-if="account.type_name == account_types.account" class="wi wi-wechat"></span>
									<span ng-if="account.type_name == account_types.wxapp" class="wi wi-wxapp"></span>
									<span ng-if="account.type_name == account_types.webapp" class="wi wi-pc"></span>
									<span ng-if="account.type_name == account_types.phoneapp" class="wi wi-app"></span>
									<span ng-if="account.type_name == account_types.xzapp" class="wi wi-xzapp"></span>
									<span ng-if="account.type_name == account_types.aliapp" class="wi wi-aliapp"></span>
									{{account.account_name}}
								</div>
								<a class="cut-select-mask" href="./index.php?c=module&a=display&do=switch&module_name={{list.name}}&uniacid={{account.uniacid}}&version_id={{account.version_id}}" ng-if="account.version_id" target="_blank">
									<div class="entry">选择进入 <i class="wi wi-angle-right"></i></div>
								</a>
								<a class="cut-select-mask" href="./index.php?c=module&a=display&do=switch&module_name={{list.name}}&uniacid={{account.uniacid}}" ng-if="!account.version_id" target="_blank">
									<div class="entry">选择进入 <i class="wi wi-angle-right"></i></div>
								</a>
							</div>
							<div class="detail" ng-if="!list.accounts">
								<div class="text-over text-center">暂无数据</div>
							</div>
						</a>
					</div>
					<!-- <div class="cut-select-pager">
						<a href="{{links.more_version}}&uniacid={{list.uniacid}}" class="more color-default">更多 >></a>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body" >
	<ul><?php  if(is_array($group_list)) { foreach($group_list as $group_info) { ?>
	<li><?php  echo $group_info['name'];?></li>
	<?php  } } ?></ul>
	</div>
</div>
<script>
	angular.module('moduleApp').value('config', {
		'userModule': <?php echo !empty($user_module) ? json_encode($user_module) : 'null'?>,
		'activeLetter': <?php echo !empty($_GPC['letter']) ? json_encode($_GPC['letter']) : 'null'?>,
		'account_types': {
			'account' : "<?php echo ACCOUNT_TYPE_SIGN;?>",
			'wxapp' : "<?php echo WXAPP_TYPE_SIGN;?>",
			'webapp' : "<?php echo WEBAPP_TYPE_SIGN;?>",
			'phoneapp' : "<?php echo PHONEAPP_TYPE_SIGN;?>",
			'welcome' : "<?php echo WELCOMESYSTEM_TYPE_SIGN;?>",
			'xzapp' : "<?php echo XZAPP_TYPE_SIGN;?>",
			'aliapp' : "<?php echo ALIAPP_TYPE_SIGN;?>",
		},
		'links': {
			'moduleAccounts': "<?php  echo url('module/display/have_permission_uniacids')?>",
			'getallLastSwitch': "<?php  echo url('module/display/getall_last_switch')?>",
		}
	});
	angular.bootstrap($('#js-module-display'), ['moduleApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>