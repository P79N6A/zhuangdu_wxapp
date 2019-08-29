var t = getApp(),e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));

Page({
    data: {
        page: 1,
        params: {},
        loaded: !1
    },
    onLoad: function(t) {
        console.log(t);
    },
    saveQrcode:function(){
            var imgSrc = "http://yiqixiu.igzlrj.com/Uploads/ad/2019-05-22/5ce4bb3cc6263.png";
                wx.downloadFile({
                  url: imgSrc,
                  success: function (res) {
                    console.log(res);
                    //图片保存到本地
                    wx.saveImageToPhotosAlbum({
                      filePath: res.tempFilePath,
                      success: function (data) {
                        wx.showToast({
                          title: '保存成功',
                          icon: 'success',
                          duration: 2000
                        })
                      },
                      fail: function (err) {
                        console.log(err);
                        if (err.errMsg === "saveImageToPhotosAlbum:fail auth deny") {
                          console.log("当初用户拒绝，再次发起授权")
                          wx.openSetting({
                            success(settingdata) {
                              console.log(settingdata)
                              if (settingdata.authSetting['scope.writePhotosAlbum']) {
                                console.log('获取权限成功，给出再次点击图片保存到相册的提示。')
                              } else {
                                console.log('获取权限失败，给出不给权限就无法正常使用的提示')
                              }
                            }
                          })
                        }
                      },
                      complete(res){
                        console.log(res);
                      }
                    })
                  }
            })
    }
});