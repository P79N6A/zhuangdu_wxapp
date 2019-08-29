var _data;

function _defineProperty(a, t, e) {
    return t in a ? Object.defineProperty(a, t, {
        value: e,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : a[t] = e, a;
}

var t, e = getApp(), ptu = require("../../utils/ptu.js");

Page({
    data: (_data = {
        isuser: !1,
        motto: "Hello World",
        autoplay: !0,
        interval: 5e3,
        duration: 1e3,
        yanzhimodal: !0,
        modalFlag: !0,
        indicatorDots: !0,
        vertical: !1
    }, _defineProperty(_data, "interval", 5e3), _defineProperty(_data, "slider", ""), 
    _defineProperty(_data, "menus", ""), _defineProperty(_data, "adid", ""), _defineProperty(_data, "apply", 0), 
    _defineProperty(_data, "applynum", 0), _defineProperty(_data, "isuser", !0), _defineProperty(_data, "appinfo", null), 
    _defineProperty(_data, "swiperIndex", 0), _defineProperty(_data, "swiperCurrent", 0), 
    _data),
    onLoad: function(a) {
        (t = this).setData({
            statusBarHeight: e.globalData.sysInfo.statusBarHeight
        });
        try {
            a.ids && e.sharevisible(a.ids);
        } catch (a) {
            wx.showToast({
                title: "错误，请重试",
                icon: "none"
            });
        }
        try {
            wx.request({
                url: e.util.url("entry/wxapp/getmenu", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    console.log(a.data), t.setData({
                        menus: a.data
                    }), e.globalData.menu = a.data;
                }
            });
        } catch (a) {
            wx.showToast({
                title: "错误，请重试",
                icon: "none"
            });
        }
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
        } catch (a) {
            wx.showToast({
                title: "错误，请重试",
                icon: "none"
            });
        }
        t.userload();
        try {
            wx.request({
                url: e.util.url("entry/wxapp/getappinfo", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    console.log(a), e.globalData.adid = a.data[0].adid, t.setData({
                        adid: a.data[0].adid,
                        appinfo: a.data[0]
                    }), console.log(a.data[0]);
                    try {
                        var o = a.data[0].wecatimg;
                        wx.downloadFile({
                            url: o,
                            success: function(a) {
                                200 === a.statusCode && (e.globalData.wecatimg = a.tempFilePath);
                            }
                        });
                    } catch (a) {}
                    e.globalData.appinfo = a.data[0], wx.setNavigationBarColor({
                        frontColor: "#000000",
                        backgroundColor: a.data[0].color
                    }), wx.setNavigationBarTitle({
                        title: a.data[0].name
                    });
                }
            });
        } catch (a) {
            wx.showToast({
                title: "错误，请重试",
                icon: "none"
            });
        }
        try {
            wx.request({
                url: e.util.url("entry/wxapp/slider", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    decodeURIComponent(a.data.inurl).split("||");
                    t.setData({
                        slider: a.data
                    }), console.log(a.data);
                }
            });
        } catch (a) {
            wx.showToast({
                title: "错误，请重试",
                icon: "none"
            });
        }
        try {
            wx.request({
                url: e.util.url("entry/wxapp/getmore", {
                    m: "lshd_yanzhi"
                }),
                data: {},
                success: function(a) {
                    console.log(a.data), t.setData({
                        getmore: a.data
                    });
                }
            });
        } catch (a) {
            wx.showToast({
                title: "错误，请重试",
                icon: "none"
            });
        }
    },
    onShow: function() {
        wx.request({
            url: e.util.url("entry/wxapp/getmoreimg", {
                m: "lshd_yanzhi"
            }),
            data: {},
            success: function(a) {
                console.log(a.data);
                try {
                    wx.setStorageSync("imgdata", a.data);
                } catch (a) {
                    wx.showToast({
                        title: "错误，请重试",
                        icon: "none"
                    });
                }
            }
        });
    },
    swiperChange: function(a) {
        this.setData({
            swiperCurrent: a.detail.current
        });
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
                        })) : "100" == a.data.code && (e.globalData.userid = a.data.userinfo, t.setData({
                            isuser: !1
                        })), console.log(e.globalData.userid);
                    },
                    fail: function(a) {
                        console.log(a);
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
                    t.userload(), a.data, t.setData({
                        isuser: !0
                    });
                }
            });
        } catch (a) {
            wx.showToast({
                title: "授权失败",
                icon: "none",
                duration: 1e3
            });
        }
    },
    tomodel: function(s) {
        wx.request({
            url: e.util.url("entry/wxapp/setformid", {
                m: "lshd_yanzhi"
            }),
            data: {
                formid: s.detail.formId,
                ids: e.globalData.userid
            },
            success: function(a) {
                var e = a.data;
                if (console.log(e), -1 < e) {
                    var o = s.currentTarget.dataset.types, n = s.currentTarget.dataset.inurl;
                    0 == o ? t.setData({
                        yanzhimodal: !1
                    }) : 1 == o ? wx.navigateTo({
                        url: n
                    }) : 2 == o ? wx.navigateTo({
                        url: "../topath/topath?url=" + encodeURIComponent(n)
                    }) : 3 == o && wx.navigateTo({
                        url: "../load/load?url=" + encodeURIComponent(n)
                    });
                } else t.setData({});
            }
        });
    },
    choose_photo: function(a) {
        wx.request({
            url: e.util.url("entry/wxapp/setformid", {
                m: "lshd_yanzhi"
            }),
            data: {
                formid: a.detail.formId,
                ids: e.globalData.userid
            },
            success: function(a) {
                wx.chooseImage({
                    count: 1,
                    sizeType: [ "compressed" ],
                    sourceType: [ "album" ],
                    success: function(a) {
                        var e = a.tempFilePaths;
                        console.log(e), wx.navigateTo({
                            url: "../yanzhifen/yanzhifen?path=" + e
                        });
                        try {
                            wx.setStorageSync("uploadimg", e);
                        } catch (a) {}
                        t.setData({
                            yanzhimodal: !0
                        });
                    }
                });
            }
        });
    },
    take_photo: function(a) {
        wx.request({
            url: e.util.url("entry/wxapp/setformid", {
                m: "lshd_yanzhi"
            }),
            data: {
                formid: a.detail.formId,
                ids: e.globalData.userid
            },
            success: function(a) {
                wx.chooseImage({
                    count: 1,
                    sizeType: [ "compressed" ],
                    sourceType: [ "camera" ],
                    success: function(a) {
                        var e = a.tempFilePaths;
                        console.log(e), wx.navigateTo({
                            url: "../yanzhifen/yanzhifen?path=" + e
                        });
                        try {
                            wx.setStorageSync("uploadimg", e);
                        } catch (a) {}
                        t.setData({
                            yanzhimodal: !0
                        });
                    }
                });
            }
        });
    },
    swiperClick: function(a) {
        e.setformid(a.detail.formId);
    },
    bindchange: function(a) {
        this.setData({
            swiperIndex: a.detail.current
        });
    },
    onShareAppMessage: function(a) {
        return t.setData({
            yanzhimodal: !0
        }), {
            title: "@所有人,当我按下快门的那一刻,似乎发现了一个新大陆!",
            path: "/lshd_yanzhi/pages/index/index?ids=" + e.globalData.userid,
            success: function(a) {
                e.sharenum(), console.log("转发成功" + e.globalData.userid), t.setData({
                    yanzhimodal: !0
                });
            },
            fail: function(a) {
                console.log("转发失败" + e.globalData.userid);
            }
        };
    },
    toUser: function() {
        wx.navigateTo({
            url: "../profile/profile"
        });
    },
    toLovers: function() {
        wx.navigateTo({
            url: "../lovers/lovers"
        });
    },
    toHeadimg: function() {
        wx.navigateTo({
            url: "../worldcup/worldcup"
        });
    },
    onmusk: function() {
        t.setData({
            yanzhimodal: !0
        });
    },
    tostar: function() {
        wx.navigateTo({
            url: "../starface/starface"
        });
    }
});