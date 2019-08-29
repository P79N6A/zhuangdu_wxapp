<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    当前位置：<span class="text-primary">代理商品-<?php  echo $storename;?></span>
</div>
	<div class="page-content">
  <form action="" method="get">
                   <input type="hidden" name="c" value="site" />
                   <input type="hidden" name="a" value="entry" />
                   <input type="hidden" name="m" value="ewei_shopv2" />
                   <input type="hidden" name="do" value="web" />
                   <input type="hidden" name="r" value="store" />
            <div class="page-toolbar">
                            <div class="col-sm-6">
                                <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('store/storegoods/add')?>&id=<?php  echo $_GPC['id'];?>"><i class='fa fa-plus'></i> 添加代理商品</a>
                            </div>

        </div>

 <?php  if(count($list)>0) { ?>

            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
				 <th style="width:25px;"></th>
				  <th style='width:50px'>顺序</th>
                        <th style=''>代理商品</th>
                        <th style="width:160px;">价格</th>
                        <th style="width:60px;">状态</th>
                        <th style="width: 125px;">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <tr>

								<td>
									<input type='checkbox'   value="<?php  echo $row['id'];?>"/>
							</td>
							  <td>
                    <?php if(cv('store.edit')) { ?>
                    <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('store/displayorder',array('id'=>$row['id']))?>" ><?php  echo $row['displayorder'];?></a>
                    <?php  } else { ?>
                    <?php  echo $row['displayorder'];?>
                    <?php  } ?>
                </td>
                        <td><?php  echo $row['title'];?></td>
                        <td><?php  echo $row['price'];?></td>
                        <td>
                                 <span class='label <?php  if($row['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
										  <?php if(cv('store.edit')) { ?>
										  data-toggle='ajaxSwitch'
										  data-switch-value='<?php  echo $row['status'];?>'
										  data-switch-value0='0|禁用|label label-default|<?php  echo webUrl('store/status',array('status'=>1,'id'=>$row['id']))?>'
										  data-switch-value1='1|启用|label label-success|<?php  echo webUrl('store/status',array('status'=>0,'id'=>$row['id']))?>'
										  <?php  } ?>
										>
										  <?php  if($row['status']==1) { ?>启用<?php  } else { ?>禁用<?php  } ?></span>
                            </td>
                        <td>
                            <?php  if(p('newstore')) { ?>
                            <a class='btn btn-default  btn-sm btn-op btn-operation' href="<?php  echo webUrl('store/goods', array('id' => $row['id']))?>">
                                <span data-toggle="tooltip" data-placement="top" title="" data-original-title="商品">
                                     <i class='icow icow-goods'></i>
                                </span>
                            </a>
                            <?php  } ?>
                          <?php if(cv('store.edit|store.view')) { ?>
                              <a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('store/edit', array('id' => $row['id']))?>">
                                  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php if(cv('shop.verify.store.edit')) { ?>编辑<?php  } else { ?>查看<?php  } ?>">
                                      <?php if(cv('shop.verify.store.edit')) { ?>
                                        <i class="icow icow-bianji2"></i>
                                      <?php  } else { ?>
                                        <i class="icow icow-chakan-copy"></i>
                                      <?php  } ?>
                                 </span>
                              </a>
                             <?php  } ?>
                            <?php  if(p('newstore')) { ?>
                                <?php if(cv('store.diypage')) { ?>
                                    <a class="btn btn-default btn-sm btn-op btn-operation" href="<?php  echo webUrl('store/diypage/settings', array('id'=>$row['id']))?>">
                                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="装修">
                                             <i class='icow icow-mendianzhuangxiu' style="font-weight: bolder"></i>
                                        </span>
                                    </a>
                                <?php  } ?>
                            <?php  } ?>
                            <?php if(cv('store.delete')) { ?>
                            <a class='btn btn-default  btn-sm btn-op btn-operation' data-toggle="ajaxRemove"  href="<?php  echo webUrl('store/delete', array('id' => $row['id']))?>" data-confirm="确认删除此门店吗？">
                                  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                     <i class='icow icow-shanchu1'></i>
                                </span>
                            </a>
                            <?php  } ?>

                            <a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('store/storegoods', array('id' => $row['id']))?>">
                                      <span data-toggle="tooltip" data-placement="top" title="" data-original-title="代理商品">
                                            <i class="icow icow-bianji2"></i>
                                     </span>
                                  </a>
                            </td>

                    </tr>
                    <?php  } } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td colspan="2">
                            <div class="btn-group">
                                <?php if(cv('store.edit')) { ?>
                                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('store/status',array('status'=>1))?>">
                                    <i class='icow icow-qiyong'></i> 启用
                                </button>
                                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('store/status',array('status'=>0))?>">
                                    <i class='icow icow-jinyong'></i> 禁用
                                </button>
                                <?php  } ?>
                                <?php if(cv('store.delete')) { ?>
                                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('store/delete')?>">
                                    <i class='icow icow-shanchu1'></i> 删除
                                </button>
                                <?php  } ?>
                            </div>
                        </td>
                        <td colspan="5" class="text-right"> <?php  echo $pager;?></td>
                    </tr>
                </tfoot>
            </table>

       </form>


        <?php  } else { ?>
<div class='panel panel-default'>
	<div class='panel-body' style='text-align: center;padding:30px;'>
		 暂时没有任何代理商品!
	</div>
</div>
<?php  } ?>
    </div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--OTEzNzAyMDIzNTAzMjQyOTE0-->