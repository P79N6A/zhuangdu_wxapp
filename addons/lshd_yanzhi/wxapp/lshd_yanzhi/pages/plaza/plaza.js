var app = getApp();

Page({
    data: {
        showTips: !0,
        alldata: "",
        pages: 0,
        sex: "女",
        appname: "",
        currentTab: 0
    },
    onShareAppMessage: function() {},
    swiperTab: function(t) {
        console.log(t.detail.current), 0 == t.detail.current ? this.setData({
            currentTab: t.detail.current,
            sex: "女"
        }) : this.setData({
            currentTab: t.detail.current,
            sex: "男"
        }), this.getitems();
    },
    clickTab: function(t) {
        if (console.log(t.target.dataset.current), this.data.currentTab === t.target.dataset.current) return !1;
        0 == t.target.dataset.current ? this.setData({
            currentTab: t.target.dataset.current,
            sex: "女",
            pages: 0
        }) : this.setData({
            currentTab: t.target.dataset.current,
            sex: "男",
            pages: 0
        }), this.getitems();
    },
    onPullDownRefresh: function(t) {
        this.setData({
            alldata: "",
            pages: 0
        }), this.getitems(), setTimeout(function() {
            wx.stopPullDownRefresh();
        }, 2e3);
    },
    onReachBottom: function(t) {
        this.bindscrolltolower();
    },
    onLoad: function() {
        this.getitems();
    },
    getitems: function() {
        var a = this;
        console.log(a.data.pages), app.util.request({
            url: "entry/wxapp/getitemsAll",
            data: {
                m: "lshd_yanzhi",
                gender: a.data.sex,
                pages: a.data.pages
            },
            success: function(t) {
                console.log(t.data), a.setData({
                    alldata: t.data,
                    pages: parseInt(a.data.pages) + 1
                });
            }
        });
    },
    bindscrolltolower: function() {
        var s = this;
        console.log(s.data.pages), app.util.request({
            url: "entry/wxapp/getitemsAll",
            data: {
                m: "lshd_yanzhi",
                gender: s.data.sex,
                pages: parseInt(s.data.pages)
            },
            success: function(t) {
                for (var a = s.data.alldata, e = 0; e < t.data.length; e++) a.push(t.data[e]);
                s.setData({
                    alldata: a,
                    pages: parseInt(s.data.pages) + 1
                });
            }
        });
    }
});