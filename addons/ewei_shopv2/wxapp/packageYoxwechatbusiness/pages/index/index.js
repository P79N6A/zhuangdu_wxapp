var a = getApp(),
  e = a.requirejs("core");
a.requirejs("jquery"), a.requirejs("foxui");
Page({
  data: {
    goods: {},
    mid: "",
    iconList: [{
        id: 0,
        imageUrl: "../../image/my/wait_pay.png",
        text: "待付款"
      },
      {
        id: 1,
        imageUrl: "../../image/my/wait_send.png",
        text: "待发货"
      },
      {
        id: 2,
        imageUrl: "../../image/my/wait_arrest.png",
        text: "待收货"
      },
      {
        id: 3,
        imageUrl: "../../image/my/wait_comment.png",
        text: "待评价"
      },
      {
        id: 4,
        imageUrl: "../../image/my/after_sale.png",
        text: "售后"
      }
    ],
    icon2List: [
      {
        imageUrl: "../../image/my/all_orders.png",
        text: "全部订单",
        pagepath: "/pages/order/index"
      },
      {
        imageUrl: "../../image/my/all_orders.png",
        text: "无效订单",
        pagepath: "/pages/order/index?status=3"
      },
      {
        imageUrl: "../../image/my/complete_orders.png",
        text: "已完成订单",
        pagepath: "/pages/order/index?status=2"
      },
      {
        imageUrl: "../../image/my/outstanding.png",
        text: "我的业绩",
        pagepath: "../wechatbusiness_performance/index/index"
      },
      {
        imageUrl: "../../image/my/management.png",
        text: "客户管理",
        pagepath: "../wechatbusinessuser/management/customer/customer"
      },
      {
        imageUrl: "../../image/my/my_invite.png",
        text: "我的邀请",
        pagepath: "../wechatbusiness_invite/wechatbusiness_invite?id=2"
      },
      {
        imageUrl: "../../image/my/invite_franchiser.png",
        text: " 邀请经销商",
        pagepath: "../wechatbusiness_invite/wechatbusiness_invite?id=1"
      },
      {
        imageUrl: "../../image/my/add_tools.png",
        text: "添加工具",
        pagepath: "/pages/custom/index?pageid=6"
      },
      {
        imageUrl: "../../image/my/add_tools.png",
        text: "我要升级",
        pagepath: "../my_upgrade/index"
      }
    ],
    wechatbusinessList: [],
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
    currentTab: 3
  },
  onLoad: function(i) {
    var o = this;
    o.getwechatbusiness();
    e.get("bargain/act", i, function(a) {
      o.setData({
        goods: a.goods,
        mid: a.mid
      }), console.log(a);
    }), a.getCache("isIpx") ? o.setData({
      isIpx: !0,
      iphonexnavbar: "fui-iphonex-navbar"
    }) : o.setData({
      isIpx: !1,
      iphonexnavbar: ""
    });
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
  navigate: function(e) {
    //console.log(e)
    var index = e.currentTarget.dataset.index;
    var id = e.currentTarget.dataset.id;
    //console.log(index)
    console.log(id)
    wx.navigateTo({
      url: '/pages/order/index?status=' + id,
    })
  },
  tabwechatbusiness: function(t) {
    this.setData({
      avatar: t.target.dataset.avatar,
      nickname: t.target.dataset.nickname
    })
  },
  getwechatbusiness: function() {
    var that = this;
    e.get("yoxwechatbusiness", {
      isajax: 1
    }, function(e) {
      that.setData({
        wechatbusinessList: e.data
      })
    })
  },
  tocertificate:function(){
    wx.navigateTo({
      url: '../certificate/index/index',
    })
  }
});