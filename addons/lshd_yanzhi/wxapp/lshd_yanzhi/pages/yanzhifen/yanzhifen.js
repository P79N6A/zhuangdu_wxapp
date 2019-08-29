var t, e = getApp(), ptu = require("../../utils/ptu.js");

Page({
    data: {
        appinfo: "",
        imgpath: "",
        fen: "",
        process_hidden: !1,
        no_result_hidden: !1,
        onshow: !0,
        uploadimg: "",
        userinfo: "",
        datainfo: [],
        modalFlag: !0,
        canvas: !1,
        wecatimg: "",
        imgurls: ""
    },
    onLoad: function(a) {
        (t = this).setData({
            appinfo: e.globalData.appinfo,
            uploadimg: wx.getStorageSync("uploadimg"),
            userinfo: e.globalData.userinfo,
            imgpath: a.path
        }), t.uploadimg(a.path);
    },
    uploadimg: function(a) {
        wx.uploadFile({
            url: e.util.url("entry/wxapp/detectface", {
                m: "lshd_yanzhi",
                userid: e.globalData.userid
            }),
            filePath: a,
            name: "file",
            success: function(a) {
                try {
                    var n = a.data, o = JSON.parse(n), i = o.data.face_list[0];
                    console.log(o), t.setData({
                        imgurls: o[0],
                        fen: i.beauty + "分",
                        process_hidden: !0,
                        datainfo: t.fenxi(i, o[0]),
                        blur: "fadeInBlur animated"
                    }), ptu.delectFile();
                } catch (e) {
                    t.setData({
                        no_result_hidden: !0
                    });
                }
                e.shareapply(), console.log(t.data.datainfo);
            }
        });
    },
    fenxi: function(a, n) {
        var o = a.gender, i = a.beauty, s = t.weixiao(a.expression), l = e.siteInfo.siteroot, u = l.substring(0, l.length - 13), c = t.getpng(i), d = [], g = Date.parse(new Date()), r = new Date(g), f = r.getFullYear(), m = r.getMonth() + 1 < 10 ? "0" + (r.getMonth() + 1) : r.getMonth() + 1, p = r.getDate() < 10 ? "0" + r.getDate() : r.getDate();
        return e.globalData.userinfo.headimg || e.globalData.userinfo.nickname || wx.request({
            url: e.util.url("entry/wxapp/getuser", {
                m: "lshd_yanzhi"
            }),
            data: {
                ids: e.globalData.userid
            },
            success: function(t) {
                console.log(t), e.globalData.userinfo = t.data[0];
            }
        }), d = o < 50 ? [ {
            userid: e.globalData.userid,
            headimg: e.globalData.userinfo.headimg,
            nickname: e.globalData.userinfo.nickname,
            age: a.age,
            expression: s,
            beauty: i,
            sex: "女",
            beautyimg: u + "addons/lshd_yanzhi/images/label/girls/" + c,
            time: f + "/" + m + "/" + p,
            userimg: n
        } ] : [ {
            userid: e.globalData.userid,
            headimg: t.data.userinfo.headimg,
            nickname: t.data.userinfo.nickname,
            age: a.age,
            expression: s,
            beauty: i,
            sex: "男",
            beautyimg: u + "addons/lshd_yanzhi/images/label/boys/" + c,
            time: f + "/" + m + "/" + p,
            userimg: n
        } ], wx.request({
            url: e.util.url("entry/wxapp/savedata", {
                m: "lshd_yanzhi"
            }),
            method: "POST",
            dataType: "json",
            data: {
                datas: d
            },
            success: function(e) {
                console.log(e.data), t.setData({
                    getitemid: e.data
                });
            }
        }), d;
    },
    getpng: function(e) {
        var t = e;
        return 0 < t && t < 40 ? "0.png" : 39 < t && t < 80 ? 80 == t ? "48.png" : "4" + parseInt(Math.floor((t - 40) / 5) + 1) + ".png" : 80 == t ? "1.png" : parseInt(Math.round((t - 80) / 2)) + ".png";
    },
    weixiao: function(e) {
        return e < 30 ? "似笑非笑" : 30 <= e && e < 50 ? "笑逐颜开" : 50 <= e && e < 60 ? "喜上眉梢" : 60 <= e && e < 70 ? "眉开眼笑" : 70 <= e && e < 80 ? "笑容满面" : 80 <= e && e < 91 ? "心花怒放" : "一笑倾城";
    },
    gohaibao: function() {
        setTimeout(function() {
            wx.hideLoading();
        }, 3e3), t.setData({
            canvas: !0,
            onshow: !1
        }), t.wecatimg(t.data.getitemid);
    },
    wecatimg: function(a) {
        wx.request({
            url: e.util.url("entry/wxapp/token", {
                m: "lshd_yanzhi"
            }),
            data: {
                itemid: a,
                ids: e.globalData.userid
            },
            success: function(e) {
                console.log(e), 200 === e.statusCode && t.download(e.data);
            }
        });
    },
    download: function(e) {
        console.log(e), wx.downloadFile({
            url: e,
            success: function(a) {
                200 === a.statusCode && (console.log(a), wx.downloadFile({
                    url: t.data.datainfo[0].beautyimg,
                    success: function(e) {
                        200 === e.statusCode && (console.log(e), t.createNewImg(a.tempFilePath, e.tempFilePath));
                    }
                }));
            }
        });
    },
    take_photo: function(e) {
        var a = this;
        wx.chooseImage({
            count: 1,
            sizeType: [ "compressed" ],
            sourceType: [ "album", "camera" ],
            success: function(e) {
                var t = e.tempFilePaths[0];
                a.setData({
                    process_hidden: !1,
                    no_result_hidden: !1,
                    imgpath: t
                }), a.uploadimg(t);
            }
        });
    },
    tap_pact: function(e) {
        this.setData({
            modalFlag: !1
        });
    },
    modal_pact: function(e) {
        this.setData({
            modalFlag: !0
        });
    },
    createNewImg: function(e, a) {
        var n = t.data.datainfo[0], o = wx.createCanvasContext("mycanvas");
        o.fillRect(0, 0, 375, 540), o.drawImage("../../images/muban.png", 0, 0, 375, 540), 
        o.drawImage("../../images/minfo.png", 35, 370, 305, 60), o.drawImage(a, 225, 0, 150, 150), 
        o.setFontSize(18), o.setFillStyle("#ff2c44"), o.setTextAlign("center"), o.fillText(t.data.appinfo.visit, 113, 468), 
        o.stroke(), o.setFontSize(15), o.setFillStyle("#fff"), o.setTextAlign("left"), o.fillText(n.age, 260, 388), 
        o.stroke(), o.setFontSize(15), o.setFillStyle("#fff"), o.setTextAlign("left"), o.fillText(n.sex, 260, 423), 
        o.stroke(), o.setFontSize(15), o.setFillStyle("#fff"), o.setTextAlign("left"), o.fillText(n.nickname, 90, 388), 
        o.stroke(), o.setFontSize(15), o.setFillStyle("#fff"), o.setTextAlign("left"), o.fillText(n.expression, 90, 423), 
        o.stroke(), o.save(), o.beginPath(), o.arc(188, 187, 100, 0, 2 * Math.PI, !1), o.clip(), 
        o.drawImage(t.data.imgpath, 85, 85, 210, 210), o.closePath(), o.restore(), o.beginPath(), 
        o.drawImage("../../images/defen.png", 0, 245, 375, 95), o.drawImage(e, 270, 440, 90, 90), 
        o.setFontSize(22), o.setFillStyle("#fff"), o.setTextAlign("left"), o.fillText(n.beauty + "分", 158, 295), 
        o.stroke(), o.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "mycanvas",
                success: function(e) {
                    var a = e.tempFilePath;
                    wx.saveImageToPhotosAlbum({
                        filePath: a,
                        success: function(e) {
                            wx.showModal({
                                title: "提示",
                                content: "已经为您保存美图在相册里啦!您可以分享到朋友圈让好友看看",
                                success: function(e) {
                                    e.confirm ? console.log("用户点击确定") : e.cancel && console.log("用户点击取消");
                                }
                            });
                        },
                        fail: function(e) {
                            wx.showToast({
                                title: "保存失败请重试",
                                icon: "none",
                                duration: 2e3
                            });
                        },
                        complete: function(e) {
                            t.setData({
                                canvas: !1,
                                onshow: !0
                            }), ptu.delectFile();
                        }
                    });
                },
                fail: function(e) {
                    console.log(e);
                },
                complete: function(e) {
                    t.setData({
                        canvas: !1,
                        onshow: !0
                    }), ptu.delectFile();
                }
            });
        }, 2e3);
    },
    onShareAppMessage: function(a) {
        return {
            title: t.data.datainfo[0].nickname + "@我,我的颜值分高达" + t.data.datainfo[0].beauty + "分,你要不要也来试试?",
            path: "/lshd_yanzhi/pages/share/share?scene=" + t.data.getitemid + "&ids=" + e.globalData.userid,
            success: function(t) {
                e.sharenum(), console.log("转发成功" + e.globalData.userid);
            },
            fail: function(t) {
                console.log("转发失败" + e.globalData.userid);
            }
        };
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});