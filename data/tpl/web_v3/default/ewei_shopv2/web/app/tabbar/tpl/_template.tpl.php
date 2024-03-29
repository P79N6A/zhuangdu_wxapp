<?php defined('IN_IA') or exit('Access Denied');?><script type="text/html" id="tpl_show_tabbar">
    <div class="diymenu-page"><i class="icon icon-icondownload"></i> 默认第一个菜单展示选中状态</div>

    <div class="diy-tabbar <%borderStyle%>" style="background: <%backgroundColor%>;">
        <%each list as item index %>
            <div class="item">
                <img class="item-icon" src="<%icon(item, index)%>" />
                <span class="item-label" style="color: <%index==0? selectedColor: color%>;"><%item.text%></span>
            </div>
         <%/each%>
    </div>
</script>

<script type="text/html" id="tpl_edit_tabbar">
    <div class="form-group" style="display: none;">
        <div class="col-sm-2 control-label">导航背景</div>
        <div class="col-sm-4">
            <div class="input-group">
                <input class="form-control input-sm diy-bind color" type="color" data-bind="backgroundColor" value="<%backgroundColor%>" />
                <span class="input-group-addon btn btn-default" data-toggle="resetColor" data-color="#f7f7fa">重置</span>
            </div>
        </div>
    </div>

    <div class="form-group" style="display: none;">
        <div class="col-sm-2 control-label">导航边框</div>
        <div class="col-sm-10">
            <label class="radio-inline"><input type="radio" name="borderStyle" value="" class="diy-bind" data-bind="borderStyle" <%if !borderStyle%>checked<%/if%>> 默认</label>
            <label class="radio-inline"><input type="radio" name="borderStyle" value="black" class="diy-bind" data-bind="borderStyle" <%if borderStyle=='black'%>checked<%/if%>> 黑色</label>
            <label class="radio-inline"><input type="radio" name="borderStyle" value="white" class="diy-bind" data-bind="borderStyle" <%if borderStyle=='white'%>checked<%/if%>> 白色</label>
        </div>
    </div>

    <div class="form-group" style="display: none;">
        <div class="col-sm-2 control-label">文字颜色</div>
        <div class="col-sm-4">
            <div class="input-group">
                <input class="form-control input-sm diy-bind color" type="color" data-bind="color" value="<%color%>" />
                <span class="input-group-addon btn btn-default" data-toggle="resetColor" data-color="#999999">重置</span>
            </div>
        </div>
        <div class="col-sm-2 control-label">文字选中颜色</div>
        <div class="col-sm-4">
            <div class="input-group">
                <input class="form-control input-sm diy-bind color" type="color" data-bind="selectedColor" value="<%selectedColor%>" />
                <span class="input-group-addon btn btn-default" data-toggle="resetColor" data-color="#ff0011">重置</span>
            </div>
        </div>
    </div>

    <div class="form-items indent" data-min="2" data-max="5">
        <div class="inner" id="form-items">
            <%each list as item index%>
                <div class="item" data-index="<%index%>">
                    <%if item.pagePath!='/pages/index/index'%>
                        <span class="btn-del del-item" title="删除"></span>
                    <%/if%>
                    <div class="item-body">
                        <div class="item-image drag-btn square">
                            <div class="tabbar-icon" id="icon-show-<%index%>">
                                <img src="<%icon(item)%>"/>
                                <img src="<%icon(item, 0)%>"/>
                            </div>
                            <div style="display: none" id="icon-input-<%index%>">
                                <input type="hidden" class="diy-bind" data-bind="iconPath" data-bind-child="list" data-bind-parent="<%index%>" />
                                <input type="hidden" class="diy-bind" data-bind="selectedIconPath" data-bind-child="list" data-bind-parent="<%index%>" />
                            </div>
                            <div class="text goods-selector" data-toggle="selectIcon2" data-input="#icon-input-<%index%>" data-element="#icon-show-<%index%>">选择图标</div>
                        </div>
                        <div class="item-form">
                            <div class="input-group" style="margin-bottom:0px;">
                                <span class="input-group-addon">导航名称</span>
                                <input type="text" class="form-control input-sm diy-bind" data-bind="text" data-bind-child="list" data-bind-parent="<%index%>" placeholder="请输入导航名称(最长5个字)" maxlength="5" value="<%item.text%>">
                            </div>
                            <div class="input-group" style="margin-top:10px; margin-bottom:0px; width: 100%">
                                <input type="text" class="form-control input-sm diy-bind" data-bind="pagePath" data-bind-child="list" data-bind-parent="<%index%>" data-bind-init="true" placeholder="请选择链接地址" value="<%item.pagePath%>" readonly id="tabbar-<%index%>">
                                <%if item.pagePath!='/pages/index/index'%>
                                    <span class="input-group-addon btn btn-default" data-toggle="selectUrl" data-platform="wxapp_tabbar" data-input="#tabbar-<%index%>">选择链接</span>
                                    <%if item.pagePath%>
                                    <span class="input-group-addon btn btn-default" data-toggle="setNull" data-element="#tabbar-<%index%>">清除链接</span>
                                    <%/if%>
                                <%/if%>
                            </div>
                        </div>
                    </div>
                </div>
            <%/each%>
        </div>
        <div class="btn btn-w-m btn-block btn-default btn-outline" id="addItem"><i class="fa fa-plus"></i> 添加一个</div>
    </div>
</script>