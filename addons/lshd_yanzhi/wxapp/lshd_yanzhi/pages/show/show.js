var t, e = getApp(), ptu = require("../../utils/ptu.js");

Page({
    data: {
        navbar: [ {
            name: "变脸",
            id: "0"
        }, {
            name: "大头贴",
            id: "1"
        }, {
            name: "变妆",
            id: "2"
        }, {
            name: "滤镜",
            id: "3"
        } ],
        currentTab: 0,
        selectTab: 0,
        imgdata: "",
        fordata: "",
        imageurl: "",
        tmpimgpath: "",
        modal: !0,
        adid: ""
    },
    navbarTap: function(a) {
        var e = a.currentTarget.dataset.idx;
        t.vnbars(e);
    },
    onLoad: function(a) {
        t = this;
        try {
            var n = wx.getStorageSync("imgdata");
            n ? t.setData({
                imgdata: n
            }) : wx.request({
                url: e.util.url("entry/wxapp/getmoreimg", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    console.log(a.data), t.setData({
                        imgdata: a.data
                    });
                }
            });
        } catch (t) {}
        var o = a.ids, s = a.ptutype;
        console.log("模板id:" + o + "项:" + s), t.setData({
            currentTab: s,
            tmpimgpath: a.tmppath,
            index: o,
            selectTab: o - 1,
            onshow: !1,
            adid: e.globalData.adid
        }), console.log(a.tmppath), t.Getdata(o), t.gets();
        try {
            wx.request({
                url: e.util.url("entry/wxapp/getapplynum", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    console.log(a.data), t.setData({
                        applynum: a.data
                    });
                }
            });
        } catch (t) {}
    },
    onShow: function() {
        t.vnbars(t.data.currentTab);
    },
    gets: function() {
        null == e.globalData.shujufanhui ? (setTimeout(function() {
            t.gets();
        }, 500), console.log("循环：")) : wx.downloadFile({
            url: e.globalData.shujufanhui,
            success: function(a) {
                200 === a.statusCode && (console.log(a), t.setData({
                    imageurl: a.tempFilePath,
                    onshow: !0
                }), setTimeout(function() {
                    ptu.delectFile(), e.shareapply();
                }, 1e3));
            },
            fail: function(a) {
                wx.showModal({
                    title: "错误",
                    content: "图片上传错误，请上传正面照片！",
                    success: function(a) {
                        t.again(), t.setData({
                            onshow: !0
                        });
                    }
                });
            }
        });
    },
    vnbars: function(a) {
        console.log(a), 0 == a ? t.setData({
            currentTab: a,
            fordata: t.data.imgdata.facemerge
        }) : 1 == a ? t.setData({
            currentTab: a,
            fordata: t.data.imgdata.facesticker
        }) : 2 == a ? t.setData({
            currentTab: a,
            fordata: t.data.imgdata.facedecoration
        }) : 3 == a && t.setData({
            currentTab: a,
            fordata: t.data.imgdata.imgfilter
        });
    },
    selectTemplate: function(n) {
        t.setData({
            selectTab: n.currentTarget.dataset.idx
        }), wx.request({
            url: e.util.url("entry/wxapp/setformid", {
                m: "lshd_yanzhi"
            }),
            data: {
                formid: n.detail.formId,
                ids: e.globalData.userid
            },
            success: function(a) {
                var e = a.data;
                console.log(e), -1 < e ? t.Getdata(parseInt(n.currentTarget.dataset.idx) + 1) : t.setData({
                    modal: !1
                });
            }
        });
    },
    Getdata: function(a) {
        0 == t.data.currentTab ? ptu.facemerge(parseInt(a), t.data.tmpimgpath) : 1 == t.data.currentTab ? ptu.facesticker(parseInt(a), t.data.tmpimgpath) : 2 == t.data.currentTab ? ptu.facedecoration(parseInt(a), t.data.tmpimgpath) : 3 == t.data.currentTab && ptu.imgfilter(parseInt(a), t.data.tmpimgpath), 
        t.setData({
            onshow: !1
        }), t.gets(), console.log("获取中！");
    },
    again: function() {
        wx.chooseImage({
            count: 1,
            sizeType: [ "compressed" ],
            sourceType: [ "album", "camera" ],
            success: function(a) {
                t.setData({
                    imageurl: a.tempFilePaths[0],
                    tmpimgpath: a.tempFilePaths[0]
                });
            }
        });
    },
    saveimg: function() {
        var a = t.data.currentTab;
        console.log(a);
        var n = t.data.navbar[parseInt(a)].name, o = e.globalData.appinfo.name;
        t.setData({
            canvas: !0,
            onshow: !1
        });
        var s = wx.createCanvasContext("mycanvas");
        s.fillRect(0, 0, 520, 710), s.drawImage("../../images/showback.jpg", 0, 0, 520, 710), 
        s.drawImage(t.data.imageurl, 10, 10, 500, 600), s.setFontSize(18), s.setFillStyle("#000000"), 
        s.setTextAlign("left"), s.fillText(o + " ———— " + n, 15, 660), s.stroke(), s.drawImage(e.globalData.wecatimg, 410, 610, 100, 100), 
        s.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "mycanvas",
                success: function(a) {
                    t.setData({
                        canvas: !1
                    });
                    var e = a.tempFilePath;
                    wx.saveImageToPhotosAlbum({
                        filePath: e,
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
                        complete: function(a) {
                            t.setData({
                                canvas: !1,
                                onshow: !0
                            });
                        }
                    });
                },
                complete: function(a) {
                    t.setData({
                        canvas: !1,
                        onshow: !0
                    });
                }
            });
        }, 500);
    },
    onShareAppMessage: function(a) {
        return t.setData({
            modal: !0
        }), {
            title: "@我,发现一个超好玩的变脸魔法,不进真的会后悔哦!",
            path: "/lshd_yanzhi/pages/index/index",
            success: function(a) {
                e.sharenum(), console.log("转发成功" + t.data.getitemid);
            },
            fail: function(a) {
                console.log("转发失败" + t.data.getitemid);
            }
        };
    },
    move: function() {},
    modal_pact: function() {
        t.setData({
            modal: !0
        });
    }
});