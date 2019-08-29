var t, e = getApp();

Page({
    data: {
        modalFlag: !0,
        modelHidden: !0,
        isuser: !0,
        userinfo: "",
        appinfo: "",
        shop: "",
        adid: ""
    },
    onLoad: function(a) {
        (t = this).userload(), t.setData({
            appinfo: e.globalData.appinfo,
            adid: e.globalData.adid
        });
        try {
            wx.request({
                url: e.util.url("entry/wxapp/shop", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    console.log(a.data[0]), t.setData({
                        shop: a.data[0]
                    });
                }
            });
        } catch (a) {}
        console.log(t.data.appinfo);
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
                            isuser: !0,
                            userinfo: e.globalData.userinfo
                        })) : "100" == a.data.code && (e.globalData.userid = a.data.userinfo, t.setData({
                            isuser: !1
                        })), console.log(e.globalData.userid);
                    }
                });
            }
        });
    },
    getUserInfo: function(a) {
        e.globalData.userinfo = a.detail.userInfo, console.log(a.detail.userInfo);
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
                success: function(a) {
                    console.log(a.data), 100 == a.data && t.setData({
                        isuser: !0
                    }), t.userload();
                }
            });
        } catch (a) {
            wx.showToast({
                title: "授权失败",
                icon: "none",
                duration: 2e3
            });
        }
    },
    close: function() {
        this.setData({
            modelHidden: !0
        });
    },
    tap_pact: function(a) {
        this.setData({
            modalFlag: !1
        });
    },
    modal_pact: function(a) {
        this.setData({
            modalFlag: !0
        });
    },
    toshop: function(a) {
        e.setformid(a.detail.formId);
        var t = a.currentTarget.dataset.types, o = a.currentTarget.dataset.inurl;
        console.log(o), 1 == t ? wx.navigateTo({
            url: "../topath/topath?url=" + encodeURIComponent(o)
        }) : 2 == t && wx.navigateTo({
            url: "../load/load?url=" + encodeURIComponent(o)
        });
    },
    alipay: function() {
        wx.setClipboardData({
            data: t.data.appinfo.alipay,
            success: function(a) {
                wx.showModal({
                    title: "领取成功",
                    content: "红包领取成功，打开支付宝即可收到！",
                    success: function(a) {
                        a.confirm ? console.log("用户点击确定") : a.cancel && console.log("用户点击取消");
                    }
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
    onShareAppMessage: function() {}
});