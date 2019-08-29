<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
.pd0{padding:0;}
input[type=checkbox].team-check{vertical-align:middle;margin:0 4px 0 0;}
    table tr td,table tr th{
        border:none !important;
        border-bottom:1px solid #efefef !important;
        padding: 15px 0 !important;

    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">团购管理</span>
</div>
<div class="page-content">
<div class="main">
    <div class="page-toolbar row m-b-sm m-t-sm">
        <form action="./index.php" method="get" class="form-horizontal" plugins="form">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="ewei_shopv2" />
            <input type="hidden" name="do" value="web" />
            <input type="hidden" name="r"  value="groups.team" />
            <input type="hidden" name="type"  value="<?php  echo $_GPC['type'];?>" />
            <div class="col-sm-6">
                <div class="btn-group btn-group-sm">

                    <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d H:i', $endtime)),true);?>
                </div>
            </div>
            <div class="col-sm-6 pull-right">
                <div class="input-group">
                    <div class="input-group-select">
                        <select name='searchtime'  class='form-control  input-sm select-md' style="width:100px;"  >
                            <option value=''>不按时间</option>
                            <option value='starttime' <?php  if($_GPC['searchtime']=='starttime') { ?>selected<?php  } ?>>开团时间</option>
                        </select>
                    </div>
                    <div  class="input-group-select">
                        <select name='searchfield'  class='form-control  input-sm select-md' style="width:100px;"  >
                            <option value='teamid' <?php  if($_GPC['searchfield']=='teamid') { ?>selected<?php  } ?>>团ID</option>
                            <option value='orderno' <?php  if($_GPC['searchfield']=='orderno') { ?>selected<?php  } ?>>订单号</option>
                        </select>
                    </div>
                    <input type="text" class="form-control input-sm"  name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键词"/>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"> 搜索</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <?php  if($_GPC['type'] == 'ing') { ?>
    <div class="panel panel-info" style="border:none;margin: 0;padding-bottom: 20px">
        <div class="input-group-btn">
            <?php  if($_GPC['sort']=='') { ?>
            <button class="btn btn-primary btn-sm" type="button" onclick="javascript:window.location.href='<?php  echo webUrl('groups/team',array('type'=>ing,'sort'=>desc))?>'">
                <i class='fa fa-sort'></i> 参团人数
            </button>
            <?php  } ?>
            <?php  if($_GPC['sort']=='desc') { ?>
            <button class="btn btn-primary btn-sm" type="button" onclick="javascript:window.location.href='<?php  echo webUrl('groups/team',array('type'=>ing,'sort'=>asc))?>'">
                <i class='fa fa-caret-down'></i> 参团人数
            </button>
            <?php  } ?>
            <?php  if($_GPC['sort']=='asc') { ?>
            <button class="btn btn-primary btn-sm" type="button" onclick="javascript:window.location.href='<?php  echo webUrl('groups/team',array('type'=>ing,'sort'=>desc))?>'">
                <i class='fa fa-caret-up'></i> 参团人数
            </button>
            <?php  } ?>
            <button class="btn btn-success btn-sm" type="button" onclick="javascript:window.location.href='<?php  echo webUrl('groups/team',array('type'=>ing,'sort'=>$_GPC['sort'],'team'=>groups))?>'">
                <i class='fa fa-group'></i> 即将成团
            </button>
            <?php  if($_GPC['type']=='ing') { ?>
            <button class="btn btn-default btn-sm" type="button" data-toggle='batch' data-href="<?php  echo webUrl('groups/team/group')?>">
                <i class='fa fa-circle'></i> 手动成团
            </button>
            <?php  } ?>
        </div>
    </div>
    <?php  } ?>
    <div class="panel-default">
        <div class="panel-body table-responsive" style="padding:0;">
            <form action="" method="post">
                <?php  if($teams) { ?>
            <table class="table table-hover ">
                <thead class="navbar-inner">
                <tr>
                    <th style="width:60px; text-align: center;">团ID</th>
                    <th style="width:220px;">商品名称</th>

                    <th style="width:80px;text-align: center;">团购进度</th>
                    <th style="width:140px;text-align: center;">开团时间</th>
                    <th style="width:140px;text-align: center;">到期时间</th>
                    <th style="width:80px;text-align: center;">状态</th>
                    <th style="width:40px; text-align: center;">操作</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                    <?php  if(is_array($teams)) { foreach($teams as $row) { ?>
                    <tr>
                        <td><?php  echo $row['teamid'];?></td>
                        <td style="text-align:left;"><?php  if(!empty($row['title'])) { ?><?php  echo $row['title'];?><?php  } else { ?>--<?php  } ?></td>
                        <td>
                            <font color="red"><?php  echo $row['num'];?></font>/<?php  echo $row['groupnum'];?>
                        </td>
                        <td><?php  echo $row['starttime'];?></td>
                        <td><?php  echo $row['endtime'];?></td>
                        <td>
                            <?php  if($row['success']==0 && $row['status'] > 0) { ?><span class="label label-info">拼 团 中</span><?php  } ?><!--label-warning-->
                            <?php  if($row['success']==1) { ?><span class="label label-success">拼团成功</span><?php  } ?><!--label-warning-->
                            <?php  if($row['success']==-1) { ?><span class="label label-danger">拼团失败</span><?php  } ?><!--label-warning-->
                        </td>
                        <td>
                            <a href="<?php  echo webUrl('groups/team/detail',array('teamid'=>$row['id']))?>" class="btn  btn-op btn-operation">
                             <span data-toggle="tooltip" data-placement="top" title="" data-original-title="查看团信息">
                                        <i class='icow icow-chakan-copy'></i>
                             </span>
                            </a>
                            <?php  if($row['success'] == 1) { ?>
                            <?php if(cv('groups.team.cancel_group')) { ?>

                            <a data-toggle="ajaxPost" href="<?php  echo webUrl('groups/team/cancel_group', array('id' => $row['teamid']))?>" data-confirm="确认取消成团吗？" class="btn  btn-op btn-operation">
                             <span data-toggle="tooltip" data-placement="top" title="" data-original-title="取消成团">
                                        <i class='fa fa-group'></i>
                             </span>
                            </a>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  if($row['success'] == 0) { ?>
                            <?php if(cv('groups.team.group')) { ?>
                            <br /><a class="btn btn-primary btn-xs" data-toggle="ajaxPost" href="<?php  echo webUrl('groups/team/group', array('id' => $row['teamid']))?>" data-confirm="确认立即成团吗？">立即成团</a>
                            <?php  } ?>
                            <?php  } ?>
                        </td>
                    </tr>
                    <?php  } } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right"><?php  echo $pager;?></td>
                    </tr>
                </tfoot>
            </table>
                <?php  } else { ?>
                <div class='panel panel-default'>
                    <div class='panel-body' style='text-align: center;padding:30px;'>
                        暂时没有任何拼团
                    </div>
                </div>
                <?php  } ?>
            </form>
        </div>
    </div>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>