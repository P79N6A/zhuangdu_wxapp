var app = getApp(), common = require("../common/common.js");

Page({
    data: {
        pagePath: "../user/user",
        list: []
    },
    to_store: function() {
        var a = this.data.userinfo;
        "" == a.store || null == a.store ? (wx, wx.navigateTo({
            url: "../../pages/store/index?&bind=1"
        })) : (wx, wx.navigateTo({
            url: "../../pages/store/detail?&id=" + a.store + "&bind=1"
        }));
    },
    store_change: function() {
        wx, wx.navigateTo({
            url: "../../pages/store/index?&bind=1"
        });
    },
    to_shop: function() {
        var a = this.data.userinfo;
        1 == a.shop ? wx.navigateTo({
            url: "../manage/index"
        }) : 2 != a.shop && 3 != a.shop || wx.navigateTo({
            url: "../manage/store?&store_id=" + a.shop_id
        });
    },
    password: function(a) {
        this.setData({
            password: a.detail.value
        });
    },
    shop_close: function() {
        this.setData({
            shadow: !1,
            manage: !1
        });
    },
    shop_login: function(a) {
        var t = this, o = a.currentTarget.dataset.status;
        "" == t.data.password || null == t.data.password ? wx.showModal({
            title: "错误",
            content: "请输入密码",
            success: function(a) {
                a.confirm ? console.log("用户点击确定") : a.cancel && console.log("用户点击取消");
            }
        }) : app.util.request({
            url: "entry/wxapp/manage",
            showLoading: !1,
            data: {
                op: "login",
                status: o,
                password: t.data.password
            },
            success: function(a) {
                "" != a.data.data ? wx.navigateTo({
                    url: "../manage/index"
                }) : t.setData({
                    password: ""
                });
            }
        });
    },
    onLoad: function(a) {
        var o = this;
        common.config(o), common.theme(o), o.setData({
            share: app.share
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "coupon",
                curr: -1
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && o.setData({
                    list: t.data
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {
        var o = this;
        app.util.request({
            url: "entry/wxapp/user",
            data: {
                op: "userinfo"
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && o.setData({
                    userinfo: t.data
                });
            }
        });
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});