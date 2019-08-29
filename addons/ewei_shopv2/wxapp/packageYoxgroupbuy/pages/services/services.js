var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    date:"",
    timer:"",
    timerflag:false
  },
  onLoad: function (options) {
    var that=this;
    var myDate = new Date();
    var hour = myDate.getHours();
    var min = myDate.getMinutes();
    var date = hour+":"+min;
    console.log(date)
    that.setData({
      date:date,
      timer: setTimeout(function () {
        that.times();
      }, 3000)
    })
  },
  onReady: function () {

  },
  times:function(){
    this.setData({
      timerflag: true
    })
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