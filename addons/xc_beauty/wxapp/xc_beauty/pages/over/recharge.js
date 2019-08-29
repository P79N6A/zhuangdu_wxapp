var app = getApp(), common = require("../common/common.js");

function wxpay(a, t) {
    a.appId;
    var e = a.timeStamp.toString(), n = a.package, r = a.nonceStr, o = a.paySign.toUpperCase();
    wx.requestPayment({
        timeStamp: e,
        nonceStr: r,
        package: n,
        signType: "MD5",
        paySign: o,
        success: function(a) {
            wx.showToast({
                title: "充值成功",
                icon: "success",
                duration: 2e3
            }), setTimeout(function() {
                wx.navigateBack({
                    delta: 1
                });
            }, 2e3);
        },
        fail: function(a) {}
    });
}

function sign(a) {
    var t = a.data.username, e = a.data.curr, n = a.data.mobile, r = a.data.name, o = a.data.amount, s = !0;
    if ("" != t && null != t || (s = !1), 1 == e) {
        "" != n && null != n || (s = !1);
        /^(((13[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/.test(n) || (s = !1);
    } else 2 == e && ("" != r && null != r || (s = !1));
    "" != o && null != o || (s = !1), a.setData({
        submit: s
    });
}

Page({
    data: {
        curr: 1,
        submit: !1,
        over: 0
    },
    choose: function(a) {
        var t = a.currentTarget.dataset.index;
        this.setData({
            over: t
        });
    },
    tab: function(a) {
        var t = a.currentTarget.dataset.index;
        t != this.data.curr && this.setData({
            curr: t
        });
    },
    all: function() {
        var a = this;
        1 == a.data.order_type ? a.setData({
            amount: a.data.userinfo.money
        }) : 2 == a.data.order_type && a.setData({
            amount: a.data.userinfo.share_o_amount
        }), sign(a);
    },
    input: function(a) {
        var t = this;
        switch (a.currentTarget.dataset.name) {
          case "amount":
            t.setData({
                amount: a.detail.value
            });
            break;

          case "username":
            t.setData({
                username: a.detail.value
            });
            break;

          case "mobile":
            t.setData({
                mobile: a.detail.value
            });
            break;

          case "name":
            t.setData({
                name: a.detail.value
            });
        }
    },
    submit: function(a) {
        var e = this, t = a.currentTarget.dataset.index;
        if (1 == t) {
            var n = {
                amount: e.data.card.content.recharge[e.data.over].r_price
            };
            "" != e.data.card.content.recharge[e.data.over].g_price && null != e.data.card.content.recharge[e.data.over].g_price && (n.gift = e.data.card.content.recharge[e.data.over].g_price), 
            app.util.request({
                url: "entry/wxapp/cardorder",
                data: n,
                success: function(a) {
                    var t = a.data;
                    "" != t.data && wxpay(t.data, e);
                }
            });
        } else if (2 == t) if (sign(e), e.data.submit) {
            n = {
                username: e.data.username,
                amount: e.data.amount,
                pay_type: e.data.curr,
                order_type: e.data.order_type
            };
            1 == e.data.curr ? n.mobile = e.data.mobile : 2 == e.data.curr && (n.name = e.data.name), 
            app.util.request({
                url: "entry/wxapp/withdraw",
                data: n,
                success: function(a) {
                    "" != a.data.data && (wx.showToast({
                        title: "申请成功",
                        icon: "success",
                        duration: 2e3
                    }), setTimeout(function() {
                        wx.navigateBack({
                            delta: 1
                        });
                    }, 2e3));
                }
            });
        } else wx.showModal({
            title: "错误",
            content: "请输入账号,金额",
            success: function(a) {
                a.confirm ? console.log("用户点击确定") : a.cancel && console.log("用户点击取消");
            }
        });
    },
    onLoad: function(a) {
        var e = this;
        common.config(e), common.theme(e), e.setData({
            edit: a.edit
        }), "" != a.order_type && null != a.order_type && e.setData({
            order_type: a.order_type
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "card"
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && e.setData({
                    card: t.data
                });
            }
        }), app.util.request({
            url: "entry/wxapp/user",
            data: {
                op: "userinfo"
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && (t.data.money = parseFloat(t.data.money).toFixed(2), e.setData({
                    userinfo: t.data
                }));
            }
        }), 2 == e.data.order_type && app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "share"
            },
            showLoading: !1,
            success: function(a) {
                var t = a.data;
                "" != t.data && e.setData({
                    share: t.data
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