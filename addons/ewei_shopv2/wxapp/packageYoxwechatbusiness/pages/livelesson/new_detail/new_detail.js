// packageYoxwechatbusiness/pages/livelesson/detail/detail.js
var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    times: "5.4", //名字
    detail: [],
    type:"",
    thumb:[],
    descript:"",
    cate_id:"",
    cate_id2:"",
    name:"",
    id:"",
    now:"9",
    all:"14",
    price:"39.2",
    winHeight: "",
    classList:[],
    topTabItems:["课程介绍","课程列表","用户评价"],
    currentTopItem: "0",
    commit_list:[],
  },
  onLoad: function (options) {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR;
        // console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
    wx.setNavigationBarTitle({
      title: options.name,
    })
    console.log(options.name)
    console.log(options.id)
    console.log(options.update_time)
    console.log(options.thumb)
    console.log(options.descript)
    that.setData({
      name: options.name,
      thumb:options.thumb,
      id: options.id,
      descript: options.descript
    })
    that.getDetail(options.id);
  },
  //切换顶部标签
  switchnavTab: function (e) {
    // var cate_id = e.currentTarget.dataset.id;
    //console.log(cate_id);
    if (this.needLoadNewDataAfterSwiper1()) {
      console.log(1)
      this.getcommit();
    }
    if (this.needLoadNewDataAfterSwiper2()) {
      console.log(2)
      this.getchapter_list();
    }
    // if (this.needLoadNewDataAfterSwiper3()) {
    //   console.log(3)
    //   this.getjm_list(cate_id);
    // }
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
  },
  bindChange: function (e) {
    //console.log(e)
    var that = this;
    // var cate_id = e.detail.current + 1;
    //console.log(cate_id);
    if (this.needLoadNewDataAfterSwiper1()) {
      console.log(1)
      this.getcommit();
    }
    if (this.needLoadNewDataAfterSwiper2()) {
      console.log(2)
      this.getchapter_list();
    }
    // if (that.needLoadNewDataAfterSwiper3()) {
    //   console.log(3)
    //   that.getjm_list(cate_id)
    // }
    that.setData({
      currentTopItem: e.detail.current
    });
  },
  onReady: function () {

  },
  needLoadNewDataAfterSwiper1: function () {
    return this.data.commit_list.length > 0 ? false : true;
  },
  getcommit: function () {
    var that = this;
    e.get("yoxfriendscircle/comment/comment_list", {
      isajax: 1,
      type: "MESSAGE",
    }, function (e) {
      that.setData({
        commit_list: e.data.list,
      })
    })
  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  // tabDetail: function (t) {
  //   this.setData({
  //     name: t.target.dataset.name,
  //     thumb: t.target.dataset.thumb,
  //     update_time: t.target.dataset.update_time,
  //     descript: t.target.dataset.descript,
  //     is_hot: t.target.dataset.is_hot
  //   })
  // },
  getDetail: function (id) {
    var that = this;
    var name = that.data.name;
    var type = that.data.type;
    var descript = that.data.descript;
    var thumb = that.data.thumb;
    var cate_id = that.data.cate_id;
    var cate_id2 = that.data.cate_id2;
    e.get("yoxwechatbusiness/course/course_edit", { 
      isajax: 1, 
      id: id,
      name: name,
      type: type,
      cate_id: cate_id,
      cate_id2: cate_id2,
      descript: descript,
      "thumb[]": thumb,
      status: 1
    }, function (e) {
    })
  },
  toclass:function(e){
    console.log(e)
    var id = this.data.id;
    var chapter_id = e.currentTarget.dataset.id;
    var name = e.currentTarget.dataset.name;
    wx.navigateTo({
      url: '../class/class?id=' + id + "&chapter_id=" + chapter_id+"&name="+name,
    })
  },
  needLoadNewDataAfterSwiper2: function () {
    return this.data.classList.length > 0 ? false : true;
  },
  getchapter_list:function(){
    var that = this;
    var id = that.data.id;
    e.get("yoxwechatbusiness/course/course_chapter_list", { 
      isajax: 1, 
      course_id: id 
      }, function (e) {
        that.setData({
          classList:e.data.list
        })
    })
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})