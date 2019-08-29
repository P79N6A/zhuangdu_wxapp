var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    uid:"",
    mainid:"",
    id:"",
    openid:"",
    avatar:"",
    nickname:"",
    userList:[],
    postsList:[],
    boardsList:[],
    logo:"",
    title:"",  //我的版块标题
    thumb:"",
    board_title:"", //我的话题标题
    boardlogo:"",
    boardtitle:"",
    views:""
  },
  onLoad: function (options) {
    console.log(options.uid)
    console.log(options.mainid)
    console.log(options.id)
    console.log(options.openid)
    var that=this;
    that.setData({
      uid:options.uid,
      mainid:options.mainid,
      id:options.id,
      openid:options.openid
    })
    that.getuser(options.openid)
    that.getgoodList(options.mainid,options.id)
  },
  getgoodList:function(mainid,id){
    var that=this;
    var mainid = that.data.mainid;
    var id = that.data.id;
    e.get("yoxsns/post/getlist", {
      isajax: 1,
      page: 1,
      bid: mainid,
      pid: id
    }, function (e) {
      that.setData({
      })
    })
  },
  tabuser:function(t){
    this.setData({
      avatar:t.target.dataset.avatar,
      nickname:t.target.dataset.nickname,
      logo: t.target.dataset.logo,
      title: t.target.dataset.title,
      thumb: t.target.dataset.thumb,
      board_title: t.target.dataset.board_title,
      boardlogo: t.target.dataset.boardlogo,
      boardtitle: t.target.dataset.boardtitle,
      views: t.target.dataset.views
    })
  },
  getuser: function (openid){
    var that=this;
    var openid = that.data.openid
    e.get("yoxsns/user", {
      isajax: 1,
      id:openid
    }, function (e) {
      that.setData({
        userList:e.result.data.member,
        postsList: e.result.data.posts,
        boardsList: e.result.data.boards
      })
      console.log(e.result.data)
    })
  },
  addfriend:function(){
    var that = this;
    var uid = that.data.uid;
    console.log(uid)
    e.get("yoxwechatbusiness/frient/frient_edit", {
      isajax: 1,
      frient_uid: uid
    }, function (e) {
      wx.showToast({
        title: '添加成功',
        icon:"success",
        duration:2000
      })
    })
  }
})