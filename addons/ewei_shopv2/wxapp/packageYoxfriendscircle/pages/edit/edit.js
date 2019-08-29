var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    textareaTxt: null,
    imgArr: null,
    location: null
  },
  onLoad: function (options) {},
  onReady: function () {},
  onShow: function () {},
  onHide: function () {},
  saveEdit:function(){
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
    this.setData({
      textareaTxt: e.detail.value
    })
  },
  chooseImage() {
    var that = this;
    wx.chooseImage({
      count: 9,
      sizeType: 'compressed',
      sourceType: ['album', 'camera'],
      success(res) {
        // tempFilePath可以作为img标签的src属性显示图片
        that.setData({
          imgArr: res.tempFilePaths
        })
      }
    })
  },
  chooseLocation:function(){
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
  issue: function (videos, name, thumb, url){
    var that=this;
    var imgArr = that.data.imgArr;
    var textareaTxt = that.data.textareaTxt;
    e.get("yoxfriendscircle/edit", {
      isajax: 1,
      description: textareaTxt,
      "thumbs[]": imgArr,
      videos: videos,
      is_featured: 0,
      name: name,
      thumb: thumb,
      url: url
    }, function (e) {
      wx.navigateBack({
        delta: 1
      })
    })
  }
})