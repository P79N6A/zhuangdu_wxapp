var t, e = getApp();

Component({
    properties: {
        line: {
            type: Boolean,
            value: !1,
            observer: function(e, t) {
                this.setData({
                    line: e
                });
            }
        },
        backgroundStyle: {
            type: String,
            value: "",
            obersver: function(e) {
                this.setData({
                    backgroundStyle: e
                });
            }
        },
        title: {
            type: String,
            value: "",
            obersver: function(e) {
                this.setData({
                    title: e
                });
            }
        }
    },
    data: {
        statusBarHeight: e.globalData.sysInfo.statusBarHeight
    },
    methods: {
        goBack: function() {
            console.log("goback"), wx.navigateBack();
        },
        goHome: function() {
            console.log("gohome"), wx.reLaunch({
                url: "/lshd_yanzhi/pages/index/index"
            });
        }
    }
});