var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    userInfo: {},
    target_id:0,
    comment:""
  },
  onLoad: function (e) {
    // console.log(e.id)
    var that = this;
    //调用应用实例的方法获取全局数据
    t.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
    that.setData({
      target_id:e.id
    })
  },
  onReady: function () {

  },
  formSubmit:function(e){
    var that = this;
    // 如果文本为空提示用户输入 否则提交表单
    if (e.detail.value.content == '') {
      wx.showModal({
        content: '请填写内容后点击提交保存！',
        showCancel: false,
        success: function (res) {
          if (res.confirm) {
            console.log('用户点击确定');
          }
        }
      })
    }else{
      wx.redirectTo({
        url: '../index'
      })
      e.get("yoxfriendscircle/comment/edit", { target_id: this.target_id, comment: this.comment, isajax: 1, type: "ARTICLE", }, function (e) {

      })
    }
  },
  onShow: function () {},
  onHide: function () {},
  onUnload: function () {},
  onPullDownRefresh: function () {},
  onReachBottom: function () {},
  onShareAppMessage: function () {}
})