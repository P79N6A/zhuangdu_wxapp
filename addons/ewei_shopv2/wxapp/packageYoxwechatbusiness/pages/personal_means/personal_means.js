var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {

  },
  onLoad: function (options) {

  },
  toedit:function(){
    wx.navigateTo({
      url: './index/index',
    })
  },
  tobank:function(){
    wx.navigateTo({
      url: '../bank/index/index',
    })
  },
  toe_c:function(){
    wx.navigateTo({
      url: '../e_contract/e_contract',
    })
  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})