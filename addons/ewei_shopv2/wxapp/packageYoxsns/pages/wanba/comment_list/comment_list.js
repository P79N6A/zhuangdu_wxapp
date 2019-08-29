var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    id:"",
    commentList:[],
    avatar:"",
    nickname:"",
    content:"",
    createtime:""
  },
  onLoad: function (options) {
    var that=this;
    console.log(options.id)
    that.setData({
      id:options.id
    })
    that.getcommentList(options.id)
  },
  onReady: function () {

  },
  tabcommentList:function(t){
    this.setData({
      avatar: t.target.dataset.avatar,
      nickname: t.target.dataset.nickname,
      content: t.target.dataset.content,
      createtime: t.target.dataset.createtime
    })
  },
  getcommentList:function(id){
    var that=this;
    var id=that.data.id
    e.get("yoxsns/post/getlist", {
      isajax: 1,
      bid: 3,
      pid: id
    }, function (e) {
     that.setData({
       commentList: e.result.list
     })
      console.log(e.result)
    })
  },
  bindshow:function(id){
    var that=this;
    var id=that.data.id;
    wx.navigateTo({
      url: '../wanba_comment/wanba_comment?id='+id,
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