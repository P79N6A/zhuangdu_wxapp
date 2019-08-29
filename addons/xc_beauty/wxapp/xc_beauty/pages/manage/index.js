var app = getApp(), common = require("../common/common.js");

Page({
    data: {
        footer_curr: 3,
        today: 1,
        page: 1,
        pagesize: 20,
        isbottom: !1,
        order_item: 0,
        order: []
    },
    tab: function(a) {
        var e = this, t = a.currentTarget.dataset.index;
        t != e.data.today && (e.setData({
            today: t
        }), app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "count",
                today: e.data.today
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && e.setData({
                    count: t.data
                });
            }
        }));
    },
    to_order: function(a) {
        var t = a.currentTarget.dataset.index;
        this.setData({
            menu: !0,
            shadow: !0,
            order_item: t
        });
    },
    menu_close: function() {
        this.setData({
            menu: !1,
            shadow: !1
        });
    },
    submit: function() {
        var t = this, e = t.data.order;
        1 == e[t.data.order_item].status && -1 == e[t.data.order_item].use ? wx.showModal({
            title: "提示",
            content: "确定核销吗？",
            success: function(a) {
                a.confirm ? app.util.request({
                    url: "entry/wxapp/manage",
                    data: {
                        op: "order_change",
                        id: e[t.data.order_item].id
                    },
                    success: function(a) {
                        "" != a.data.data && (e[t.data.order_item].is_use = parseInt(e[t.data.order_item].is_use) + 1, 
                        e[t.data.order_item].is_use == parseInt(e[t.data.order_item].can_use) && (e[t.data.order_item].use = 1), 
                        t.setData({
                            order: e
                        }), wx.showToast({
                            title: "核销成功",
                            icon: "success",
                            duration: 2e3
                        }));
                    }
                }) : a.cancel && console.log("用户点击取消");
            }
        }) : 2 == e[t.data.order_item].status && -1 == e[t.data.order_item].refund_status && wx.showModal({
            title: "提示",
            content: "确定退款吗？",
            success: function(a) {
                a.confirm ? app.util.request({
                    url: "entry/wxapp/orderrefund",
                    data: {
                        id: e[t.data.order_item].id
                    },
                    success: function(a) {
                        "" != a.data.data && (e[t.data.order_item].use = 1, t.setData({
                            order: e,
                            shadow: !1,
                            menu: !1
                        }), wx.showToast({
                            title: "退款成功",
                            icon: "success",
                            duration: 2e3
                        }));
                    }
                }) : a.cancel && console.log("用户点击取消");
            }
        });
    },
    input: function(a) {
        this.setData({
            search: a.detail.value
        });
    },
    search: function() {
        var o = this, a = o.data.search;
        null != a && "" != a ? app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "order_search",
                out_trade_no: a
            },
            success: function(a) {
                var t = a.data;
                if ("" != t.data) {
                    var e = o.data.order;
                    e.push(t.data), o.setData({
                        order: e,
                        order_item: e.length - 1,
                        shadow: !0,
                        menu: !0
                    });
                }
            }
        }) : wx.showModal({
            title: "错误",
            content: "请输入订单号",
            success: function(a) {
                a.confirm ? console.log("用户点击确定") : a.cancel && console.log("用户点击取消");
            }
        });
    },
    scan: function() {
        var o = this;
        wx.scanCode({
            onlyFromCamera: !0,
            success: function(a) {
                console.log(a), app.util.request({
                    url: "entry/wxapp/manage",
                    data: {
                        op: "order_search",
                        id: a.result
                    },
                    success: function(a) {
                        var t = a.data;
                        if ("" != t.data) {
                            var e = o.data.order;
                            e.push(t.data), o.setData({
                                order: e,
                                order_item: e.length - 1,
                                shadow: !0,
                                menu: !0
                            });
                        }
                    }
                });
            }
        });
    },
    onLoad: function(a) {
        var e = this;
        common.config(e), common.theme(e), e.setData({
            userinfo: app.userinfo
        }), app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "index"
            },
            showLoading: !1,
            success: function(a) {
                var t = a.data;
                "" != t.data && ("" != t.data.count && null != t.data.count && e.setData({
                    count: t.data.count
                }), "" != t.data.order && null != t.data.order && e.setData({
                    order: t.data.order
                }));
            }
        }), app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "income",
                page: e.data.page,
                pagesize: e.data.pagesize
            },
            success: function(a) {
                var t = a.data;
                "" != t.data ? e.setData({
                    list: t.data,
                    page: e.data.page + 1
                }) : e.setData({
                    isbottom: !0
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        var e = this;
        e.data.isbottom || app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "income",
                page: e.data.page,
                pagesize: e.data.pagesize
            },
            success: function(a) {
                var t = a.data;
                "" != t.data ? e.setData({
                    list: e.data.list.concat(t.data),
                    page: e.data.page + 1
                }) : e.setData({
                    isbottom: !0
                });
            }
        });
    }
});