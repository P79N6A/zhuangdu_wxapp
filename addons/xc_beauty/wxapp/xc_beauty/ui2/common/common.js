var app = getApp();

function config(e) {
    var o = [ {
        pagePath: "../index/index",
        text: "主页",
        iconPath: "../../resource/theme3_14.png",
        selectedIconPath: "../../resource/theme3_15.png",
        status: 1
    }, {
        pagePath: "../service/service",
        text: "项目",
        iconPath: "../../resource/theme3_16.png",
        selectedIconPath: "../../resource/theme3_17.png",
        status: 1
    }, {
        pagePath: "../rotate/rotate",
        text: "抽奖",
        iconPath: "../../resource/theme3_18.png",
        selectedIconPath: "../../resource/theme3_19.png",
        status: 1
    }, {
        pagePath: "../store/porder",
        text: "预约",
        iconPath: "../../resource/theme3_20.png",
        selectedIconPath: "../../resource/theme3_21.png",
        status: 1
    }, {
        pagePath: "../user/user",
        text: "我的",
        iconPath: "../../resource/theme3_22.png",
        selectedIconPath: "../../resource/theme3_23.png",
        status: 1
    } ], t = "";
    if ("" != app.config && null != app.config) {
        if (wx.setNavigationBarTitle({
            title: app.config.content.title
        }), "" != (t = app.config.content).footer && null != t.footer) for (var n = 0; n < t.footer.length; n++) "" != t.footer[n].text && null != t.footer[n].text && (o[n].text = t.footer[n].text), 
        "" != t.footer[n].icon && null != t.footer[n].icon && (o[n].iconPath = t.footer[n].icon), 
        "" != t.footer[n].select && null != t.footer[n].select && (o[n].selectedIconPath = t.footer[n].select), 
        "" != t.footer[n].link && null != t.footer[n].link && (o[n].pagePath = t.footer[n].link), 
        "" != t.footer[n].status && null != t.footer[n].status && (o[n].status = t.footer[n].status);
        "" != app.model && null != app.model && (t.model = app.model);
    }
    for (n = 0; n < o.length; n++) e.data.pagePath == o[n].pagePath && 1 == o[n].status && e.setData({
        footerCurr: n + 1
    });
    e.g_footer = g_footer, e.call_mobile = call_mobile, e.updateUserInfo = updateUserInfo, 
    is_user(e), e.user_close = user_close, e.setData({
        footer: o,
        config: t
    });
}

function theme(e) {
    var o = {
        name: "theme1",
        color: "#e74479",
        icon: [ "../../resource/icon01.png", "../../resource/icon02.png", "../../resource/icon03.png", "../../resource/icon04.png", "../../resource/icon05.png", "../../resource/icon06.png", "../../resource/icon07.png", "../../resource/icon08.png", "../../resource/icon09.png", "../../resource/icon10.png", "../../resource/icon11.png", "../../resource/icon12.png", "../../resource/icon13.png", "../../resource/icon14.png", "../../resource/icon15.png", "../../resource/icon16.png", "../../resource/icon17.png", "../../resource/icon18.png", "../../resource/icon19.png", "../../resource/icon20.png", "../../resource/icon21.png", "../../resource/icon22.png", "../../resource/icon23.png", "../../resource/icon24.png", "../../resource/icon25.png", "../../resource/icon26.png" ]
    };
    if ("" != app.theme && null != app.theme) {
        var t = app.theme.content;
        if (2 == t.theme) {
            if (o.name = "theme" + t.theme, o.color = t.color, "" != t.icon && null != t.icon) for (var n = 0; n < t.icon.length; n++) "" != t.icon[n] && null != t.icon[n] && (o.icon[n] = t.icon[n]);
        } else 3 == t.theme && (o.name = "theme3", o.color = "#444444");
    }
    "theme3" == o.name ? wx.setNavigationBarColor({
        frontColor: "#000000",
        backgroundColor: "#fff",
        animation: {
            duration: 400,
            timingFunc: "easeIn"
        }
    }) : wx.setNavigationBarColor({
        frontColor: "#ffffff",
        backgroundColor: o.color,
        animation: {
            duration: 400,
            timingFunc: "easeIn"
        }
    }), e.setData({
        theme: o
    });
}

function login(e, o) {
    app.util.getUserInfo(function(e) {
        var o = {};
        "" != e.wxInfo && null != e.wxInfo ? (o = e.wxInfo).op = "userinfo" : o.op = "userinfo", 
        "" != app.scene && null != app.scene && (o.scene = app.scene), app.util.request({
            url: "entry/wxapp/index",
            showLoading: !1,
            data: o,
            success: function(e) {
                var o = e.data;
                "" != o.data && (app.userinfo = o.data);
            }
        });
    });
}

function g_footer(e) {
    var o = e.currentTarget.dataset.url;
    -1 < o.indexOf(",") ? (o = o.split(","), wx.navigateToMiniProgram({
        appId: o[0],
        path: o[1],
        success: function(e) {
            console.log(e);
        }
    })) : -1 < o.indexOf("$") ? "store" == (o = o.split("$"))[0] && (-1 == o[1] ? app.util.request({
        url: "entry/wxapp/user",
        showLoading: !1,
        data: {
            op: "userinfo"
        },
        success: function(e) {
            var o = e.data;
            "" != o.data && "" != o.data.store && null != o.data.store && wx.navigateTo({
                url: "../../pages/store/detail?&id=" + o.data.store + "&bind=false"
            });
        }
    }) : wx.navigateTo({
        url: "../../pages/store/detail?&id=" + o[1] + "&bind=false"
    })) : wx.reLaunch({
        url: o
    });
}

function call_mobile() {
    wx.makePhoneCall({
        phoneNumber: this.data.config.mobile
    });
}

function updateUserInfo(e) {
    var t = getApp(), n = this;
    "" != e.detail.userInfo && null != e.detail.userInfo && (t.util.getUserInfo(function(e) {
        var o = {};
        "" != e.wxInfo && null != e.wxInfo ? (o = e.wxInfo).op = "userinfo" : o.op = "userinfo", 
        t.util.request({
            url: "entry/wxapp/index",
            showLoading: !1,
            data: o,
            success: function(e) {
                var o = e.data;
                "" != o.data && (t.userinfo = o.data, n.setData({
                    userinfo: o.data
                }));
            }
        });
    }, e.detail), console.log(e));
}

function is_user(e) {
    var o = wx.getStorageSync("userInfo") || {};
    "" != o.wxInfo && null != o.wxInfo || e.setData({
        shadow: !0,
        get_userinfo: !0
    });
}

function user_close() {
    this.setData({
        shadow: !1,
        get_userinfo: !1
    });
}

module.exports = {
    config: config,
    theme: theme,
    login: login,
    g_footer: g_footer,
    call_mobile: call_mobile,
    updateUserInfo: updateUserInfo,
    is_user: is_user,
    user_close: user_close
};