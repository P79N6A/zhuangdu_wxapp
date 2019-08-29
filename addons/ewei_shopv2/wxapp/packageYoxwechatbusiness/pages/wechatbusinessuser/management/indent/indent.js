var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    navList: ["全部", "订单状态","排序"],
    currentTab: 1,
    currentnavTab: 4,
    show: false,//控制下拉列表的显示隐藏，false隐藏、true显示
    show2: false,
    show3: false,
    selectData: ['全部', '今日成交的', '昨日成交的', '一周内成交的', '30天内成交的'],
    selectData2: ['全部', '未付款', '已付款', '待发货', '已发货'],
    selectData3:['录入时间','成交额由低到高','成交额由高到低'],
    index: 0,
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
    order_list:[],
    agentid:"",
    createtime:"",

  },
  onLoad: function (options) {
    var that=this;
    that.getorderList();
  },
  navbarTap: function (e) {
    console.log(e)
    this.setData({
      currentnavTab: e.currentTarget.dataset.index
    })
    if (e.currentTarget.dataset.index == 1) {
      this.selectTap();
    }
    if (e.currentTarget.dataset.index == 0) {
      this.selectTap2();
    }
    if (e.currentTarget.dataset.index == 2) {
      this.selectTap3();
    }
  },
  selectTap() {
    this.setData({
      show: !this.data.show
    });
  },
  selectTap2() {
    this.setData({
      show2: !this.data.show2
    });
  },
  selectTap3() {
    this.setData({
      show3: !this.data.show3
    });
  },
  // 点击下拉列表
  optionTap(e) {
    let Index = e.currentTarget.dataset.index;//获取点击的下拉列表的下标
    this.setData({
      index: Index,
      show: !this.data.show
    });
  },
  optionTap2(e) {
    let Index = e.currentTarget.dataset.index;//获取点击的下拉列表的下标
    this.setData({
      index: Index,
      show2: !this.data.show2
    });
  },
  optionTap3(e) {
    let Index = e.currentTarget.dataset.index;//获取点击的下拉列表的下标
    this.setData({
      index: Index,
      show3: !this.data.show3
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
  toaddorder:function(){
    wx.navigateTo({
      url: '../../addorder/addorder',
    })
  },
  taborderList:function(t){
    this.setData({
      agentid:t.target.dataset.agentid,
      createtime: t.target.dataset.createtime
    })
  },
  getorderList: function (uid, begin_time, end_time){
    var that=this;
    if(uid==''|| uid==undefined){
      var user_mark = "TEAM";
    }else{
      var user_mark = "SINGLE";
    }
    e.get("yoxwechatbusiness/order/order_list", {
      isajax: 1,
      begin_time: begin_time,
      end_time: end_time,
      user_mark: user_mark,
      uid:uid
    }, function (e) {
      if (e.status == 0) {
        wx.showModal({
          title: '',
          content: e.result.message,
          success(res) {
            wx.navigateBack({
              delta: 1
            })
            return;
          }
        })
      }
      e.data.createtime = a.formatTimeTwo(e.data.createtime, 'Y/M/D h:m:s')
      that.setData({
        order_list:e.data.list
      })
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