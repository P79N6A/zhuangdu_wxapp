var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util")),
  s = t.requirejs("wxParse/wxParse");
Page({
  data: {
    winHeight: "", //窗口高度
    currentTab: 0, //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    classList: [],
    tabList: [],
    catearticle: [],
    winHeight: "", //窗口高度
    currentTopItem: "0", //预设当前项的值,
    imagesList: [
      './tab_template/index_template/images/bg.jpg',
      './tab_template/index_template/images/bg.jpg',
      './tab_template/index_template/images/bg.jpg'
    ],
    indicatorDots: false,
    autoplay: false,
    interval: 5000,
    duration: 1000,
    swiperIndex: 0,
    scrollLeft: 0, //tab标题的滚动条位置
    community1: [],
    community2: [],
    community3: [],
    images: "",
    title: "",
    createtime: "",
    goodcount: "",
    postcount: "",
    communityList: [],
    tabname: "",
    name: "",
    descript: "",
    thumb: "",
    update_time: "",
    is_hot: ""
  },
  // 滚动切换标签样式
  switchTab: function(e) {
    this.setData({
      currentTab: e.detail.current
    });
    this.checkCor();
  },
  // 点击标题切换当前页时改变样式
  swichNav: function(e) {
    var cur = e.target.dataset.current;
    if (this.data.currentTab == cur) {
      return false;
    } else {
      this.setData({
        currentTab: cur
      })
    }
  },
  // 判断当前滚动超过一屏时，设置tab标题滚动条。
  checkCor: function() {
    if (this.data.currentTab > 4) {
      this.setData({
        scrollLeft: 500
      })
    } else {
      this.setData({
        scrollLeft: 0
      })
    }
  },
  tabclasses: function(t) {
    this.setData({
      name: t.target.dataset.name,
      descript: t.target.dataset.descript,
      thumb: t.target.dataset.thumb,
      is_hot: t.target.dataset.is_hot
    })
  },
  getclasses: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxwechatbusiness/course/course_cate_list", {
      isajax: 1
    }, function(e) {
      that.setData({
        classList: e.data.list
      })
      wx.hideToast();
    })
  },
  tabTabs: function(t) {
    this.setData({
      tabname: t.target.dataset.name
    })
  },
  getcatearticle: function() {
    var that = this;
    e.get("yoxarticle/list", {
      isajax: 1
    }, function(e) {
      that.setData({
        catearticle: e.data.list
      })
    })
  },
  gettabs: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxwechatbusiness/course/course_list", {
      isajax: 1
    }, function(e) {
      for (var i = 0; i < e.data.list.length; i++) {
        e.data.list[i].update_time = a.formatTimeTwo(e.data.list[i].update_time, 'Y/M/D h:m:s')
      }
      that.setData({
        tabList: e.data.list
      })
      wx.hideToast();
      //console.log(e.data.list)
    })
  },
  jumpDetail: function(e) {
    // console.log(e)
    var id = e.currentTarget.dataset.id;
    var name = e.currentTarget.dataset.name;
    var update_time = e.currentTarget.dataset.update_time;
    var thumb = e.currentTarget.dataset.thumb;
    var descript = e.currentTarget.dataset.descript
    // wx.navigateTo({
    //   url: './detail/detail?id=' + id + "&name=" + name + "&update_time" + update_time,
    // })
    wx.navigateTo({
      url: './new_detail/new_detail?id=' + id + "&name=" + name + "&update_time=" + update_time + "&thumb=" + thumb + "&descript=" + descript,
    })
  },
  // 滚动切换标签样式
  switchnavTab: function(e) {
    this.setData({
      currentTab: e.detail.current
    });
    this.checkCor();
  },
  swiperChange(e) {
    const that = this;
    that.setData({
      swiperIndex: e.detail.current,
    })
  },
  // 点击标题切换当前页时改变样式
  swichNav: function(e) {
    var cur = e.target.dataset.currenttab;
    if (this.data.currentTab == cur) {
      return false;
    } else {
      this.setData({
        currentTab: cur
      })
    }
  },
  //事件处理函数
  // switchnavTab: function(e) {
  //   let that = this;
  //   that.setData({
  //     currentTab: e.detail.current
  //   });
  // },
  swichNav1: function(e) {
    let that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
  tabcommunity: function(t) {
    this.setData({
      images: t.target.dataset.images,
      title: t.target.dataset.title,
      createtime: t.target.dataset.createtime,
      goodcount: t.target.dataset.goodcount,
      postcount: t.target.dataset.postcount
    })
  },
  getCommunity1: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      bid:11,
      page:1
    }, function(e) {
      for (var i = 0; i < e.result.list;i++){
        s.wxParse('article1', 'html', e.result.list[i].content, that); 
      }
      that.setData({
        community1: e.result.list
      })
      wx.hideToast();
    })
  },
  getCommunity2: function () {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      bid: 12,
      page: 1
    }, function (e) {
      for (var i = 0; i < e.result.list; i++) {
        s.wxParse('article2', 'html', e.result.list[i].content, that);
      }
      that.setData({
        community2: e.result.list
      })
      wx.hideToast();
    })
  },
  getCommunity3: function () {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      bid: 13,
      page: 1
    }, function (e) {
      for (var i = 0; i < e.result.list; i++) {
        s.wxParse('article3', 'html', e.result.list[i].content, that);
      }
      that.setData({
        community3: e.result.list
      })
      wx.hideToast();
    })
  },
  getcatearticle: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxarticle/list", {
      isajax: 1
    }, function(e) {
      that.setData({
        catearticle: e.data.list
      })
      wx.hideToast();
    })
  },
  tocommunity_more:function(e){
    var bid = e.currentTarget.dataset.bid;
    wx.navigateTo({
      url: '../../../packageYoxsns/pages/board/board?id='+bid,
    })
  },
  getArticle: function() {
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that = this;
    e.get("yoxarticle/list/getlist", {
      isajax: 1
    }, function(e) {
      wx.hideToast();
    })
  },
  //判断当前滚动超过一屏时，设置tab标题滚动条。
  checkCor: function() {
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
  onLoad: function() {
    var that = this;
    that.getclasses();
    that.gettabs();
    that.getCommunity1();
    that.getCommunity2();
    that.getCommunity3();
    that.getcatearticle();
    //  高度自适应
    wx.getSystemInfo({
      success: function(res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR+1070;
        that.setData({
          winHeight: calc
        });
      }
    });
  }
})