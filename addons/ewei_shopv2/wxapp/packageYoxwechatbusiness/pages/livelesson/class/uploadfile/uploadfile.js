var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    imgs: [],
    files:[],
    videos:[],
    id:""
  },
  onLoad: function (options) {
    console.log(options.id);
    var that=this;
    that.setData({
      id:options.id
    })
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
            var imgs = that.data.imgs;
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
            if (imgs.length > 9) {
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
  },
  //选择要上传的上传文件
  choosefilefun() {
    let that = this;
    if (that.data.files!==[]) {
      var type = "document";
    } else {
      var type = "image";
    }
    wx.chooseMessageFile({
      count: 10,
      type: type,
      success(res) {
        that.loading("正在上传...");
        var o = e.getUrl("util/uploader/upload", {
          file: "file"
        }),
          i = res.tempFiles[0].path;
        console.log(i)
        wx.uploadFile({
          url: o,
          filePath: i,
          name: "file",
          success: function (n) {
            that.hideLoading();
            console.log(n.data)
          },
          fail: function (n) {
            console.log(n)
          }
        });
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
  complete:function(){
    this.getedit();
  },  
  downloadFile: function (e) {
    console.log(e);
    let type = e.currentTarget.dataset.type;
    let url = e.currentTarget.dataset.url;
    switch (type) {
      case "pdf":
        url += 'pdf';
        break;
      case "word":
        url += 'docx';
        break;
      case "excel":
        url += 'xlsx';
        break;
      default:
        url += 'pptx';
        break;
    }
    wx.downloadFile({
      url: url,
      header: {},
      success: function (res) {
        var filePath = res.tempFilePath;
        console.log(filePath);
        wx.openDocument({
          filePath: filePath,
          success: function (res) {
            console.log('打开文档成功')
          },
          fail: function (res) {
            console.log(res);
          },
          complete: function (res) {
            console.log(res);
          }
        })
      },
      fail: function (res) {
        console.log('文件下载失败');
      },
      complete: function (res) { },
    })
  },
  getedit: function () {
    var that = this;
    var id = that.data.id;
    var files = that.data.files;
    var imgs = that.data.imgs;
    console.log(imgs)
    var videos = that.data.videos;
    if (files == [] || imgs!==[]){
      var type = "image";
    }else{
      var type = "document";
    }
    e.get("yoxwechatbusiness/course/course_message_edit", {
      isajax: 1,
      type: type,
      "image[]": imgs,
      "file[]": files,
      "video[]":videos,
      course_id: id
    }, function (e) {
      if (e.result.message == "成功") {
        wx.navigateBack({
          delta: 1
        })
      }
    })
  },
  deleteImg(e) {
    let _index = e.currentTarget.dataset.index;
    let imgs = this.data.imgs;
    imgs.splice(_index, 1);
    this.setData({
      imgs
    })
  }
})