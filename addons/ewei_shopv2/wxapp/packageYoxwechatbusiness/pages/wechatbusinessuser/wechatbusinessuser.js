var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    mydownleveluserList:[],
    avatar:"",
    nickname:""
  },
  onLoad: function (options) {
    var that=this;
    that.getuserList();
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
  tabuserList:function(t){
    this.setData({
      avatar:t.target.dataset.avatar,
      nickname:t.target.dataset.nickname
    })
  },
  getuserList: function (begin_time, end_time){
    var that=this;
    e.get("yoxwechatbusiness/user/my_down_level_user", { 
      isajax: 1,
      begin_time: begin_time,
      end_time: end_time
      }, function (e) {
      if (e.status == 0) {  
        wx.showModal({
          title: '',
          content: e.result.message,
          success(res) {
            wx.navigateBack({
              delta: 1
            })
            return;
          }
        })
      }
      that.setData({
        mydownleveluserList:e.data.list
      })
    })
  },
  detailchange:function(e){
    console.log(e)
    var uid = e.currentTarget.dataset.uid
    wx.navigateTo({
      url: './detail/detail?uid='+uid,
    })
  },
  onShareAppMessage: function () {}
})