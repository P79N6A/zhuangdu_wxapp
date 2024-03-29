<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div>
    <ol class="breadcrumb we7-breadcrumb">
        <a href="<?php  echo url('account/manage', array('account_type' => ACCOUNT_TYPE_PHONEAPP_NORMAL))?>"><i class="wi wi-back-circle"></i></a>
        <li><a href="<?php  echo url('account/manage', array('account_type' => ACCOUNT_TYPE_PHONEAPP_NORMAL))?>">APP列表</a></li>
        <li>新建APP</li>
    </ol>
    <div id="js-phone-app" ng-controller="phoneCreateCtrl">
        <div id="select">
            <div class="form-defalut we7-form">
                <div class="form-group" ng-if = "!uniacid">
                    <label for="" class="control-label col-sm-2">APP名称</label>
                    <div class="form-controls col-sm-10">
                        <input type="text" class="form-control wxapp-name" ng-model="phoneappinfo.name" placeholder="APP名称">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">APP描述</label>
                    <div class="form-controls col-sm-10">
                        <input type="text" ng-model="phoneappinfo.description" class="form-control wxapp-name" placeholder="版本描述">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">版本号</label>
                    <div class="form-controls col-sm-10">
                        <input type="text" ng-model="phoneappinfo.version" class="form-control wxapp-name" placeholder="版本号，只能是数字、点，数字最多两位，例如 1.01">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2">添加应用</label>
                    <div class="form-controls col-sm-10">
                        <ul class="app-list">
                            <li class="we7-input-img active" style="float:left;" ng-repeat="module in selectedModule">
                                <img ng-src="{{module.logo}}" />
                                <p>{{module.title}}</p>
                            </li>
                            <li class="we7-input-img" style="float:left;">
                                <a href="javascript:;" class="input-addon" data-toggle="modal" data-target="#add_module"><span>+</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a type="button" class="btn btn-primary" ng-click="savePhoneApp()">创建APP</a>
            </div>
        </div>



        <div class="uploader-modal modal fade module" id="add_module"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog we7-modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">添加应用(点击添加)</h4>
                    </div>

                    <div class="modal-body material-content clearfix">
                        <div class="material-body">
                            <div class="row">
                                <div class="col-sm-2 select-module" ng-repeat="module in modules" ng-click="selectOrCancelModule(module)">
                                    <div class="item" >
                                        <img ng-src="{{ module.logo }}"  alt="" class="icon" ng-if="module.main_module == ''"/>
                                        <div class="name">{{ module.title }}</div>
                                        <div class="mask">
                                            <span class="wi wi-right"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    angular.module('phoneApp').value('config', {
        'uniacid' : <?php  echo $uniacid;?>,
        'version_id' : <?php  echo $version_id;?>,
        'modules' : <?php  echo json_encode($modules)?>,
        'version_info' : <?php  echo json_encode($version_info)?>,
        'links':{
            'create_phone_url' : "<?php  echo url('phoneapp/manage/save');?>",
        }
    });
    angular.bootstrap($('#js-phone-app'), ['phoneApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>