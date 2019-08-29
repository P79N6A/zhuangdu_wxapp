var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(t) {
    return t && t.__esModule ? t : {
        default: t
    };
}

var app = getApp();

Page({
    data: {
        photoWidth: wx.getSystemInfoSync().windowWidth / 3.3,
        w: wx.getSystemInfoSync().windowWidth
    },
    onLoad: function(t) {
        console.log(t);
        var e = this;
        _request2.default.get("Content", {
            content_id: t.id,
            type: "take"
        }).then(function(t) {
            t.create_time = e.getDateDiff(1e3 * t.create_time), console.log(t), e.setData({
                contentData: t
            });
        }), _request2.default.get("index").then(function(t) {
            app.globalData.title = JSON.parse(t.system.system).title, app.globalData.topimg = t.system.system_content.share_bg, 
            app.globalData.toptext = JSON.parse(t.system.system).share_txt, app.globalData.day_sign_status = JSON.parse(t.system.system).day_sign_status, 
            app.globalData.banquan = JSON.parse(t.system.system).copyright, app.globalData.class = t.class, 
            app.globalData.member_Advert = t.member_advert;
            var e = {
                memberImg: t.member.member_head_portrait,
                memberName: t.member.member_name,
                memberId: t.member.member_id,
                memberAdmin: t.member.is_system
            };
            app.globalData.member = e;
        });
    },
    onReady: function() {},
    onShow: function() {},
    onShareAppMessage: function() {
        var e = this, t = {
            types: "fx",
            id: e.data.contentData.content_id
        };
        return _request2.default.post("operation", t).then(function(t) {
            e.data.contentData.sharenb = parseInt(e.data.contentData.sharenb) + 1, e.setData({
                contentData: e.data.contentData
            });
        }), {
            title: e.data.contentData.text.slice(0, 20) + "...",
            imageUrl: "img" == e.data.contentData.type ? e.data.contentData.content[0] : app.globalData.topimg,
            path: "myxs_fodder/pages/textInfo/textInfo?id=" + e.data.contentData.content_id
        };
    },
    ReturnNew: function() {
        console.log("a"), wx.redirectTo({
            url: "../index/index"
        });
    },
    download: function(t) {
        var e = this, a = e.data.contentData.content_id, n = e.data.contentData.type;
        wx.getSetting({
            success: function(t) {
                "img" == n ? e.downImg() : "video" == n && e.downVideo();
            }
        }), wx.setClipboardData({
            data: e.data.contentData.text
        });
        var o = {
            types: "xz",
            id: a
        };
        _request2.default.post("operation", o).then(function(t) {
            e.data.contentData.donnb = parseInt(e.data.contentData.donnb) + 1, e.setData({
                contentData: e.data.contentData
            });
        });
    },
    downVideo: function() {
        wx.showLoading({
            title: "视频下载中..."
        }), wx.downloadFile({
            url: this.data.contentData.content[0],
            success: function(t) {
                wx.saveVideoToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function(t) {
                        wx.showToast({
                            title: "下载成功"
                        });
                    },
                    fail: function() {
                        wx.showToast({
                            title: "下载以取消"
                        });
                    },
                    complete: function() {
                        wx.hideLoading();
                    }
                });
            }
        });
    },
    downImg: function() {
        var e = 0, a = this;
        for (var t in a.data.contentData.content) wx.downloadFile({
            url: a.data.contentData.content[t],
            success: function(t) {
                wx.saveImageToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function(t) {
                        ++e == a.data.contentData.content.length ? wx.showToast({
                            title: e + "/" + a.data.contentData.content.length,
                            duration: 500
                        }) : wx.showToast({
                            title: e + "/" + a.data.contentData.content.length,
                            duration: 5e3
                        });
                    },
                    fail: function(t) {
                        wx.showToast({
                            title: "下载已取消"
                        });
                    },
                    complete: function(t) {}
                });
            },
            fail: function(t) {}
        });
    },
    TextCopi: function(t) {
        wx.setClipboardData({
            data: this.data.contentData.text
        });
    },
    previewImage: function(t) {
        console.log(t);
        var e = t.target.dataset.src;
        wx.previewImage({
            current: e,
            urls: this.data.contentData.content
        });
    },
    CollectionL: function(t) {
        var e = this, a = {
            types: "sz",
            id: e.data.contentData.content_id
        };
        _request2.default.post("operation", a).then(function(t) {
            e.data.contentData.clstate = t.status, t.status ? e.data.contentData.clnb = parseInt(e.data.contentData.clnb) + 1 : e.data.contentData.clnb = parseInt(e.data.contentData.clnb) - 1, 
            e.setData({
                contentData: e.data.contentData
            });
        });
    },
    getDateDiff: function(t) {
        var e, a = 864e5, n = new Date().getTime(), o = new Date(t), s = n - t;
        if (!(s < 0)) {
            var i = s / a, c = s / 36e5, r = s / 6e4;
            if (1 <= i) if (parseInt(i) < 2) e = "昨天"; else {
                var d = o.getMonth() + 1 < 10 ? "0" + (o.getMonth() + 1) : o.getMonth() + 1, l = o.getDate() < 10 ? "0" + o.getDate() : o.getDate();
                o.getHours(), o.getMinutes(), o.getSeconds();
                e = d + "月" + l + "日";
            } else e = 1 <= c ? parseInt(c) + "小时前" : 1 <= r ? parseInt(r) + "分钟前" : "刚刚";
            return e;
        }
    }
});