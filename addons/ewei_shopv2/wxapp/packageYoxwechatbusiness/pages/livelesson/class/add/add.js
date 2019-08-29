var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    id: "",
    message: "",
    luStatu: false, //di'bu,
    voice: [],
    url:[],
    state: ""
  },
  onLoad: function(options) {
    console.log(options.id)
    var that = this;
    that.setData({
      id: options.id
    })
  },
  getedit: function() {
    var that = this;
    var id = that.data.id;
    var message = that.data.message;
    var voice = that.data.voice;
    console.log(voice)
    var url = that.data.url;
    e.get("yoxwechatbusiness/course/course_message_edit", {
      isajax: 1,
      type: "message",
      message: message,
      voice: url,
      course_id: id
    }, function(e) {
      if (e.result.message == "成功") {
        wx.navigateBack({
          delta: 1
        })
      }
    })
  },
  send: function() {
    this.getedit();
  },
  text_input: function(e) {
    this.setData({
      message: e.detail.value
    })
  },
  // 触摸开始
  touchStart: function() {
    // console.log('touchStart', e);
    // var start = e.timeStamp;
    // var seconds = (start % (1000 * 60)) / 1000;
    this.setData({
      luStatu: true
    })
    // this.recorderManager.start({
    //   format: 'mp3'
    // });
    var s = this;
    //console.log("start");
    wx.startRecord({
      success: function(res) {
        console.log(res);
        var tempFilePath = res.tempFilePath;
        s.setData({
          voice: tempFilePath
        });
      },
      fail: function(res) {
        console.log("fail");
        console.log(res);
        //录音失败
      }
    });
  },

  // 触摸结束
  touchEnd: function() {
    this.setData({
      luStatu: false
    })
    var s = this;
    console.log("end");
    wx.stopRecord();
    setTimeout(function() {
      var o = e.getUrl("util/uploader/upload", {
        file: "file",
        type: "voice"
      }),
        i = s.data.voice;
      console.log(i)
      wx.uploadFile({
        url: o,
        filePath: i,
        name: "file",
        success: function(n) {
          console.log(n)
          s.hideLoading();
          console.log(n.data)
          var o = JSON.parse(n.data);
          console.log(o.files[0])
          console.log(o.files[0].url)
          var url = o.files[0].url;
          s.setData({
            url: url
          })
          s.getedit();
        },
        fail: function(n) {
          console.log(n)
        }
      })
    }, 1000)

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
  onReady: function() {
  },
  onShow: function() {
    t.getRecordAuth();
  }
})