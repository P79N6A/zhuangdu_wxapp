var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    title: "",
    sliderList: [{
        selected: true,
        imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg'
      },
      {
        selected: false,
        imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg'
      },
      {
        selected: false,
        imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg'
      },
    ],
    use: "",
    place: "",
    price: "",
    id:"",
    image:[],
    detailTabItems: ["品牌档案", "终端形象", "最新资讯"],
    detailTopItem: "0",
    winHeight: "",
    detail_commendList:[
      {
        imgUrl:"",
        title:"水母焕肤面膜",
        price:"108.00"
      },
      {
        imgUrl: "",
        title: "水母焕肤面膜",
        price: "108.00"
      },
      {
        imgUrl: "",
        title: "水母焕肤面膜",
        price: "108.00"
      },
      {
        imgUrl: "",
        title: "水母焕肤面膜",
        price: "108.00"
      },  
      {
        imgUrl: "",
        title: "水母焕肤面膜",
        price: "108.00"
      },
      {
        imgUrl: "",
        title: "水母焕肤面膜",
        price: "108.00"
      }
    ],
    editList:""
  },
  onLoad: function(options) {
    var that = this;
    // console.log(options.title)
    // console.log(options.use)
    // console.log(options.place)
    // console.log(options.price)
    console.log(options.id)
    that.getedit();
    wx.getSystemInfo({
      success: function (res) {
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
    that.setData({
      title: options.title,
      use: options.use,
      place: options.place,
      price: options.price,
      image:options.image,
      id:options.id
    })
    //console.log(that.data.title)
  },
  //轮播图绑定change事件，修改图标的属性是否被选中
  switchTab: function(e) {
    var sliderList = this.data.sliderList;
    var i, item;
    for (i = 0; item = sliderList[i]; ++i) {
      item.selected = e.detail.current == i;
    }
    this.setData({
      sliderList: sliderList
    });
  },
  switchTab: function (e) {
    this.setData({
      detailTopItem: e.currentTarget.dataset.idx
    });
  },
  getedit:function(){
    var that=this;
    var id = that.data.id;
    console.log(id)
    e.get("yoxbrand/index/brand_edit", {
      isajax: 1,
      id: id,
    }, function (e) {
      that.setData({
        editList:e.data.attitude
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

  }
})