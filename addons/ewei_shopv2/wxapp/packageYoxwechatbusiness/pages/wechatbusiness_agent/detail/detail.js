var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    id:"",
    detail:[]
  },
  onLoad: function (options) {
    console.log(options.id)
    var that=this;
    that.setData({
      id:options.id
    })
    that.getdetail(options.id)
  },
  onReady: function () {

  },
  onShow: function () {

  },
  getdetail:function(id){
    var that=this;
    var id = that.data.id;
    e.get("yoxwechatbusiness/level/level_info", {
      isajax: 1,
      id:id
    }, function (e) {
      that.setData({
        detail: e.data
      })
    })
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