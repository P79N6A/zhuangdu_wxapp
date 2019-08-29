var t = getApp();

Page({
    data: {
        close: 0,
        text: "",
        json:{}
    },
    onLoad: function(t) {
        console.log(t);
        this.setData({
            close: t.close,
            text: t.text
        });
        if (t.json && typeof(t.json)!="undefined") {
            var json=JSON.parse(t.json);
                this.setData({
                    json: json
                });
        }

    },
    onShow: function() {
        var e = t.getCache("sysset").shopname;
        wx.setNavigationBarTitle({
            title: e || "提示"
        });
    },
    bind: function() {
        var t = this, e = setInterval(function() {
            wx.getSetting({
                success: function(n) {
                    var a = n.authSetting["scope.userInfo"];
                    a && (wx.reLaunch({
                        url: "/pages/index/index?auth_json="+JSON.stringify(t.data.json)
                    }), clearInterval(e), t.setData({
                        userInfo: a
                    }));
                }
            });
        }, 1e3);
    }
});