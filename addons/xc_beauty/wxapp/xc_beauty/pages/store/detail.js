var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    call: function(a) {
        wx.makePhoneCall({
            phoneNumber: this.data.list.mobile
        });
    },
    map: function(a) {
        var t = this;
        wx.openLocation({
            latitude: parseFloat(t.data.list.map.latitude),
            longitude: parseFloat(t.data.list.map.longitude),
            name: t.data.list.address,
            address: t.data.list.address,
            scale: 28
        });
    },
    qie: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    yuyue: function() {
        var a = this;
        "theme3" == a.data.theme.name ? wx.navigateTo({
            url: "../../ui2/store/porder?&id=" + a.data.list.id
        }) : wx.navigateTo({
            url: "porder?&id=" + a.data.list.id
        });
    },
    onLoad: function(a) {
        var n = this;
        common.config(n), common.theme(n), "" != a.bind && null != a.bind && n.setData({
            bind: a.bind
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "store_detail",
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