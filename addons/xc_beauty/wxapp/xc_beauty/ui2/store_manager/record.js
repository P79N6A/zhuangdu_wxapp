var app = getApp(), common = require("../common/common.js");

function sign(e) {
    app.util.request({
        url: "entry/wxapp/manage",
        data: {
            op: "record",
            page: e.data.page,
            pagesize: e.data.pagesize,
            store: e.data.store_id,
            curr: e.data.curr
        },
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

Page({
    data: {
        curr: 1,
        page: 1,
        pagesize: 20,
        isbottom: !1,
        list: []
    },
    tab: function(t) {
        var a = this, e = t.currentTarget.dataset.index;
        e != a.data.curr && (a.setData({
            curr: e,
            list: [],
            page: 1,
            isbtttom: !1
        }), sign(a));
    },
    onLoad: function(t) {
        var a = this;
        common.config(a), common.theme(a), a.setData({
            store_id: t.store_id,
            curr: t.curr
        }), app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "store_detail",
                id: t.store_id
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && "" != a.data.store && null != a.data.store && wx.setNavigationBarTitle({
                    title: a.data.store.name
                });
            }
        }), sign(a);
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.data.isbttom || sign(this);
    }
});