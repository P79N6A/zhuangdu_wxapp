var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    to_index: function() {
        "theme3" == this.data.theme.name ? wx.reLaunch({
            url: "../../ui2/index/index"
        }) : wx.reLaunch({
            url: "../index/index"
        });
    },
    submit: function() {
        var a = this;
        "theme3" == a.data.theme.name ? wx.navigateTo({
            url: "../../ui2/store/porder?&id=" + a.data.list.cid + "&member_id=" + a.data.list.id + "&member_name=" + a.data.list.name
        }) : wx.navigateTo({
            url: "porder?&id=" + a.data.list.cid + "&member_id=" + a.data.list.id + "&member_name=" + a.data.list.name
        });
    },
    zan: function() {
        var t = this, n = t.data.list;
        -1 == t.data.list.is_zan && app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "zan",
                id: n.id
            },
            success: function(a) {
                "" != a.data.data && (n.is_zan = 1, t.setData({
                    list: n
                }));
            }
        });
    },
    onLoad: function(a) {
        var n = this;
        common.config(n), common.theme(n), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "member_detail",
                id: a.id
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && n.setData({
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