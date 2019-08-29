var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    topTabItems: ["私信", "粉丝", "系统"],
    personalList: [],
    currentTopItem: "0",
    winHeight: "",
  },
  //切换顶部标签
  switchnavTab: function(e) {
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
  },
  bindChange: function(e) {
    var that = this;
    that.setData({
      currentTopItem: e.detail.current
    })
  },
  onLoad: function(options) {
    var that = this;
    that.getcommit();
    wx.getSystemInfo({
      success: function(res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR;
        // console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
  },
  getcommit: function() {
    var that = this;
    e.get("yoxfriendscircle/comment/comment_list", {
      isajax: 1,
      type: "MESSAGE"
    }, function(e) {
      that.setData({
        personalList: e.data.list,
      })
    })
  }
})