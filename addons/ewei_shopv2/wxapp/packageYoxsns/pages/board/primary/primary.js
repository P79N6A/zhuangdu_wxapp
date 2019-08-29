var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
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
  onReady: function() {

  },
  onShow: function() {

  },
  onHide: function() {

  },
  onUnload: function() {

  },
  formSubmit: function(mainid) {
    var that = this;
    var bid = that.data.mainid;
    console.log(bid)
    var content = that.data.content;
    var title = that.data.title;
    var imgs = that.data.imgs;
    // 如果文本为空提示用户输入 否则提交表单
    if (content == '' || title == '') {
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
        bid: bid,
        title: title,
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
        if (bid==4){
          wx.redirectTo({
            url: '../appeal/appeal?id='+bid,
          })
          return;
        }
        wx.redirectTo({
          url: '../board?id=' + bid,
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
    console.log(that.data.content)
  },
  titleSubmit: function(e) {
    var that = this;
    console.log(e.detail.value)
    that.setData({
      title: e.detail.value
    })
    console.log(that.data.title)
  },
  loading: function (t) {
    void 0 !== t && "" != t || (t = "加载中"), wx.showToast({
      title: t,
      icon: "loading",
      duration: 5e6
    });
  },
  hideLoading: function () {
    wx.hideToast();
  },
  chooseImg() {
    let that = this;
    let len = this.data.imgs;
    if (len >= 9) {
      this.setData({
        lenMore: 1
      })
      return;
    }
    wx.chooseImage({
      success: function (n) {
        that.loading("正在上传...");
        var o = e.getUrl("util/uploader/upload", {
          file: "file"
        }),
          i = n.tempFilePaths;
          console.log(i)
        wx.uploadFile({
          url: o,
          filePath: i[0],
          name: "file",
          success: function (n) {
            that.hideLoading();
            var o = JSON.parse(n.data);
            var imgs=that.data.imgs;
            console.log(o.files[0].url)
            var url = o.files[0].url;
            // if (0 != o.error) e.alert("上传失败");
            // else if ("function" == typeof t) {
                  imgs.push(url)
                  console.log(imgs)
                  that.setData({
                    imgs
                  })
                  console.log(that.data.imgs)
                  if(imgs.length>9){
                    wx.showModal({
                      title: '提示',
                      content: '最多只能有九张图片'
                    })
                    return;
                  }
            // }
          },
          fail: function (n) {
            console.log(n)
          }
        });
      }
    });
    // wx.chooseImage({
    //   success: (res) => {
    //     e.get("util/uploader/upload", {}, function(e) {
    //       let tempFilePaths = res.tempFilePaths;
    //       console.log(tempFilePaths)
    //       let imgs = that.data.imgs;
    //       console.log(imgs)
    //       for (let i = 0; i < tempFilePaths.length; i++) {
    //         if (imgs.length < 2) {
    //           imgs.push(tempFilePaths[i])
    //         } else {
    //           that.setData({
    //             imgs
    //           })
    //           wx.showModal({
    //             title: '提示',
    //             content: '最多只能有两张图片'
    //           })
    //           return;
    //         }
    //       }
    //       that.setData({
    //         imgs
    //       })
    //     })
    //   }
    // })
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
  onPullDownRefresh: function() {

  },
  onReachBottom: function() {

  },
  onShareAppMessage: function() {

  }
})