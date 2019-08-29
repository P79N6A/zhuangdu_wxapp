var t, e = getApp();

Page({
    data: {
        winHeight: "",
        template: [],
        ptutypes: 0,
        adid: ""
    },
    onLoad: function(a) {
        var s = this;
        s.setData({
            statusBarHeight: e.globalData.sysInfo.statusBarHeight
        }), wx.getSystemInfo({
            success: function(t) {
                var a = t.windowHeight * (750 / t.windowWidth) - 180;
                console.log(a), s.setData({
                    winHeight: a
                });
            }
        });
        var t = a.name;
        console.log(t), wx.request({
            url: e.util.url("entry/wxapp/" + t, {
                m: "lshd_yanzhi"
            }),
            data: {},
            success: function(t) {
                console.log(t.data), s.setData({
                    template: t.data,
                    ptutypes: a.types
                });
            }
        }), wx.request({
            url: e.util.url("entry/wxapp/getappinfo", {
                m: "lshd_yanzhi"
            }),
            data: {},
            success: function(t) {
                console.log(t), s.setData({
                    adid: t.data[0].adid,
                    appinfo: t.data[0]
                }), console.log(t.data[0]);
            }
        });
    },
    toshow: function(t) {
        var e = this, s = parseInt(t.currentTarget.dataset.itemid) + 1;
        wx.chooseImage({
            count: 1,
            sizeType: [ "compressed" ],
            sourceType: [ "album", "camera" ],
            success: function(t) {
                var a = t.tempFilePaths;
                console.log(a), wx.navigateTo({
                    url: "../show/show?ids=" + s + "&ptutype=" + e.data.ptutypes + "&tmppath=" + a
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
    footerTap: e.footerTap
});