var t, e = getApp();

Page({
    data: {
        photo: "../../images/ownupload.jpg",
        starbg: "../../images/star.jpg",
        menu: null,
        onMatch: !0,
        star: [],
        starname: "明星",
        adid: "",
        text: "与你最像的明星是?"
    },
    onLoad: function(a) {
        (t = this).setData({
            menu: e.globalData.menu,
            onshow: !0,
            ontest: !0,
            adid: e.globalData.adid
        }), console.log(t.data.menu);
    },
    upimg: function() {
        t.setData({
            onMatch: !1
        }), wx.setNavigationBarTitle({
            title: "正在识别"
        }), wx.chooseImage({
            count: 1,
            sizeType: [ "compressed" ],
            sourceType: [ "album" ],
            success: function(a) {
                var o = a.tempFilePaths;
                console.log(o[0]), wx.uploadFile({
                    url: e.util.url("entry/wxapp/faceitem", {
                        m: "lshd_yanzhi",
                        ids: e.globalData.userid
                    }),
                    filePath: o[0],
                    name: "file",
                    success: function(a) {
                        var e = JSON.parse(a.data);
                        console.log(e), t.setData({
                            photo: o,
                            starbg: e.user_info.url,
                            starname: e.user_info.name,
                            onMatch: !0,
                            text: "经测算,我和" + e.user_info.name + "相似度达" + e.score + "%"
                        }), wx.setNavigationBarTitle({
                            title: "测测明星脸"
                        });
                    }
                });
            }
        });
    },
    onShareAppMessage: function(a) {
        return {
            title: "测一测你最像哪位明星!",
            path: "/lshd_yanzhi/pages/index/index",
            success: function(a) {
                console.log("转发成功" + e.globalData.userid);
            },
            fail: function(a) {
                console.log("转发失败" + e.globalData.userid);
            }
        };
    }
});