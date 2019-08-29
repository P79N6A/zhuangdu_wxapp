var t, e = getApp();

Page({
    data: {},
    onLoad: function(o) {
        t = this;
    },
    getUserInfo: function(o) {
        console.log(o.detail.userInfo), console.log(e.globalData.userid);
        try {
            wx.request({
                url: e.util.url("entry/wxapp/setinfo"),
                data: {
                    m: "lshd_civilized",
                    opt: "setuserinfo",
                    nickname: o.detail.userInfo.nickName,
                    headimg: o.detail.userInfo.avatarUrl,
                    ids: e.globalData.userid
                },
                success: function(o) {
                    console.log(o.data), "200" == o.data.code ? (e.globalData.userinfo = o.data.userinfo, 
                    e.globalData.userid = o.data.userinfo.id) : "100" == o.data.code && (e.globalData.userid = o.data.userinfo), 
                    wx.navigateBack({});
                }
            });
        } catch (o) {
            wx.showToast({
                title: "授权失败",
                icon: "none",
                duration: 2e3
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