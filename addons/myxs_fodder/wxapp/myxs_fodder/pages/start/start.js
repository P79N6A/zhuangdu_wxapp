var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

var t, app = getApp();

Page({
    data: {
        tst: "立即进入",
        code: "",
        HttpBl: 0
    },
    onLoad: function(e) {
        var s = this;
        _request2.default.get("index").then(function(e) {
            app.globalData.title = JSON.parse(e.system.system).title, app.globalData.topimg = e.system.system_content.share_bg, 
            app.globalData.toptext = JSON.parse(e.system.system).share_txt, app.globalData.day_sign_status = JSON.parse(e.system.system).day_sign_status, 
            app.globalData.banquan = JSON.parse(e.system.system).copyright, app.globalData.class = e.class, 
            app.globalData.member_Advert = e.member_advert;
            var a = {
                memberImg: e.member.member_head_portrait,
                memberName: e.member.member_name,
                memberId: e.member.member_id,
                memberAdmin: e.member.is_system
            };
            if (app.globalData.member = a, "" == e.system.system_content.stat_bg || null == e.system.system_content.stat_bg) return wx.redirectTo({
                url: "../index/index"
            }), !1;
            t = setTimeout(function() {
                wx.redirectTo({
                    url: "../index/index"
                });
            }, 2500), s.setData({
                jingruimg: e.system.system_content.stat_bg,
                HttpBl: 1,
                Banquan: JSON.parse(e.system.system).copyright
            });
        });
    },
    index: function() {
        clearInterval(t), wx.redirectTo({
            url: "../index/index"
        });
    }
});