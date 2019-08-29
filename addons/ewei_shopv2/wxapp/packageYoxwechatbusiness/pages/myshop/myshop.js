var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    userInfo: {},
    avatar:"",
    nickname:"",
    list:{},
    title:"",
    thumb:"",
    uid:""
  },
  onLoad: function (options) {
    var that = this;
    // console.log(options.avatar)
    // console.log(options.nickname)
    console.log(options.uid)
    //调用应用实例的方法获取全局数据
    t.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
    that.setData({
      avatar:options.avatar,
      nickname:options.nickname,
      uid: options.uid
    })
    that.getmyInfo();
  },
  tabmyInfo:function(t){
    this.setData({
      thumb:t.target.dataset.thumb,
      title:t.target.dataset.title
    })
  },
  getmyInfo:function(){
    var that=this;
    e.get("goods/get_list", { isajax: 1 }, function (e) {
      that.setData({
        list:e.list
      })
      //console.log(e.list)
    })
  },
  getattitude: function (uid) {
    var that = this;
    e.get("yoxfriendscircle.attitude.edit", { 
        isajax: 1,
        type: "STORE",
        attitude:"COLLECT",
        target_id:uid
      }, function (e) {
        if(e.status==0){
          wx.showModal({
            title: '',
            content: e.result.message,
            success(res){
              return;
            }
          })
        }
    })
  },
  collection:function(){
    var that=this;
    var uid = that.data.uid;
    console.log(uid)
    that.getattitude(uid);
  },
  onReady: function () {

  },
  todetail:function(e){
    console.log(e)
    var id = e.currentTarget.dataset.id;
    console.log(id)
    wx.navigateTo({
      url: '/pages/goods/detail/index?id='+id,
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