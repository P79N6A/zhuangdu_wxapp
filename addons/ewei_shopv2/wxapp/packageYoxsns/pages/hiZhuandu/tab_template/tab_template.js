var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    topTabItems: ["推荐", "彩妆", "护肤", "美容", "招商", "更多"],
    group2List:[
      {
        imgUrl:"",
        text:"品牌讲堂"
      },
      {
        imgUrl: "",
        text: "VIP会员"
      },
      {
        imgUrl: "",
        text: "精品小课"
      },
      {
        imgUrl: "",
        text: "大师课"
      },
      {
        imgUrl: "",
        text: "免费专区"
      },
      {
        imgUrl: "",
        text: "课程优惠劵"
      }
    ],
    winHeight: "",//窗口高度
    currentTopItem: "0", //预设当前项的值,
    imagesList: [
      './index_template/images/bg.jpg',
      './index_template/images/bg.jpg',
      './index_template/images/bg.jpg'
    ],
    indicatorDots: false,
    autoplay: false,
    interval: 5000,
    duration: 1000,
    swiperIndex:0,
    currentTab:"0",
    community: [],
    images: "",
    title: "",
    createtime: "",
    goodcount: "",
    postcount: ""
  },
  swiperChange(e) {
    const that = this;
    that.setData({
      swiperIndex: e.detail.current,
    })
  },
  // 滚动切换标签样式
  switchTab: function (e) {
    this.setData({
      currentTab: e.detail.currenttab
    });
    this.checkCor();
  },
  onDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../post/post?id=' + id,
    })
  },
  // 点击标题切换当前页时改变样式
  swichNav: function (e) {
    var cur = e.target.dataset.currenttab;
    if (this.data.currentTab == cur) { return false; }
    else {
      this.setData({
        currentTab: cur
      })
    }
  },
  //判断当前滚动超过一屏时，设置tab标题滚动条。
  checkCor: function () {
    if (this.data.currentTab > 4) {
      this.setData({
        scrollLeft: 300
      })
    } else {
      this.setData({
        scrollLeft: 0
      })
    }
  },
  tabcommunity: function (t) {
    this.setData({
      images: t.target.dataset.images,
      title: t.target.dataset.title,
      createtime: t.target.dataset.createtime,
      goodcount: t.target.dataset.goodcount,
      postcount: t.target.dataset.postcount
    })
  },
  getCommunity: function () {
    var that = this;
    e.get("yoxsns/board/getlist", {
      isajax: 1
    }, function (e) {
      that.setData({
        community: e.result.list
      })
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
  onPullDownRefresh: function() {

  },
  onReachBottom: function() {

  },
  onShareAppMessage: function() {

  },
  onLoad: function () {
    var that = this;
    that.getCommunity();
    //  高度自适应
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR+1050;
        console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
  },
  footerTap: t.footerTap
})