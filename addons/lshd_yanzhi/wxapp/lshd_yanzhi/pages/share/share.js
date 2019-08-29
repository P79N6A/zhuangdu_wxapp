var t, e = getApp();

Page({
    data: {
        isuser: !1,
        process_hidden: !1,
        no_result_hidden: !1,
        datainfo: ""
    },
    onLoad: function(a) {
        var o = decodeURIComponent(a.scene);
        console.log(o), t = this, wx.request({
            url: e.util.url("entry/wxapp/getiteminfo", {
                m: "lshd_yanzhi"
            }),
            data: {
                ids: o
            },
            success: function(e) {
                try {
                    var a = JSON.parse(e.data.datas);
                    console.log(a), t.setData({
                        datainfo: a
                    }), t.userload(), console.log("11111111111111111111111");
                } catch (e) {
                    wx.navigateTo({
                        url: "../index/index"
                    }), t.userload();
                }
            }
        });
        try {
            decodeURIComponent(a.ids) && e.sharevisible(decodeURIComponent(a.ids));
        } catch (e) {}
    },
    userload: function() {
        wx.login({
            success: function(a) {
                wx.request({
                    url: e.util.url("entry/wxapp/getuserinfo", {
                        m: "lshd_yanzhi"
                    }),
                    data: {
                        code: a.code
                    },
                    success: function(a) {
                        console.log(a.data), "200" == a.data.code ? (e.globalData.userinfo = a.data.userinfo, 
                        e.globalData.userid = a.data.userinfo.id, t.setData({
                            isuser: !0
                        })) : "100" == a.data.code && (e.globalData.userid = a.data.userinfo), console.log(e.globalData.userid);
                    }
                });
            }
        });
    },
    getUserInfo: function(a) {
        e.globalData.userinfo = a.detail.userInfo, console.log(a.detail.userInfo), console.log(e.globalData.userid);
        try {
            wx.request({
                url: e.util.url("entry/wxapp/upuser", {
                    m: "lshd_yanzhi"
                }),
                data: {
                    nickname: a.detail.userInfo.nickName,
                    headimg: a.detail.userInfo.avatarUrl,
                    ids: e.globalData.userid
                },
                success: function(e) {
                    console.log(e.data), 100 == e.data && t.setData({
                        isuser: !0
                    });
                }
            });
        } catch (e) {
            wx.showToast({
                title: "授权失败",
                icon: "none",
                duration: 2e3
            });
        }
    },
    chooimage: function(a) {
        console.log(a.detail.formId), e.setformid(a.detail.formId), wx.chooseImage({
            count: 1,
            sizeType: [ "compressed" ],
            sourceType: [ "album", "camera" ],
            success: function(e) {
                var a = e.tempFilePaths;
                console.log(a), wx.navigateTo({
                    url: "../yanzhifen/yanzhifen?path=" + a
                });
                try {
                    wx.setStorageSync("uploadimg", a);
                } catch (e) {}
            }
        });
    },
    toIndex: function() {
        wx.navigateTo({
            url: "../index/index"
        });
    }
});