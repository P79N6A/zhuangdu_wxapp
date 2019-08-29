var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
     tablist: [
      {
        imgurl_active: "../image/count_active.png",
        imgurl: "../image/count.png",
        text: "统计",
        pagePath: "../count/count"
      },
      {
        imgurl_active: "../image/order_active.png",
        imgurl: "../image/order.png",
        text: "订单管理",
        pagePath: "../indent/indent"
      },
      {
        imgurl_active: "../image/custom_active.png",
        imgurl: "../image/custom.png",
        text: "客户管理",
        pagePath: "../customer/customer"
      }
    ],
    navList: [
      {
        imgurl: "",
        text: "用户画像",
        pagePath: "../user_picture/user_picture"
      },
      {
        imgurl: "",
        text: "销售概况",
        pagePath: "../sale_situation/sale_situation"
      },
      {
        imgurl: "",
        text: "客户分析",
        pagePath: "../customer_analyse/customer_analyse"
      },
      {
        imgurl: "",
        text: "商品分析",
        pagePath: "../goods_analyse/goods_analyse"
      }
    ],
    currentTab: 0
  },
  onLoad: function (options) {
    var that=this;
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

})