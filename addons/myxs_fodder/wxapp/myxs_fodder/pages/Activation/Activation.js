var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

Page({
    data: {},
    vfCode: function(e) {
        this.setData({
            Code: e.detail.value
        });
    },
    ReturnNew: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    submt: function() {
        console.log(this.data.Code);
        var e = {
            code: this.data.Code
        };
        _request2.default.post("CheckGrouping", e).then(function(e) {
            console.log(e), e.status ? wx.showModal({
                content: "恭喜您验证成功",
                showCancel: !1,
                confirmText: "确定",
                confirmColor: "#ff4081",
                success: function(e) {
                    wx.reLaunch({
                        url: "../index/index"
                    });
                }
            }) : wx.showModal({
                content: "验证失败,请重新提交,或联系管理员.",
                showCancel: !1,
                confirmText: "确定",
                confirmColor: "#ff4081"
            });
        });
    }
});