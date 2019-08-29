// packageYoxwechatbusiness/pages/livelesson/detail/detail.js
var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    name:"", //名字
    detail:[],
    thumb:"",
    update_time:"",
    descript:"",
    is_hot:""
  },
  onLoad: function (options) {
    var that=this;
    wx.setNavigationBarTitle({
      title: options.name,
    })
    that.getDetail(options.id);
  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  tabDetail:function(t){
    this.setData({
      name: t.target.dataset.name,
      thumb: t.target.dataset.thumb,
      update_time: t.target.dataset.update_time,
      descript: t.target.dataset.descript,
      is_hot: t.target.dataset.is_hot
    })
  },
  getDetail:function(id){
    var that = this;
    e.get("yoxwechatbusiness/course/course_edit", { isajax: 1, id:id }, function (e) {
        e.data.update_time = a.formatTimeTwo(e.data.update_time, 'Y/M/D h:m:s')
      that.setData({
        detail:e.data
      })
    })
  },
  comein: function (options){
    var that=this;
    wx.navigateTo({
      url: '../class/class?id='+that.options.id+"&name="+that.options.name +"&update_time",
    })
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})