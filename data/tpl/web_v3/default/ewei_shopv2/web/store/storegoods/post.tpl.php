<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style>
    .checkbox-inline{
        display: block;
    }    .btns a i{
        display: inline-block;
        width: 100%;
        height: 20px;
        background: #f95959;
    }
    .btn-color {
        width: 25px;
        height: 25px;
        border: 1px solid #fff;
        margin: 2px;
        padding: 0;
    }

</style>
<div class="page-header">
    当前位置：<span class="text-primary">添加代理商品</small>
    </span>
</div>

<div class="page-content">

<form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php  echo $store['id'];?>"/>

    <div class="form-group">
        <label class="col-lg control-label must">当前门店</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" name="storename" class="form-control" value="<?php  echo $store['storename'];?>"
                   readonly="readonly" />
        </div>
    </div>


    <div class="form-group" id="product">
            <label class="col-lg control-label">指定商品</label>
            <div class="col-sm-9 col-xs-12">
                <div class="input-group">
                    <input type="text" id="goodsid_text" name="goodsid_text" value="" class="form-control text" readonly="">
                    <div class="input-group-btn">
                        <button class="btn btn-primary select_goods" type="button">选择商品</button>
                    </div>
                </div>
                <div class="input-group multi-img-details container ui-sortable goods_show">
                    <?php  if(!empty($goods)) { ?>
                    <?php  if(is_array($goods)) { foreach($goods as $g) { ?>
                    <div class="multi-item" data-id="<?php  echo $g['id'];?>" data-name="goodsid" id="<?php  echo $g['id'];?>">
                        <img class="img-responsive img-thumbnail" src="<?php  echo tomedia($g['thumb'])?>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" style="width:100px;height:100px;">
                        <div class="img-nickname"><?php  echo $g['title'];?></div>
                        <input type="hidden" value="<?php  echo $g['id'];?>" name="goodsid[]">
                        <em onclick="removeHtml(<?php  echo $g['id'];?>)" class="close">×</em>
                        <div style="clear:both;"></div>
                    </div>
                    <?php  } } ?>
                    <?php  } ?>
                </div>
                <script>
                    $(function(){
                        var title = '';
                        $('.img-nickname').each(function(){
                            title += $(this).html()+';';
                        });
                        $('#goodsid_text').val(title);
                    })
                    myrequire(['web/goods_selector'],function (Gselector) {
                        $('.select_goods').click(function () {
                            var ids = select_goods_ids();
                            Gselector.open('goods_show','',0,true,'',ids);
                        });
                    })
                    function goods_show(data) {
//                        console.log(data);
                        if(data.act == 1){
                            var html = '<div class="multi-item" data-id="'+data.id+'" data-name="goodsid" id="'+data.id+'">'
                                +'<img class="img-responsive img-thumbnail" src="'+data.thumb+'" onerror="this.src=\'../addons/ewei_shopv2/static/images/nopic.png\'" style="width:100px;height:100px;">'
                                +'<div class="img-nickname">'+data.title+'</div>'
                                +'<input type="hidden" value="'+data.id+'" name="goodsid[]">'
                                +'<em onclick="removeHtml('+data.id+')" class="close">×</em>'
                                +'</div>';

                            $('.goods_show').append(html);
                            var title = '';
                            $('.img-nickname').each(function(){
                                title += $(this).html()+';';
                            });
                            $('#goodsid_text').val(title);
                        }else if(data.act == 0){
                            removeHtml(data.id);
                        }

                    }
                    function removeHtml(id){
                        $("[id='"+id+"']").remove();
                        var title = '';
                        $('.img-nickname').each(function(){
                            title += $(this).html()+';';
                        });
                        $('#goodsid_text').val(title);
                    }
                    function select_goods_ids(){
                        var goodsids = [];
                        $(".multi-item").each(function(){
                            goodsids.push($(this).attr('data-id'));
                        });
                        return goodsids;
                    }
                </script>

            </div>
        </div>


    <div class="form-group">
        <label class="col-lg control-label"></label>
        <div class="col-sm-9 col-xs-12">
            <input type="submit" value="提交" class="btn btn-primary"/>

            <input type="button" name="back" onclick='history.back()' <?php if(cv('store.add|store.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
        </div>
    </div>
</form>
</div>
<script language='javascript'>
    $(function () {
        $(':radio[name=type]').click(function () {
            type = $("input[name='type']:checked").val();

            if (type == '1' || type == '3') {
                $('#pick_info').show();
            } else {
                $('#pick_info').hide();
            }
        })
    })

</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>