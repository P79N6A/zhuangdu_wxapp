App({
    onLaunch: function(e) {
        var o = this;
        o.ffff = e;
        var n = unescape(e.query.scene);
        console.log(n), "" != n && null != n && "undefined" != n && (0 < n.indexOf("c_beauty/pages/order/buy") ? "/" == n.slice(0, 1) ? o.buy_share = n : o.buy_share = "/" + n : o.scene = n);
    },
    onShow: function(e) {},
    onHide: function() {},
    onError: function(e) {},
    util: require("we7/resource/js/util.js"),
    tabBar: {
        color: "#123",
        selectedColor: "#1ba9ba",
        borderStyle: "#1ba9ba",
        backgroundColor: "#fff",
        list: [ {
            pagePath: "/we7_wxappdemo/pages/index/index",
            iconPath: "/we7/resource/icon/home.png",
            selectedIconPath: "/we7/resource/icon/homeselect.png",
            text: "首页"
        }, {
            pagePath: "/we7_wxappdemo/pages/footer/footer",
            iconPath: "/we7/resource/icon/user.png",
            selectedIconPath: "/we7/resource/icon/userselect.png",
            text: "底部"
        }, {
            pagePath: "/we7_wxappdemo/pages/todo/todo",
            iconPath: "/we7/resource/icon/todo.png",
            selectedIconPath: "/we7/resource/icon/todoselect.png",
            text: "ToDo"
        }, {
            pagePath: "/we7_wxappdemo/pages/pay/pay",
            iconPath: "/we7/resource/icon/pay.png",
            selectedIconPath: "/we7/resource/icon/payselect.png",
            text: "支付"
        } ]
    },
    globalData: {
        userInfo: null
    },
    siteInfo: require("siteinfo.js")
});