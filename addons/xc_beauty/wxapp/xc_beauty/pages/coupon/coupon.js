var app = getApp(), common = require("../common/common.js");

Page({
    data: {
        curr: -1
    },
    tab: function(t) {
        var n = this, a = t.currentTarget.dataset.index;
        a != n.data.curr && (n.setData({
            curr: a,
            list: []
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "coupon",
                curr: n.data.curr
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && n.setData({
                    list: a.data
                });
            }
        }));
    },
    onLoad: function(t) {
        var n = this;
        common.config(n), common.theme(n), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "coupon",
                curr: n.data.curr
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && n.setData({
                    list: a.data
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});