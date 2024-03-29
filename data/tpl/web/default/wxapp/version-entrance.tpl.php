<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div id="js-weentrance-display" ng-controller="WxappEntranceCtrl">
	<table class="table we7-table table-hover site-list">
		<col width="100px"/>
		<col width="40px"/>
		<col width="180px"/>
		<tr>
			<th colspan="3">名称</th>
			<th colspan="3">小程序入口标识</th>
			<th colspan="3">操作</th>
		</tr>
		<tr ng-repeat="module_info in moduleList">
			<td colspan="3">{{module_info.title}}</td>
			<td colspan="3">{{module_info.do}}</td>
			<td colspan="3">
				<a href="javascript:;" id="copy-{{module_info.eid}}" class="color-default" clipboard supported="supported" text="module_info.do" on-copied="success(module_info.eid)">点击复制</a>
			</td>
		</tr>
	</table>
</div>
<script>
	angular.module('wxApp').value('config', {
		moduleList: <?php echo !empty($module_info) ? json_encode($module_info) : 'null'?>,
	});
	angular.bootstrap($('#js-weentrance-display'), ['wxApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>