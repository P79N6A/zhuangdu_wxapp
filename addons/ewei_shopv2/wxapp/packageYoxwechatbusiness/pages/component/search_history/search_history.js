var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    topTabItems: ["心得", "产品", "成分",],
    currentTopItem: "0",
    winHeight: "",
    heartfeelList:[],
    historysearchList:[
      {
        name:"水母面膜"
      },
      {
        name:"新肌百优精华"
      }
    ], 
    hotsearchList:[],
    is_hot:"",
    keyword:"",
    searchValue:""
  },
  onLoad: function (options) {
    var that = this;
    that.getlist();
    that.gethotsearch();
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR+720;
        // console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
  },
  //切换顶部标签
  switchTab: function (e) {
    if (this.needLoadNewDataAfterSwiper()) {
      this.gethotsearch();
    }
    if (this.needLoadNewDataAfterSwiper()) {
      this.getlist();
    }
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
  },
  //swiperChange
  bindChange: function (e) {
    var that = this;
    if (that.needLoadNewDataAfterSwiper()){
      that.gethotsearch();
    }
    if (that.needLoadNewDataAfterSwiper()) {
      that.getlist();
    }
    that.setData({
      currentTopItem: e.detail.current
    });
  },
  tabhotsearch:function(t){
    this.setData({
      hotsearchList:t.target.dataset.keyword
    })
  },
  needLoadNewDataAfterSwiper: function () {
    return this.data.hotsearchList.length > 0 ? false : true;
    return this.data.heartList.length > 0 ? false : true;
  },
  gethotsearch:function(){
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that=this;
    e.get("yoxwechatbusiness/hotsearch", {
      isajax:1,
      is_hot:1,
      type:"INGREDIENT"
    }, function (e) {
      that.setData({
        hotsearchList:e.data.list
      })
      wx.hideToast();
    })
  },
  searchValuechange:function(e){
    var searchValue = e.currentTarget.dataset.searchvalue;
    console.log(searchValue)
    wx.navigateTo({
      url: '../index?searchValue=' + searchValue,
    })
  },
  getlist:function(){
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that=this;
    e.get("yoxsns/board/getlist",{
      isajax: 1,
      page: 1
    },function(e){
      that.setData({
        heartfeelList:e.result.list
      })
      wx.hideToast();
    })
  },
  onDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../../../../packageYoxsns/pages/post/post?id=' + id,
    })
  },
  toindex:function(e){
    var searchValue = e.currentTarget.dataset.name;
    wx.navigateTo({
      url: '../index?searchValue=' + searchValue,
    })
  },
  searchvalue:function(e){
    this.setData({
      searchValue: e.detail.value
    })
    console.log(this.data.searchValue)
  },
  search:function(){
    var searchValue = this.data.searchValue;
    console.log(searchValue)
    wx.navigateTo({
      url: '../index?searchValue=' + searchValue,
    })
  },
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

  }
})