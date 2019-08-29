<?php defined('IN_IA') or exit('Access Denied');?><div class="">
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('sysset.save.notice')) { ?>
        <?php  echo tpl_selector('openids',array('key'=>'openid','text'=>'nickname', 'thumb'=>'avatar','multi'=>1,'placeholder'=>'昵称/姓名/手机号','buttontext'=>'选择通知人', 'items'=>$salers,'url'=>webUrl('member/query') ))?>
        <span class='help-block'>订单生成后商家通知，可以制定多个人，如果不填写则不通知</span>

        <?php  } else { ?>
        <div class="input-group multi-img-details" id='saler_container'>
            <?php  if(is_array($salers)) { foreach($salers as $saler) { ?>
            <div class="multi-item saler-item" openid='<?php  echo $saler['openid'];?>'>
            <input type="hidden" value="<?php  echo $saler['openid'];?>" name="openids[]">
            <img class="img-responsive img-thumbnail" src='<?php  echo $saler['avatar'];?>' onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'">
            <div class='img-nickname'><?php  echo $saler['nickname'];?></div>
            <input type="hidden" value="<?php  echo $saler['openid'];?>" name="openids[]">
        </div>
        <?php  } } ?>
    </div>
    <?php  } ?>
</div>
</div>
<!--NDAwMDA5NzgyNw==-->