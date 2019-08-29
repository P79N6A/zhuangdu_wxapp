var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    chose_list:[
      {
        idx:0,
        imgUrl:"./images/01.png",
        title:"我是讲师",
        choose:false
      },
      {
        idx: 1,
        imgUrl: "./images/03.png",
        title: "我是机构",
        choose: false
      },
      {
        idx: 2,
        imgUrl: "./images/02.png",
        title: "我是听众",
        choose: false
      }
    ],
    chooseflag:false,
    textchooseflag1:false,
    textchooseflag2: false,
    textchooseflag3: false,
  },
  onLoad: function (options) {

  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  choose:function(e){
    this.setData({
      chooseflag: e.currentTarget.dataset.idx
    })
  },
  textchoose1:function(e){
    this.setData({
      textchooseflag1: !this.data.textchooseflag1
    })
  },
  textchoose2:function(e){
    this.setData({
      textchooseflag2: !this.data.textchooseflag2
    })
  },
  textchoose3:function(e){
    this.setData({
      textchooseflag3: !this.data.textchooseflag3
    })
  },
  tocate:function(){
    wx.navigateTo({
      url: '../categorty/categorty',
    })
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})