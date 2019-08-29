var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    name: "",
    time_start: "",
    time_end: "",
    img:""
  },
  onLoad: function (options) {
    var that = this;
  },
  onReady: function () {

  },

  onShow: function () {

  },

  onHide: function () {

  },
  onUnload: function () {

  },
  getbankedit: function () {
    var that = this;
    var name = that.data.name;
    var time_start = that.data.time_start;
    var time_end = that.data.time_end;
    var img = that.data.img;
    e.get("yoxwakeupchallenge/activity/activity_edit", {
      isajax: 1,
      name: name,
      time_start: time_start,
      time_end: time_end,
      image:img
    }, function (e) {
      that.setData({
      })
    })
  },
  getname: function (e) {
    var that = this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      name: e.detail.value
    })
  },
  gettimestart: function (e) {
    var that = this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      time_start: e.detail.value
    })
  },
  gettimeend: function (e) {
    var that = this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      time_end: e.detail.value
    })
  },
  formSubmit(e) {
    this.getbankedit();
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
    let img = this.data.img;
    wx.chooseImage({
      success: function (n) {
        e.loading("正在上传...");
        var o = e.getUrl("util/uploader/upload", {
          file: "file"
        }),
          i = n.tempFilePaths;
        wx.uploadFile({
          url: o,
          filePath: i[0],
          name: "file",
          success: function (n) {
            e.hideLoading();
            var o = JSON.parse(n.data);
            console.log(o)
            var url = o.files[0].url;
            that.setData({
              img:url
            })
            if (0 != o.error) e.alert("上传失败");
            else if ("function" == typeof t) {
              var i = o.files[0];
              t(i);
            }
          }
        });
      }
    });
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})