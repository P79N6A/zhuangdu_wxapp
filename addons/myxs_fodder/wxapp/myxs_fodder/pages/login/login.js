var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

var app = getApp();

Page({
    data: {},
    onLoad: function(e) {
        this.setData({
            topimg: app.globalData.topimg
        });
    },
    fanhui: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    uploadUserinfo: function(e) {
        var a = getApp();
        if (console.log(e), "getUserInfo:ok" != e.detail.errMsg) wx.navigateBack({
            delta: 1
        }); else {
            a.globalData.member.memberImg = e.detail.userInfo.avatarUrl, a.globalData.member.memberName = e.detail.userInfo.nickName;
            var t = {
                avatarUrl: e.detail.userInfo.avatarUrl,
                nickName: e.detail.userInfo.nickName
            };
            console.log(t, "DaTaShow"), _request2.default.get("UpdateMember", t).then(function(e) {
                e ? wx.redirectTo({
                    url: "../My/My"
                }) : wx.showToast({
                    title: "未知错误",
                    icon: "none"
                });
            });
        }
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});