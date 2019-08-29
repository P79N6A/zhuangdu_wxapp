var _image = require("../../utils/image.js"), _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

var app = getApp(), siteroot = app.siteInfo.siteroot, upurl = (siteroot = siteroot.replace("app/index.php", "web/index.php")) + "?i=" + app.siteInfo.uniacid + "&c=utility&a=file&do=upload&thumb=0";

Page({
    data: {
        accounts: [ "请选分类" ],
        fileType: "all",
        accountIndex: 0,
        files: [],
        szie: 9,
        addbl: 1,
        ShowBl: !0
    },
    Closeadvert: function() {
        this.setData({
            advshow: 0
        });
    },
    onLoad: function(e) {
        this.setData({
            type: "system",
            advCode: app.globalData.member_Advert.member.advert_text,
            advshow: app.globalData.member_Advert.member.show
        }), this.categoryDataQuery();
    },
    NextF: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    categoryDataQuery: function() {
        var s = this;
        _request2.default.get("listClass").then(function(e) {
            var t = [], a = [];
            for (var i in e) t[i] = e[i].class_name, a[i] = e[i].class_id;
            s.setData({
                accounts: t,
                accountsid: a,
                class_id: a[0]
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
    bindAccountChange: function(e) {
        var t = this.data.accountsid[e.detail.value];
        this.setData({
            accountIndex: e.detail.value,
            class_id: t
        });
    },
    chooseImage: function(e) {
        var t = this;
        "all" == t.data.fileType ? wx.showActionSheet({
            itemList: [ "照片", "视频" ],
            success: function(e) {
                0 === e.tapIndex ? t.SaveImg() : 1 === e.tapIndex && t.SaveVideo();
            }
        }) : "img" == t.data.fileType && t.SaveImg();
    },
    SaveImg: function() {
        var a = this;
        wx.chooseImage({
            count: a.data.szie,
            sizeType: [ "original" ],
            sourceType: [ "album", "camera" ],
            success: function(e) {
                for (var t in a.data.szie = a.data.szie - e.tempFilePaths.length, a.setData({
                    fileType: "img",
                    szie: a.data.szie
                }), 0 == a.data.szie && a.setData({
                    addbl: 0,
                    gifH: 264
                }), e.tempFilePaths) a.uploadfile(e.tempFilePaths[t]);
            }
        });
    },
    SaveVideo: function(e) {
        var t = this;
        wx.chooseVideo({
            compressed: !1,
            maxDuration: 60,
            sourceType: [ "album" ],
            success: function(e) {
                t.setData({
                    fileType: "video",
                    addbl: 0,
                    videoW: 300,
                    videoh: 225,
                    duration: parseInt(e.duration),
                    yulanvideo: e.tempFilePath
                }), t.uploadfile(e.tempFilePath);
            }
        });
    },
    uploadfile: function(e) {
        var t = this, a = "";
        "video" == t.data.fileType && (a = "video"), wx.showLoading({
            title: "上传中...请稍等",
            mask: !0
        }), wx.uploadFile({
            url: upurl + "&upload_type=" + a,
            filePath: e,
            name: "file",
            success: function(e) {
                null != JSON.parse(e.data).message && wx.showToast({
                    title: JSON.parse(e.data).message
                }), t.setData({
                    files: t.data.files.concat(JSON.parse(e.data).url)
                });
            }
        }).onProgressUpdate(function(e) {
            console.log(e.progress), 100 == e.progress && wx.hideLoading();
        });
    },
    previewImage: function(e) {
        wx.previewImage({
            current: e.currentTarget.id,
            urls: this.data.files
        });
    },
    TextData: function(e) {
        this.setData({
            textData: e.detail.value
        });
    },
    ShowBl: function(e) {
        this.setData({
            ShowBl: e.detail.value
        });
    },
    Release: function() {
        var e = this;
        if ("video" == this.data.fileType && (e.data.files[1] = e.data.videoW, e.data.files[2] = e.data.videoh, 
        e.setData({
            files: e.data.files
        })), null == this.data.textData || "" == this.data.textData) return wx.showToast({
            title: "内容不能为空",
            icon: "none"
        }), !1;
        if (this.data.files.length < 1) return wx.showToast({
            title: "请至少上传一张图片",
            icon: "none"
        }), !1;
        var t = {
            textDat: this.data.textData,
            files: this.data.files.toString(),
            class_id: this.data.class_id,
            ShowBl: this.data.ShowBl,
            file_type: this.data.fileType
        };
        console.log(t), _request2.default.post("Content", t).then(function(e) {
            e ? wx.redirectTo({
                url: "../index/index"
            }) : wx.showToast({
                title: "未知错误",
                icon: "none"
            });
        });
    }
});