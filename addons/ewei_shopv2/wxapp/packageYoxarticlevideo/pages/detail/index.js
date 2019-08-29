var t = getApp(),e = t.requirejs("core"),s = t.requirejs("wxParse/wxParse"),a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
    data: {
        info: {},
        loaded: !1
    },
    onLoad: function(t) {
        this.getDetail(t.id);
    },

    getDetail: function(id){
        this.setData({
            loading: !0
        });
        var that = this;
        e.get("yoxarticlevideo/edit", {
          isajax: 1,
          id:id
        }, function (e) {
            var content=e.data.content;
            s.wxParse('article', 'html', content, that);
             that.setData({
                loading: 0,
                info:e.data
            });
            wx.setNavigationBarTitle({title:e.data.name});
        })

    }


});