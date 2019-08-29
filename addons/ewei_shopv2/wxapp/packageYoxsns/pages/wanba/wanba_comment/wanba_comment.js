var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    content: "",
    id: ""
  },
  onLoad: function(options) {
    console.log(options.id)
    var that = this;
    that.setData({
      id: options.id
    })
  },
  onReady: function() {

  },
  formSubmit: function(id) {
    var that = this;
    //console.log(event)
    var content = that.data.content;
    var id = that.data.id;
    // 如果文本为空提示用户输入 否则提交表单
    if (content == '') {
      wx.showModal({
        content: '请填写内容后点击提交保存！',
        showCancel: false,
        success: function(res) {
          if (res.confirm) {
            //console.log('用户点击确定');
          }
        }
      });
    } else {
      e.get("yoxsns/post/reply", {
        isajax: 1,
        bid:3,
        rpid: 0,
        pid: id,
        content: content
      }, function(e) {
          wx.showToast({
            title: '评论成功',
          })
          wx.redirectTo({
            url:'../comment_list/comment_list?id='+id,
          })
      })
    }
  },
  commentSubmit: function(e) {
    //console.log(e)
    var that = this;
    console.log(e.detail.value)
    that.setData({
      content: e.detail.value
    })

    //console.log(that.data.content)
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
  onShareAppMessage: function() {

  }
})