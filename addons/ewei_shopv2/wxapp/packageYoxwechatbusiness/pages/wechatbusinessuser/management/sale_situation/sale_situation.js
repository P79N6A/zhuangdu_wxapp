var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
var rate=0;
var canvasWidth = 0;
var canvasHeight = 0;
Page({
  data: {
    dateList: ["日报", "周报", "月报", "自定义"],
    currentTab: 0,
    lineCanvasData: {
      canvasId: 'lineAreaCanvas',
    },
    overview:[],
    date1: '',
    date2: '',
    datePickerValue1: ['', '', ''],
    datePickerValue2: ['', '', ''],
    datePickerIsShow1: false,
    dateflag1: false,
    dateflag2: false,
    datePickerIsShow2: false
  },
  onLoad: function (options) {
    var that = this;
    //var systemInfo = t.systemInfo;
    rate = 375 / 750;
    var updateData = {};
    canvasWidth = 375 - rate * 64;
    console.log(canvasWidth)
    canvasHeight = rate * 306 + rate * 44 + rate * 34 + rate * 22;
    console.log(canvasHeight)
    var yMax = 1;
    var yMin = 0;
    var xMax = 800;
    var xMin = 0;
    updateData['lineCanvasData.canvasWidth'] = canvasWidth;
    updateData['lineCanvasData.axisPadd'] = { left: rate * 5, top: rate * 44, right: rate * 5 };
    updateData['lineCanvasData.axisMargin'] = { bottom: rate * 34, left: rate * 26 };
    updateData['lineCanvasData.yAxis.fontSize'] = rate * 22;
    updateData['lineCanvasData.yAxis.fontColor'] = '#637280';
    updateData['lineCanvasData.yAxis.lineColor'] = '#fd2d6b';
    updateData['lineCanvasData.yAxis.lineWidth'] = rate * 2;
    updateData['lineCanvasData.yAxis.dataWidth'] = rate * 62;
    updateData['lineCanvasData.yAxis.isShow'] = true;
    updateData['lineCanvasData.yAxis.isDash'] = false;
    updateData['lineCanvasData.yAxis.minData'] = yMin;
    updateData['lineCanvasData.yAxis.maxData'] = yMax;
    updateData['lineCanvasData.yAxis.padd'] = rate * 306 / (yMax - yMin);

    updateData['lineCanvasData.xAxis.dataHeight'] = rate * 26;
    updateData['lineCanvasData.xAxis.fontSize'] = rate * 22;
    updateData['lineCanvasData.xAxis.isShow'] = true;
    updateData['lineCanvasData.xAxis.isDash'] = false;
    updateData['lineCanvasData.xAxis.fontColor'] = '#637280';
    updateData['lineCanvasData.xAxis.lineColor'] = '#fd2d6b';
    updateData['lineCanvasData.xAxis.lineWidth'] = rate * 2;
    updateData['lineCanvasData.xAxis.minData'] = xMin;
    updateData['lineCanvasData.xAxis.maxData'] = xMax;
    updateData['lineCanvasData.xAxis.padd'] = (canvasWidth - rate * 103) / (xMax - xMin);

    updateData['lineCanvasData.point'] = { size: rate * 4, isShow: false };
    updateData['lineCanvasData.canvasHeight'] = canvasHeight;


    that.setData(updateData);
  },
  navbarTap:function(){
    this.setData({
      currentTab: e.currentTarget.dataset.index
    })
  },
  onReady: function () {

  },
  onShow: function () {
    var that=this;
    //var systemInfo = t.systemInfo;
    rate = 375 / 750;
    var updateData = {};
    canvasWidth = 375 - rate * 64;
    canvasHeight = rate * 306 + rate * 44 + rate * 34 + rate * 22;

    var yMax = 1;
    var yMin = 0;
    var xMax = 700;
    var xMin = 0;
    // var series = [{
    //   data: [
    //     { x: 0, y: 111, title: '111' },
    //     { x: 2, y: 60, title: '60' },
    //     { x: 4, y: 450, title: '200' },
    //     { x: 5, y: 111, title: '111' },
    //     { x: 6, y: 260, title: '260' }
    //   ]
    // }];
    var xAxisData = [
      { x: 0, y: 0, title: '04/12' },
      { x: 150, y: 0, title: '04/13' },
      { x: 300, y: 0, title: '04/14' },
      { x: 400, y: 0, title: '04/15' },
      { x: 500, y: 0, title: '04/16' },
      { x: 650, y: 0, title: '04/17' },
      { x: 800, y: 0, title: '04/18' }
    ];
    var yAxisData = [
      { x: 0, y: 0, title: '0' },
      { x: 0, y: 0.2, title: '0.2' },
      { x: 0, y: 0.4, title: '0.4' },
      { x: 0, y: 0.6, title: '0.6' },
      { x: 0, y: 0.8, title: '0.8' },
      { x: 0, y: 1, title: '1' }
    ];
    yMax = 1;
    yMin = 0;
    xMax = 800;
    xMin = 0;
    updateData['lineCanvasData.xAxis.minData'] = xMin;
    updateData['lineCanvasData.xAxis.maxData'] = xMax;
    updateData['lineCanvasData.xAxis.padd'] = (canvasWidth - rate * 98) / (xMax - xMin);
    updateData['lineCanvasData.point'] = { size: rate * 4, isShow: true };
    updateData['lineCanvasData.yAxis.minData'] = yMin;
    updateData['lineCanvasData.yAxis.maxData'] = yMax;
    updateData['lineCanvasData.yAxis.padd'] = rate * 306 / (yMax - yMin);
    // updateData['lineCanvasData.series'] = series;
    updateData['lineCanvasData.xAxis.data'] = xAxisData;
    updateData['lineCanvasData.yAxis.data'] = yAxisData;
    
    that.setData(updateData);
  },
  onHide: function () {

  },
  showDatepicker1: function (e) {
    this.setData({
      datePickerIsShow1: true,
      dateflag1: true
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
    this.getsales_overview(begin_time2, end_time2)
  },
  datePickerOnCancelClick: function (event) {
    console.log('datePickerOnCancelClick');
    console.log(event);
    this.setData({
      datePickerIsShow1: false,
      datePickerIsShow2: false
    });
  },
  onUnload: function () {

  },
  onPullDownRefresh: function () {

  },
  getsales_overview: function (begin_time, end_time) {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxwechatbusiness/order/sales_overview", {
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
        overview:e.data
      })
      wx.hideToast();
    })
  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})