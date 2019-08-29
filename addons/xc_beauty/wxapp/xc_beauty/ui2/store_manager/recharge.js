var app = getApp(), common = require("../common/common.js");

function sign(t) {
    var a = t.data.userinfo, e = t.data.amount, n = "";
    "" != e && null != e || (n = "请输入或选择充值金额"), "" != a && null != a || (n = "请选择要充值的会员"), 
    "" == n ? t.setData({
        submit: !0
    }) : wx.showModal({
        title: "错误",
        content: n
    });
}

Page({
    data: {
        submit: !1,
        over: -1
    },
    input: function(t) {
        var a = this;
        switch (t.currentTarget.dataset.name) {
          case "search":
            a.setData({
                search: t.detail.value
            });
            break;

          case "amount":
            a.setData({
                amount: t.detail.value
            });
            break;

          case "gift":
            a.setData({
                gift: t.detail.value
            });
            break;

          case "content":
            a.setData({
                content: t.detail.value
            });
        }
    },
    choose: function(t) {
        var a = t.currentTarget.dataset.index, e = this.data.card.content.recharge, n = e[a].r_price, o = "";
        "" != e[a].g_price && null != e[a].r_price && (o = e[a].g_price), this.setData({
            amount: n,
            gift: o,
            over: a
        });
    },
    submit: function() {
        var e = this, t = e.data.search;
        "" != t && null != t ? app.util.request({
            url: "entry/wxapp/user",
            data: {
                op: "member_search",
                search: e.data.search,
                store: e.data.store_id
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && e.setData({
                    userinfo: a.data
                });
            }
        }) : wx.showModal({
            title: "错误",
            content: "请输入手机号"
        });
    },
    pay: function() {
        var a = this;
        if (sign(a), a.data.submit) {
            var t = {
                openid: a.data.userinfo.openid,
                amount: a.data.amount,
                store: a.data.store_id
            };
            "" != a.data.gift && null != a.data.gift && (t.gift = a.data.gift), "" != a.data.content && null != a.data.content && (t.content = a.data.content), 
            app.util.request({
                url: "entry/wxapp/admincard",
                data: t,
                success: function(t) {
                    "" != t.data.data && (a.setData({
                        userinfo: "",
                        over: -1,
                        amount: "",
                        gift: "",
                        content: "",
                        submit: !1
                    }), wx.showToast({
                        title: "充值成功",
                        icon: "success",
                        duration: 2e3
                    }));
                }
            });
        }
    },
    onLoad: function(t) {
        var e = this;
        common.config(e), common.theme(e), e.setData({
            store_id: t.store_id
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
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "card"
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && e.setData({
                    card: a.data
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