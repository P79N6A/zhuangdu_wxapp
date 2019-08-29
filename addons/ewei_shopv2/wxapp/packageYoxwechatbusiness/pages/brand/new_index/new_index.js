var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
const nameList = ["妆度ZOONDUOO", "妆度ZOONDUOO", "妆度ZOONDUOO", "妆度ZOONDUOO", "妆度ZOONDUOO", "妆度ZOONDUOO"];

const generateItem = () => {
  return {
    name: nameList[Math.floor(Math.random() * 3.99)],
  }
}
Page({
  data: {
    topTabItems: [
      {
        title:"找工厂",
        id:1
      },
      {
        title: "找品牌",
        id:2
      }, 
      {
        title:"找加盟",
        id:3
      }
    ],
    navTabItems:[],
    currentTopItem: "0",
    navTopItem: "0",
    winHeight: "",
    sliderList: [
      { selected: true, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg' },
      { selected: false, imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg' },
    ],
    groupList:[
      {
        imgUrl:"",
        text:"新上线"
      },
      {
        imgUrl: "",
        text: "中国馆"
      },
      {
        imgUrl: "",
        text: "国际馆"
      },
      {
        imgUrl: "",
        text: "品牌库"
      }
    ],
    group4List:[
      {
        imgUrl:"",
        title:"妆度",
        explain:"天然 时尚 健康"
      },
      {
        imgUrl: "",
        title: "妆度",
        explain: "天然 时尚 健康"
      },
      {
        imgUrl: "",
        title: "妆度",
        explain: "天然 时尚 健康"
      },
      {
        imgUrl: "",
        title: "妆度",
        explain: "天然 时尚 健康"
      },
      {
        imgUrl: "",
        title: "妆度",
        explain: "天然 时尚 健康"
      },
      {
        imgUrl: "",
        title: "妆度",
        explain: "天然 时尚 健康"
      }
    ],
    pushList: [
      generateItem(),
      generateItem(),
      generateItem(),
      generateItem()
    ],
    tabTxt: ['品类', '产地', '价格'],//分类
    tab: [true, true, true],
    group5commendList:[
      {
        imgUrl:"",
        title:"ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      },
      {
        imgUrl: "",
        title: "ZOONDUOO"
      }
    ],
    group5commendList2:[
      {
        imgUrl:"",
        title:"妆度 ZOONDUOO",
        explain:"天然 时尚 健康",
        place:"中国香港",
        use:"护肤",
        price:"100-200"
      },
      {
        imgUrl: "",
        title: "妆度 ZOONDUOO",
        explain: "天然 时尚 健康",
        place: "中国香港",
        use: "护肤",
        price: "100-200"
      },
      {
        imgUrl: "",
        title: "妆度 ZOONDUOO",
        explain: "天然 时尚 健康",
        place: "中国香港",
        use: "护肤",
        price: "100-200"
      },
      {
        imgUrl: "",
        title: "妆度 ZOONDUOO",
        explain: "天然 时尚 健康",
        place: "中国香港",
        use: "护肤",
        price: "100-200"
      },
      {
        imgUrl: "",
        title: "妆度 ZOONDUOO",
        explain: "天然 时尚 健康",
        place: "中国香港",
        use: "护肤",
        price: "100-200"
      },
      {
        imgUrl: "",
        title: "妆度 ZOONDUOO",
        explain: "天然 时尚 健康",
        place: "中国香港",
        use: "护肤",
        price: "100-200"
      }
    ],
    gc_list:[],
    pp_list:[],
    jm_list:[],
    placeList:[],
    name:"",
    flag:false,
    showflag:false,
    cate_list:[]
  },
  onLoad: function (options) {
    var that = this;
    that.getbrand_cate();
    that.getgc_list();
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR+2000;
        // console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
    setInterval(() => {
      that.setData({
        pushList: [
          generateItem(),
          generateItem(),
          generateItem(),
          generateItem()
        ]
      })
    }, 5000)
  },
  handleSwipeOut(...args) {
    console.log(args)
  },
  handleClickCard(...args) {
    console.log(args)
  },
  // 选项卡
  filterTab: function (e) {
    var that=this;
    var data = [true, true, true], index = e.currentTarget.dataset.index;
    console.log(index)
    if(index==2){
      if (that.data.flag==false){
        var order_by = "brand_price_range_desc";
        that.getgc_list(order_by);
        that.setData({
          flag:true
        })
      }else{
        var order_by = "brand_price_range_asc";
        that.getgc_list(order_by)
      }
    }
    if(index==1){
      that.setData({
        showflag:!that.data.showflag
      })
      if(that.data.showflag==true){
        that.getplaceList();
      }
    }
    data[index] = !this.data.tab[index];
    that.setData({
      tab: data
    })
  },
  //切换顶部标签
  switchnavTab: function (e) {
    var cate_id = e.currentTarget.dataset.id;
    //console.log(cate_id);
    if (this.needLoadNewDataAfterSwiper1()){
      console.log(1)
      this.getgc_list(cate_id);
    }
    if (this.needLoadNewDataAfterSwiper2()) {
      console.log(2)
      this.getpp_list(cate_id);
    }
    if (this.needLoadNewDataAfterSwiper3()) {
      console.log(3)
      this.getjm_list(cate_id);
    }
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
  },
  //事件处理函数
  bindViewTap: function () {

  },
  //轮播图绑定change事件，修改图标的属性是否被选中
  switchTab: function (e) {
    var that=this;
    var sliderList = that.data.sliderList;
    var i, item;
    for (i = 0; item = sliderList[i]; ++i) {
      item.selected = e.detail.current == i;
    }
    that.setData({
      sliderList: sliderList
    });
  },
  switchnavTab2:function(e){
    var id = e.currentTarget.dataset.id;
    console.log(id)
    if(this.needLoadNewDataAfterBrand1()){
      this.getbrand_list(id);
    }
    this.setData({
      navTopItem: e.currentTarget.dataset.idx
    });
  },
  bindnavChange:function(e){
    var id = e.currentTarget.dataset.id;
    console.log(id)
    if (this.needLoadNewDataAfterBrand1()) {
      this.getbrand_list(id);
    }
    this.setData({
      navTopItem: e.detail.current
    });
  },
  //swiperChange
  bindChange: function (e) {
    //console.log(e)
    var that = this;
    var cate_id = e.detail.current+1;
    //console.log(cate_id);
    if (that.needLoadNewDataAfterSwiper1()) {
      console.log(1)
      that.getgc_list(cate_id)
    }
    if (that.needLoadNewDataAfterSwiper2()) {
      console.log(2)
      that.getpp_list(cate_id)
    }
    if (that.needLoadNewDataAfterSwiper3()) {
      console.log(3)
      that.getjm_list(cate_id)
    }
    that.setData({
      currentTopItem: e.detail.current
    });
  },
  todetail:function(e){
    //console.log(e)
    var title=e.currentTarget.dataset.title;
    var use=e.currentTarget.dataset.use;
    var price=e.currentTarget.dataset.price;
    var image=e.currentTarget.dataset.image;
    var place=e.currentTarget.dataset.place;
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: './detail/detail?title='+title+"&use="+use+"&price="+price+"&image="+image+"&place="+place+"&id="+id,
    })
  },
  tabcatelist:function(t){
    this.setData({
      name:t.target.dataset.name
    })
  },
  getbrand_cate:function(){
    var that=this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxbrand/index/brand_cate_list", {
      isajax: 1
    }, function (e) {
      that.setData({
        navTabItems:e.data.list
      })
      wx.hideToast();
      //console.log(that.data.navTabItems)
    })
  },
  needLoadNewDataAfterSwiper1: function () {
    return this.data.gc_list.length > 0 ? false : true;
  },
  needLoadNewDataAfterSwiper2: function(){
    return this.data.pp_list.length > 0 ? false : true;
  },
  needLoadNewDataAfterSwiper3: function() {
    return this.data.jm_list.length > 0 ? false : true;
  },
  needLoadNewDataAfterBrand1:function(){
    return this.data.cate_list.length > 0 ? false : true;
  },
  getgc_list: function (cate_id, order_by){
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxbrand/index/gc_list", {
      isajax: 1,
      cate_id:cate_id,
      order_by: order_by
    }, function (e) {
      that.setData({
        gc_list:e.data.list
      })
      wx.hideToast();
    })
  },
  getbrand_list:function(id){
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxbrand.index.brand_list", {
      isajax: 1,
      id: id
    }, function (e) {
      that.setData({
        cate_list:e.data.list
      })
      wx.hideToast();
    })
  },
  getpp_list:function (cate_id, order_by) {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxbrand/index/pp_list", {
      isajax: 1,
      cate_id: cate_id,
      order_by: order_by
    }, function (e) {
      that.setData({
        pp_list: e.data.list
      })
      wx.hideToast();
    })
  },
  getjm_list: function (cate_id, order_by) {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxbrand/index/jm_list", {
      isajax: 1,
      cate_id: cate_id,
      order_by: order_by
    }, function (e) {
      that.setData({
        jm_list: e.data.list
      })
      wx.hideToast();
    })
  },
  getplaceList:function(){
    var that=this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxbrand/index/origin_place_list", {
      isajax: 1
    }, function (e) {
      that.setData({
        placeList:e.data.list
      })
      wx.hideToast();
    })
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