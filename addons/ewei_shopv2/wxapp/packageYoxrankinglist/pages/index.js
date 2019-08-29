var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    topTabItems: ["热搜榜", "话题榜", "同城榜", "好友榜"],
    currentTopItem: "0",
    winHeight: "",
    hotsearchList: [],
    topicList: [],
    samecityList: [{
        imgUrl: "",
        title: "#新乡身边事#",
        discussnumber: "4.5万",
        readnumber: "2亿"
      },
      {
        imgUrl: "",
        title: "#新乡线下实体店#",
        discussnumber: "4.5万",
        readnumber: "2亿"
      },
      {
        imgUrl: "",
        title: "#新乡身边事#",
        discussnumber: "4.5万",
        readnumber: "2亿"
      },
      {
        imgUrl: "",
        title: "#妆度水母面膜#",
        discussnumber: "4.5万",
        readnumber: "2亿"
      },
      {
        imgUrl: "",
        title: "#妆度水母面膜#",
        discussnumber: "4.5万",
        readnumber: "2亿"
      }
    ],
    goodfriendList: [],
    currentTab: 0,
    items: [{
        "pagePath": "../../pages/index/index",
        "iconPath": "../../static/images/tabbar/icon-1.png",
        "selectedIconPath": "../../static/images/tabbar/icon-1-active.png",
        "text": "首页"
      },
      {
        "pagePath": "../../pages/shop/caregory/index",
        "iconPath": "../../static/images/tabbar/icon-10.png",
        "selectedIconPath": "../../static/images/tabbar/icon-10-active.png",
        "text": "妆度"
      },
      // {
      //   "pagePath": "../../pages/topage/ToHiZhuandu/ToHiZhuandu",
      //   "iconPath": "../../static/images/tabbar/icon-102.png",
      //   "selectedIconPath": "../../static/images/tabbar/icon-102.png",
      //   "text": "美妆助理"
      // },
      {
        "pagePath": "../../packageYoxgroupbuy/pages/index/index",
        "iconPath": "../../static/images/tabbar/icon-9.png",
        "selectedIconPath": "../../static/images/tabbar/icon-9-active.png",
        "text": "咔咔团"
      },
      {
        "pagePath": "../../packageYoxwechatbusiness/pages/index/index",
        "iconPath": "../../static/images/tabbar/icon-5.png",
        "selectedIconPath": "../../static/images/tabbar/icon-5-active.png",
        "text": "我的"
      },
    ],
    flag: true,
    topicflag: false,
    searchvalue: "大家正在搜：XXXXX",
    latitude: "",
    longitude: ""
  },
  onLoad: function(options) {
    var that = this;
    that.gethotsearch();
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
  //事件处理函数
  bindChange: function(e) {
    let that = this;
    that.setData({
      currentTab: e.detail.current
    });
  },
  swichNav: function(e) {
    let that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
  //切换顶部标签
  switchTab: function(e) {
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
    if (this.needLoadNewDataAfterSwiper()) {
      this.gettopic();
    }
    if (this.needLoadNewDataAfterSwiper()) {
      this.getfriend();
    }
    if (this.needLoadNewDataAfterSwiper()) {
      this.getcity();
    }
  },
  //swiperChange
  bindChange: function(e) {
    var that = this;
    that.setData({
      currentTopItem: e.detail.current
    });
    if (that.needLoadNewDataAfterSwiper()) {
      that.gettopic();
    }
    if (that.needLoadNewDataAfterSwiper()) {
      that.getfriend();
    }
    if (that.needLoadNewDataAfterSwiper()) {
      that.getcity();
    }
  },
  gethotsearch: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/hotsearch", {
      isajax: 1
    }, function(e) {
      that.setData({
        hotsearchList: e.data.list
      })
      wx.hideToast();
    })
  },
  getcity: function() {
    var that = this;
    wx.getLocation({
      success: function(res) {
        console.log(res.latitude, res.longitude)
        wx.showToast({
          title: "请稍后",
          icon: 'loading',
          duration: (5000 <= 0) ? 5000 : 5000
        });
        e.get("yoxwechatbusiness/frient/update_lat_lng", {
          isajax: 1,
          lng: res.longitude,
          lat: res.latitude,
          page:1
        }, function(e) {
        })
      },
    })
  },
  needLoadNewDataAfterSwiper: function() {
    return this.data.topicList.length > 0 ? false : true;
    return this.data.goodfriendList.length > 0 ? false : true;
  },
  gettopic: function(searchvalue) {  
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      page: 1,
      keyword: searchvalue
    }, function(e) {
      that.setData({
        topicList: e.result.list
      })
      wx.hideToast();
    })
  },
  getfriend: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxwechatbusiness/frient/frient_list", {
      isajax: 1
    }, function(e) {
      that.setData({
        goodfriendList: e.data.list
      })
      wx.hideToast();
    })
  },
  changesearchBox: function() {
    this.setData({
      flag: false,
      topicflag: true
    })
  },
  searchvalue: function(e) {
    console.log(e.detail.value)
    this.setData({
      searchvalue: e.detail.value
    })
  },
  searchIt: function() {
    var searchValue = this.data.searchvalue;
    console.log(searchValue)
    //this.gettopic(searchValue);
    wx.navigateTo({
      url: '../../packageYoxsns/pages/board/board?searchValue=' + searchValue,
    })
  },
  hot_search:function(e){
    var that=this;
    var keyword=e.currentTarget.dataset.keyword;
    console.log(keyword)
    that.setData({
      searchvalue:keyword
    })
    var searchValue = that.data.searchvalue;
    console.log(searchValue)
    wx.navigateTo({
      url: '../../packageYoxsns/pages/board/board?searchValue=' + searchValue,
    })
    // that.gettopic(searchValue); 
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
  footerTap: t.footerTap
})