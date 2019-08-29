var app = getApp(), common = require("../common/common.js");

Page({
    data: {
        page: 1,
        pagesize: 21,
        isbottom: !1
    },
    store_on: function() {
        this.setData({
            store_page: !0
        });
    },
    store_close: function() {
        this.setData({
            store_page: !1
        });
    },
    store_choose: function(t) {
        var e = this, a = t.currentTarget.dataset.index;
        e.setData({
            store_curr: a,
            store_id: e.data.store_list[a].id,
            store_page: !1,
            page: 1,
            isbottom: !1
        });
        var o = {
            op: "store_member",
            page: e.data.page,
            pagesize: e.data.pagesize
        };
        "" != e.data.store_id && null != e.data.store_id && (o.id = e.data.store_id), app.util.request({
            url: "entry/wxapp/index",
            data: o,
            success: function(t) {
                var a = t.data;
                "" != a.data ? e.setData({
                    list: a.data,
                    page: e.data.page + 1
                }) : e.setData({
                    list: [],
                    isbottom: !0
                });
            }
        });
    },
    submit: function(t) {
        var a = this, e = t.currentTarget.dataset.index;
        wx.navigateTo({
            url: "../store/porder?&id=" + a.data.list[e].cid + "&member_id=" + a.data.list[e].id + "&member_name=" + a.data.list[e].name
        });
    },
    onLoad: function(t) {
        var o = this;
        common.config(o), common.theme(o), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "store_member",
                page: o.data.page,
                pagesize: o.data.pagesize
            },
            success: function(t) {
                var a = t.data;
                "" != a.data ? o.setData({
                    list: a.data,
                    page: o.data.page + 1
                }) : o.setData({
                    isbottom: !0
                });
            }
        }), wx.getLocation({
            type: "wgs84",
            success: function(t) {
                var a = t.latitude, e = t.longitude;
                t.speed, t.accuracy;
                o.setData({
                    latitude: a,
                    longitude: e
                });
            },
            complete: function() {
                var t = {
                    op: "store"
                };
                null != o.data.latitude && "" != o.data.latitude && (t.latitude = o.data.latitude), 
                null != o.data.longitude && "" != o.data.longitude && (t.longitude = o.data.longitude), 
                app.util.request({
                    url: "entry/wxapp/order",
                    data: t,
                    showLoading: !1,
                    success: function(t) {
                        var a = t.data;
                        "" != a.data && o.setData({
                            store_list: a.data
                        });
                    }
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
        if (!e.data.isbottom) {
            var t = {
                op: "store_member",
                page: e.data.page,
                pagesize: e.data.pagesize
            };
            "" != e.data.store_id && null != e.data.store_id && (t.id = e.data.store_id), app.util.request({
                url: "entry/wxapp/index",
                data: t,
                success: function(t) {
                    var a = t.data;
                    "" != a.data ? e.setData({
                        list: e.data.list.concat(a.data),
                        page: e.data.page + 1
                    }) : e.setData({
                        isbottom: !0
                    });
                }
            });
        }
    }
});