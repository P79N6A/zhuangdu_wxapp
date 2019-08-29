var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    title:"",
    content:"",
    imgs: []
  },
  onLoad: function (options) {

  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  formSubmit: function (id) {
    var that = this;
    //console.log(event)
    var content = that.data.content;
    var title = that.data.title;
    var imgs = that.data.imgs;
    // 如果文本为空提示用户输入 否则提交表单
    if (content== '' || title== '') {
      wx.showModal({
        content: '请填写内容或标题后点击提交保存！',
        showCancel: false,
        success: function (res) {
          if (res.confirm) {
            //console.log('用户点击确定');
          }
        }
      });
    } else {
      e.get("yoxsns/post/submit", {
        isajax: 1,
        bid: 3,
        title: title,
        content: content,
        images: imgs
      }, function (e) {
        console.log(e.status)
        if(e.status==0){
          wx.showToast({
            title: e.result.message,
          })
          return;
        }
        wx.showToast({
          title: '发表成功',
        })
        wx.redirectTo({
          url: '../wanba',
        })
      })
    }
  },
  commentSubmit: function (e) {
    //console.log(e)
    var that = this;
    console.log(e.detail.value)
    that.setData({
      content: e.detail.value
    })
    console.log(that.data.content)
  },
  titleSubmit:function(e){
    var that = this;
    console.log(e.detail.value)
    that.setData({
      title: e.detail.value
    })
    console.log(that.data.title)
  },
  chooseImg() {
    let that = this;
    let len = this.data.imgs;
    if (len >= 2) {
      this.setData({
        lenMore: 1
      })
      return;
    }
    wx.chooseImage({
      success: (res) => {
        e.get("yoxutil/uploader", {}, function (e) {
          let tempFilePaths = res.tempFilePaths;
          console.log(tempFilePaths)
          let imgs = that.data.imgs;
          for (let i = 0; i < tempFilePaths.length; i++) {
            if (imgs.length < 2) {
              imgs.push(tempFilePaths[i])
            } else {
              that.setData({
                imgs
              })
              wx.showModal({
                title: '提示',
                content: '最多只能有九张图片'
              })
              return;
            }
          }
          that.setData({
            imgs
          })
        })
      }
    })
  },
  previewImg(e) {
    let index = e.currentTarget.dataset.index;
    let imgs = this.data.imgs;
    wx.previewImage({
      current: imgs[index],
      urls: imgs,
    })
  },
  deleteImg(e) {
    let _index = e.currentTarget.dataset.index;
    let imgs = this.data.imgs;
    imgs.splice(_index, 1);
    this.setData({
      imgs
    })
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})