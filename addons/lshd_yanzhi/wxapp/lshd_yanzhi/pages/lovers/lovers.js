var t, e = getApp();

Page({
    data: {
        A: "../../images/nomy.png",
        B: "../../images/nota.png",
        Aimg: "",
        Bimg: "",
        Ayanzhi: null,
        Byanzhi: null,
        similarity: "",
        menu: null,
        adid: ""
    },
    onLoad: function(a) {
        (t = this).setData({
            menu: e.globalData.menu,
            onshow: !0,
            ontest: !0,
            adid: e.globalData.adid,
            statusBarHeight: e.globalData.sysInfo.statusBarHeight
        }), console.log(t.data.menu);
    },
    gos: function() {
        if (t.data.Aimg && t.data.Bimg) {
            t.setData({
                onshow: !1
            });
            try {
                wx.request({
                    url: e.util.url("entry/wxapp/facecompare", {
                        m: "lshd_yanzhi"
                    }),
                    data: {
                        ids: e.globalData.userid,
                        imga: t.data.Aimg,
                        imgb: t.data.Bimg
                    },
                    success: function(a) {
                        console.log(a), t.setData({
                            similarity: a.data.data.similarity,
                            ontest: !1,
                            onshow: !0
                        }), e.shareapply(), console.log(a.data.data.similarity);
                    }
                });
            } catch (t) {}
        } else wx.showToast({
            title: "请传两张图片",
            icon: "none"
        });
    },
    uploadimg: function(o) {
        wx.chooseImage({
            count: 1,
            sizeType: [ "compressed" ],
            sourceType: [ "album", "camera" ],
            success: function(a) {
                t.setData({
                    onshow: !1
                });
                var n = a.tempFilePaths[0];
                try {
                    wx.uploadFile({
                        url: e.util.url("entry/wxapp/facecompareup", {
                            m: "lshd_yanzhi",
                            ids: e.globalData.userid
                        }),
                        filePath: n,
                        name: "file",
                        success: function(e) {
                            var a = JSON.parse(e.data);
                            console.log(a), "1" == o.currentTarget.dataset.types ? t.setData({
                                A: n,
                                Aimg: a[0],
                                Ayanzhi: a.data.face_list[0],
                                onshow: !0
                            }) : t.setData({
                                B: n,
                                Bimg: a[0],
                                Byanzhi: a.data.face_list[0],
                                onshow: !0
                            });
                        },
                        fail: function(t) {
                            console.log(t);
                        }
                    });
                } catch (t) {}
            }
        });
    },
    onShareAppMessage: function() {},
    tomodel: function(o) {
        wx.request({
            url: e.util.url("entry/wxapp/setformid", {
                m: "lshd_yanzhi"
            }),
            data: {
                formid: o.detail.formId,
                ids: e.globalData.userid
            },
            success: function(t) {
                var e = t.data;
                if (console.log(e), -1 < e) {
                    var a = o.currentTarget.dataset.types, n = o.currentTarget.dataset.inurl;
                    0 == a ? wx.chooseImage({
                        count: 1,
                        sizeType: [ "compressed" ],
                        sourceType: [ "album", "camera" ],
                        success: function(t) {
                            var e = t.tempFilePaths;
                            console.log(e), wx.navigateTo({
                                url: "../yanzhifen/yanzhifen?path=" + e
                            });
                            try {
                                wx.setStorageSync("uploadimg", e);
                            } catch (t) {}
                        }
                    }) : 1 == a ? wx.navigateTo({
                        url: n
                    }) : 2 == a ? wx.navigateTo({
                        url: "../topath/topath?url=" + encodeURIComponent(n)
                    }) : 3 == a && wx.navigateTo({
                        url: "../load/load?url=" + encodeURIComponent(n)
                    });
                } else wx.navigateTo({
                    url: "../index/index"
                });
            }
        });
    },
    goBack: function() {
        wx.navigateBack();
    },
    goHome: function() {
        wx.reLaunch({
            url: "../index/index"
        });
    },
    saveimg: function() {
        t.setData({
            canvas: !0,
            onshow: !1
        }), t.createNewImg(e.globalData.wecatimg);
    },
    createNewImg: function(e) {
        var a = wx.createCanvasContext("mycanvas"), n = t.data.Ayanzhi, o = t.data.Byanzhi;
        a.fillRect(0, 0, 750, 938), a.drawImage("../../images/520.jpg", 0, 0, 750, 938), 
        a.drawImage(t.data.A, 42, 358, 320, 370), a.drawImage(t.data.B, 398, 358, 320, 370), 
        a.setFontSize(88), a.setFillStyle("#ffffff"), a.setTextAlign("center"), a.fillText(t.data.similarity, 370, 264), 
        a.stroke(), a.setFontSize(20), a.setFillStyle("#ff458c"), a.setTextAlign("left"), 
        a.fillText(n.age, 226, 762), a.stroke(), a.setFontSize(20), a.setFillStyle("#ff458c"), 
        a.setTextAlign("left"), a.fillText(o.age, 590, 762), a.stroke(), a.setFontSize(20), 
        a.setFillStyle("#ff458c"), a.setTextAlign("left"), a.fillText(n.beauty, 144, 762), 
        a.stroke(), a.setFontSize(20), a.setFillStyle("#ff458c"), a.setTextAlign("left"), 
        a.fillText(o.beauty, 508, 762), a.stroke(), a.setFontSize(20), a.setFillStyle("#ff458c"), 
        a.setTextAlign("left"), a.fillText(n.expression, 328, 762), a.stroke(), a.setFontSize(20), 
        a.setFillStyle("#ff458c"), a.setTextAlign("left"), a.fillText(o.expression, 698, 762), 
        a.stroke(), a.drawImage(e, 590, 784, 156, 156), a.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "mycanvas",
                success: function(e) {
                    var a = e.tempFilePath;
                    wx.saveImageToPhotosAlbum({
                        filePath: a,
                        success: function(t) {
                            wx.showModal({
                                title: "提示",
                                content: "已经为您保存美图在相册里啦!您可以分享到朋友圈让好友看看",
                                success: function(t) {
                                    t.confirm ? console.log("用户点击确定") : t.cancel && console.log("用户点击取消");
                                }
                            });
                        },
                        fail: function(t) {
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
                            });
                        }
                    });
                },
                fail: function(t) {
                    console.log(t);
                },
                complete: function(e) {
                    t.setData({
                        canvas: !1,
                        onshow: !0
                    });
                }
            });
        }, 2e3);
    },
    goOn: function() {
        t.setData({
            A: "../../images/nomy.png",
            B: "../../images/nota.png",
            ontest: !0,
            Ayanzhi: !1,
            Byanzhi: !1
        });
    },
    move: function() {}
});