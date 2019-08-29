<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/header-base', TEMPLATE_INCLUDEPATH));?>
<?php  if(!MERCHANTID) { ?>
<?php  $frames = buildframes(FRAME);_calc_current_frames($frames);?>
<?php  } ?>
<div data-skin="default" class="skin-default <?php  if($_GPC['main-lg']) { ?> main-lg-body <?php  } ?>">
<?php  if(!$_W['merch_error']) { ?>
<div class="head">
	<nav class="navbar navbar-default" role="navigation">
		<div class="container <?php  if(!empty($frames['section']['platform_module_menu']['plugin_menu'])) { ?>plugin-head<?php  } ?>">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php  if(!MERCHANTID) { ?><?php  echo $_W['siteroot'];?><?php  } else { ?><?php  echo FX_URL . 'web/merch.php';?><?php  } ?>">
					<img src="<?php  if(!empty($_W['setting']['copyright']['blogo'])) { ?><?php  echo tomedia($_W['setting']['copyright']['blogo'])?><?php  } else { ?><?php  echo $_W['siteroot'];?>web/resource/images/logo/logo.png<?php  } ?>" class="pull-left" width="110px" height="35px">
					<span class="version hidden"><?php echo IMS_VERSION;?></span>
				</a>
			</div>
            <style>.head .navbar-right, .head .navbar-left,.navbar-nav > li > a{ font-size:inherit; line-height:25px;}</style>
			<?php  if(!empty($_W['uid']) || $_SESSION['role']=='merchant') { ?>
			<div class="collapse navbar-collapse">
                <?php  if($_SESSION['role']=='merchant') { ?>
                <ul class="nav navbar-nav navbar-left">
                    <li<?php  if($_GPC['ac']=='merchant') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('application/merchant/setting')?>"><i class="fa fa-desktop"></i>&nbsp;&nbsp; 管理中心</a></li>
                    <li<?php  if($_GPC['ac']=='activity') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('activity/activity/display')?>"><i class="fa fa-gift"></i>&nbsp;&nbsp; 活动管理</a></li>
                    <li<?php  if($_GPC['ac']=='records') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('records/records/display')?>"><i class="fa fa-list"></i>&nbsp;&nbsp; 报名管理</a></li>
                    <li<?php  if($_GPC['do']=='application' && $_GPC['ac']!='merchant') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('application/plugins/display')?>"><i class="fa fa-cubes"></i>&nbsp;&nbsp; 应用扩展</a></li>
				</ul>
			    <ul class="nav navbar-nav navbar-right">
					<li>
						<a href="javascript:;"  data-toggle="dropdown" style="display:block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><img src="<?php  echo tomedia($_SESSION['role_logo'])?>?time=<?php  echo time()?>" class="img-responsive img-thumbnail" onerror="this.src='../../../web/resource/images/nopic-107.png'" width="30" height="30"/> <?php  echo $_SESSION['role_name'];?> </a>
					</li>
					<li>
						<a href="<?php  echo web_url('user/logout');?>" style="display:block; max-width:185px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-sign-out"></i>退出系统 </a>
					</li>
				</ul>
			    <?php  } else { ?>
                <ul class="nav navbar-nav navbar-left">
					<li<?php  if($_GPC['ac']=='welcome') { ?> class="active"<?php  } ?>><a href="<?php  echo url('platform/cover', array('eid' => $entries['cover'][0]['eid'], 'version_id' => 0))?>"><i class="fa fa-home"></i>&nbsp;&nbsp; 首页</a></li>
                    <li<?php  if($_GPC['ac']=='activity') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('activity/activity/display')?>"><i class="fa fa-gift"></i>&nbsp;&nbsp; 活动管理</a></li>
                    <li<?php  if($_GPC['ac']=='records') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('records/records/display')?>"><i class="fa fa-list"></i>&nbsp;&nbsp; 报名管理</a></li>
                    <li<?php  if($_GPC['ac']=='member') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('member/member/display')?>"><i class="fa fa-user"></i>&nbsp;&nbsp; 会员管理</a></li>
                    <li<?php  if($_GPC['do']=='application') { ?> class="active"<?php  } ?>><a href="<?php  echo web_url('application/plugins/display')?>"><i class="fa fa-cubes"></i>&nbsp;&nbsp; 应用扩展</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="wi wi-user color-gray"></i><?php  echo $_W['user']['username'];?> <span class="caret"></span></a>
						<ul class="dropdown-menu color-gray" role="menu">
							<li>
								<a href="<?php  echo url('user/profile');?>" target="_blank"><i class="wi wi-account color-gray"></i> 我的账号</a>
							</li>
							<?php  if($_W['isfounder']) { ?>
							<li class="divider"></li>
							<?php  if(uni_user_see_more_info(ACCOUNT_MANAGE_NAME_VICE_FOUNDER, false)) { ?>
							<li><a href="<?php  echo url('cloud/upgrade');?>" target="_blank"><i class="wi wi-update color-gray"></i> 自动更新</a></li>
							<?php  } ?>
							<li><a href="<?php  echo url('system/updatecache');?>" target="_blank"><i class="wi wi-cache color-gray"></i> 更新缓存</a></li>
							<li class="divider"></li>
							<?php  } ?>
							<li>
								<a href="<?php  echo url('user/logout');?>"><i class="fa fa-sign-out color-gray"></i> 退出系统</a>
							</li>
						</ul>
					</li>
				</ul>
                <?php  } ?>
			</div>
			<?php  } else { ?>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"><a href="<?php  echo url('user/register');?>">注册</a></li>
					<li class="dropdown"><a href="<?php  echo url('user/login');?>">登录</a></li>
				</ul>
			</div>
			<?php  } ?>
		</div>
	</nav>
</div>
<?php  } ?>
<?php  if(empty($_COOKIE['check_setmeal']) && !empty($_W['account']['endtime']) && ($_W['account']['endtime'] - TIMESTAMP < (6*86400))) { ?>
<div class="system-tips we7-body-alert" id="setmeal-tips">
	<div class="container text-right">
		<div class="alert-info">
			<a href="<?php  if($_W['isfounder']) { ?><?php  echo url('user/edit', array('uid' => $_W['account']['uid']));?><?php  } else { ?>javascript:void(0);<?php  } ?>">
				该公众号管理员服务有效期：<?php  echo date('Y-m-d', $_W['account']['starttime']);?> ~ <?php  echo date('Y-m-d', $_W['account']['endtime']);?>.
				<?php  if($_W['account']['endtime'] < TIMESTAMP) { ?>
				目前已到期，请联系管理员续费
				<?php  } else { ?>
				将在<?php  echo floor(($_W['account']['endtime'] - strtotime(date('Y-m-d')))/86400);?>天后到期，请及时付费
				<?php  } ?>
			</a>
			<span class="tips-close" onclick="check_setmeal_hide();"><i class="wi wi-error-sign"></i></span>
		</div>
	</div>
</div>
<script>
	function check_setmeal_hide() {
		util.cookie.set('check_setmeal', 1, 1800);
		$('#setmeal-tips').hide();
		return false;
	}
</script>
<?php  } ?> 
<div class="main">
<?php  if(!defined('IN_MESSAGE')) { ?>
<div class="container">
	<a href="javascript:;" class="js-big-main button-to-big color-gray" title="加宽"><?php  if($_GPC['main-lg']) { ?>正常<?php  } else { ?>宽屏<?php  } ?></a>
	<div class="panel panel-content main-panel-content <?php  if(!empty($frames['section']['platform_module_menu']['plugin_menu'])) { ?>panel-content-plugin<?php  } ?>">
		<div class="content-head panel-heading main-panel-heading">
        <?php  if(!MERCHANTID) { ?>
			<?php  if(($_GPC['c'] != 'cloud' && !empty($_GPC['m']) && !in_array($_GPC['m'], array('keyword', 'special', 'welcome', 'default', 'userapi', 'service'))) || defined('IN_MODULE')) { ?>
				<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/header-module', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/header-module', TEMPLATE_INCLUDEPATH));?>
			<?php  } else { ?>
				<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/header-' . FRAME, TEMPLATE_INCLUDEPATH)) : (include fx_template('common/header-' . FRAME, TEMPLATE_INCLUDEPATH));?>
			<?php  } ?>
        <?php  } else { ?>
        	<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/header-module', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/header-module', TEMPLATE_INCLUDEPATH));?>
        <?php  } ?>
		</div>
	<div class="panel-body clearfix main-panel-body <?php  if(!empty($_W['setting']['copyright']['leftmenufixed'])) { ?>menu-fixed<?php  } ?>">
    	<style>.panel-content-plugin .panel-menu .list-group-item:not(.list-group-more){padding-left:30px;}</style>
		<div class="left-menu">
        <?php  fx_load()->func('tpl');?>
        <?php  $getlistFrames = 'get'.$controller.'Frames';?>
        <?php  $_frames = $getlistFrames();?>
        <?php $_frames = empty($_frames) ? $GLOBALS['frames'] : $_frames; _calc_current_frames2($_frames);?>
        <?php  if(MERCHANTID) { ?>
            <style>.left-menu .fa{width:14px;}</style>
			<div class="left-menu-content">
            	<?php  if(is_array($_frames)) { foreach($_frames as $k => $frame) { ?>
                <div class="panel panel-menu">
                    <div class="panel-heading">
                        <span class="no-collapse"><?php  echo $frame['title'];?></span>
                    </div>
                    <ul class="list-group">
                        <?php  if(is_array($frame['items'])) { foreach($frame['items'] as $link) { ?>
                        <li class="list-group-item <?php  echo $link['active'];?>">
                            <a href="<?php  echo $link['url'];?>" class="text-over"><?php  echo $link['title'];?></a>
                        </li>
                        <?php  } } ?>
                    </ul>
                </div>
                <?php  } } ?>
			</div>
        <?php  } else { ?>
        	<style>.left-menu .no-fa .fa{display:none}</style>
        	<?php  if(empty($frames['section']['platform_module_menu']['plugin_menu'])) { ?>
            <div class="left-menu-content">
                <?php  if(is_array($_frames)) { foreach($_frames as $k => $frame) { ?>                               
                <div class="panel panel-menu">
                    <div class="panel-heading" style="padding-left:0;">
                        <span class="no-collapse no-fa"><?php  echo str_replace('&nbsp;','',$frame['title'])?><i class="wi wi-appsetting pull-right setting"></i></span>
                    </div>
                    <ul class="list-group panel-collapse">
                        <?php  if(is_array($frame['items'])) { foreach($frame['items'] as $link) { ?>
                        <li class="list-group-item <?php  echo $link['active'];?>">
                            <a href="<?php  echo $link['url'];?>" class="text-over"><?php  echo str_replace('&nbsp;','',$link['title'])?></a>
                        </li>
                        <?php  } } ?>                            
                    </ul>
                </div>
                <?php  } } ?>
            </div>
        	<?php  } else { ?>
            <div class="plugin-menu clearfix">
                <div class="plugin-menu-main pull-left">
                    <ul class="list-group">
                        <li class="list-group-item<?php  if($_W['current_module']['name'] == $frames['section']['platform_module_menu']['plugin_menu']['main_module']) { ?> active<?php  } ?>">
                            <a href="<?php  echo url('home/welcome/ext', array('m' => $frames['section']['platform_module_menu']['plugin_menu']['main_module']))?>">
                                <i class="wi wi-main-apply"></i>
                                <div>主应用</div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <div>插件</div>
                        </li>
                        <?php  if(is_array($frames['section']['platform_module_menu']['plugin_menu']['menu'])) { foreach($frames['section']['platform_module_menu']['plugin_menu']['menu'] as $plugin_name => $plugin) { ?>
                        <li class="list-group-item<?php  if($_W['current_module']['name'] == $plugin_name) { ?> active<?php  } ?>">
                            <a href="<?php  echo url('home/welcome/ext', array('m' => $plugin_name))?>">
                                <img src="<?php  echo $plugin['icon'];?>" alt="" class="img-icon" />
                                <div><?php  echo $plugin['title'];?></div>
                            </a>
                        </li>
                        <?php  } } ?>
                    </ul>
                    <?php  unset($plugin_name);?>
                    <?php  unset($plugin);?>
                </div>
                <div class="plugin-menu-sub pull-left"> 
                	<?php  if(is_array($_frames)) { foreach($_frames as $k => $frame) { ?>                               
                    <div class="panel panel-menu">
                        <div class="panel-heading" style="padding-left:0;">
                        	<span class="no-collapse no-fa"><?php  echo str_replace('&nbsp;','',$frame['title'])?><i class="wi wi-appsetting pull-right setting"></i></span>
                        </div>
                        <ul class="list-group panel-collapse">
                        	<?php  if(is_array($frame['items'])) { foreach($frame['items'] as $link) { ?>
                            <li class="list-group-item <?php  echo $link['active'];?>">
                                <a href="<?php  echo $link['url'];?>" class="text-over"><i class="fa fa-cog"></i><?php  echo str_replace('&nbsp;','',$link['title'])?></a>
                            </li>
                            <?php  } } ?>                            
                        </ul>
                    </div>
                    <?php  } } ?>
            	</div>
            </div>
            <?php  } ?>
        <?php  } ?>
		</div>
		<div class="right-content">
<?php  } ?>
