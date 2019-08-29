var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    sliderList: [
      { selected: true, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
    ],
    coupon_list:[
      {
        id:1,
        imgUrl:"./image/imgUrl.jpg",
        title:"诱色美颜气垫CC",
        descript:"精选细腻均匀的弹力遮瑕粉末，遮瑕力强，一抹隐形毛孔、痘印、斑点、细纹等",
        one_price:"128",
        kg:"13"
      },
      {
        id: 2,
        imgUrl: "./image/imgUrl.jpg",
        title: "诱色美颜气垫CC",
        descript: "精选细腻均匀的弹力遮瑕粉末，遮瑕力强，一抹隐形毛孔、痘印、斑点、细纹等",
        one_price: "128",
        kg: "13"
      },
      {
        id: 3,
        imgUrl: "./image/imgUrl.jpg",
        title: "诱色美颜气垫CC",
        descript: "精选细腻均匀的弹力遮瑕粉末，遮瑕力强，一抹隐形毛孔、痘印、斑点、细纹等",
        one_price: "128",
        kg: "13"
      }
    ]
  },
  onLoad: function (options) {

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
  toservices:function(){
    wx.navigateTo({
      url: '../services/services',
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