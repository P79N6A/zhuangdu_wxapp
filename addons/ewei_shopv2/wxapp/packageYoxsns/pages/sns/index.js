var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    winHeight: "",//窗口高度
    currentTab: 0, //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    community:[],
    images:"",
    title:"",
    createtime:"",
    goodcount:"",
    postcount:""
  },
  // 滚动切换标签样式
  switchTabnav: function (e) {
    this.setData({
      currentTab: e.detail.current
    });
    // this.checkCor();
  },
  // 点击标题切换当前页时改变样式
  swichNav1: function (e) {
    var cur = e.target.dataset.current;
    if (this.needLoadNewDataAfterSwiper1()) {
      this.getCommunity(id);
    }
    if (this.data.currentTaB == cur) { return false; }
    else {
      this.setData({
        currentTab: cur
      })
    }
  },
  onDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../post/post?id=' + id,
    })
  },
  //判断当前滚动超过一屏时，设置tab标题滚动条。
  // checkCor: function () {
  //   if (this.data.currentTab > 4) {
  //     this.setData({
  //       scrollLeft: 300
  //     })
  //   } else {
  //     this.setData({
  //       scrollLeft: 0
  //     })
  //   }
  // },
  tabcommunity:function(t){
    this.setData({
      images: t.target.dataset.images,
      title: t.target.dataset.title,
      createtime:t.target.dataset.createtime,
      goodcount: t.target.dataset.goodcount,
      postcount: t.target.dataset.postcount
    })
  },
  needLoadNewDataAfterSwiper1: function () {
    return this.data.community.length > 0 ? false : true;
  },
  getCommunity:function(){
    var that=this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/board/getlist", {
      isajax: 1
    }, function (e) {
      that.setData({
        community: e.result.list
      })
      wx.hideToast();
      console.log(that.data.community)
    })
  },
  onLoad: function () {
    var that = this;
    //  高度自适应
    that.getCommunity();
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR;
        console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
  },
  footerTap: t.footerTap
})