<?php defined('IN_IA') or exit('Access Denied');?>    </div>
    <div class="container-fluid footer text-center" role="footer">	
        <div class="friend-link">
            <?php  if(empty($_W['setting']['copyright']['footerright'])) { ?>
                
            <?php  } else { ?>
                <?php  echo $_W['setting']['copyright']['footerright'];?>
            <?php  } ?>
            <?php  print_r($_W['module'])?>
        </div>
        <div class="copyright"><?php  if(empty($_W['setting']['copyright']['footerleft'])) { ?>Copyright <b><?php  echo $_W['account']['name'];?></b> &copy; 2016-2018 . All Rights Reserved.<?php  } else { ?><?php  echo $_W['setting']['copyright']['footerleft'];?><?php  } ?></div>
    </div>
    <?php  if(!empty($_W['setting']['copyright']['statcode'])) { ?><?php  echo $_W['setting']['copyright']['statcode'];?><?php  } ?>
</div>
<script>
//会员搜索
function search_members() {
	if( $.trim($('#member-kwd').val())==''){
		util.tips('请输入关键词');
		$('#member-kwd').focus();
		return;
	}
	$("#module-member").html("正在搜索....")
	$.get("<?php  echo $_W['siteroot'];?>/web/index.php?c=site&a=entry&m=fx_activity&do=member&ac=member&op=selectmember", {
		keyword: $.trim($('#member-kwd').val())
	}, function(dat){
		$('#module-member').html(dat);
	});
}
</script>
</body>
</html>