<?php defined('IN_IA') or exit('Access Denied');?><style>
    .fui-goods-list{width:100%;border-bottom: 1px dashed #e1ecee;padding-top:5px;padding-bottom:5px;}
    .fui-goods-list span{display: block;padding:0;}
</style>
<form class="form-horizontal form-validate" action="<?php  echo webUrl('order/op/sendcancel')?>" method="post" enctype="multipart/form-data">
	<input type='hidden' name='id' value='<?php  echo $id;?>' />

	<div class="modal-dialog">
           <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">取消发货</h4>
            </div>
               <div class="modal-body">

               <?php  if($bundles) { ?>
               <div class="form-group">
                   <label class="col-sm-2 control-label must">选择包裹</label>
                   <div class="col-sm-10 col-xs-12">
                       <div class="alert alert-danger">
                           请选择需要取消发货的包裹
                       </div>
                       <?php  if(is_array($bundles)) { foreach($bundles as $k => $b) { ?>
                       <label class="fui-goods-list checkbox-inline row" style="padding:0;">
							<span class="col-sm-1">
								<input type="radio" name="bundle" style="margin-top:10px;" value="<?php  echo $b['sendtype'];?>"/>
							</span>
                           <div class="col-sm-11" style="padding:5px 0;">
                               <?php  if(is_array($b['goods'])) { foreach($b['goods'] as $g) { ?>
                               <div class="row" style="margin:0;padding-bottom:5px">
								   <span class="col-sm-1">
										<img src="<?php  echo tomedia($g['thumb'])?>" width="25" height="25" alt="">
								   </span>
									<span class="col-sm-11">
										<?php  if($g['ispresell']==1) { ?><label class="label label-danger" style="padding:2px 4px;margin-right:3px;">预售</label><?php  } ?><?php  echo $g['title'];?>
									</span>
                               </div>
                               <?php  } } ?>
                           </div>
                       </label>
                       <?php  } } ?>
                   </div>
               </div>
               <?php  } ?>

               <?php  if($sendgoods) { ?>
               <div class="form-group">
                   <label class="col-sm-2 control-label must">包裹商品</label>
                   <div class="col-sm-9 col-xs-12">
                       <label class="fui-goods-list checkbox-inline row" style="padding:0;">
                           <div class="col-sm-11" style="padding:5px 0;">
                               <div class="row" style="margin:0;padding-bottom:5px">
								   <span class="col-sm-2">
										<img src="<?php  echo tomedia($sendgoods['thumb'])?>" width="40" alt="">
								   </span>
									<span class="col-sm-10">
										<?php  if($sendgoods['ispresell']==1) { ?><label class="label label-danger" style="padding:2px 4px;margin-right:3px;">预售</label><?php  } ?><?php  echo $sendgoods['title'];?>
									</span>
                               </div>
                           </div>
                       </label>
                   </div>
               </div>
               <?php  } ?>

                    <textarea style="height:150px;" class="form-control" name="remark"  placeholder="取消发货原因" ></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">提交</button>
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
            </div>
        </div>
</form>

<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+4-->