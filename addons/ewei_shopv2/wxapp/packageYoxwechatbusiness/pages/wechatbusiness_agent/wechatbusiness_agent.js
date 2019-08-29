var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    prev:"<",
    next:">",
    choose_url:"",
    img_base64: "",
    level_list:[]
  },
  onLoad: function(options) {
    var that = this;
    that.getagentList();
    that.getlevelist();
  },
  onReady: function() {

  },
  choose:function(){
    console.log(1111111);
    this.setData({
        choose_url:"../../image/agent/choose.png"
    });
  },
  onShow: function() {

  },
  onHide: function() {

  },
  onUnload: function() {

  },
  onPullDownRefresh: function() {

  },
  onReachBottom: function() {

  },
  getagentList: function() {
    var that = this;
    e.get("yoxwechatbusiness/agent", {
      isajax: 1
    }, function(e) {
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
        img_base64: e.data.img_base64
      })
    })
  },
  togoods:function(e){
    var package_id = e.currentTarget.dataset.package_id;
    console.log(package_id)
    wx.navigateTo({
      url: '../../../pages/goods/package/detail/index?id=' + package_id,
    })
  },
  getlevelist: function() {
    var that = this;
    e.get("yoxwechatbusiness.level.level_list", {
      isajax: 1
    }, function(e) {
      that.setData({
        level_list:e.data.list
      })
    })
  },
  // todetail:function(e){
  //   var id = e.currentTarget.dataset.id;
  //   wx.navigateTo({
  //     url: './detail/detail?id='+id,
  //   })
  // },
  onShareAppMessage: function() {

  }
})