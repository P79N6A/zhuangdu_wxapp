<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
    .popover{z-index:10000;}
    .alert{margin:10px 0;padding:10px;}
    .content .table{border-collapse:collapse;border:none;}
    .content .table tr{border-collapse: collapse;border-left:1px solid #ededed;border-right:1px solid #ededed;}
    .content .table > tbody > tr > td{border-top:1px solid #ededed;border-left:0;border-right:0;}
    .table{margin-bottom:0;}
    .we7-modal-dialog .modal-body, .modal-dialog .modal-body{padding:10px 20px;}
</style>
<div style='height:480px;overflow:auto;min-width:850px;'>
    <table class="table" border="1" cellspacing="0" width="100%" cellpadding="0">
        <thead style="background: #f7f7f7;">
        <tr>
            <td>商品</td>
            <th style="width:100px;text-align: center;">操作</th>
        </tr>
        </thead>
        <tbody>
            <?php  if(is_array($ds)) { foreach($ds as $row) { ?>
            <tr>
              <td><img src='<?php  echo tomedia($row['thumb'])?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' /> <?php  echo $row['title'];?></td>
              <td style="width:80px;"><a href="javascript:;" class="label label-primary" onclick='biz.selector.set(this, <?php  echo json_encode($row);?>)'>选择</a></td>
            </tr>
            <?php  } } ?>
            <?php  if(count($ds)<=0) { ?>
            <tr>
                <td colspan='2' style="text-align: center;">未找到商品</td>
            </tr>
            <?php  } ?>
        </tbody>
    </table>
    <div style="text-align:right;width:100%;">
        <?php  echo $pager;?>
    </div>
</div>
<script type="text/javascript">
    require(['bootstrap'], function ($) {
        $('[data-toggle="tooltip"]').tooltip({
            container: $(document.body)
        });
        $('[data-toggle="popover"]').popover({
            container: $(document.body)
        });
    });
    //分页函数
    var type = '';
    function select_page(url,pindex,obj) {
        if(pindex==''||pindex==0){
            return;
        }
        var keyword = $.trim($("#goodsid_input").val());
        $("#goodsid_input").html('<div class="tip">正在进行搜索...</div>');

        $.ajax({
            url:"<?php  echo webUrl('groups/goods/query')?>",
            type:'get',
            data:{keyword:keyword,page:pindex,psize:10},
            async : false, //默认为true 异步
            success:function(data){
                $(".content").html(data);
            }
        });
    }

</script>