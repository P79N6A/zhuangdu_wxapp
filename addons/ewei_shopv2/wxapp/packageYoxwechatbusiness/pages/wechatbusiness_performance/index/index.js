var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
var rate = 0;
var canvasWidth = 0;
var canvasHeight = 0;
Page({
  data: {
    commissionList:[
      {
        number:"449494946131315778",
        type:"正常采购",
        performance:"+888"
      },
      {
        number: "449494946131315778",
        type: "正常采购",
        performance: "+888"
      },
      {
        number: "449494946131315778",
        type: "正常采购",
        performance: "+888"
      }
    ],
    commission:[],
    begin_time:"",
    end_time:"",
    pay_price_total:"",
    date:"",
    currentTab:0,
    datePickerValue1: ['', '', ''],
    dateflag1: false,
    datePickerIsShow1: false,
    date1:"",
    showList:["显示增加业绩","显示减轻业绩"],
    lineCanvasData: {
      canvasId: 'lineAreaCanvas',
    },
    x:[],
    y:[]
  },
  onLoad: function (options) {
    var that=this;
    wx.getSystemInfo({
      success:function(res){
        console.log(res.screenWidth);
        rate = res.screenWidth / 750;
        var updateData = {};
        canvasWidth = res.screenWidth - rate * 64;
        console.log(canvasWidth)
        canvasHeight = rate * 306 + rate * 44 + rate * 34 + rate * 22;
        console.log(canvasHeight)
        var yMax = 500;
        var yMin = 0;
        var xMax = 30;
        var xMin = 0;
        updateData['lineCanvasData.canvasWidth'] = canvasWidth;
        updateData['lineCanvasData.axisPadd'] = { left: rate * 5, top: rate * 44, right: rate * 5 };
        updateData['lineCanvasData.axisMargin'] = { bottom: rate * 34, left: rate * 26 };
        updateData['lineCanvasData.yAxis.fontSize'] = rate * 22;
        updateData['lineCanvasData.yAxis.fontColor'] = '#fd2d6b';
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
        updateData['lineCanvasData.xAxis.fontColor'] = '#fd2d6b';
        updateData['lineCanvasData.xAxis.lineColor'] = '#fd2d6b';
        updateData['lineCanvasData.xAxis.lineWidth'] = rate * 2;
        updateData['lineCanvasData.xAxis.minData'] = xMin;
        updateData['lineCanvasData.xAxis.maxData'] = xMax;
        updateData['lineCanvasData.xAxis.padd'] = (canvasWidth - rate * 1) / (xMax - xMin);

        updateData['lineCanvasData.point'] = { size: rate * 4, isShow: false };
        updateData['lineCanvasData.canvasHeight'] = canvasHeight;
        that.setData(updateData);
      }
    })
  },
  onReady: function () {

  },
  onShow: function () {

  },
  showDatepicker1: function (e) {
    this.setData({
      datePickerIsShow1: true,
      dateflag1: true
    });
  },
  datePickerOnSureClick1: function (e) {
    //console.log('datePickerOnSureClick');
    //console.log(e);
    var that = this;
    that.setData({
      date1: `${e.detail.value[0]}-${e.detail.value[1]}`,
      datePickerValue1: e.detail.value,
      datePickerIsShow1: false
    });
    //console.log(this.data.date1)
    console.log(that.data.datePickerValue1)
    var end_time = (that.data.datePickerValue1).join("-")
    //console.log(end_time)
    var end_time2 = Date.parse(new Date(end_time))
    end_time2 = end_time2/1000
    console.log(end_time2)
    console.log(that.data.datePickerValue1[0])
    var datePickerValue1_change = that.data.datePickerValue1[0].toString();
    console.log(datePickerValue1_change)
    console.log(that.data.datePickerValue1[1])
    var date = a.computeTime(datePickerValue1_change, that.data.datePickerValue1[1])
    console.log(date)
    date[0] = date[0] / 1000
    date[1] = date[1] / 1000
    that.setData({
      end_time: date[0],
      date: date[1]
    })

    that.getcommission(that.data.end_time);
  },
  onHide: function () {

  },
  onUnload: function () {

  },
  getcommission: function (end_time){
    var that=this;
    var date = that.data.date;
    console.log(date)
    e.get("yoxwechatbusiness/user/my_performance", { 
      isajax: 1,
      page:1,
      begin_time: end_time,
      end_time:  date
      }, function (e) {
      if (e.status == 0) {
        wx.showModal({
          title: '',
          content: e.message,
          success(res) {
            wx.navigateBack({
              delta: 1
            })
            return;
          }
        })
      }
      that.setData({
        commissionList:e.data.list,
        commission:e.data.user_info,
        pay_price_total:e.data.pay_price_total,
        x: e.data.xy.x,
        y: e.data.xy.y
      })
        wx.getSystemInfo({
          success: function (res) {
            rate = res.screenWidth / 750;
            var updateData = {};
            canvasWidth = res.screenWidth - rate * 64;
            canvasHeight = rate * 306 + rate * 44 + rate * 34 + rate * 22;
            var yMax = 500;
            var yMin = 0;
            var xMax = 31;
            var xMin = 0;
            var series = [{
              data: [
                // { x: 0, y: 111, title: '111' },
                // { x: 2, y: 60, title: '60' },
                // { x: 4, y: 450, title: '200' },
                // { x: 5, y: 111, title: '111' },
                // { x: 6, y: 260, title: '260' }
              ]
            }];
            var xAxisData = [
              { x: 0, y: 0, title: '0' },
              { x: 1, y: 0, title: '1' },
              { x: 2, y: 0, title: '2' },
              { x: 3, y: 0, title: '3' },
              { x: 4, y: 0, title: '4' },
              { x: 5, y: 0, title: '5' },
              { x: 6, y: 0, title: '6' },
              { x: 7, y: 0, title: '7' },
              { x: 8, y: 0, title: '8' },
              { x: 9, y: 0, title: '9' },
              { x: 10, y: 0, title: '10' },
              { x: 11, y: 0, title: '11' },
              { x: 12, y: 0, title: '12' },
              { x: 13, y: 0, title: '13' },
              { x: 14, y: 0, title: '14' },
              { x: 15, y: 0, title: '15' },
              { x: 16, y: 0, title: '16' },
              { x: 17, y: 0, title: '17' },
              { x: 18, y: 0, title: '18' },
              { x: 19, y: 0, title: '19' },
              { x: 20, y: 0, title: '20' },
              { x: 21, y: 0, title: '21' },
              { x: 22, y: 0, title: '22' },
              { x: 23, y: 0, title: '23' },
              { x: 24, y: 0, title: '24' },
              { x: 25, y: 0, title: '25' },
              { x: 26, y: 0, title: '26' },
              { x: 27, y: 0, title: '27' },
              { x: 28, y: 0, title: '28' },
              { x: 29, y: 0, title: '29' },
              { x: 30, y: 0, title: '30' },
              { x: 31, y: 0, title: '31' },
            ];
            console.log(xAxisData)
            var yAxisData = that.data.y;
            console.log(yAxisData)
            yMax = 450;
            yMin = 0;
            xMax = that.data.x.length;
            console.log(xMax)
            xMin = 0;
            updateData['lineCanvasData.xAxis.minData'] = xMin;
            updateData['lineCanvasData.xAxis.maxData'] = xMax;
            updateData['lineCanvasData.xAxis.padd'] = (canvasWidth - rate * 1) / (xMax - xMin);
            updateData['lineCanvasData.point'] = { size: rate * 4, isShow: true };
            updateData['lineCanvasData.yAxis.minData'] = yMin;
            updateData['lineCanvasData.yAxis.maxData'] = yMax;
            updateData['lineCanvasData.yAxis.padd'] = rate * 306 / (yMax - yMin);
            updateData['lineCanvasData.series'] = series;
            updateData['lineCanvasData.xAxis.data'] = xAxisData;
            updateData['lineCanvasData.yAxis.data'] = yAxisData;
            that.setData(updateData);
          },
        })
    })
  },
  todetail:function(e){
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../detail/detail?id='+id,
    })
  }, 
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})