var _request = require("../../utils/request.js"), _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(t) {
    return t && t.__esModule ? t : {
        default: t
    };
}

var urlCommon = /.*\/attachment\/([0-9]*$)/, app = getApp();

Page({
    data: {
        leng2: wx.getSystemInfoSync().windowWidth / 3,
        photoWidth: wx.getSystemInfoSync().windowWidth / 5,
        dataNull: 2,
        isAdmin: 0,
        judi: wx.getSystemInfoSync().windowHeight - 80,
        dataList: []
    },
    onLoad: function() {
        var e = this;
        this.dayObtain(), wx.getStorage({
            key: "Day",
            success: function(t) {
                var a = new Date().getDate();
                ("" == t.data || t.data < a) && e.setData({
                    dot: 1
                });
            },
            complete: function(t) {
                "getStorage:fail data not found" == t.errMsg && e.setData({
                    dot: 1
                });
            }
        }), e.setData({
            tatle: app.globalData.title,
            topimg: app.globalData.topimg,
            name: app.globalData.member.memberName,
            isAdmin: app.globalData.member.memberAdmin,
            isday: app.globalData.day_sign_status
        });
    },
    onShow: function() {
        wx.showShareMenu({
            withShareTicket: !0
        });
        this.categoryDataQuery(), wx.pageScrollTo({
            scrollTop: this.data.zTop,
            duration: 0
        });
    },
    clickPlay: function(t) {
        for (var a in this.data.dataList) if ("video" == this.data.dataList[a].type) {
            var e = wx.createVideoContext("Myplay" + a);
            "Myplay" + a != t.currentTarget.id && e.pause();
        }
    },
    onPageScroll: function(t) {
        var e = this;
        wx.createSelectorQuery().selectAll(".TextimgList").boundingClientRect(function(t) {
            for (var a in t) {
                if (t[a].top < 240 && 110 < t[a].top && t[a].bottom < e.data.judi) {
                    if (e.data.dataList[t[a].dataset.min].content[t[a].dataset.index].isplay = 1, e.setData({
                        dataList: e.data.dataList
                    }), "video" == e.data.dataList[t[a].dataset.min].content[t[a].dataset.index].type) wx.createVideoContext("Myplay" + a).play();
                } else if (e.data.dataList[t[a].dataset.min].content[t[a].dataset.index].isplay = 0, 
                e.setData({
                    dataList: e.data.dataList
                }), "video" == e.data.dataList[t[a].dataset.min].content[t[a].dataset.index].type) wx.createVideoContext("Myplay" + a).pause();
            }
        }).exec(), this.setData({
            zTop: t.scrollTop
        });
    },
    previewImage: function(t) {
        var a = t.target.dataset.src, e = t.currentTarget.dataset.min, n = t.target.dataset.index, i = [];
        for (var s in this.data.dataList[e].content[n].content) i = i.concat(this.data.dataList[e].content[n].content[s]);
        wx.previewImage({
            current: a,
            urls: i
        });
    },
    CollectionL: function(t) {
        var a = this, e = t.currentTarget.dataset.min, n = t.currentTarget.dataset.index, i = {
            types: "sz",
            id: t.currentTarget.id
        };
        _request2.default.post("operation", i).then(function(t) {
            a.data.dataList[e].content[n].clstate = t.status, t.status ? a.data.dataList[e].content[n].clnb = parseInt(a.data.dataList[e].content[n].clnb) + 1 : a.data.dataList[e].content[n].clnb = parseInt(a.data.dataList[e].content[n].clnb) - 1, 
            a.setData({
                dataList: a.data.dataList
            });
        });
    },
    Refresh: function() {
        this.categoryDataQuery(), this.setData({
            menuleft: 0
        });
    },
    Myshow: function(t) {
        "" == this.data.name ? wx.navigateTo({
            url: "../login/login"
        }) : wx.navigateTo({
            url: "../My/My"
        });
    },
    Release: function(t) {
        wx.navigateTo({
            url: "../Release/Release"
        });
    },
    Daysign: function(t) {
        var a = new Date().getDate();
        wx.setStorage({
            key: "Day",
            data: a
        }), this.setData({
            dot: 0
        }), wx.navigateTo({
            url: "../Daysign/Daysign"
        });
    },
    DataTextTooger: function(t) {
        var a = t.currentTarget.dataset.min, e = t.currentTarget.dataset.l;
        0 == this.data.dataList[a].content[e].textShow ? (this.data.dataList[a].content[e].textShow = 1, 
        this.data.dataList[a].content[e].ShowBtn = "展开") : (this.data.dataList[a].content[e].textShow = 0, 
        this.data.dataList[a].content[e].ShowBtn = "收起"), this.setData({
            dataList: this.data.dataList
        });
    },
    scrollSelect: function(t) {
        this.setData({
            menubl: t.currentTarget.id
        }), this.setData({
            menuleft: t.currentTarget.offsetLeft - 100
        }), this.dataQuery(t.currentTarget.id), this.setData({
            class_id: t.currentTarget.id
        }), wx.pageScrollTo({
            scrollTop: 0,
            duration: 0
        });
    },
    categoryDataQuery: function() {
        this.setData({
            menulist: app.globalData.class,
            menubl: app.globalData.class[0].class_id,
            class_id: app.globalData.class[0].class_id
        }), this.dataQuery(app.globalData.class[0].class_id);
    },
    dataQuery: function(t) {
        var n = this;
        _request2.default.get("listcontent", {
            class_id: t
        }).then(function(t) {
            if (console.log(t), 0 == t.content.length) return n.setData({
                dataNull: 0
            }), !1;
            for (var a in n.setData({
                dataNull: 1
            }), t.content) t.content[a].isplay = 0, t.content[a].create_time = n.getDateDiff(1e3 * t.content[a].create_time), 
            55 < t.content[a].text.length ? (t.content[a].textTypeL = 1, t.content[a].textShow = 1, 
            t.content[a].ShowBtn = "展开") : t.content[a].textTypeL = 0;
            t.content[0].isplay = 1;
            var e = [ t ];
            n.setData({
                dataList: e
            });
        });
    },
    Closeadvert: function(t) {
        var a = t.currentTarget.dataset.i;
        this.data.dataList[a].advert.show = 0, this.setData({
            dataList: this.data.dataList
        });
    },
    download: function(t) {
        var a = this, e = t.currentTarget.dataset.min, n = t.currentTarget.dataset.index, i = t.currentTarget.id, s = t.currentTarget.dataset.type;
        wx.getSetting({
            success: function(t) {
                "img" == s ? a.downImg(e, n) : "video" == s && a.downVideo(e, n);
            }
        }), wx.setClipboardData({
            data: a.data.dataList[e].content[n].text
        });
        var o = {
            types: "xz",
            id: i
        };
        _request2.default.post("operation", o).then(function(t) {
            a.data.dataList[e].content[n].donnb = parseInt(a.data.dataList[e].content[n].donnb) + 1, 
            a.setData({
                dataList: a.data.dataList
            });
        });
    },
    downVideo: function(t, a) {
        wx.showLoading({
            title: "视频下载中..."
        }), wx.downloadFile({
            url: this.data.dataList[t].content[a].content[0],
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
    downImg: function(a, e) {
        var n = 0, i = this;
        for (var t in i.data.dataList[a].content[e].content) wx.downloadFile({
            url: i.data.dataList[a].content[e].content[t],
            success: function(t) {
                wx.saveImageToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function(t) {
                        ++n == i.data.dataList[a].content[e].content.length ? wx.showToast({
                            title: n + "/" + i.data.dataList[a].content[e].content_siz,
                            duration: 500
                        }) : wx.showToast({
                            title: n + "/" + i.data.dataList[a].content[e].content_siz,
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
        var a = t.currentTarget.dataset.min, e = t.currentTarget.dataset.index;
        wx.setClipboardData({
            data: this.data.dataList[a].content[e].text
        });
    },
    getDateDiff: function(t) {
        var a, e = 864e5, n = new Date().getTime(), i = new Date(t), s = n - t;
        if (!(s < 0)) {
            var o = s / e, d = s / 36e5, r = s / 6e4;
            if (1 <= o) if (parseInt(o) < 2) a = "昨天"; else {
                var c = i.getMonth() + 1 < 10 ? "0" + (i.getMonth() + 1) : i.getMonth() + 1, l = i.getDate() < 10 ? "0" + i.getDate() : i.getDate();
                i.getHours(), i.getMinutes(), i.getSeconds();
                a = c + "月" + l + "日";
            } else a = 1 <= d ? parseInt(d) + "小时前" : 1 <= r ? parseInt(r) + "分钟前" : "刚刚";
            return a;
        }
    },
    dayObtain: function() {
        var t = "周" + "日一二三四五六".charAt(new Date().getDay()), a = new Date().getDate();
        this.setData({
            week: t,
            day: a
        });
    },
    onShareAppMessage: function(t) {
        var a = this;
        if ("button" == t.from) {
            var e = t.target.dataset.min, n = t.target.dataset.index, i = {
                types: "fx",
                id: t.target.dataset.id
            };
            return _request2.default.post("operation", i).then(function(t) {
                a.data.dataList[e].content[n].sharenb = parseInt(a.data.dataList[e].content[n].sharenb) + 1, 
                a.setData({
                    dataList: a.data.dataList
                });
            }), {
                title: a.data.dataList[e].content[n].text.slice(0, 20) + "...",
                imageUrl: "img" == a.data.dataList[e].content[n].type ? a.data.dataList[e].content[n].content[0] : app.globalData.topimg,
                path: "myxs_fodder/pages/textInfo/textInfo?id=" + t.target.dataset.id
            };
        }
        return {
            title: app.globalData.toptext,
            imageUrl: app.globalData.topimg,
            path: "myxs_fodder/pages/start/start"
        };
    },
    onReachBottom: function(t) {
        var e = this, a = 0, n = 0;
        for (var i in e.data.dataList) n = i;
        a = a + e.data.dataList[n].content.length - 1;
        var s = this.data.dataList[n].content[a].content_id, o = {
            type: "jz",
            class_id: this.data.class_id,
            content_id: s
        };
        _request2.default.get("listcontent", o).then(function(t) {
            if (0 === t.content.length) return wx.showToast({
                title: "加载已完成"
            }), !1;
            for (var a in t.content) t.content[a].isplay = 0, t.content[a].create_time = e.getDateDiff(1e3 * t.content[a].create_time), 
            55 < t.content[a].text.length ? (t.content[a].textTypeL = 1, t.content[a].textShow = 1, 
            t.content[a].ShowBtn = "展开") : t.content[a].textTypeL = 0;
            e.setData({
                dataList: e.data.dataList.concat(t)
            });
        });
    },
    plays: function(t) {
        var a = this, e = t.currentTarget.dataset.min, n = t.currentTarget.dataset.index;
        for (var i in a.data.dataList) a.data.dataList[e].content[n].isplay = 0;
        a.data.dataList[e].content[n].isplay = 1, a.setData({
            dataList: a.data.dataList
        }), wx.createVideoContext("Myplay" + n).play();
    },
    onHide: function() {
        var t = this;
        if (null == t.data.dataList) return !1;
        for (var a in t.data.dataList) for (var e in t.data.dataList[a].content) t.data.dataList[a].content[e].isplay = 0;
        this.setData({
            dataList: t.data.dataList
        });
    }
});