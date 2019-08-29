var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request), _image = require("../../utils/image.js");

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
            banquan: app.globalData.banquan,
            advCode: app.globalData.member_Advert.member_set.advert_text,
            advshow: app.globalData.member_Advert.member_set.show
        }), this.myinfo();
    },
    Closeadvert: function() {
        this.setData({
            advshow: 0
        });
    },
    myinfo: function() {
        var t = this;
        _request2.default.get("member").then(function(e) {
            t.setData({
                UserImages: e.status.member_head_portrait,
                Name: e.status.member_name,
                memberid: e.status.member_id
            });
        });
    },
    chooseImage: function() {
        var t = this;
        (0, _image.chooseImage)().then(function(e) {
            return t.setData({
                UserImages: e
            }), (0, _image.upload)(e);
        }, function(e) {
            toast("选择图片失败");
        }).then(function(e) {
            t.setData({
                UserImages: e.url
            });
        }, function(e) {
            toast("图片上传失败");
        });
    },
    NameData: function(e) {
        this.setData({
            Name: e.detail.value
        });
    },
    Sub: function() {
        var e = {
            type: "update",
            nickName: this.data.Name,
            avatarUrl: this.data.UserImages
        };
        _request2.default.post("UpdateMember", e).then(function(e) {
            e ? wx.navigateBack({
                delta: 1
            }) : wx.showToast({
                title: "保存失败",
                icon: "none"
            });
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    copyright: function() {
        wx.navigateTo({
            url: "../copyright/copyright"
        });
    },
    fanhui: function() {
        wx.navigateBack({
            delta: 1
        });
    }
});