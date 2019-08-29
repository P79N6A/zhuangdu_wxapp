var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    input: function(t) {
        this.setData({
            content: t.detail.value
        });
    },
    submit: function() {
        var n = this;
        "" != n.data.content && null != n.data.content ? app.util.request({
            url: "entry/wxapp/service",
            data: {
                op: "member_discuss",
                id: n.data.list.member,
                out_trade_no: n.data.list.out_trade_no,
                content: n.data.content
            },
            success: function(t) {
                "" != t.data.data && (wx.showToast({
                    title: "提交成功",
                    icon: "success",
                    duration: 2e3
                }), setTimeout(function() {
                    "theme3" == n.data.theme.name ? wx.reLaunch({
                        url: "../../ui2/user/user"
                    }) : wx.reLaunch({
                        url: "../user/user"
                    });
                }, 2e3));
            }
        }) : wx.showModal({
            title: "错误",
            content: "请输入内容"
        });
    },
    onLoad: function(t) {
        var e = this;
        common.config(e), common.theme(e), app.util.request({
            url: "entry/wxapp/order",
            data: {
                op: "detail",
                out_trade_no: t.out_trade_no
            },
            success: function(t) {
                var n = t.data;
                "" != n.data && e.setData({
                    list: n.data
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