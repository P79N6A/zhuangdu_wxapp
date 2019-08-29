var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    navList:["全部","客户状态"],
    currentTab: 2,
    currentnavTab:3,
    show: false,//控制下拉列表的显示隐藏，false隐藏、true显示
    show2:false,
    selectData: ['全部', '月尾还款', '持续跟进', '潜在客户', '未接', '意向客户'],
    selectData2: ['全部', '今日新增', '今日跟进过', '30未跟进过', '从未跟进过', '30天内跟进过'],
    index:0,
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
    mydownleveluserList: [],
    avatar: "",
    nickname: ""
  },
  onLoad: function (options) {
    var that = this;
    that.getuserList();
  },
  navbarTap: function (e) {
    console.log(e)
    this.setData({
      currentnavTab: e.currentTarget.dataset.index
    })
    if (e.currentTarget.dataset.index==1){
      this.selectTap();
    }
    if (e.currentTarget.dataset.index == 0){
      this.selectTap2();
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
  // 点击下拉列表
  optionTap(e) {
    let Index = e.currentTarget.dataset.index;//获取点击的下拉列表的下标
    this.setData({
      index: Index,
      show: !this.data.show
    });
  },
  optionTap2(e){
    let Index = e.currentTarget.dataset.index;//获取点击的下拉列表的下标
    this.setData({
      index: Index,
      show2: !this.data.show2
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
  tabuserList: function (t) {
    this.setData({
      avatar: t.target.dataset.avatar,
      nickname: t.target.dataset.nickname
    })
  },
  getuserList: function (begin_time, end_time) {
    var that = this;
    e.get("yoxwechatbusiness/user/my_down_level_user", {
      isajax: 1,
      begin_time: begin_time,
      end_time: end_time
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
      that.setData({
        mydownleveluserList: e.data.list
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