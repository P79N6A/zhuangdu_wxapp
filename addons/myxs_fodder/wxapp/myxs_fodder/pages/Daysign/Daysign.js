var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(t) {
    return t && t.__esModule ? t : {
        default: t
    };
}

function _defineProperty(t, e, a) {
    return e in t ? Object.defineProperty(t, e, {
        value: a,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[e] = a, t;
}

Page(_defineProperty({
    data: {
        maskHidden: !1,
        animationData: "",
        shareText: "分享",
        iconBl: 1
    },
    onLoad: function(t) {
        var e = this;
        this.dataShow(), this.dayObtain(), wx.getSystemInfo({
            success: function(t) {
                e.setData({
                    pixel: t.pixelRatio
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    ReturnNew: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    dataShow: function() {
        var a = this;
        _request2.default.get("ListDaySign").then(function(e) {
            wx.getImageInfo({
                src: e.sign_img,
                success: function(t) {
                    e.sign_img = t.path, a.setData({
                        imgBd: t.path
                    });
                }
            }), "" != e.qr_img && wx.getImageInfo({
                src: e.qr_img,
                success: function(t) {
                    a.setData({
                        ewm: t.path
                    });
                }
            }), wx.getImageInfo({
                src: e.day_url,
                success: function(t) {
                    a.setData({
                        Rqiimg: t.path
                    });
                }
            }), a.setData({
                DaySign: e
            });
        });
    },
    dayObtain: function() {
        var t = "周" + "日一二三四五六".charAt(new Date().getDay()), e = new Date().getDate(), a = new Date().getFullYear(), n = new Date().getUTCMonth() + 1;
        this.setData({
            week: t,
            day: e,
            n: a,
            y: n,
            eW: {
                "周一": "MONDAY",
                "周二": "TUESDAY",
                "周三": "WEDNESDAY",
                "周四": "THURSDAY",
                "周五": "FRIDAY",
                "周六": "SATURDAY",
                "周日": "SUNDAY"
            }[t]
        });
    },
    shareS: function() {},
    formSubmit: function(t) {
        var e = wx.createAnimation({
            duration: 1500,
            timingFunction: "ease",
            delay: 0,
            transformOrigin: "50% 50%"
        });
        e.width(180).step(), this.setData({
            animationData: e.export(),
            shareText: "保存成功",
            iconBl: 0
        });
        var a = this;
        setTimeout(function() {
            a.drawImg();
        }, 1e3);
    },
    baocun: function() {
        var t = wx.createAnimation({
            duration: 800,
            timingFunction: "ease",
            delay: 0,
            transformOrigin: "50% 50%"
        });
        t.width(62).step(), this.setData({
            animationData: t.export(),
            shareText: "分享",
            iconBl: 1
        });
    },
    drawImg: function() {
        var a = this, t = 750, e = 500, n = (this.data.pixelRatio, wx.createCanvasContext("share"));
        wx.getImageInfo({
            src: a.data.imgBd,
            success: function(t) {}
        }), n.setFillStyle("#fff"), n.fillRect(0, 0, t, 1134), n.drawImage(a.data.imgBd, 0, 0, 750, e), 
        null != a.data.ewm && n.drawImage(a.data.ewm, 295, 914, 160, 160), n.drawImage(a.data.Rqiimg, 215, 350, 320, 320), 
        n.setFontSize(38), n.setFillStyle("rgb(177, 177, 177)"), n.fillText(a.data.n + "/" + a.data.y, 30, 550), 
        n.setFontSize(32), n.fillText(a.data.eW, t - n.measureText(a.data.eW).width - 30, 600), 
        n.setFontSize(38), n.setFillStyle("#000"), n.fillText(a.data.week, t - n.measureText(a.data.week).width - 30, 550);
        var i = a.data.DaySign.sign_content.split(""), s = "", o = [];
        n.setFontSize(28), n.setFillStyle("#000");
        for (var r = 0; r < i.length; r++) n.measureText(s).width < 520 ? s += i[r] : (r--, 
        o.push(s), s = "");
        if (o.push(s), 2 < o.length) {
            var u = o.slice(0, 2)[1], c = "";
            for (r = 0; r < u.length && n.measureText(c).width < 220; r++) c += u[r];
        }
        for (var d = 0; d < o.length; d++) n.fillText(o[d], 115, 780 + 50 * d);
        n.setFontSize(58), n.setFillStyle("#000"), n.fillText(a.data.DaySign.sign_title, (750 - n.measureText(a.data.DaySign.sign_title).width) / 2, 710), 
        n.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "share",
                success: function(t) {
                    var e = t.tempFilePath;
                    a.setData({
                        imagePath: e
                    }), wx.saveImageToPhotosAlbum({
                        filePath: e,
                        success: function(t) {
                            wx.showModal({
                                title: "日签已成功保存至相册",
                                content: "快去分享朋友圈，叫朋友给你点赞 ~",
                                showCancel: !1,
                                confirmText: "我知道了",
                                confirmColor: "#ff4081",
                                success: function(t) {
                                    var e = wx.createAnimation({
                                        duration: 800,
                                        timingFunction: "ease",
                                        delay: 0,
                                        transformOrigin: "50% 50%"
                                    });
                                    e.width(62).step(), a.setData({
                                        animationData: e.export(),
                                        shareText: "分享",
                                        iconBl: 1
                                    });
                                }
                            });
                        }
                    });
                },
                fail: function(t) {}
            });
        }, 200);
    }
}, "onShareAppMessage", function(t) {
    var e = this, a = t.target.dataset.index, n = {
        types: "fx",
        id: t.target.dataset.id
    };
    return _request2.default.post("operation", n).then(function(t) {
        e.data.dataList[a].sharenb = parseInt(e.data.dataList[a].sharenb) + 1, e.setData({
            dataList: e.data.dataList
        });
    }), {
        path: "myxs_fodder/pages/start/start"
    };
}));