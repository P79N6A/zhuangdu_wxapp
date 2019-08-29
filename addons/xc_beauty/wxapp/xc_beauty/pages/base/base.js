var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    onLoad: function(d) {
        common.login(this), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "base"
            },
            showLoading: !1,
            success: function(e) {
                var a = e.data, t = 1;
                "" != a.data && ("" != a.data.config && null != a.data.config && (app.config = a.data.config), 
                "" != a.data.theme && null != a.data.theme && (app.theme = a.data.theme, "" != a.data.theme.content && null != a.data.theme.content && 3 == a.data.theme.content.theme && (t = 3)), 
                "" != a.data.map && null != a.data.map && (app.map = a.data.map), "" != a.data.share && null != a.data.share && (app.share = a.data.share), 
                "" != a.data.audit && null != a.data.audit && (app.audit = a.data.audit));
                var n = app.buy_share;
                if ("" != d.share && null != d.share) {
                    var o = unescape(d.share);
                    wx.redirectTo({
                        url: o,
                        success: function(e) {
                            console.log(e);
                        },
                        fail: function(e) {
                            console.log(e);
                        }
                    });
                } else "" != n && null != n && "undefined" != n ? wx.navigateTo({
                    url: n
                }) : 3 == t ? wx.redirectTo({
                    url: "../../ui2/index/index"
                }) : wx.redirectTo({
                    url: "../index/index"
                });
                app.util.request({
                    url: "entry/wxapp/grouprefund",
                    showLoading: !1
                }), app.util.request({
                    url: "entry/wxapp/orderdo",
                    showLoading: !1
                });
            }
        }), wx.getSystemInfo({
            success: function(e) {
                app.model = e.model;
            }
        });
    },
    onReady: function() {},
    onShow: function() {
        var e = 1;
        "" != app.theme && null != app.theme && ("" != app.theme.content && null != app.theme.content && 3 == app.theme.content.theme && (e = 3), 
        3 == e ? wx.redirectTo({
            url: "../../ui2/index/index"
        }) : wx.redirectTo({
            url: "../index/index"
        }));
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});