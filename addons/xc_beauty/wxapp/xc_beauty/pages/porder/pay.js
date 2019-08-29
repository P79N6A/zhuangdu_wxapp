var app = getApp(), common = require("../common/common.js");

function wxpay(a, e) {
    a.appId;
    var t = a.timeStamp.toString(), o = a.package, n = a.nonceStr, s = a.paySign.toUpperCase();
    wx.requestPayment({
        timeStamp: t,
        nonceStr: n,
        package: o,
        signType: "MD5",
        paySign: s,
        success: function(a) {
            var o = setInterval(function() {
                app.util.request({
                    url: "entry/wxapp/check",
                    showLoading: !1,
                    data: {
                        out_trade_no: e.data.list.out_trade_no
                    },
                    success: function(a) {
                        var t = a.data;
                        "" != t.data && 1 == t.data.status && (clearInterval(o), wx.showToast({
                            title: "支付成功",
                            icon: "success",
                            duration: 2e3
                        }), setTimeout(function() {
                            3 == e.data.list.order_type ? wx.redirectTo({
                                url: "../../pages/group/order"
                            }) : wx.redirectTo({
                                url: "../../pages/order/detail?&out_trade_no=" + e.data.list.out_trade_no
                            });
                        }, 2e3));
                    }
                });
            }, 1e3);
        },
        fail: function(a) {
            console.log(a);
        }
    });
}

Page({
    data: {
        pay_type: 2,
        coupon_curr: -1
    },
    menu_on: function() {
        this.setData({
            menu: !0,
            shadow: !0
        });
    },
    menu_close: function() {
        this.setData({
            menu: !1,
            shadow: !1,
            pay: !1
        });
    },
    pay_on: function() {
        var a = this;
        if (1 == a.data.userinfo.card) if (parseFloat(a.data.o_amount) > parseFloat(a.data.userinfo.money)) {
            var t = (parseFloat(a.data.o_amount) - parseFloat(a.data.userinfo.money)).toFixed(2);
            a.setData({
                tip: !0,
                tip_amount: t
            });
        } else a.setData({
            tip: !1
        }); else a.setData({
            tip: !1
        });
        a.setData({
            shadow: !0,
            pay: !0
        });
    },
    pay_choose: function(a) {
        var t = a.currentTarget.dataset.index;
        t != this.data.pay_type && this.setData({
            pay_type: t
        });
    },
    input: function(a) {
        switch (a.currentTarget.dataset.name) {
          case "content":
            this.setData({
                content: a.detail.value
            });
            break;

          case "password":
            this.setData({
                password: a.detail.value
            });
        }
    },
    coupon_choose: function(a) {
        var t = this, o = a.currentTarget.dataset.index;
        if (o != t.data.coupon_curr) {
            var e = t.data.coupon[o].coupon.name, n = t.data.list.amount;
            "" != (s = t.data.card) && null != s && 1 == t.data.userinfo.card && 1 == t.data.list.service_list.sale_status && 1 == s.content.discount_status && (n = (parseFloat(n) * parseFloat(s.content.discount) / 10).toFixed(2)), 
            n = (parseFloat(n) - parseFloat(e)).toFixed(2), t.setData({
                coupon_curr: o,
                coupon_price: e,
                o_amount: n
            });
        } else {
            var s;
            n = t.data.list.amount;
            "" != (s = t.data.card) && null != s && 1 == t.data.userinfo.card && 1 == t.data.list.service_list.sale_status && 1 == s.content.discount_status && (n = (parseFloat(n) * parseFloat(s.content.discount) / 10).toFixed(2)), 
            t.setData({
                coupon_curr: -1,
                coupon_price: null,
                o_amount: n
            });
        }
    },
    submit: function(a) {
        var o = this;
        if (1 == o.data.pay_type) {
            var t = {
                out_trade_no: o.data.list.out_trade_no,
                pay_type: o.data.pay_type,
                form_id: a.detail.formId
            };
            -1 != o.data.coupon_curr && (t.coupon_id = o.data.coupon[o.data.coupon_curr].cid), 
            "" != o.data.content && null != o.data.content && (t.content = o.data.content), 
            app.util.request({
                url: "entry/wxapp/orderpay",
                data: t,
                success: function(a) {
                    var t = a.data;
                    "" != t.data && (1 == t.data.status ? (console.log(t.data), wxpay(t.data, o)) : 2 == t.data.status && (wx.showToast({
                        title: "支付成功",
                        icon: "success",
                        duration: 2e3
                    }), setTimeout(function() {
                        3 == o.data.list.order_type ? wx.redirectTo({
                            url: "../group/order"
                        }) : wx.redirectTo({
                            url: "../order/detail?&out_trade_no=" + o.data.list.out_trade_no
                        });
                    }, 2e3)));
                }
            });
        } else 2 == o.data.pay_type && o.setData({
            pay: !1,
            sign: !0,
            shadow: !0,
            form_id: a.detail.formId
        });
    },
    sign_close: function() {
        this.setData({
            shadow: !1,
            sign: !1,
            password: ""
        });
    },
    sign_btn: function() {
        var o = this, a = o.data.password;
        if ("" == a || null == a) o.setData({
            sign_error: !0
        }); else {
            var t = {
                out_trade_no: o.data.list.out_trade_no,
                pay_type: o.data.pay_type,
                form_id: o.data.form_id,
                password: a
            };
            -1 != o.data.coupon_curr && (t.coupon_id = o.data.coupon[o.data.coupon_curr].cid), 
            "" != o.data.content && null != o.data.content && (t.content = o.data.content), 
            app.util.request({
                url: "entry/wxapp/orderpay",
                data: t,
                success: function(a) {
                    console.log(a.data);
                    var t = a.data;
                    "" != t.data && (o.setData({
                        shadow: !1,
                        sign: !1,
                        password: ""
                    }), 1 == t.data.status ? wxpay(t.data, o) : 2 == t.data.status && (3 == o.data.list.order_type ? wx.redirectTo({
                        url: "../../pages/group/order"
                    }) : wx.redirectTo({
                        url: "../../pages/order/detail?&out_trade_no=" + o.data.list.out_trade_no
                    })));
                }
            });
        }
    },
    onLoad: function(a) {
        var e = this;
        common.config(e), common.theme(e), app.util.request({
            url: "entry/wxapp/order",
            data: {
                op: "detail",
                out_trade_no: a.out_trade_no
            },
            success: function(a) {
                var t = a.data;
                "" != t.data && (e.setData({
                    list: t.data,
                    o_amount: t.data.amount
                }), app.util.request({
                    url: "entry/wxapp/user",
                    showLoading: !1,
                    data: {
                        op: "userinfo"
                    },
                    success: function(a) {
                        var t = a.data;
                        "" != t.data && (e.setData({
                            userinfo: t.data
                        }), app.util.request({
                            url: "entry/wxapp/index",
                            showLoading: !1,
                            data: {
                                op: "card"
                            },
                            success: function(a) {
                                var t = a.data;
                                if ("" != t.data) {
                                    var o = e.data.o_amount;
                                    1 == e.data.list.service_list.sale_status && (1 == t.data.content.level_status && 1 == e.data.userinfo.card && null != e.data.userinfo.card_price && "" != e.data.userinfo.card_price ? (t.data.content.discount = e.data.userinfo.card_price, 
                                    o = (parseFloat(o) * parseFloat(e.data.userinfo.card_price) / 10).toFixed(2)) : 1 == t.data.content.discount_status && 1 == e.data.userinfo.card && (o = (parseFloat(o) * parseFloat(t.data.content.discount) / 10).toFixed(2))), 
                                    e.setData({
                                        card: t.data,
                                        o_amount: o
                                    });
                                }
                            },
                            complete: function() {
                                app.util.request({
                                    url: "entry/wxapp/order",
                                    showLoading: !1,
                                    data: {
                                        op: "coupon",
                                        amount: e.data.o_amount
                                    },
                                    success: function(a) {
                                        var t = a.data;
                                        "" != t.data && e.setData({
                                            coupon: t.data
                                        });
                                    }
                                });
                            }
                        }));
                    }
                }));
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