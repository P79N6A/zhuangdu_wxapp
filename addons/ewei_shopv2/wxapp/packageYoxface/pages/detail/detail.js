var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    suggest_info:""
  },
  onLoad: function (options) {
    console.log(JSON.parse(options.suggest_info))
    var suggest_info = JSON.parse(options.suggest_info)
    this.setData({
      suggest_info:suggest_info
    })
  }, 
  togoods_detail:function(e){
    var id=e.currentTarget.dataset.id;
    console.log(id);
    wx.navigateTo({
      url: '../../../pages/goods/detail/index?id='+id,
    })
  }, 
  topackage_detail:function(e){
    var id = e.currentTarget.dataset.id;
    console.log(id);
    wx.navigateTo({
      url: '../../../pages/goods/package/index?id='+id,
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