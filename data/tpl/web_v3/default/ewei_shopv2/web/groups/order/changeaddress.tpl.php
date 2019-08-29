<?php defined('IN_IA') or exit('Access Denied');?><script type="text/javascript" src="../addons/ewei_shopv2/static/js/dist/area/cascade.js"></script>

<form class="form-horizontal form-validate" action="<?php  echo webUrl('groups/order/changeaddress')?>" method="post" enctype="multipart/form-data">
	<input type='hidden' name='id' value='<?php  echo $id;?>' />

	<div class="modal-dialog">
           <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">编辑收货信息</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">收 货 人 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="realname" class="form-control" value="<?php  echo $user['realname'];?>" data-rule-required='true' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="mobile" class="form-control" value="<?php  echo $user['mobile'];?>" data-rule-required='true' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p>
                            <select id="sel-provance" name="province" onChange="selectCity();" class="select form-control" style="width:123px;display:inline;">
                                <option value="" selected="true">省/直辖市</option>
                            </select>
                            <select id="sel-city" name="city" onChange="selectcounty(0)" class="select form-control" style="width:135px;display:inline;">
                                <option value="" selected="true">请选择</option>
                            </select>
                            <select id="sel-area" name="area" class="select form-control" style="width:130px;display:inline;">
                                <option value="" selected="true">请选择</option>
                            </select>
                        </p>
                        <input type="text" name="address" class="form-control" value="<?php  echo $address_info;?>" data-rule-required='true' />
                    </div>
                </div>
                <input type="hidden" name="changead" value="1" />

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">保存</button>
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
            </div>
        </div>
        </div>
</form>
<script language="javascript">
    cascdeInit("<?php  echo $new_area?>","0","<?php echo isset($user['province'])?$user['province']:''?>","<?php echo isset($user['city'])?$user['city']:''?>","<?php echo isset($user['area'])?$user['area']:''?>");
</script>



<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+454mI5p2D5omA5pyJ-->