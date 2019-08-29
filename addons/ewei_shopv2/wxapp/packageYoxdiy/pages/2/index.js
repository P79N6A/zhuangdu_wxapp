var t = getApp(),e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));

Page({
    data: {
        page: 1,
        params: {},
        loaded: !1,
        can_upgrade:0,
        nav:1,
        bigImg:0,
        bigImgSrc:''
    },
    onLoad: function(t) {
        console.log(t);
    },

    getList: function(){


    },
    changeNav:function(option){
        var id = option.currentTarget.dataset.id;
        this.setData({
            nav:id
        });
    },
    bigDetail:function(option){
        console.log(option);
        var id = option.currentTarget.dataset.id;
        var bigImgSrc = option.currentTarget.dataset.bigimgsrc;
        this.setData({
            bigImg:1,
            bigImgSrc:bigImgSrc
        });
    },
    upgradeRightNow:function(){
        e.get("yoxwechatbusiness/user/i_wand_up", {
          isajax: 1,
        }, function (e) {

        })


       this.setData({
            can_upgrade:0,
            upgrade:1,
        });
    },
    upgrade:function(){
       this.setData({
            can_upgrade:1
        });
    },
    closeShadow:function(){
       this.setData({
            bigImg:0
        });
    }
});