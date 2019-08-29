var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    activityList:[],
    image:"",
    nickname:"",
    begin_time_format:"",
    name:"",
    end_time_format:""
  },
  onLoad: function (options) {
    var that=this;
    that.getchallengeList();
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
  tabchallengeList:function(t){
    this.setData({
      name: t.target.dataset.name,
      image: t.target.dataset.image,
      nickname: t.target.dataset.nickname,
      begin_time_format: t.target.dataset.begin_time_format,
      end_time_format: t.target.dataset.end_time_format
    })
  },
  getchallengeList: function () {
    var that = this;
    e.get("yoxwakeupchallenge/activity", {
      isajax: 1
    }, function (e) {
      that.setData({
        activityList:e.data.list
      })
    })
  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})