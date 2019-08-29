var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    topTabItems:["待领优惠券","我的优惠券"],
    winHeight: "",
    currentTopItem: "0",
    currentnavItem:"0",
    currentnavItem2: "0",
    waitnav:["全部","职场","护肤","变美","彩妆","皱纹","痘痘","防晒","美容","变白"],
    mynav: ["课程优惠券", "直播间通用劵", "平台活动劵", "定制劵", "直播间VIP劵", "手气劵", "妆度专享劵"],
    myList:[
      {
        content:"国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        day:"13",
        coupon_price: "30"
      }
    ],
    waitList:[
      {
        imgurl: "",
        content: "国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        learn: "91.2万次",
        price: "99",
        coupon_price: "30"
      },
      {
        imgurl: "",
        content: "国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        learn: "91.2万次",
        price: "99",
        coupon_price: "30"
      },
      {
        imgurl: "",
        content: "国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        learn: "91.2万次",
        price: "99",
        coupon_price: "30"
      },
      {
        imgurl: "",
        content: "国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        learn: "91.2万次",
        price: "99",
        coupon_price: "30"
      },
      {
        imgurl: "",
        content: "国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        learn: "91.2万次",
        price: "99",
        coupon_price: "30"
      },
      {
        imgurl: "",
        content: "国际皮肤科权威教 授14堂抗衰老课： 教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科教你祛皱/祛斑/科",
        learn: "91.2万次",
        price: "99",
        coupon_price: "30"
      }
    ]
  },
  onLoad: function(options) {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR+500;
        // console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
  },
  onReady: function() {

  },
  switchnavTab: function (e) {
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
  },
  switchnavTab2:function(e){
    this.setData({
      currentnavItem: e.currentTarget.dataset.idx,
      currentnavItem2: e.currentTarget.dataset.idx
    })
  },
  bindChange: function (e) {
    var that = this;
    that.setData({
      currentTopItem: e.detail.current
    });
  }
})