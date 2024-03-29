<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">

    当前位置：<span class="text-primary">收银台管理</span>
</div>
<div class="page-content">
    <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r" value="cashier.user" />

        <div class="page-toolbar m-b-sm m-t-sm">
            <div class="col-sm-3">
                <span class=''>
                    <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('cashier/user/add')?>"><i class="fa fa-plus"></i> 添加收银台</a>
                </span>
            </div>


            <div class="col-sm-6 pull-right">
                <div class="input-group">
                    <div class="input-group-select">
                        <select name='categoryid' class='form-control  input-sm select-md' style="width:100px;"  >
                            <option value=''>分类</option>
                            <?php  if(is_array($category)) { foreach($category as $g) { ?>
                            <option value="<?php  echo $g['id'];?>" <?php  if($_GPC['categoryid']==$g['id']) { ?>selected<?php  } ?>><?php  echo $g['catename'];?></option>
                            <?php  } } ?>
                        </select>
                    </div>
                    <div class="input-group-select">
                        <select name='status' class='form-control  input-sm select-md' style="width:100px;"  >
                            <option value=''>审核状态</option>
                            <option value='0' <?php  if($_GPC['status']=='0') { ?>selected<?php  } ?>>关闭</option>
                            <option value='1' <?php  if($_GPC['status']=='1') { ?>selected<?php  } ?>>开启</option>
                        </select>
                    </div>
                    <input type="text" class="form-control input-sm"  name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="商户名称/联系人/手机号"/>
                     <span class="input-group-btn">

                                            <button class="btn btn-primary" type="submit"> 搜索</button>
                    </span>
                </div>

            </div>
        </div>
    </form>
    <?php  if(count($list)>0) { ?>
    <div class="page-table-header">
        <input type="checkbox">
        <div class="btn-group">
            <?php if(cv('cashier.user.edit')) { ?>
            <a class='btn btn-sm btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('cashier/user/status',array('status'=>1))?>"  data-confirm='确认要设置为开启收银台吗?'>
                <i class="icow icow-qiyong"></i>开启</a>
            <a class='btn btn-sm btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('cashier/user/status',array('status'=>0))?>" data-confirm='确认要设置为关闭收银台吗?'>
                <i class="icow icow-jinyong"></i> 关闭</a>
            <?php  } ?>
            <?php if(cv('cashier.user.delete')) { ?>
            <a class="btn btn-sm btn-default btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('cashier/user/delete')?>">
                <i class='icow icow-shanchu1'></i> 删除</a>
            <?php  } ?>
        </div>
    </div>
    <table class="table table-hover table-responsive">
        <thead class="navbar-inner" >
        <tr>
            <th style="width:25px;"></th>
            <th>收银台名称</th>
            <th>联系人</th>
            <th>申请时间</th>
            <th style='width:70px;'>状态</th>
            <th style='width:65px;'>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $row) { ?>
        <tr rel="pop" data-title="ID: <?php  echo $row['id'];?> ">

            <td>
                <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
            </td>
            <td><?php  echo $row['title'];?></td>
            <td><?php  echo $row['name'];?><br/><?php  echo $row['mobile'];?></td>
            <td><?php  echo date('Y-m-d H:i',$row['createtime'])?></td>
            <td>
                <?php  if(empty($row['status'])) { ?>
                <span class="label label-default">关闭</span>
                <?php  } else { ?>
                <span class="label label-primary">开启</span>
                <?php  } ?>
            </td>
            <td  style="overflow:visible;">
                <?php if(cv('cashier.user.view|cashier.user.edit')) { ?>
                <a href="<?php  echo webUrl('cashier/user/edit', array('id' => $row['id']))?>" class="btn btn-default btn-sm btn-op btn-operation" >
                      <span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php if(cv('cashier.user.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>">
                          <?php if(cv('cashier.user.edit')) { ?>
                    <i class="icow icow-bianji2"></i>
                          <?php  } else { ?>
                          <i class="icow icow-chakan-copy"></i>
                          <?php  } ?>
                     </span>
                </a>
                <?php  } ?>
                <?php if(cv('cashier.user.delete')) { ?>
                <a data-toggle='ajaxRemove' href="<?php  echo webUrl('cashier/user/delete', array('id' => $row['id']))?>"class="btn btn-default btn-sm btn-op btn-operation" data-confirm='确认要删除此商户吗?'>
                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                         <i class='icow icow-shanchu1'></i>
                    </span>
                </a>
                <?php  } ?>
            </td>
        </tr>
        <?php  } } ?>
        </tbody>
        <tfoot>
            <tr>
                <td><input type="checkbox"></td>
                <td colspan="2">
                    <div class="btn-group">
                        <?php if(cv('cashier.user.edit')) { ?>
                        <a class='btn btn-sm btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('cashier/user/status',array('status'=>1))?>"  data-confirm='确认要设置为开启收银台吗?'>
                            <i class="icow icow-qiyong"></i>开启</a>
                        <a class='btn btn-sm btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('cashier/user/status',array('status'=>0))?>" data-confirm='确认要设置为关闭收银台吗?'>
                            <i class="icow icow-jinyong"></i> 关闭</a>
                        <?php  } ?>
                        <?php if(cv('cashier.user.delete')) { ?>
                        <a class="btn btn-sm btn-default btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('cashier/user/delete')?>">
                            <i class='icow icow-shanchu1'></i> 删除</a>
                        <?php  } ?>
                    </div>
                </td>
                <td colspan="3" class="text-right">
                    <?php  echo $pager;?>
                </td>
            </tr>
        </tfoot>
    </table>


    <?php  } else { ?>
    <div class='panel panel-default'>
        <div class='panel-body' style='text-align: center;padding:30px;'>
            暂时没有任何收银台用户!
        </div>
    </div>
    <?php  } ?>
</div>
<script language="javascript">


</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--4000097827-->