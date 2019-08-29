var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    to_recharge: function(o) {
        var t = this;
        if (1 == t.data.userinfo.card) if ("" != t.data.userinfo.store && null != t.data.userinfo.store) {
            var a = o.currentTarget.dataset.index;
            wx.navigateTo({
                url: "recharge?&edit=" + a + "&order_type=1"
            });
        } else wx.showModal({
            title: "提示",
            content: "请先绑定门店"
        }); else wx.showModal({
            title: "提示",
            content: "请先开通会员",
            success: function(o) {
                o.confirm ? wx.navigateTo({
                    url: "../card/info?&edit=1"
                }) : o.cancel && console.log("用户点击取消");
            }
        });
    },
    to_record: function() {
        1 == this.data.userinfo.card ? wx.navigateTo({
            url: "record"
        }) : wx.showModal({
            title: "提示",
            content: "请先开通会员",
            success: function(o) {
                o.confirm ? wx.navigateTo({
                    url: "../card/info?&edit=1"
                }) : o.cancel && console.log("用户点击取消");
            }
        });
    },
    onLoad: function(o) {
        var a = this;
        common.config(a), common.theme(a), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "card"
            },
            success: function(o) {
                var t = o.data;
                "" != t.data && a.setData({
                    card: t.data
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {
        var a = this;
        app.util.request({
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
        });
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});