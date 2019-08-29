var lastY = 0, PULL_DEFAULT = -1, PULL_LT_HEIGHT = 1, PULL_GT_HEIGHT = 2, PULL_REFRESHING = 0, platform = "ios", scale = 375 / wx.getSystemInfoSync().windowWidth * 2;

Component({
    properties: {
        backgroundColor: {
            type: String,
            value: "#000"
        },
        refreshHeight: {
            type: Number,
            value: 80
        },
        textColor: {
            type: String,
            value: "white"
        }
    },
    data: {
        pullState: PULL_DEFAULT,
        dynamicHeight: 0,
        refreshHeight: 60,
        scrollTop: 0
    },
    created: function() {
        platform = wx.getSystemInfoSync().platform, scale = wx.getSystemInfoSync().windowWidth / 375 * 2;
    },
    attached: function() {},
    ready: function() {},
    moved: function() {},
    detached: function() {},
    methods: {
        autoRefresh: function() {
            this._pullStateChange(PULL_REFRESHING, this.data.refreshHeight), this.triggerEvent("onRefresh");
        },
        stopPullRefresh: function() {
            this.setData({
                pullState: PULL_DEFAULT,
                dynamicHeight: 0
            }, function() {
                wx.pageScrollTo({
                    scrollTop: 0,
                    duration: 0
                });
            });
        },
        isRefreshing: function() {
            return PULL_REFRESHING == this.data.pullState;
        },
        isPullState: function() {
            return PULL_DEFAULT != this.data.pullState;
        },
        handletouchstart: function(t) {
            lastY = t.touches[0].clientY;
        },
        handletouchmove: function(t) {
            t.touches[0].pageY;
            var e = t.touches[0].clientY - lastY;
            if (!(0 < this.data.scrollTop || e < 0)) {
                var a = this.data.dynamicHeight + e;
                a > this.data.refreshHeight ? this._pullStateChange(0 == this.data.pullState ? 0 : PULL_GT_HEIGHT, a) : (a = a < 0 ? 0 : a, 
                this._pullStateChange(0 == this.data.pullState ? 0 : PULL_LT_HEIGHT, a)), lastY = t.touches[0].clientY;
            }
        },
        handletouchend: function(t) {
            var e = this.data.refreshHeight;
            if (0 != this.data.pullState) {
                var a = this.data.dynamicHeight;
                0 < this.data.scrollTop && PULL_DEFAULT != this.data.pullState ? a - scale * this.data.scrollTop > e ? (this._pullStateChange(PULL_REFRESHING, e), 
                this.triggerEvent("onRefresh")) : (this._pullStateChange(PULL_DEFAULT, 0), wx.pageScrollTo({
                    scrollTop: 0,
                    duration: 0
                })) : a >= this.data.refreshHeight ? (this._pullStateChange(PULL_REFRESHING, e), 
                this.triggerEvent("onRefresh")) : this._pullStateChange(PULL_DEFAULT, 0);
            } else this._pullStateChange(PULL_REFRESHING, e);
        },
        handletouchcancel: function(t) {
            this._pullStateChange(PULL_DEFAULT, 0);
        },
        onPageScroll: function(t) {
            0 < t.scrollTop && PULL_DEFAULT != this.data.pullState && (this.data.dynamicHeight - scale * t.scrollTop < this.data.refreshHeight ? this.setData({
                pullState: PULL_LT_HEIGHT
            }) : this.setData({
                pullState: PULL_GT_HEIGHT
            })), this.data.scrollTop = t.scrollTop;
        },
        _isAndriod: function() {
            return "ios" == platform;
        },
        _pullStateChange: function(t, e) {
            this.setData({
                pullState: t,
                dynamicHeight: e
            }), this.triggerEvent("onPullState");
        }
    }
});