var t = getApp(),e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));

Page({
    data: {
        page: 1,
        params: {},
        loaded: !1
    },
    onLoad: function(t) {
        console.log(t);
    },

    getList: function(){
        // this.setData({
        //     loading: !0
        // });

    },
    add_upgrade_good: function(){
        var goods_ids=[];
        e.get("yoxwechatbusiness/user_upgrade_goods/batch_add_user_upgrade_goods", {
          isajax: 1,
          goods_ids:goods_ids
        }, function (e) {

        })
    },
    delete_upgrade_good: function(){
        var id=0;
        e.get("yoxwechatbusiness/user_upgrade_goods/user_upgrade_goods_delete", {
          isajax: 1,
          id:id
        }, function (e) {

        })
    }
});