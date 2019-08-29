var _util = require("we7/resource/js/util.js"), _util2 = _interopRequireDefault(_util);

function _interopRequireDefault(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

App({
    onLaunch: function(e) {},
    onShow: function(e) {},
    onHide: function() {},
    onError: function(e) {
        console.log(e);
    },
    util: _util2.default,
    globalData: {
        userInfo: null,
        openid: 0
    },
    siteInfo: require("siteinfo.js")
});