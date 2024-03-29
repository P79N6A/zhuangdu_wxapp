<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!--系统管理首页-->
<div class="welcome-container js-system-welcome" ng-controller="WelcomeCtrl" ng-cloak>
	<div ng-if="ads" class="ad-img we7-margin-bottom">
		
		<!--<div id="welcome-ad" class="carousel slide" data-ride="carousel">

			<div class="carousel-inner" role="listbox">
			  <div class="item " ng-class="key ==0 ? 'active' : ''" ng-repeat="(key, ad) in ads">
				<a ng-href="{{ad.url}}" target="_blank"  alt="{{ad.title}}"><img ng-src="{{ad.src}}" alt="{{ad.title}}" ></a>
			  </div>
			</div>

			<a class="left carousel-control" href="#welcome-ad" role="button" data-slide="prev">
			  <span class="wi wi-angle-left" aria-hidden="true"></span>
			  <span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#welcome-ad" role="button" data-slide="next">
			  <span class="wi wi-angle-right" aria-hidden="true"></span>
			  <span class="sr-only">Next</span>
			</a>
		</div>
		<!-- <a ng-href="{{ad.url}}" target="_blank" ng-repeat="ad in ads"><img ng-src="{{ad.src}}" alt="" class="img-responsive" style="margin: 0 auto;"></a> -->
	</div>-->
	<div class="row">
		<div class="col-sm-6">
			<div class="panel we7-panel account-stat">
				<div class="panel-heading">微信应用模块</div>
				<div class="panel-body we7-padding-vertical">
					<div class="col-sm-4 text-center">
						<div class="title">未安装应用</div>
						<div class="num">
							<a href="<?php  echo url('module/manage-system/not_installed', array('support' => MODULE_SUPPORT_ACCOUNT_NAME))?>" class="color-default">{{module_statistics.account.total.uninstall}}</a>
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="title">可升级应用</div>
						<div class="num">
						{{module_statistics.account.total.upgrade}}
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="title">应用总数</div>
						<div class="num">
							<a href="<?php  echo url('module/manage-system/installed', array('support' => MODULE_SUPPORT_ACCOUNT_NAME))?>" class="color-default">{{module_statistics.account.total.all}}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel we7-panel account-stat">
				<div class="panel-heading">小程序应用模块</div>
				<div class="panel-body we7-padding-vertical">
					<div class="col-sm-4 text-center">
						<div class="title">未安装应用</div>
						<div class="num">
							<a href="<?php  echo url('module/manage-system/not_installed', array('support' => MODULE_SUPPORT_WXAPP_NAME))?>" class="color-default">{{module_statistics.wxapp.total.uninstall}}</a>
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="title">可升级应用</div>
						<div class="num">
						{{module_statistics.wxapp.total.upgrade}}
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="title">应用总数</div>
						<div class="num">
							<a href="<?php  echo url('module/manage-system/installed', array('support' => MODULE_SUPPORT_WXAPP_NAME))?>" class="color-default">{{module_statistics.wxapp.total.all}}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="modal-loading" style="width:100%">
			<div style="text-align:center;background-color: transparent;">
				<img style="width:48px; height:48px; margin-top:10px;margin-bottom:10px;" src="resource/images/loading.gif" title="正在努力加载...">
			</div>
		</div>
	</div>
	<!--<div class="panel we7-panel system-update" ng-if="upgrade_show == 1">
		<div class="panel-heading">
			<span class="color-gray pull-right">当前版本：<?php echo IMS_FAMILY;?><?php echo IMS_VERSION;?>（<?php echo IMS_RELEASE_DATE;?>）</span>
			系统更新
		</div>
		<div class="panel-body we7-padding-vertical clearfix">
			<div class="col-sm-3 text-center">
				<div class="title">更新文件</div>
				<div class="num">{{upgrade.file_nums}} 个</div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">更新数据库</div>
				<div class="num">{{upgrade.database_nums}} 项</div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">更新脚本</div>
				<div class="num">{{upgrade.script_nums}} 项</div>
			</div>
			<div class="col-sm-3 text-center">
				<a href="<?php  echo url('cloud/upgrade');?>" class="btn btn-danger">去更新</a>
			</div>
		</div>
	</div>
	<div class="panel we7-panel database">
		<div class="panel-heading">
			数据库备份提醒
		</div>
		<div class="panel-body clearfix">
			<div class="col-sm-9">
				<span class="day"><?php  echo $backup_days;?></span>
				<span class="color-default">天</span>
				没有备份数据库了,请及时备份!
			</div>
			<div class="col-sm-3 text-center">
				<a class="btn btn-default" href="<?php  echo url('system/database');?>">开始备份</a>
			</div>
		</div>
	</div>
	<div class="panel we7-panel apply-list">
		<div class="panel-heading">
			<span class="pull-right">
				<a href="<?php  echo url('module/manage-system', array('support' => MODULE_SUPPORT_ACCOUNT_NAME))?>" class="color-default">查看更多公众号应用</a>
				<span class="we7-padding-horizontal inline-block color-gray">|</span>
				<a href="<?php  echo url('module/manage-system', array('support' => MODULE_SUPPORT_WXAPP_NAME))?>" class="color-default">查看更多小程序应用</a>
			</span>
			可升级应用
			<div class="btn-group">
				<span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer">
					筛选 <span class="caret"></span>
				</span>
				<ul class="dropdown-menu" style="top: 150%;left: -103px;">
					<li><a href="javascript:;" ng-click="searchType('all')">全部可升级应用</a></li>
					<li><a href="javascript:;" ng-click="searchType('has_new_version')">应用版本更新</a></li>
					<li><a href="javascript:;" ng-click="searchType('has_new_branch')">应用分支升级</a></li>
				</ul>
			</div>
		</div>
		<div class="panel-body welcome-apple-list">
			<div class="apply-item" ng-repeat="module in upgrade_modules" ng-if="!module.is_ignore && $index+1 <= 8">
				<a href="{{module.link}}" target="_blank"><img src="{{module.logo}}" class="apply-img"/></a>
				<a href="{{module.link}}" target="_blank"><span class="text-over">{{module.title|limitTo:4}}</span></a>
				<a href="{{module.link}}" target="_blank" class="color-red" ng-if="module.has_new_version == 1">升级</a>
				<a href="{{module.link}}" target="_blank" class="color-red" ng-if="module.has_new_branch == 1">新分支</a>
				<a href="javascript:;"data-toggle="popover" class="ignore" class="color-default" data-placement="bottom" data-trigger="hover" data-content="忽略当前版本更新，重新升级到模块列表自行升级" ng-click="ignoreUpdate(module.name)">忽略</a>
			</div>
			<div class="text-center" ng-if="upgrade_modules_show == 0">
				没有可升级的应用
			</div>
		</div>
	</div>
	<div class="panel we7-panel apply-list" ng-show="not_installed_show == 1">
		<div class="panel-heading">
			<span class="pull-right">
				<a href="<?php  echo url('module/manage-system/not_installed', array('support' => MODULE_SUPPORT_ACCOUNT_NAME))?>" class="color-default">查看更多公众号应用</a>
				<span class="we7-padding-horizontal inline-block color-gray">|</span>
				<a href="<?php  echo url('module/manage-system/not_installed', array('support' => MODULE_SUPPORT_WXAPP_NAME))?>" class="color-default">查看更多小程序应用</a>
			</span>
			未安装应用
		</div>
		<div class="panel-body">
			<a href="{{module.link}}" target="_blank" class="apply-item" ng-repeat="module in not_installed_module" ng-if="$index+1 <= 8">
				<img src="{{module.logo}}" class="apply-img"/>
				<span class="text-over">{{module.title|limitTo:4}}</span>
				<span class="color-red">未安装</span>
			</a>
		</div>
	</div>
</div>-->
<!--end 系统管理首页-->
<script type="text/javascript">
	$(function(){
		angular.module('systemApp').value('config', {
			notices: <?php echo !empty($notices) ? json_encode($notices) : 'null'?>,
			systemUpgradeUrl : "<?php  echo url('home/welcome/get_system_upgrade')?>",
			upgradeModulesUrl: "<?php  echo url('home/welcome/get_upgrade_modules')?>",
			moduleStatisticsUrl: "<?php  echo url('home/welcome/get_module_statistics')?>",
			ignoreUpdateUrl : "<?php  echo url('home/welcome/ignore_update_module')?>",
		});
		angular.bootstrap($('.js-system-welcome'), ['systemApp']);
	});
</script>	
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>