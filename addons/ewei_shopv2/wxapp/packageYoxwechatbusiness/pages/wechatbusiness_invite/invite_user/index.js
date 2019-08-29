var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    prev:"<",
    next:">",
    choose_id:0,
    img_base64: "",
    level_list:[],
    invite_list:[],
    share:0,
    now_month:''
  },
  onLoad: function(options) {
    var that = this;
    that.getNowMonth();
    that.getagentList();
    that.getlevelist();
  },
  onReady: function() {

  },
  invite:function(e){
    this.setData({
         share: 1
    });
  },
  closeInvite:function(){
    this.setData({
         share: 0
    });
  },
  goToShare:function(){
    wx.navigateTo({
        url:"../invite_qrcode/index"
    });
  },
  choose:function(e){
    var id = e.currentTarget.dataset.id;
    if(this.data.choose_id==id){
        id=0;

    }
      this.setData({
           choose_id: id
      });
  },
  replaceMonth:function(){
      var now_month=this.data.now_month;
      now_month=now_month.replace(new RegExp(/年/g),'-');
      now_month=now_month.replace(new RegExp(/月/g),'-');
      return now_month;
  },
  prev:function(){
      this.getPreMonth(this.replaceMonth());
  },
  next:function(){
      this.getNextMonth(this.replaceMonth());
  },
  getPreMonth:function(date) {
        var arr = date.split('-');
        var year = arr[0]; //获取当前日期的年份
        var month = arr[1]; //获取当前日期的月份
        var day = arr[2]; //获取当前日期的日
        var days = new Date(year, month, 0);
        days = days.getDate(); //获取当前日期中月的天数
        var year2 = year;
        var month2 = parseInt(month) - 1;
        if (month2 == 0) {
            year2 = parseInt(year2) - 1;
            month2 = 12;
        }
        if (month2 < 10) {
            month2 = '0' + month2;
        }
        var t2 = year2 + '年' + month2 + '月';
        this.setData({
           now_month: t2
        });
    },
    getNextMonth:function(date) {
        var arr = date.split('-');
        var year = arr[0]; //获取当前日期的年份
        var month = arr[1]; //获取当前日期的月份
        var day = arr[2]; //获取当前日期的日
        var days = new Date(year, month, 0);
        days = days.getDate(); //获取当前日期中的月的天数
        var year2 = year;
        var month2 = parseInt(month) + 1;
        if (month2 == 13) {
            year2 = parseInt(year2) + 1;
            month2 = 1;
        }
        // var day2 = day;
        // var days2 = new Date(year2, month2, 0);
        // days2 = days2.getDate();
        // if (day2 > days2) {
        //     day2 = days2;
        // }
        if (month2 < 10) {
            month2 = '0' + month2;
        }
        var t2 = year2 + '年' + month2 + '月';
        this.setData({
           now_month: t2
        });
    },
  getNowMonth:function(){
      var timestamp = Date.parse(new Date());
      var date = new Date(timestamp);
      //获取年份  
      var Y =date.getFullYear();
      //获取月份  
      var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1);
      //获取当日日期 
      var D = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(); 
      console.log("当前时间：" + Y + '年'  + M+ '月' + D+ '日' ); 
      this.setData({
         now_month: Y + '年'  + M+ '月'
      });

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
  getagentList: function() {
    var that = this;
    e.get("yoxwechatbusiness/agent", {
      isajax: 1
    }, function(e) {
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
        img_base64: e.data.img_base64
      })
    })
  },
  togoods:function(e){
    var package_id = e.currentTarget.dataset.package_id;
    console.log(package_id)
    wx.navigateTo({
      url: '../../../pages/goods/package/detail/index?id=' + package_id,
    })
  },
  getlevelist: function() {
    var that = this;
    e.get("yoxwechatbusiness.level.level_list", {
      isajax: 1
    }, function(e) {
      that.setData({
        level_list:e.data.list
      })
    });
    // e.get("yoxwechatbusiness.level.level_info", {
    //   isajax: 1,id:1
    // }, function(e) {

    // });
    e.get("yoxwechatbusiness.user.invite_statistics", {
      isajax: 1
    }, function(e) {

        that.setData({
          invite_list:e.data.level_list
        })
    })
  },
  // todetail:function(e){
  //   var id = e.currentTarget.dataset.id;
  //   wx.navigateTo({
  //     url: './detail/detail?id='+id,
  //   })
  // },

  //转发
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      console.log(res);
    }
    return {
      title: '转发',
      path: '/packageYoxwechatbusiness/pages/wechatbusiness_invite/invite_qrcode/index?id='+this.data.prev,
      success: function (res) {
        console.log('成功', res)
      }
    }
  }

})