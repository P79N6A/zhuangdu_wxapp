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

var urlCommon = /.*\/attachment\/([0-9]*$)/, app = getApp();

Page((_defineProperty(_Page = {
    data: {
        leng2: wx.getSystemInfoSync().windowWidth / 3,
        photoWidth: wx.getSystemInfoSync().windowWidth / 5,
        dataNull: 2,
        judi: wx.getSystemInfoSync().windowHeight - 80
    },
    onLoad: function() {
        var e = this;
        this.categoryDataQuery(), this.refreshView = this.selectComponent("#refreshView"), 
        this.dayObtain(), wx.getStorage({
            key: "Day",
            success: function(t) {
                var a = new Date().getDate();
                "" == t.data && e.setData({
                    dot: 1
                }), t.data < a && e.setData({
                    dot: 1
                });
            },
            complete: function(t) {
                "getStorage:fail data not found" == t.errMsg && e.setData({
                    dot: 1
                });
            }
        }), e.setData({
            memberid: app.globalData.member.memberId,
            isAdmin: app.globalData.member.memberAdmin
        });
    },
    onShow: function() {
        wx.pageScrollTo({
            scrollTop: this.data.zTop,
            duration: 0
        });
    },
    ReturnNew: function() {
        wx.navigateBack({
            delta: 1
        });
    },
    previewImage: function(t) {
        var a = t.target.dataset.src, e = t.target.dataset.index, n = [];
        for (var i in this.data.dataList[e].content) n = n.concat(this.data.dataList[e].content[i]);
        wx.previewImage({
            current: a,
            urls: n
        });
    },
    DataTextTooger: function(t) {
        var a = t.currentTarget.dataset.l;
        0 == this.data.dataList[a].textShow ? (this.data.dataList[a].textShow = 1, this.data.dataList[a].ShowBtn = "展开") : (this.data.dataList[a].textShow = 0, 
        this.data.dataList[a].ShowBtn = "收起"), this.setData({
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
        var e = this;
        _request2.default.get("listcontent", {
            type: "my_content",
            class_id: t
        }).then(function(t) {
            if (0 == t.length) return e.setData({
                dataNull: 0
            }), !1;
            for (var a in e.setData({
                dataNull: 1
            }), t) t[a].isplay = 0, t[a].create_time = e.getDateDiff(1e3 * t[a].create_time), 
            1 == t[a].content_status ? t[a].admintooger = 1 : 2 == t[a].content_status && (t[a].admintooger = 0), 
            55 < t[a].text.length ? (t[a].textTypeL = 1, t[a].textShow = 1, t[a].ShowBtn = "展开") : t[a].textTypeL = 0;
            t[0].isplay = 1, e.setData({
                dataList: t
            });
        });
    },
    handletouchstart: function(t) {},
    handletouchmove: function(t) {},
    handletouchend: function(t) {},
    handletouchcancel: function(t) {},
    onPageScroll: function(t) {},
    onPullDownRefresh: function(t) {
        this.dataQuery(this.data.menubl);
    },
    getDateDiff: function(t) {
        var a, e = 864e5, n = new Date().getTime(), i = new Date(t), s = n - t;
        if (!(s < 0)) {
            var o = s / e, r = s / 36e5, d = s / 6e4;
            if (1 <= o) if (parseInt(o) < 2) a = "昨天"; else {
                var c = i.getMonth() + 1 < 10 ? "0" + (i.getMonth() + 1) : i.getMonth() + 1, l = i.getDate() < 10 ? "0" + i.getDate() : i.getDate();
                i.getHours(), i.getMinutes(), i.getSeconds();
                a = c + "月" + l + "日";
            } else a = 1 <= r ? parseInt(r) + "小时前" : 1 <= d ? parseInt(d) + "分钟前" : "刚刚";
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
    onReachBottom: function(t) {
        var a = this.data.dataList.length - 1, e = this.data.dataList[a].content_id, n = {
            type: "my_content",
            class_id: this.data.class_id,
            content_id: e
        }, i = this;
        _request2.default.get("listcontent", n).then(function(t) {
            if (0 === t.length) return wx.showToast({
                title: "加载已完成"
            }), !1;
            for (var a in t) t[a].isplay = 0, t[a].create_time = i.getDateDiff(1e3 * t[a].create_time), 
            1 == t[a].content_status ? t[a].admintooger = 1 : 2 == t[a].content_status && (t[a].admintooger = 0), 
            55 < t[a].text.length ? (t[a].textTypeL = 1, t[a].textShow = 1, t[a].ShowBtn = "展开") : t[a].textTypeL = 0;
            i.setData({
                dataList: i.data.dataList.concat(t)
            });
        });
    },
    adminShow: function(t) {
        var a, e = this, n = t.currentTarget.id, i = t.currentTarget.dataset.index, s = {
            type: "hide",
            content_id: n
        };
        a = e.data.dataList[i].admintooger ? "是否开启隐藏" : "是否开启显示", wx.showModal({
            title: "",
            content: a,
            confirmText: "确定",
            complete: function(t) {
                t.confirm && _request2.default.post("Content", s).then(function(t) {
                    t.status ? (0 == e.data.dataList[i].admintooger ? e.data.dataList[i].admintooger = 1 : e.data.dataList[i].admintooger = 0, 
                    e.setData({
                        dataList: e.data.dataList
                    })) : wx.showToast({
                        title: "未知错误",
                        icon: "none"
                    });
                });
            }
        });
    },
    adminDelete: function(t) {
        var a = this, e = t.currentTarget.id, n = t.currentTarget.dataset.index, i = {
            type: "delete",
            content_id: e
        };
        wx.showModal({
            title: "",
            content: "是否删除这条内容",
            confirmText: "确定",
            complete: function(t) {
                t.confirm && _request2.default.post("Content", i).then(function(t) {
                    t.status ? (a.data.dataList.splice(n, 1), a.setData({
                        dataList: a.data.dataList
                    })) : wx.showToast({
                        title: "未知错误",
                        icon: "none"
                    });
                });
            }
        });
    },
    plays: function(t) {
        var a = this, e = t.currentTarget.dataset.index;
        for (var n in a.data.dataList) a.data.dataList[n].isplay = 0;
        a.data.dataList[e].isplay = 1, a.setData({
            dataList: a.data.dataList
        }), wx.createVideoContext("Myplay" + e).play();
    }
}, "onPageScroll", function(t) {
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