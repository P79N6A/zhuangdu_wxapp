var e = require("utils/core.js");

App({
  onShow: function() {
    this.onLaunch();
  },
  onLaunch: function() {
    var e = this;
    e.globalData.myDevice = wx.getSystemInfoSync();
    wx.getSystemInfo({
      success: function(t) {
        "0" == t.model.indexOf("iPhone X") ? e.setCache("isIpx", t.model) : e.setCache("isIpx", "");
      }
    });
    var t = this;
    wx.getSystemInfo({
      success: function(e) {
        wx.setStorageSync("systemInfo", e);
        var n = e.windowWidth,
          o = e.windowHeight;
        t.globalData.ww = n, t.globalData.hh = o;
      }
    }), this.getConfig();
    this.getUserInfo(function(e) {}, function(e, t) {
      var t = t ? 1 : 0,
        e = e || "";
      t && wx.redirectTo({
        url: "/pages/message/auth/index?close=" + t + "&text=" + e
      });
    });
  },
  requirejs: function(e) {
    return require("utils/" + e + ".js");
  },
  systemInfo: null,
  getConfig: function() {
    if (null !== this.globalData.api) return {
      api: this.globalData.api,
      approot: this.globalData.approot,
      appid: this.globalData.appid
    };
    var e = wx.getExtConfigSync();
    return console.log(e), this.globalData.api = e.config.api, this.globalData.approot = e.config.approot,
      this.globalData.appid = e.config.appid, e.config;
  },
  getCache: function(e, t) {
    var n = +new Date() / 1e3,
      o = "";
    n = parseInt(n);
    try {
      (o = wx.getStorageSync(e + this.globalData.appid)).expire > n || 0 == o.expire ? o = o.value : (o = "",
        this.removeCache(e));
    } catch (e) {
      o = void 0 === t ? "" : t;
    }
    return o = o || "";
  },
  setCache: function(e, t, n) {
    var o = +new Date() / 1e3,
      i = !0,
      a = {
        expire: n ? o + parseInt(n) : 0,
        value: t
      };
    try {
      wx.setStorageSync(e + this.globalData.appid, a);
    } catch (e) {
      i = !1;
    }
    return i;
  },
  removeCache: function(e) {
    var t = !0;
    try {
      wx.removeStorageSync(e + this.globalData.appid);
    } catch (e) {
      t = !1;
    }
    return t;
  },
  getUserInfo: function(t, n) {
    var o = this,
      i = {};
    !(i = o.getCache("userinfo")) || i.needauth ? wx.login({
      success: function(a) {
        a.code ? e.post("wxapp/login", {
          code: a.code
        }, function(a) {
          a.error ? e.alert("获取用户登录态失败:" + a.message) : a.isclose && n && "function" == typeof n ? n(a.closetext, !0) : wx.getUserInfo({
            success: function(n) {
              i = n.userInfo, e.get("wxapp/auth", {
                data: n.encryptedData,
                iv: n.iv,
                sessionKey: a.session_key
              }, function(e) {
                1 == e.isblack && wx.showModal({
                    title: "无法访问",
                    content: "您在商城的黑名单中，无权访问！",
                    success: function(e) {
                      e.confirm && o.close(), e.cancel && o.close();
                    }
                  }), n.userInfo.openid = e.openId, n.userInfo.id = e.id, n.userInfo.uniacid = e.uniacid,
                  n.needauth = 0, o.setCache("userinfo", n.userInfo, 7200), o.setCache("userinfo_openid", n.userInfo.openid),
                  o.setCache("userinfo_id", e.id), o.getSet(), t && "function" == typeof t && t(i);
              });
            },
            fail: function() {
              console.log(a), e.get("wxapp/check", {
                openid: a.openid
              }, function(e) {
                console.log(e), 1 == e.isblack && wx.showModal({
                    title: "无法访问",
                    content: "您在商城的黑名单中，无权访问！",
                    success: function(e) {
                      e.confirm && o.close(), e.cancel && o.close();
                    }
                  }), e.needauth = 1, o.setCache("userinfo", e, 7200), o.setCache("userinfo_openid", a.openid),
                  o.setCache("userinfo_id", a.id), o.getSet(), t && "function" == typeof t && t(i);
              });
            }
          });
        }) : e.alert("获取用户登录态失败:" + a.errMsg);
      },
      fail: function() {
        e.alert("获取用户信息失败!");
      }
    }) : t && "function" == typeof t && t(i);
  },
  close: function() {
    this.globalDataClose.flag = !0, wx.reLaunch({
      url: "/pages/index/index"
    });
  },
  getSet: function() {
    var t = this;
    "" == t.getCache("cacheset") && setTimeout(function() {
      var n = t.getCache("cacheset");
      e.get("cacheset", {
        version: n.version
      }, function(e) {
        e.update && t.setCache("cacheset", e.data);
      });
    }, 10);
  },
  url: function(e) {
    e = e || {};
    var t = {},
      n = "",
      o = "",
      i = this.getCache("usermid");
    n = e.mid || "", o = e.merchid || "", "" != i ? ("" != i.mid && void 0 !== i.mid || (t.mid = n),
        "" != i.merchid && void 0 !== i.merchid || (t.merchid = o)) : (t.mid = n, t.merchid = o),
      this.setCache("usermid", t, 7200);
  },
  impower: function(e, t, n) {
    wx.getSetting({
      success: function(o) {
        console.log(o), o.authSetting["scope." + e] || wx.showModal({
          title: "用户未授权",
          content: "您点击了拒绝授权，暂时无法" + t + "，点击去设置可重新获取授权喔~",
          confirmText: "去设置",
          success: function(e) {
            e.confirm ? wx.openSetting({
              success: function(e) {}
            }) : "route" == n ? wx.switchTab({
              url: "/pages/index/index"
            }) : "details" == n || wx.navigateTo({
              url: "/pages/index/index"
            });
          }
        });
      }
    });
  },
  // 权限询问
  getRecordAuth: function () {
    wx.getSetting({
      success(res) {
        console.log(res)
        if (!res.authSetting['scope.record']) {
          wx.authorize({
            scope: 'scope.record',
            success() {
              console.log("succ")
            }, fail() {
              console.log("fail")
            }
          })
        } else {
          console.log("record has been authed")
        }
      }, fail(res) {
        console.log("fail")
        console.log(res)
      }
    })
  },
  globalDataClose: {
    flag: !1
  },
  //事件处理函数
  bindChange: function(e) {
    let that = this;
    that.setData({
      currentTab: e.detail.current
    });
  },
  swichNav: function(e) {
    let that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
  globalData: {
    defaultCity: '',
    currentTab:0,
    defaultCounty: '',
    weatherData: '',
    air: '',
    day: '',
    heWeatherBase: "https://free-api.heweather.com",
    tencentMapKey: "4HYBZ-EB23D-SLC42-HQ5R3-LP3LQ-OZFU5",
    heWeatherKey: "4a817b4338e04cc59bdb92da7771411e",
    appid: "wxd73b0879fffcddde",
    api: "https://zdu.igdlrj.com/app/ewei_shopv2_api.php?i=2",
    approot: "https://zdu.igdlrj.com/addons/ewei_shopv2/",
    userInfo: null,
    myDevice: null,
    items: [
      {
        "pagePath": "pages/index/index",
        "iconPath": "./static/images/tabbar/icon-1.png",
        "selectedIconPath": "./static/images/tabbar/icon-1-active.png",
        "text": "首页"
      },
      {
        "pagePath": "pages/shop/caregory/index",
        "iconPath": "./static/images/tabbar/icon-10.png",
        "selectedIconPath": "./static/images/tabbar/icon-10-active.png",
        "text": "妆度"
      },
      {
        "pagePath": "packageYoxgroupbuy/pages/index/index",
        "iconPath": "./static/images/tabbar/icon-9.png",
        "selectedIconPath": "./static/images/tabbar/icon-9-active.png",
        "text": "咔咔团"
      },
      {
        "pagePath": "pages/topage/ToUser/ToUser",
        "iconPath": "./static/images/tabbar/icon-5.png",
        "selectedIconPath": "./static/images/tabbar/icon-5-active.png",
        "text": "我的"
      },
    ]
  },
});