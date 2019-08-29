var _Page, _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(t) {
    return t && t.__esModule ? t : {
        default: t
    };
}

function _defineProperty(t, a, e) {
    return a in t ? Object.defineProperty(t, a, {
        value: e,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[a] = e, t;
}

var urlCommon = /.*\/attachment\/([0-9]*$)/;

Page((_defineProperty(_Page = {
    data: {
        leng2: wx.getSystemInfoSync().windowWidth / 3,
        photoWidth: wx.getSystemInfoSync().windowWidth / 5,
        judi: wx.getSystemInfoSync().windowHeight - 80
    },
    onLoad: function(t) {
        this.MyCollectionShow();
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    ReturnNew: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    MyCollectionShow: function() {
        var i = this;
        _request2.default.get("DownloadContentList", {}).then(function(t) {
            var a = [];
            if (0 == t.length) return i.setData({
                dataNull: 0
            }), !1;
            for (var e in i.setData({
                dataNull: 1
            }), t) t[e].isplay = 0, t[e].create_time = i.getDateDiff(1e3 * t[e].create_time), 
            55 < t[e].text.length ? (t[e].textTypeL = 1, t[e].textShow = 1, t[e].ShowBtn = "展开") : t[e].textTypeL = 0, 
            a = a.concat(t[e]);
            t[0].isplay = 1, i.setData({
                dataList: a
            });
        });
    },
    DataTextTooger: function(t) {
        var a = t.currentTarget.dataset.l;
        0 == this.data.dataList[a].textShow ? (this.data.dataList[a].textShow = 1, this.data.dataList[a].ShowBtn = "展开") : (this.data.dataList[a].textShow = 0, 
        this.data.dataList[a].ShowBtn = "收起"), this.setData({
            dataList: this.data.dataList
        });
    },
    CollectionL: function(t) {
        var a = this, e = t.currentTarget.dataset.index, i = {
            types: "sz",
            id: t.currentTarget.id
        };
        _request2.default.post("operation", i).then(function(t) {
            a.data.dataList[e].clstate = t.status, t.status ? a.data.dataList[e].clnb = parseInt(a.data.dataList[e].clnb) + 1 : a.data.dataList[e].clnb = parseInt(a.data.dataList[e].clnb) - 1, 
            a.setData({
                dataList: a.data.dataList
            });
        });
    },
    previewImage: function(t) {
        var a = t.target.dataset.src, e = t.target.dataset.index, i = [];
        for (var n in this.data.dataList[e].content) i = i.concat(this.data.dataList[e].content[n]);
        wx.previewImage({
            current: a,
            urls: i
        });
    },
    download: function(t) {
        var a = this, e = t.currentTarget.dataset.index, i = t.currentTarget.id, n = t.currentTarget.dataset.type;
        wx.getSetting({
            success: function(t) {
                "img" == n ? a.downImg(e) : "video" == n && a.downVideo(e);
            }
        });
        var o = {
            types: "xz",
            id: i
        };
        _request2.default.post("operation", o).then(function(t) {
            a.data.dataList[e].donnb = parseInt(a.data.dataList[e].donnb) + 1, a.setData({
                dataList: a.data.dataList
            });
        }), wx.setClipboardData({
            data: a.data.dataList[e].text
        });
    },
    downVideo: function(t) {
        wx.showLoading({
            title: "视频下载中..."
        }), wx.downloadFile({
            url: this.data.dataList[t].content[0],
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
    downImg: function(a) {
        var e = 0, i = this;
        for (var t in i.data.dataList[a].content) wx.downloadFile({
            url: i.data.dataList[a].content[t],
            success: function(t) {
                wx.saveImageToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function(t) {
                        ++e == i.data.dataList[a].content.length ? wx.showToast({
                            title: e + "/" + i.data.dataList[a].content_siz,
                            duration: 500
                        }) : wx.showToast({
                            title: e + "/" + i.data.dataList[a].content_siz,
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
        var a = t.currentTarget.dataset.index;
        wx.setClipboardData({
            data: this.data.dataList[a].text
        });
    },
    getDateDiff: function(t) {
        var a, e = 864e5, i = new Date().getTime(), n = new Date(t), o = i - t;
        if (!(o < 0)) {
            var s = o / e, d = o / 36e5, r = o / 6e4;
            if (1 <= s) if (parseInt(s) < 2) a = "昨天"; else {
                var c = n.getMonth() + 1 < 10 ? "0" + (n.getMonth() + 1) : n.getMonth() + 1, l = n.getDate() < 10 ? "0" + n.getDate() : n.getDate();
                n.getHours(), n.getMinutes(), n.getSeconds();
                a = c + "月" + l + "日";
            } else a = 1 <= d ? parseInt(d) + "小时前" : 1 <= r ? parseInt(r) + "分钟前" : "刚刚";
            return a;
        }
    }
}, "onShareAppMessage", function(t) {
    var a = this;
    if ("button" == t.from) {
        var e = t.target.dataset.min, i = t.target.dataset.index, n = {
            types: "fx",
            id: t.target.dataset.id
        };
        return _request2.default.post("operation", n).then(function(t) {
            a.data.dataList[e].content[i].sharenb = parseInt(a.data.dataList[e].content[i].sharenb) + 1, 
            a.setData({
                dataList: a.data.dataList
            });
        }), {
            title: a.data.dataList[e].content[i].text.slice(0, 20) + "...",
            imageUrl: "img" == a.data.dataList[e].content[i].type ? a.data.dataList[e].content[i].content[0] : app.globalData.topimg,
            path: "myxs_fodder/pages/textInfo/textInfo?id=" + t.target.dataset.id
        };
    }
    return {
        title: app.globalData.toptext,
        imageUrl: app.globalData.topimg,
        path: "myxs_fodder/pages/start/start"
    };
}), _defineProperty(_Page, "onReachBottom", function(t) {
    var a = this.data.dataList.length - 1, e = {
        type: "jz",
        log_id: this.data.dataList[a].log_id
    }, i = this;
    _request2.default.get("DownloadContentList", e).then(function(t) {
        if (0 === t.length) return wx.showToast({
            title: "加载已完成"
        }), !1;
        for (var a in t) t[a].isplay = 0, t[a].create_time = i.getDateDiff(1e3 * t[a].create_time), 
        55 < t[a].text.length ? (t[a].textTypeL = 1, t[a].textShow = 1, t[a].ShowBtn = "展开") : t[a].textTypeL = 0;
        i.setData({
            dataList: i.data.dataList.concat(t)
        });
    });
}), _defineProperty(_Page, "plays", function(t) {
    var a = this, e = t.currentTarget.dataset.index;
    for (var i in a.data.dataList) a.data.dataList[i].isplay = 0;
    a.data.dataList[e].isplay = 1, a.setData({
        dataList: a.data.dataList
    }), wx.createVideoContext("Myplay" + e).play();
}), _defineProperty(_Page, "onPageScroll", function(t) {
    var e = this;
    wx.createSelectorQuery().selectAll(".TextimgList").boundingClientRect(function(t) {
        for (var a in t) {
            if (t[a].top < 240 && 110 < t[a].top && t[a].bottom < e.data.judi) {
                if (e.data.dataList[a].isplay = 1, "video" == e.data.dataList[a].type) wx.createVideoContext("Myplay" + a).play();
            } else e.data.dataList[a].isplay = 0;
            e.setData({
                dataList: e.data.dataList
            });
        }
    }).exec(), this.setData({
        zTop: t.scrollTop
    });
}), _defineProperty(_Page, "clickPlay", function(t) {
    for (var a in this.data.dataList) if ("video" == this.data.dataList[a].type) {
        var e = wx.createVideoContext("Myplay" + a);
        "Myplay" + a != t.currentTarget.id && e.pause();
    }
}), _Page));