var app = getApp(), common = require("../common/common.js"), WxParse = require("../../../wxParse/wxParse.js");

function GetRequest(n) {
    n = n;
    var t = new Object();
    if (-1 != n.indexOf("?")) for (var e = n.substr(1).split("&"), a = 0; a < e.length; a++) t[e[a].split("=")[0]] = unescape(e[a].split("=")[1]);
    return t;
}

Page({
    data: {},
    onLoad: function(a) {
        var o = this;
        common.config(o, app), common.theme(o, app);
        var n = GetRequest(unescape(a.url));
        "" != n.id && null != n.id && app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "article",
                id: n.id
            },
            success: function(n) {
                var t = n.data;
                if ("" != t.data && (o.setData({
                    list: t.data,
                    url: unescape(a.url)
                }), 2 == t.data.link_type)) {
                    var e = t.data.content;
                    WxParse.wxParse("content", "html", e, o, 5);
                }
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});