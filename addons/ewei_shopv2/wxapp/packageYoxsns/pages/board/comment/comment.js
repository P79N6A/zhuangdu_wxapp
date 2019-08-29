var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    content: "",
    id: "",
    mainid: ""
  },
  onLoad: function(options) {
    //console.log(options.id)
    //console.log(options.mainid)
    var that = this;
    that.setData({
      id: options.id,
      mainid: options.mainid
    })
  },
  onReady: function() {

  },
  formSubmit: function(id, mainid) {
    var that = this;
    var content = that.data.content;
    console.log(content)
    var id = that.data.id;
    console.log(id)
    var mainid = that.data.mainid;
    console.log(mainid)
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
        bid: mainid,
        rpid: 0,
        pid: id,
        content: content
      }, function(e) {
        if (content.length < 3) {
          wx.showModal({
            content: e.result.message,
            showCancel: false,
            success: function(res) {
              return;
            }
          })
        } else {
          wx.showModal({
            title: '评论成功',
            content: '',
            showCancel: false,
            success: function(res) {
              if (res.confirm) {
                //console.log('用户点击确定');
                wx.redirectTo({
                  url: '../lists?id=' + id + "&mainid=" + mainid,
                })
              }
            }
          })
        }
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