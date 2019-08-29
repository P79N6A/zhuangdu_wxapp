// packageYoxwechatbusiness/pages/component/mainIndex/mainIndex.js
var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    sliderList: [
      { selected: true, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
    ],
    navList:[
      {
        imgUrl:"./images/face-cleansing-cream.png",
        text:"洁面"
      },
      {
        imgUrl: "./images/astringent.png",
        text: "化妆水"
      },
      {
        imgUrl: "./images/essence.png",
        text: "精华"
      },
      {
        imgUrl: "./images/cream.png",
        text: "乳霜"
      },
      {
        imgUrl: "./images/eye-cream.png",
        text: "眼霜"
      },
      {
        imgUrl: "./images/mask.png",
        text: "面膜"
      },
      {
        imgUrl: "./images/sun-block.png",
        text: "防晒"
      },
      {
        imgUrl: "./images/toiletries.png",
        text: "洗护"
      }
    ],
    items: [{
      "pagePath": "../../../../pages/index/index",
      "iconPath": "../../../../static/images/tabbar/icon-1.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-1-active.png",
      "text": "首页"
    },
    {
      "pagePath": "../../../../pages/shop/caregory/index",
      "iconPath": "../../../../static/images/tabbar/icon-2.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-2-active.png",
      "text": "分类"
    },
    {
      "pagePath": "../../../../pages/topage/ToHiZhuandu/ToHiZhuandu",
      "iconPath": "../../../../static/images/tabbar/icon-102.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-102.png",
      "text": "美妆助理"
    },
    {
      "pagePath": "../../../../packageYoxsns/pages/new_wanba/new_wanba",
      "iconPath": "../../../../static/images/tabbar/icon-4.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-4-active.png",
      "text": "玩吧"
    },
    {
      "pagePath": "../../../../pages/topage/ToUser/ToUser",
      "iconPath": "../../../../static/images/tabbar/icon-5.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-5-active.png",
      "text": "会员中心"
    },
    ],
    currentTab: 0,
    commentsList:[],
    comment:"",
    add_time_format:"",
    avatar:"",
    product_name:"",
    product_thumb:""
  },
  onLoad: function (options) {
    var that=this;
    that.getcomment();
  },

  onReady: function () {

  },
  onShow: function () {

  },
  tabcomment:function(t){
    this.setData({
      comment:t.target.dataset.comment,
      add_time_format: t.target.dataset.add_time_format,
      avatar: t.target.dataset.avatar,
      product_name: t.target.dataset.product_name,
      product_thumb: t.target.dataset.product_thumb
    })
  },
  //事件处理函数
  bindChange: function (e) {
    let that = this;
    that.setData({
      currentTab: e.detail.current
    });
  },
  swichNav: function (e) {
    let that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
  getcomment:function(){
    var that=this;
    e.get("yoxfriendscircle/comment/comment_list", {
      isajax: 1,
      type: "PRODUCT"
    }, function (e) {
      that.setData({
        commentsList:e.data.list
      })
      //console.log(result.data.list)
    })
  },
  //轮播图绑定change事件，修改图标的属性是否被选中
  switchTab: function (e) {
    var sliderList = this.data.sliderList;
    var i, item;
    for (i = 0; item = sliderList[i]; ++i) {
      item.selected = e.detail.current == i;
    }
    this.setData({
      sliderList: sliderList
    });
  },
  searchchange:function(e){
    //console.log(e)
    var searchValue = e.currentTarget.dataset.searchvalue;
    wx.navigateTo({
      url: '../index?searchValue=' + searchValue,
    })
  },
  tosearch:function(){
    wx.navigateTo({
      url: '../search_history/search_history',
    })
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