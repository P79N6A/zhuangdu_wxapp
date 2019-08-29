var common = require("../common/common.js"), app = getApp();

Page({
    data: {
        page: 1,
        pagesize: 20,
        isbottom: !1,
        footer_curr: 3
    },
    search: function(a) {
        var e = this, t = a.detail.value;
        e.setData({
            page: 1,
            isbottom: !1
        });
        var o = {
            op: "online",
            page: e.data.page,
            pagesize: e.data.pagesize
        };
        "" != t && null != t && (o.search = t), app.util.request({
            url: "entry/wxapp/user",
            method: "POST",
            data: o,
            success: function(a) {
                var t = a.data;
                "" != t.data && (1 == t.data.admin ? (e.setData({
                    xc: t.data
                }), "" != t.data.list && null != t.data.list ? e.setData({
                    page: e.data.page + 1
                }) : e.setData({
                    isbottom: !0
                })) : wx.showModal({
                    title: "错误",
                    content: "没有权限",
                    showCancel: !1,
                    success: function(a) {
                        a.confirm ? wx.reLaunch({
                            url: "../base/base"
                        }) : a.cancel && console.log("用户点击取消");
                    }
                }));
            }
        });
    },
    onLoad: function(a) {
        var e = this;
        common.config(e), common.theme(e);
        var o = setInterval(function() {
            app.util.request({
                url: "entry/wxapp/user",
                method: "POST",
                data: {
                    op: "online",
                    page: e.data.page,
                    pagesize: e.data.pagesize
                },
                success: function(a) {
                    var t = a.data;
                    "" != t.data && (clearInterval(o), 1 == t.data.admin ? (e.setData({
                        xc: t.data
                    }), "" != t.data.list && null != t.data.list ? e.setData({
                        page: e.data.page + 1
                    }) : e.setData({
                        isbottom: !0
                    })) : wx.showModal({
                        title: "错误",
                        content: "没有权限",
                        showCancel: !1,
                        success: function(a) {
                            a.confirm ? wx.reLaunch({
                                url: "../index/index"
                            }) : a.cancel && console.log("用户点击取消");
                        }
                    }));
                }
            });
        }, 1e3);
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {
        var e = this;
        e.setData({
            page: 1,
            isbottom: !1
        }), app.util.request({
            url: "entry/wxapp/user",
            method: "POST",
            data: {
                op: "online",
                page: e.data.page,
                pagesize: e.data.pagesize
            },
            success: function(a) {
                var t = a.data;
                wx.stopPullDownRefresh(), "" != t.data && (1 == t.data.admin ? (e.setData({
                    xc: t.data
                }), "" != t.data.list && null != t.data.list ? e.setData({
                    page: e.data.page + 1
                }) : e.setData({
                    isbottom: !0
                })) : wx.showModal({
                    title: "错误",
                    content: "没有权限",
                    showCancel: !1,
                    success: function(a) {
                        a.confirm ? wx.reLaunch({
                            url: "../index/index"
                        }) : a.cancel && console.log("用户点击取消");
                    }
                }));
            }
        });
    },
    onReachBottom: function() {
        var o = this;
        o.data.isbottom || app.util.request({
            url: "entry/wxapp/user",
            method: "POST",
            data: {
                op: "online",
                page: o.data.page,
                pagesize: o.data.pagesize
            },
            success: function(a) {
                var t = a.data;
                if ("" != t.data) {
                    var e = o.data.xc;
                    "" != t.data.list && null != t.data.list ? (e.list = e.list.concat(t.data.list), 
                    o.setData({
                        xc: e,
                        page: o.data.page + 1
                    })) : o.setData({
                        isbottom: !0
                    });
                }
            }
        });
    }
});