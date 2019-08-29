var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    id: "",
    mainid:"",
    commentList: [],
    avatar: "",
    nickname: "",
    content: "",
    createtime: ""
  },
  onLoad: function (options) {
    var that = this;
    //console.log(options.id)
    //console.log(options.mainid)
    that.setData({
      id: options.id,
      mainid: options.mainid
    })
    that.getcommentList(options.id, options.mainid)
  },
  onReady: function () {

  },
  tabcommentList: function (t) {
    this.setData({
      avatar: t.target.dataset.avatar,
      nickname: t.target.dataset.nickname,
      content: t.target.dataset.content,
      createtime: t.target.dataset.createtime
    })
  },
  getcommentList: function (id, mainid) {
    var that = this;
    var id = that.data.id;
    var mainid = that.data.mainid;
    e.get("yoxsns/post/getlist", {
      isajax: 1,
      bid: mainid,
      pid: id
    }, function (e) {
      that.setData({
        commentList: e.result.list
      })
      console.log(e.result.list)
    })
  },
  bindshow: function (id, mainid) {
    var that = this;
    var id = that.data.id;
    //console.log(id)
    var mainid = that.data.mainid;
    //console.log(mainid)
    wx.navigateTo({
      url: './comment/comment?id=' + id+"&mainid="+mainid,
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