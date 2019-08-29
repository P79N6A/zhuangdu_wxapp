var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery")),
  s = t.requirejs("wxParse/wxParse");
Page({

  data: {
    id:"",
    detail:[]
  },

  onLoad: function (options) {
    console.log(options.id)
    var that = this;
    that.setData({
      id: options.id
    })
    that.getdetailindex();
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
  getdetailindex:function(){
    var that=this;
    console.log(that.data.id);
    var id = that.data.id;
    e.get("yoxarticle/index", {
      isajax: 1,
      aid:id
    }, function (e) {
      s.wxParse('article', 'html', e.data.article_content, that);
      that.setData({
        detail:e.data
      })
    })
  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})