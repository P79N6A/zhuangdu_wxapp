var app = getApp(), common = require("../common/common.js");

function sign(t) {
    var a = t.data.curr, e = t.data.content, n = !0;
    0 == a && (n = !1), "" != e && null != e || (n = !1), t.setData({
        submit: n
    });
}

function getUrlParam(t, a) {
    var e = new RegExp("(^|&)" + a + "=([^&]*)(&|$)"), n = t.split("?")[1].match(e);
    return null != n ? unescape(n[2]) : null;
}

Page({
    data: {
        curr: 0,
        sign: -1,
        imgs: [],
        submit: !1
    },
    choose: function(t) {
        var a = this, e = t.currentTarget.dataset.index;
        a.data.curr != e && (a.setData({
            curr: e
        }), sign(a));
    },
    sign: function() {
        this.setData({
            sign: -this.data.sign
        });
    },
    input: function(t) {
        this.setData({
            content: t.detail.value
        }), sign(this);
    },
    upload: function() {
        var n = this;
        if (n.data.imgs.length < 5) {
            var s = "entry/wxapp/upload";
            -1 == s.indexOf("http://") && -1 == s.indexOf("https://") && (s = app.util.url(s));
            var t = wx.getStorageSync("userInfo").sessionid;
            !getUrlParam(s, "state") && t && (s = s + "&state=we7sid-" + t), s = s + "&state=we7sid-" + t;
            var a = getCurrentPages();
            a.length && (a = a[getCurrentPages().length - 1]) && a.__route__ && (s = s + "&m=" + a.__route__.split("/")[0]), 
            wx.chooseImage({
                count: 5,
                success: function(t) {
                    for (var a = t.tempFilePaths, e = (n.data.imgs, 0); e < a.length; e++) wx.uploadFile({
                        url: s,
                        filePath: a[e],
                        name: "file",
                        formData: {
                            user: "test"
                        },
                        success: function(t) {
                            console.log(t.data);
                            var a = n.data.imgs;
                            a.push(t.data), n.setData({
                                imgs: a
                            });
                        }
                    });
                }
            });
        } else wx.showModal({
            title: "错误",
            content: "最多上传五张图片",
            success: function(t) {
                t.confirm ? console.log("用户点击确定") : t.cancel && console.log("用户点击取消");
            }
        });
    },
    previewImage: function(t) {
        var a = t.currentTarget.dataset.index;
        wx.previewImage({
            current: this.data.imgs[a],
            urls: this.data.imgs
        });
    },
    submit: function() {
        var a = this;
        if (a.data.submit) {
            var t = {
                op: "discuss_post",
                pid: a.data.list.pid,
                content: a.data.content,
                score: a.data.curr,
                tip: a.data.sign,
                out_trade_no: a.data.list.out_trade_no
            };
            0 < a.data.imgs.length && (t.imgs = JSON.stringify(a.data.imgs)), app.util.request({
                url: "entry/wxapp/service",
                data: t,
                success: function(t) {
                    "" != t.data.data && (wx.showToast({
                        title: "提交成功",
                        icon: "success",
                        duration: 2e3
                    }), setTimeout(function() {
                        "theme3" == a.data.theme.name ? wx.reLaunch({
                            url: "../../ui2/user/user"
                        }) : wx.reLaunch({
                            url: "../user/user"
                        });
                    }, 2e3));
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
                "" != a.data && e.setData({
                    list: a.data
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