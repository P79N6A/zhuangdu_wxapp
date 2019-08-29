var app = getApp(), common = require("../common/common.js"), WxParse = require("../../../wxParse/wxParse.js");

function time_up(n) {
    var e = setInterval(function() {
        var a = n.data.flash;
        if ("" != a && null != a && 0 < a.length) {
            for (var t = 0; t < a.length; t++) 0 < a[t].second ? a[t].second = a[t].second - 1 : 0 < a[t].min ? (a[t].min = a[t].min - 1, 
            a[t].second = 59) : 0 < a[t].hour ? (a[t].hour = a[t].hour - 1, a[t].min = 59, a[t].second = 59) : 0 < a[t].day ? (a[t].day = a[t].day - 1, 
            a[t].hour = 23, a[t].min = 59, a[t].second = 59) : a.splice(t, 1);
            n.setData({
                flash: a
            });
        } else clearInterval(e);
    }, 1e3);
}

Page({
    data: {
        pagePath: "../index/index",
        indicatorDots: !0,
        autoplay: !0,
        interval: 5e3,
        duration: 1e3,
        imgheights: [],
        current: 0
    },
    imageLoad: function(a) {
        var t = a.detail.width, n = t / (e = a.detail.height);
        console.log(t, e);
        var e = 750 / n, o = this.data.imgheights;
        o.push(e), this.setData({
            imgheights: o
        });
    },
    bindchange: function(a) {
        console.log(a.detail.current), this.setData({
            current: a.detail.current
        });
    },
    call: function() {
        var a = this.data.map;
        wx.makePhoneCall({
            phoneNumber: a.content.mobile
        });
    },
    map: function() {
        var a = this.data.map;
        wx.openLocation({
            latitude: parseFloat(a.content.latitude),
            longitude: parseFloat(a.content.longitude),
            name: a.content.address,
            address: a.content.address,
            scale: 28
        });
    },
    link: function(a) {
        var t = a.currentTarget.dataset.link, n = a.currentTarget.dataset.appid;
        "" != n && null != n ? wx.navigateToMiniProgram({
            appId: n,
            path: "",
            success: function(a) {
                console.log(a);
            },
            fail: function() {
                wx.showModal({
                    title: "错误",
                    content: "跳转失败"
                });
            }
        }) : "" != t && null != t && (t = escape(t), wx.navigateTo({
            url: "../link/link?&url=" + t
        }));
    },
    getcoupon: function(a) {
        var t = a.currentTarget.dataset.index;
        wx.navigateTo({
            url: "../coupon/index?&id=" + this.data.coupon[t].id
        });
    },
    onLoad: function(a) {
        var l = this;
        if (common.config(l), common.theme(l), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "index"
            },
            showLoading: !1,
            success: function(a) {
                var t = a.data;
                if ("" != t.data) {
                    if ("" != t.data.banner && null != t.data.banner && l.setData({
                        banner: t.data.banner
                    }), "" != t.data.map && null != t.data.map && l.setData({
                        map: t.data.map
                    }), "" != t.data.ad && null != t.data.ad && l.setData({
                        ad: t.data.ad
                    }), "" != t.data.nav && null != t.data.nav && l.setData({
                        nav: t.data.nav
                    }), "" != t.data.coupon && null != t.data.coupon && l.setData({
                        coupon: t.data.coupon
                    }), "" != t.data.group && null != t.data.group && l.setData({
                        group: t.data.group
                    }), "" != t.data.flash && null != t.data.flash) {
                        for (var n = t.data.flash, e = 0; e < n.length; e++) {
                            var o, i, s, d;
                            o = parseInt(parseInt(n[e].fail) / 86400), i = parseInt((n[e].fail - 86400 * o) / 3600), 
                            s = parseInt((n[e].fail - 86400 * o - 3600 * i) / 60), d = parseInt(n[e].fail % 60), 
                            n[e].day = o, n[e].hour = i, n[e].min = s, n[e].second = d;
                        }
                        l.setData({
                            flash: n
                        }), time_up(l);
                    }
                    if ("" != t.data.pclass && null != t.data.pclass && l.setData({
                        pclass: t.data.pclass
                    }), "" != t.data.sort && null != t.data.sort) l.setData({
                        sort: t.data.sort
                    }); else {
                        l.setData({
                            sort: [ {
                                name: "banner",
                                status: 1
                            }, {
                                name: "map",
                                status: 1
                            }, {
                                name: "coupon",
                                status: 1
                            }, {
                                name: "group",
                                status: 1
                            } ]
                        });
                    }
                }
            }
        }), "" != app.audit && null != app.audit && (l.setData({
            audit: app.audit
        }), "" != app.audit.content && null != app.audit.content)) {
            app.audit.content;
            WxParse.wxParse("content", "html", app.audit.content, l, 5);
        }
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {
        var a = this, t = "/xc_beauty/pages/index/index";
        t = escape(t);
        var n = a.data.config.title + "-首页";
        "" != a.data.config.share_index_title && null != a.data.config.share_index_title && (n = a.data.config.share_index_title);
        var e = "";
        "" != a.data.config.share_index_img && null != a.data.config.share_index_img && (e = a.data.config.share_index_img);
        var o = "/xc_beauty/pages/base/base?&share=" + t, i = 1;
        return "" != app.share && null != app.share && "" != app.share.content && null != app.share.content && (i = app.share.content.status), 
        1 == i && (o = o + "&scene=" + app.userinfo.openid), {
            title: n,
            path: o,
            imageUrl: e,
            success: function(a) {
                console.log(a);
            },
            fail: function(a) {
                console.log(a);
            }
        };
    }
});