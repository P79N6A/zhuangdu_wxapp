// packageYoxsns/pages/hiZhuandu/index.js
var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    navList:[ 
      {
        imgUrl:"https://zdu.igdlrj.com/attachment/images/2/2018/12/Ti4sBZ0M4qhQpDkfhg0D46qd4Q4FQ4.png",
        name:"皮肤测试"
      },
      {
        imgUrl:"https://zdu.igdlrj.com/attachment/images/2/2018/12/E9v3EJ3uGo0UQTuhz9TLv0P0VApPz9.png",
        name:"肤质报告"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/Zj7P18P01B970PD1zP77pPjO0Wg9pZ.png",
        name: "护肤方案"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/UMpwSpZRe77w81zw4N9kPlvpm11nz7.png",
        name: "美妆在线"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/Rq7GV7c9AccRy5KT97TKor4LWYwK4c.png",
        name: "我得计划"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/h30SVQqWHz0nymBlHMV0y62zd2BvC2.png",
        name: "变妆show"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/KDw3ILh1P81Cdol5O3zqweq51Pe85P.png",
        name: "化妆台"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/SlQnA8K8tL838PS0t3N7d1eZ110t0t.png",
        name: "专属笔记"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/weq1mi1QrHUqeUP9Dp1qI11EKRH9UE.png",
        name: "产品推荐"
      },
      {
        imgUrl: "https://zdu.igdlrj.com/attachment/images/2/2018/12/l3PPMC24SF3PjDsJK523345PmFlkeO.png",
        name: "更多"
      }
    ],
    topTabItems: ["推荐", "彩妆", "护肤", "美容", "招商", "更多"],
    group2List: [
      {
        imgUrl: "",
        text: "品牌讲堂"
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
    items: [{
      "pagePath": "../../../pages/index/index",
      "iconPath": "../../../static/images/tabbar/icon-1.png",
      "selectedIconPath": "../../../static/images/tabbar/icon-1-active.png",
      "text": "首页"
    },
    {
      "pagePath": "../../../pages/shop/caregory/index",
      "iconPath": "../../../static/images/tabbar/icon-10.png",
      "selectedIconPath": "../../../static/images/tabbar/icon-10-active.png",
      "text": "妆度"
    },
    // {
    //   "pagePath": "../../../pages/topage/ToHiZhuandu/ToHiZhuandu",
    //   "iconPath": "../../../static/images/tabbar/icon-102.png",
    //   "selectedIconPath": "../../../static/images/tabbar/icon-102.png",
    //   "text": "美妆助理"
    // },
    {
      "pagePath": "../../../packageYoxgroupbuy/pages/index/index",
      "iconPath": "../../../static/images/tabbar/icon-9.png",
      "selectedIconPath": "../../../static/images/tabbar/icon-9-active.png",
      "text": "咔咔团"
    },
    {
      "pagePath": "../../../pages/topage/ToUser/ToUser",
      "iconPath": "../../../static/images/tabbar/icon-5.png",
      "selectedIconPath": "../../../static/images/tabbar/icon-5-active.png",
      "text": "我的"
    },
    ],
    currentTab: 2,
    winHeight: "",//窗口高度
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
    winHeight: "",//窗口高度
    // currentTab: "0", //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    community: [],
    images: "",
    title: "",
    createtime: "",
    goodcount: "",
    postcount: "",
    location: '',
    county: '',
    today: "",
    weatherData: '',
    air: '',
    dress: '',
    weather:[],
    catearticle:[],
    communityList:[],
    currentnavtab:0
  },
  // 滚动切换标签样式
  switchTab: function (e) {
    this.setData({
      currentnavtab: e.detail.currenttab
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
  swichNav: function (e) {
    var cur = e.target.dataset.currenttab;
    if (this.data.currentnavtab == cur) { return false; }
    else {
      this.setData({
        currentnavtab: cur
      })
    }
  },
  //事件处理函数
  switchnavTab: function (e) {
    let that = this;
    console.log(e)
    var current = e.detail.current;
    console.log(current)
    // if(that.needLoadNewDataAfterSwiper()){
      that.getCommunity(current);
    // }
    that.setData({
      currentnavtab: e.detail.current
    });
  },
  tips:function(){
    wx.showToast({
      icon:"loading",
      title: '功能正在开发中',
    })
  },
  swichNav1: function (e) {
    let that = this;
    console.log(e)
    var current = e.currentTarget.dataset.current;
    console.log(current)
    // if(that.needLoadNewDataAfterSwiper()){
      that.getCommunity(current);
    // }
    if (this.data.currentnavtab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentnavtab: e.target.dataset.current
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
  getCommunity: function (current) {
    var that = this;
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      bid: current
    }, function (e) {
      var arr = e.result.list;
      //var arr2 = arr.slice(0,3)
      // console.log(arr2)
      that.setData({
        community: arr
      })
    })
  },
  needLoadNewDataAfterSwiper: function () {
    return this.data.community.length > 0 ? false : true;
  },
  getcatearticle: function () {
    var that = this;
    e.get("yoxarticle/list", {
      isajax: 1
    }, function (e) {
      that.setData({
        catearticle: e.data.list
      })
    })
  },
  getArticle: function () {
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that = this;
    e.get("yoxarticle/list/getlist", {
      isajax: 1
    }, function (e) {
      wx.hideToast();
    })
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
  onLoad: function (options) {
    var that = this;
    //  高度自适应
    that.getCommunity();
    that.getArticle();
    that.getcatearticle();
    //更新当前日期
    t.globalData.day = a.formatTime(new Date()).split(' ')[0];
    that.setData({
      today: t.globalData.day
    });
    //定位当前城市
    // that.getLocation();
    that.getlocation();
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
  // //定位当前城市
  // getLocation: function () {
  //   var that = this;
  //   wx.getLocation({
  //     type: 'wgs84',
  //     success: function (res) {
  //       //console.log(res)
  //       //当前的经度和纬度
  //       let latitude = res.latitude;
  //       //console.log(latitude)
  //       let longitude = res.longitude;
  //       //console.log(longitude)
  //       wx.request({
  //         url: `https://apis.map.qq.com/ws/geocoder/v1/?location=${latitude},${longitude}&key=${t.globalData.tencentMapKey}`,
  //         success: res => {
  //           //console.log(res)
  //           t.globalData.defaultCity = t.globalData.defaultCity ? t.globalData.defaultCity : res.data.result.ad_info.city;
  //           //console.log(t.globalData.defaultCity)
  //           t.globalData.defaultCounty = t.globalData.defaultCounty ? t.globalData.defaultCounty : res.data.result.ad_info.district;
  //           //console.log(t.globalData.defaultCounty)
  //           that.setData({
  //             location: t.globalData.defaultCity,
  //             county: t.globalData.defaultCounty
  //           });
  //           // that.getWeather();
  //           // that.getAir();
  //         }
  //       })
  //     }
  //   })
  // },
  getlocation:function(){
    var that=this;
    var location = that.data.location
    wx.getLocation({
      type: 'wgs84',
      success: function(res) {
        e.get("yoxwechatbusiness/weather", {
          isajax: 1,
          location: "广州"
        }, function (e) {
          that.setData({
            weather:e.data.HeWeather6[0]
          })
          console.log(e.data.HeWeather6[0])
        })
      },
    })
  },
  // //获取天气
  // getWeather: function (e) {
  //   var length = this.data.location.length;
  //   var city = this.data.location.slice(0, length - 1); //分割字符串
  //   //console.log(city);
  //   var that = this;
  //   var param = {
  //     key: t.globalData.heWeatherKey,
  //     location: city
  //   };
  //   //发出请求
  //   wx.request({
  //     url: t.globalData.heWeatherBase + "/s6/weather",
  //     data: param,
  //     header: {
  //       'content-type': 'application/json'
  //     },
  //     success: function (res) {
  //       console.log(res)
  //       t.globalData.weatherData = res.data.HeWeather6[0].status == "unknown city" ? "" : res.data.HeWeather6[0];
  //       var weatherData = t.globalData.weatherData ? t.globalData.weatherData.now : "暂无该城市天气信息";
  //       var dress = t.globalData.weatherData ? res.data.HeWeather6[0].lifestyle[1] : { txt: "暂无该城市天气信息" };
  //       that.setData({
  //         weatherData: weatherData, //今天天气情况数组 
  //         dress: dress //生活指数
  //       });
  //     }
  //   })
  // },
  // //获取当前空气质量情况
  // getAir: function (e) {
  //   var length = this.data.location.length;
  //   var city = this.data.location.slice(0, length - 1);
  //   var that = this;
  //   var param = {
  //     key: t.globalData.heWeatherKey,
  //     location: city
  //   };
  //   wx.request({
  //     url: t.globalData.heWeatherBase + "/s6/air/now",
  //     data: param,
  //     header: {
  //       'content-type': 'application/json'
  //     },
  //     success: function (res) {
  //       t.globalData.air = res.data.HeWeather6[0].status == "unknown city" ? "" : res.data.HeWeather6[0].air_now_city;
  //       that.setData({
  //         air: t.globalData.air
  //       });
  //     }
  //   })
  // },

  //点击更改定位切换到城市页面
  jump: function () {
    //关闭本页去切换城市，返回时就可以重新初始化定位信息哦
    wx.reLaunch({
      url: './switchcity/switchcity'
    });
  },
  onDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../post/post?id=' + id,
    })
  },
  tocallenge:function(){
    wx.navigateTo({
      url: '../../../packageYoxwakeupchallenge/pages/challenge/index/index',
    })
  },
  tofaceinvestigating:function(){
    wx.navigateTo({
      url: '../../../packageYoxface/pages/index',
    })
  },
  // getlist: function () {
  //   wx.showToast({
  //     title: "请稍后",
  //     icon: 'loading',
  //     duration: (5000 <= 0) ? 5000 : 5000
  //   });
  //   var that = this;
  //   e.get("yoxsns/board/getlist", {
  //     isajax: 1,
  //     page: 1
  //   }, function (e) {
  //     that.setData({
  //       communityList: e.result.list
  //     })
  //     wx.hideToast();
  //   })
  // },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },

  onShareAppMessage: function () {

  },
  footerTap: t.footerTap
})