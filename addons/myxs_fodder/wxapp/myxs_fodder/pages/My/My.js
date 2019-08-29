var app = getApp();

Page({
    data: {
        isAdmin: 0,
        banquan: app.globalData.banquan
    },
    onLoad: function(a) {
        this.setData({
            banquan: app.globalData.banquan,
            advCode: app.globalData.member_Advert.member.advert_text,
            advshow: app.globalData.member_Advert.member.show,
            adv: "adunit-025a2ed6a2bac2fb"
        }), this.myinfo();
    },
    Closeadvert: function() {
        this.setData({
            advshow: 0
        });
    },
    myinfo: function() {
        this.setData({
            memberImg: app.globalData.member.memberImg,
            name: app.globalData.member.memberName,
            memberid: app.globalData.member.memberId,
            isAdmin: app.globalData.member.memberAdmin
        });
    },
    MyRelease: function() {
        wx.navigateTo({
            url: "../MyRelease/MyRelease"
        });
    },
    MySet: function() {
        wx.navigateTo({
            url: "../MySet/MySet"
        });
    },
    onShow: function() {
        this.myinfo();
    },
    copyright: function() {
        wx.navigateTo({
            url: "../copyright/copyright"
        });
    },
    fanhui: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    Activation: function() {
        wx.navigateTo({
            url: "../Activation/Activation"
        });
    }
});