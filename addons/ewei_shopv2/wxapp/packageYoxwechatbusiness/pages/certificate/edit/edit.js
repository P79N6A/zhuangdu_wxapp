var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    merch_user_list: [],
    merchname: "",
    certificate: [],
    merchid: ""
  },
  onLoad: function(options) {
    var that = this;
    that.getbrand_cate_list();
  },
  tabbrand_cate_list: function(t) {
    this.setData({
      merchname: t.target.dataset.merchname
    })
  },
  add_path: function() {

  },
  merch_choice: function(e) {
    this.setData({
      merchid: e.currentTarget.dataset.id
    })
    console.log(this.data.merchid)
  },
  complete: function() {
    this.getcertificate_edit();
  },
  getbrand_cate_list: function() {
    var that = this;
    e.get("merch/yoxmerch/merch_user_list", {
      pindex: 1,
      isajax: 1
    }, function(e) {
      that.setData({
        merch_user_list: e.data.list
      })
    })
  },
  loading: function(t) {
    void 0 !== t && "" != t || (t = "加载中"), wx.showToast({
      title: t,
      icon: "loading",
      duration: 5e6
    });
  },
  hideLoading: function() {
    wx.hideToast();
  },
  chooseImg() {
    let that = this;
    let len = this.data.certificate;
    if (len >= 9) {
      this.setData({
        lenMore: 1
      })
      return;
    }
    wx.chooseImage({
      success: function(n) {
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
          success: function(n) {
            that.hideLoading();
            var o = JSON.parse(n.data);
            var certificate = that.data.certificate;
            console.log(o.files[0].url)
            var url = o.files[0].url;
            // if (0 != o.error) e.alert("上传失败");
            // else if ("function" == typeof t) {
            certificate.push(url)
            console.log(certificate)
            that.setData({
              certificate
            })
            console.log(that.data.certificate)
            if (certificate.length > 9) {
              wx.showModal({
                title: '提示',
                content: '最多只能有九张图片'
              })
              return;
            }
            // }
          },
          fail: function(n) {
            console.log(n)
          }
        });
      }
    });
  },
  getcertificate_edit: function(id) {
    var that = this;
    var merchid = that.data.merchid;
    var certificate = that.data.certificate;
    e.get("yoxwechatbusiness/certificate/certificate_edit", {
      isajax: 1,
      id: id,
      "certificate[]": certificate,
      merchid: merchid
    }, function(e) {
      wx.showToast({
        title: e.result.message,
        icon:"success",
        duration:2000
      })
      // wx.navigateBack({
      //     url:'../index/index'
      // })
    })
   }
  })