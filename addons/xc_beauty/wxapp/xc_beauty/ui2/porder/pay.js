var app = getApp(), common = require("../common/common.js");

function wxpay(t, e) {
    t.appId;
    var a = t.timeStamp.toString(), o = t.package, n = t.nonceStr, s = t.paySign.toUpperCase();
    wx.requestPayment({
        timeStamp: a,
        nonceStr: n,
        package: o,
        signType: "MD5",
        paySign: s,
        success: function(t) {
            var o = setInterval(function() {
                app.util.request({
                    url: "entry/wxapp/check",
                    showLoading: !1,
                    data: {
                        out_trade_no: e.data.list.out_trade_no
                    },
                    success: function(t) {
                        var a = t.data;
                        "" != a.data && 1 == a.data.status && (clearInterval(o), wx.showToast({
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
        fail: function(t) {
            e.setData({
                can_pay: !0
            });
        }
    });
}

function time_down(t) {
    t.setData({
        times: 30
    });
    var a = setInterval(function() {
        0 == t.data.times ? (t.setData({
            can_pay: !0
        }), clearInterval(a)) : t.setData({
            times: t.data.times - 1
        });
    }, 1e3);
}

Page({
    data: {
        pay_type: 2,
        coupon_curr: -1,
        can_pay: !0
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
    pay_choose: function(t) {
        var a = t.currentTarget.dataset.index;
        a != this.data.pay_type && this.setData({
            pay_type: a
        });
    },
    input: function(t) {
        switch (t.currentTarget.dataset.name) {
          case "content":
            this.setData({
                content: t.detail.value
            });
            break;

          case "password":
            this.setData({
                password: t.detail.value
            });
        }
    },
    coupon_choose: function(t) {
        var a = this, o = t.currentTarget.dataset.index;
        if (o != a.data.coupon_curr) {
            var e = a.data.coupon[o].coupon.name, n = a.data.list.amount;
            "" != (s = a.data.card) && null != s && 1 == a.data.userinfo.card && 1 == a.data.list.service_list.sale_status && 1 == s.content.discount_status && "" != s.content.discount && null != s.content.discount && (n = (parseFloat(n) * parseFloat(s.content.discount) / 10).toFixed(2)), 
            n = (parseFloat(n) - parseFloat(e)).toFixed(2), a.setData({
                coupon_curr: o,
                coupon_price: e,
                o_amount: n
            });
        } else {
            var s;
            n = a.data.list.amount;
            "" != (s = a.data.card) && null != s && 1 == a.data.userinfo.card && 1 == a.data.list.service_list.sale_status && 1 == s.content.discount_status && "" != s.content.discount && null != s.content.discount && (n = (parseFloat(n) * parseFloat(s.content.discount) / 10).toFixed(2)), 
            a.setData({
                coupon_curr: -1,
                coupon_price: null,
                o_amount: n
            });
        }
    },
    submit: function(t) {
        var o = this;
        if (o.data.can_pay) if (o.setData({
            can_pay: !1
        }), time_down(o), 1 == o.data.pay_type) {
            var a = {
                out_trade_no: o.data.list.out_trade_no,
                pay_type: o.data.pay_type,
                form_id: t.detail.formId
            };
            -1 != o.data.coupon_curr && (a.coupon_id = o.data.coupon[o.data.coupon_curr].cid), 
            "" != o.data.content && null != o.data.content && (a.content = o.data.content), 
            app.util.request({
                url: "entry/wxapp/orderpay",
                data: a,
                success: function(t) {
                    var a = t.data;
                    "" != a.data && (1 == a.data.status ? (console.log(a.data), wxpay(a.data, o)) : 2 == a.data.status && wx.redirectTo({
                        url: "../../pages/porder/success"
                    }));
                }
            });
        } else 2 == o.data.pay_type && o.setData({
            pay: !1,
            sign: !0,
            shadow: !0,
            form_id: t.detail.formId
        });
    },
    sign_close: function() {
        this.setData({
            shadow: !1,
            sign: !1,
            password: "",
            can_pay: !0
        });
    },
    sign_btn: function() {
        var o = this, t = o.data.password;
        if ("" == t || null == t) o.setData({
            sign_error: !0
        }); else {
            var a = {
                out_trade_no: o.data.list.out_trade_no,
                pay_type: o.data.pay_type,
                form_id: o.data.form_id,
                password: t
            };
            -1 != o.data.coupon_curr && (a.coupon_id = o.data.coupon[o.data.coupon_curr].cid), 
            "" != o.data.content && null != o.data.content && (a.content = o.data.content), 
            app.util.request({
                url: "entry/wxapp/orderpay",
                data: a,
                success: function(t) {
                    var a = t.data;
                    "" != a.data && (o.setData({
                        shadow: !1,
                        sign: !1,
                        password: ""
                    }), 1 == a.data.status ? wxpay(a.data, o) : 2 == a.data.status && (wx.showToast({
                        title: "支付成功",
                        icon: "success",
                        duration: 2e3
                    }), setTimeout(function() {
                        3 == o.data.list.order_type ? wx.redirectTo({
                            url: "../../pages/group/order"
                        }) : wx.redirectTo({
                            url: "../../pages/order/detail?&out_trade_no=" + o.data.list.out_trade_no
                        });
                    }, 2e3)));
                }
            });
        }
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
                var a = t.data;
                "" != a.data && (e.setData({
                    list: a.data,
                    o_amount: a.data.amount
                }), app.util.request({
                    url: "entry/wxapp/user",
                    showLoading: !1,
                    data: {
                        op: "userinfo"
                    },
                    success: function(t) {
                        var a = t.data;
                        "" != a.data && (e.setData({
                            userinfo: a.data
                        }), app.util.request({
                            url: "entry/wxapp/index",
                            showLoading: !1,
                            data: {
                                op: "card"
                            },
                            success: function(t) {
                                var a = t.data;
                                if ("" != a.data) {
                                    var o = e.data.o_amount;
                                    1 == e.data.list.service_list.sale_status && (1 == a.data.content.level_status && 1 == e.data.userinfo.card && null != e.data.userinfo.card_price && "" != e.data.userinfo.card_price ? (a.data.content.discount = e.data.userinfo.card_price, 
                                    o = (parseFloat(o) * parseFloat(e.data.userinfo.card_price) / 10).toFixed(2)) : 1 == a.data.content.discount_status && 1 == e.data.userinfo.card && "" != a.data.content.discount && null != a.data.content.discount && (o = (parseFloat(o) * parseFloat(a.data.content.discount) / 10).toFixed(2))), 
                                    e.setData({
                                        card: a.data,
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
                                    success: function(t) {
                                        var a = t.data;
                                        "" != a.data && e.setData({
                                            coupon: a.data
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