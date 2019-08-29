var app = getApp();

function config(e) {
    var o = [ {
        pagePath: "../index/index",
        text: "首页",
        iconPath: "../../resource/footer01.png",
        selectedIconPath: "../../resource/footer001.png",
        status: 1
    }, {
        pagePath: "../service/service",
        text: "优惠套餐",
        iconPath: "../../resource/footer02.png",
        selectedIconPath: "../../resource/footer002.png",
        status: 1
    }, {
        pagePath: "../rotate/rotate",
        text: "抽奖",
        iconPath: "../../resource/footer03.png",
        selectedIconPath: "../../resource/footer03.png",
        status: 1
    }, {
        pagePath: "../store/porder",
        text: "预约",
        iconPath: "../../resource/footer07.png",
        selectedIconPath: "../../resource/footer007.png",
        status: 1
    }, {
        pagePath: "../user/user",
        text: "我的",
        iconPath: "../../resource/footer05.png",
        selectedIconPath: "../../resource/footer005.png",
        status: 1
    } ], n = "";
    if ("" != app.config && null != app.config) {
        if (wx.setNavigationBarTitle({
            title: app.config.content.title
        }), "" != (n = app.config.content).footer && null != n.footer) for (var t = 0; t < n.footer.length; t++) "" != n.footer[t].text && null != n.footer[t].text && (o[t].text = n.footer[t].text), 
        "" != n.footer[t].icon && null != n.footer[t].icon && (o[t].iconPath = n.footer[t].icon), 
        "" != n.footer[t].select && null != n.footer[t].select && (o[t].selectedIconPath = n.footer[t].select), 
        "" != n.footer[t].link && null != n.footer[t].link && (o[t].pagePath = n.footer[t].link), 
        "" != n.footer[t].status && null != n.footer[t].status && (o[t].status = n.footer[t].status);
        "" != app.model && null != app.model && (n.model = app.model);
    }
    for (t = 0; t < o.length; t++) e.data.pagePath == o[t].pagePath && 1 == o[t].status && e.setData({
        footerCurr: t + 1
    });
    e.g_footer = g_footer, e.call_mobile = call_mobile, e.updateUserInfo = updateUserInfo, 
    is_user(e), e.user_close = user_close, e.setData({
        footer: o,
        config: n
    });
}

function theme(e) {
    var o = {
        name: "theme1",
        color: "#e74479",
        icon: [ "../../resource/icon01.png", "../../resource/icon02.png", "../../resource/icon03.png", "../../resource/icon04.png", "../../resource/icon05.png", "../../resource/icon06.png", "../../resource/icon07.png", "../../resource/icon08.png", "../../resource/icon09.png", "../../resource/icon10.png", "../../resource/icon11.png", "../../resource/icon12.png", "../../resource/icon13.png", "../../resource/icon14.png", "../../resource/icon15.png", "../../resource/icon16.png", "../../resource/icon17.png", "../../resource/icon18.png", "../../resource/icon19.png", "../../resource/icon20.png", "../../resource/icon21.png", "../../resource/icon22.png", "../../resource/icon23.png", "../../resource/icon24.png", "../../resource/icon25.png", "../../resource/icon26.png", "../../resource/icon28.png", "../../resource/icon30.png", "../../resource/group03.png", "../../resource/icon27.png", "../../resource/icon29.png" ]
    };
    if ("" != app.theme && null != app.theme) {
        var n = app.theme.content;
        if (2 == n.theme) {
            if (o.name = "theme" + n.theme, o.color = n.color, "" != n.icon && null != n.icon) for (var t = 0; t < n.icon.length; t++) "" != n.icon[t] && null != n.icon[t] && (o.icon[t] = n.icon[t]);
        } else 3 == n.theme && (o.name = "theme3", o.color = "#444444");
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
                url: "../store/detail?&id=" + o.data.store + "&bind=false"
            });
        }
    }) : wx.navigateTo({
        url: "../store/detail?&id=" + o[1] + "&bind=false"
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
    var n = getApp(), t = this;
    "" != e.detail.userInfo && null != e.detail.userInfo && (n.util.getUserInfo(function(e) {
        var o = {};
        "" != e.wxInfo && null != e.wxInfo ? (o = e.wxInfo).op = "userinfo" : o.op = "userinfo", 
        "" != n.scene && null != n.scene && (o.scene = n.scene), n.util.request({
            url: "entry/wxapp/index",
            showLoading: !1,
            data: o,
            success: function(e) {
                var o = e.data;
                "" != o.data && (n.userinfo = o.data, t.setData({
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