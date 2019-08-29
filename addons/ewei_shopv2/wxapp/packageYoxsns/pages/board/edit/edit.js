var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    textareaTxt: null,
    imgArr: null,
    location: null,
    title: "",
    content: "",
    mainid: "",
    imgs: []
  },
  onLoad: function(options) {
    console.log(options.mainid)
    var that = this;
    that.setData({
      mainid: options.mainid
    })
  },
  onReady: function() {},
  onShow: function() {},
  onHide: function() {},
  onUnload: function() {},
  onReachBottom: function() {},
  onShareAppMessage: function() {},
  saveEdit: function() {
    wx.showModal({
      title: '将此次编辑保留',
      content: '',
      cancelText: '不保留',
      confirmText: '保留',
      success(res) {
        if (res.confirm) {
          // console.log('用户点击确定')
        } else if (res.cancel) {
          wx.navigateBack({
            delta: 1
          })
        }
      }
    })
  },
  getInputValue(e) {
    var that = this;
    console.log(e.detail.value)
    that.setData({
      content: e.detail.value
    })
    console.log(that.data.content)
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
        e.get("yoxutil/uploader", {}, function(e) {
          let tempFilePaths = res.tempFilePaths;
          console.log(tempFilePaths)
          let imgs = that.data.imgs;
          console.log(imgs)
          for (let i = 0; i < tempFilePaths.length; i++) {
            if (imgs.length < 2) {
              imgs.push(tempFilePaths[i])
            } else {
              that.setData({
                imgs
              })
              wx.showModal({
                title: '提示',
                content: '最多只能有两张图片'
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
  formSubmit: function(mainid) {
    var that = this;
    var mainid = that.data.mainid;
    console.log(mainid)
    var content = that.data.content;
    //var title = that.data.title;
    var imgs = that.data.imgs;
    // 如果文本为空提示用户输入 否则提交表单
    if (content == '') {
      wx.showModal({
        content: '请填写内容或标题后点击提交保存！',
        showCancel: false,
        success: function(res) {
          if (res.confirm) {
            //console.log('用户点击确定');
          }
        }
      });
    } else {
      e.get("yoxsns/post/submit", {
        isajax: 1,
        bid: mainid,
        content: content,
        images: imgs
      }, function(e) {
        console.log(e.status)
        if (e.status == 0) {
          wx.showToast({
            title: e.result.message,
          })
          return;
        }
        wx.showToast({
          title: '发表成功',
        })
        wx.redirectTo({
          url: '../board?id=' + mainid,
        })
      })
    }
  },
  chooseLocation: function() {
    var that = this;
    wx.chooseLocation({
      success(res) {
        // console.log(res.name)
        that.setData({
          location: res.name
        })
      }
    })
  },
  issue: function() {
    var that = this;
    wx.navigateBack({
      delta: 1
    })
    // e.get()
  }
})