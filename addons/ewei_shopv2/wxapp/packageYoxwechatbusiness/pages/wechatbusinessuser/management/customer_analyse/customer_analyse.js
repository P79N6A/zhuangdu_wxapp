var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    orderlist:[],
    dateList: ["日报", "周报", "月报", "自定义"],
    userList: ["新老客交易额", "新老客成交数"],
    currentTab: 0,
    currentusertab: 0,
    date1: '',
    date2: '',
    datePickerValue1: ['', '', ''],
    datePickerValue2: ['', '', ''], 
    datePickerIsShow1: false,
    dateflag1:false,
    dateflag2:false,
    datePickerIsShow2: false
  },
  onLoad: function (options) {
    var that=this;
  },
  navbarTap: function (e) {
    this.setData({
      currentTab: e.currentTarget.dataset.index
    })
  },
  userbarTap: function (e) {
    this.setData({
      currentusertab: e.currentTarget.dataset.index
    })
  },
  showDatepicker1: function(e){
    this.setData({
      datePickerIsShow1: true,
      dateflag1 :true
    });
  },
  showDatepicker2: function (e) {
    this.setData({
      datePickerIsShow2: true,
      dateflag2: true
    });
  },
  datePickerOnSureClick1: function (e) {
    console.log('datePickerOnSureClick');
    console.log(e);
    this.setData({
      date1: `${e.detail.value[0]}.${e.detail.value[1]}.${e.detail.value[2]}`,
      datePickerValue1: e.detail.value,
      datePickerIsShow1: false
    });
  },
  datePickerOnSureClick2: function (e) {
    console.log('datePickerOnSureClick');
    console.log(e);
    this.setData({
      date2: `${e.detail.value[0]}.${e.detail.value[1]}.${e.detail.value[2]}`,
      datePickerValue2: e.detail.value,
      datePickerIsShow2: false
    });
    var begin_time = (this.data.datePickerValue1).join(".");
    console.log(begin_time)
    var begin_time2 = Date.parse(new Date(begin_time))
    begin_time2 = begin_time2 / 1000
    console.log(begin_time2)
    var end_time = (this.data.datePickerValue2).join(".");
    console.log(end_time)
    var end_time2 = Date.parse(new Date(end_time))
    end_time2 = end_time2 / 1000
    console.log(end_time2)
    this.getorder_list(begin_time2, end_time2)
  },
  datePickerOnCancelClick: function (event) {
    console.log('datePickerOnCancelClick');
    console.log(event);
    this.setData({
      datePickerIsShow1: false,
      datePickerIsShow2: false
    });
  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  getorder_list: function (begin_time, end_time){
    var that=this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxwechatbusiness/order/order_list", {
      isajax: 1,
      begin_time: begin_time,
      end_time: end_time
    }, function (e) {
      that.setData({
        orderlist:e.data
      })
      wx.hideToast();
    })
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})