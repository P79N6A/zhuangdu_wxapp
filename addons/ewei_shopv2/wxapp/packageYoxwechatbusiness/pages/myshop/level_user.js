// packageYoxwechatbusiness/pages/myshop/myshop.js
var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    sliderList: [
      { selected: true, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
    ],
    levelList:[],
    name:"",
    userList:[],
    curNav: 0,
    curIndex: 0,
    avatar:"",
    nickname:""
  },
  left: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    that.setData({
      curNav: index,
      curIndex: index,
    })
    var level=e.currentTarget.dataset.level;
    that.getuser(level);
  },
  onLoad: function (options) {
    var that = this;
    that.getlevel();
    that.getuser();
    //调用应用实例的方法获取全局数据
    t.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
  },
  //轮播图绑定change事件，修改图标的属性是否被选中
  switchTab: function (e) {
    var sliderList = this.data.sliderList;
    var i, item;
    for (i = 0; item = sliderList[i]; ++i) {
      item.selected = e.detail.current == i;
    }
    this.setData({
      sliderList: sliderList
    });
  },
  tabuser:function(t){
    this.setData({
      avatar:t.target.dataset.avatar,
      nickname: t.target.dataset.nickname
    })
  },
  getuser:function(level){
    var that=this;
    e.get("yoxwechatbusiness/user/user_list", { isajax: 1, level:  level }, function (e) {
      that.setData({
        userList:e.data.list
      })
    })
  },
  tablevel: function(t){
    this.setData({
      name:t.target.dataset.name
    })
  },
  getlevel:function(){
    var that=this;
    e.get("yoxwechatbusiness/level/level_list", { isajax: 1 }, function (e) {
      that.setData({
        levelList:e.data.list
      })
      //console.log(e.data.list)
    })
  },
  ToSearchResult:function(e){
    // console.log(e)
    var avatar = e.currentTarget.dataset.avatar;
    var nickname = e.currentTarget.dataset.nickname;
    var uid = e.currentTarget.dataset.uid;
    wx.navigateTo({
      url: 'myshop?avatar=' + avatar+'&nickname='+nickname+"&uid="+uid,
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