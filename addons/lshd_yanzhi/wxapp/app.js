var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
    return typeof t;
} : function(t) {
    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t;
};

App({
    onLaunch: function() {
        console.log(wx.getSystemInfoSync());
    },
    util: require("we7/resource/js/util.js"),
    globalData: {
        userinfo: null,
        userid: 0,
        otherid: 0,
        appinfo: "",
        datealls: "",
        shujufanhui: null,
        wecatimg: "",
        menu: null,
        sysInfo: wx.getSystemInfoSync()
    },
    sharenum: function() {
        wx.request({
            url: this.seturl("entry/wxapp/upshare", {
                m: "lshd_yanzhi"
            }),
            data: {
                ids: this.globalData.userid
            },
            success: function(t) {
                console.log(t);
            }
        });
    },
    sharevisible: function(t) {
        wx.request({
            url: this.seturl("entry/wxapp/sharevisible", {
                m: "lshd_yanzhi"
            }),
            data: {
                ids: t
            },
            success: function(t) {
                console.log(t);
            }
        });
    },
    shareapply: function() {
        wx.request({
            url: this.seturl("entry/wxapp/shareapply", {
                m: "lshd_yanzhi"
            }),
            data: {
                ids: this.globalData.userid
            },
            success: function(t) {
                console.log(t);
            }
        });
    },
    setformid: function(t) {
        wx.request({
            url: this.seturl("entry/wxapp/setformid", {
                m: "lshd_yanzhi"
            }),
            data: {
                formid: t,
                ids: this.globalData.userid
            },
            success: function(t) {
                return t.data;
            }
        });
    },
    seturl: function(t, s) {
        var e = this.siteInfo.siteroot + "?i=" + this.siteInfo.uniacid + "&t=" + this.siteInfo.multiid + "&v=" + this.siteInfo.version + "&from=wxapp&";
        if (t && ((t = t.split("/"))[0] && (e += "c=" + t[0] + "&"), t[1] && (e += "a=" + t[1] + "&"), 
        t[2] && (e += "do=" + t[2] + "&")), s && "object" === (void 0 === s ? "undefined" : _typeof(s))) for (var o in s) o && s.hasOwnProperty(o) && s[o] && (e += o + "=" + s[o] + "&");
        return e;
    },
    siteInfo: require("siteinfo.js")
});