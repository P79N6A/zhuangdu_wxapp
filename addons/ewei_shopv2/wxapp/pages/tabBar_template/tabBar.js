var t = getApp();
Page({

  data: {
    currentTab: 0,
    items: [
      {
        "pagePath": "../index/index",
        "iconPath": "../../static/images/tabbar/icon-1.png",
        "selectedIconPath": "../../static/images/tabbar/icon-1-active.png",
        "text": "首页"
      },
      { 
        "pagePath": "../shop/caregory/index",
        "iconPath": "../../static/images/tabbar/icon-10.png",
        "selectedIconPath": "../../static/images/tabbar/icon-10-active.png",
        "text": "妆度"
      },
      // {
      //   "pagePath": "../topage/ToHiZhuandu/ToHiZhuandu",
      //   "iconPath": "../../static/images/tabbar/icon-102.png",
      //   "selectedIconPath": "../../static/images/tabbar/icon-102.png",
      //   "text": "美妆助理"
      // },
      {
        "pagePath": "../../packageYoxgroupbuy/pages/index/index",
        "iconPath": "../../static/images/tabbar/icon-9.png",
        "selectedIconPath": "../../static/images/tabbar/icon-9-active.png",
        "text": "咔咔团"
      },
      {
        "pagePath": "../topage/Tosns/ToUser/ToUser",
        "iconPath": "../../static/images/tabbar/icon-5.png",
        "selectedIconPath": "../../static/images/tabbar/icon-5-active.png",
        "text": "我的"
      },
    ]
  },
  //事件处理函数
  bindChange: function (e) {
    let that = this;
    that.setData({
      currentTab: e.detail.current
    });
  },
  swichNav: function (e) {
    let that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
  onLoad: function (options) {
    let that = this;
    t.getUserInfo(function (userInfo) {
      that.setData({
        userInfo: userInfo
      })
    })
  }
})