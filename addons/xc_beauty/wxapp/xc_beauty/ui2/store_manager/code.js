var app = getApp(), common = require("../common/common.js");

Page({
    data: {},
    previewImage: function() {
        wx.previewImage({
            current: this.data.share,
            urls: [ this.data.share ]
        });
    },
    saveImageToPhotosAlbum: function() {
        var t = this.data.share;
        "" != t && null != t ? (wx.showLoading({
            title: "保存中"
        }), wx.downloadFile({
            url: t,
            success: function(t) {
                wx.saveImageToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function(t) {
                        console.log(t), wx.hideLoading(), wx.showToast({
                            title: "保存成功",
                            icon: "success",
                            duration: 2e3
                        });
                    },
                    fail: function(t) {
                        wx.hideLoading(), wx.showToast({
                            title: "保存失败",
                            icon: "none",
                            duration: 2e3
                        });
                    }
                });
            }
        })) : wx.showModal({
            title: "错误",
            content: "保存图片失败"
        });
    },
    onLoad: function(t) {
        var e = this;
        common.config(e), common.theme(e), e.setData({
            store_id: t.store_id
        }), app.util.request({
            url: "entry/wxapp/manage",
            data: {
                op: "store_detail",
                id: t.store_id
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && "" != a.data.store && null != a.data.store && wx.setNavigationBarTitle({
                    title: a.data.store.name
                });
            }
        }), app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "store_detail",
                id: t.store_id
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && e.setData({
                    list: a.data
                });
            }
        }), app.util.request({
            url: "entry/wxapp/admincode",
            data: {
                id: t.store_id
            },
            success: function(t) {
                var a = t.data;
                "" != a.data && e.setData({
                    share: a.data.share
                });
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