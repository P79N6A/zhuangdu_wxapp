var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    filePath: "",
    flag: false,
    url: "",
    suggest_info: "",
    result_flag: false,
    suggest: "",
    title: "",
    marketprice: "",
    price: ""
  },
  onLoad: function(options) {

  },
  onReady: function() {

  },
  onShow: function() {

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
  upload: function(t) {
    var that = this;
    wx.chooseImage({
      success: function(n) {
        that.loading("正在上传...");
        var o = e.getUrl("util/uploader/upload", {
            file: "file"
          }),
          i = n.tempFilePaths;
        wx.uploadFile({
          url: o,
          filePath: i[0],
          name: "file",
          success: function(n) {
            that.hideLoading();
            var o = JSON.parse(n.data);
            //console.log(o.files[0].url)
            if (0 != o.error) e.alert("上传失败");
            else if ("function" == typeof t) {
              var i = o.files[0];
              t(i);
            }
            //console.log(o)
            that.setData({
              url: o.files[0].url,
              flag: true
            })
            //console.log(that.data.url)
          },
          fail: function(n) {
            console.log(n)
          }
        });
      }
    });
  },
  tabimage: function(t) {
    // this.setData({
    //   suggest:t.target.dataset.suggest,

    // })
  },
  toimage: function() {
    var that = this;
    var url = that.data.url;
    // console.log(url)
    e.post("yoxface/face_result", {
      isajax: 1,
      "image[]": url,
    }, function(e) {
      that.setData({
        suggest_info: e.data.files.faces,
        flag: false,
        // result_flag:true
      })
      var suggest_info = that.data.suggest_info;
      console.log(suggest_info)
      wx.navigateTo({
        url: './detail/detail?suggest_info=' + JSON.stringify(suggest_info),
      })
    })
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