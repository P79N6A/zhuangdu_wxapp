var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    to_coupon: function(o) {
        var n = this, e = o.currentTarget.dataset.index;
        -1 == n.data.list[e].user && (1 == n.data.userinfo.card ? wx.showModal({
            title: "提示",
            content: "确定兑换优惠券吗？",
            success: function(o) {
                o.confirm ? app.util.request({
                    url: "entry/wxapp/order",
                    data: {
                        op: "to_coupon",
                        id: n.data.list[e].id
                    },
                    success: function(o) {
                        if ("" != o.data.data) {
                            wx.showToast({
                                title: "兑换成功",
                                icon: "success",
                                duration: 2e3
                            });
                            var t = n.data.list, a = n.data.userinfo;
                            a.score = parseInt(a.score) - parseInt(t[e].score), t[e].user = 1, n.setData({
                                list: t,
                                userinfo: a
                            });
                        }
                    }
                }) : o.cancel && console.log("用户点击取消");
            }
        }) : wx.showModal({
            title: "错误",
            content: "请先开通会员！",
            success: function(o) {
                o.confirm ? console.log("用户点击确定") : o.cancel && console.log("用户点击取消");
            }
        }));
    },
    onLoad: function(o) {
        var a = this;
        common.config(a), common.theme(a), app.util.request({
            url: "entry/wxapp/user",
            data: {
                op: "userinfo"
            },
            success: function(o) {
                var t = o.data;
                "" != t.data && (t.data.money = parseFloat(t.data.money).toFixed(2), a.setData({
                    userinfo: t.data
                }));
            }
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "score_coupon"
            },
            success: function(o) {
                var t = o.data;
                "" != t.data && a.setData({
                    list: t.data
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