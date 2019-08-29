// pages/ToFriendscircle/ToFriendscircle.js
var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery")),
  s = t.requirejs("wxParse/wxParse");
Page({
  data: {
    winHeight: "", //窗口高度
    currentnavTab: 0, //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    id: null,
    wanbaList: {},
    avatar: "", //头像
    createtime: "", //时间
    images: "", //图片
    nickname: "", //名
    title: "", //题目
    content: "",
    checked: 0, //标记
    goodcount: 0, //赞数
    postcount: "", //转发数
    isbest: "", //评论数(最精彩)
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
  onLoad: function(options) {
    // wx.navigateTo({
    //   url: '../../packageYoxFriendscircle/pages/index',
    // })
    // wx.redirectTo({
    //   url: '../../packageYoxFriendscircle/pages/index',
    // })
    // wx.reLaunch({
    //   url: '../index/index'
    // })
    // wx.navigateBack({
    //   delta: 9999999999999999999999999999999
    // })
    var that = this;
    wx.getSystemInfo({
        success: function(res) {
          var clientHeight = res.windowHeight,
            clientWidth = res.windowWidth,
            rpxR = 750 / clientWidth;
          var calc = clientHeight * rpxR;
          that.setData({
            winHeight: calc
          });
        },
      }),
      that.getWanba();
  },
  //事件处理函数
  bindChange: function (e) {
    let that = this;
    that.setData({
      currentnavTab: e.detail.current
    });
  },
  swichNav: function (e) {
    let that = this;
    if (this.data.currentnavTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentnavTab: e.target.dataset.current
      })
    }
  },
  onReady: function() {

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
  onShareAppMessage: function() {

  },
  // 滚动切换标签样式
  switchnavTab: function(e) {
    this.setData({
      currentnavTab: e.detail.current
    });
    this.checkCor();
  },
  // 点击标题切换当前页时改变样式
  swichNav1: function(e) {
    var cur = e.target.dataset.current;
    if (this.data.currentnavTab == cur) {
      return false;
    } else {
      this.setData({
        currentnavTab: cur
      })
    }
  },
  //判断当前滚动超过一屏时，设置tab标题滚动条。
  checkCor: function() {
    if (this.data.currentnavTab > 4) {
      this.setData({
        scrollLeft: 300
      })
    } else {
      this.setData({
        scrollLeft: 0
      })
    }
  },
  onCollectionTap: function(event) {
    var that = this;
    // var index = event.currentTarget.dataset.index;
    var commitUser_id = event.currentTarget.dataset.id;
    var wanbaList = that.data.wanbaList;
    e.get("yoxsns/post/like", {
      bid: 3,
      pid: commitUser_id
    }, function(e) {
      // var collectStatus = false;
       if (e.result.isgood == 1) { //如果是没点赞+1
        wx.showToast({
          title: '点赞成功'
        })
       } else if (e.result.isgood == 0){
        // collectStatus = false
        // wanbaList[i].goodcount = e.result.good
        wx.showToast({
          title: '取消点赞'
        })
      }
      that.getWanba();
      that.setData({
        wanbaList: wanbaList
      })
    })
  },
  onDackTap: function(e) {
    var index = e.currentTarget.dataset.index;
    //var commitUser_id = e.currentTarget.dataset.id;
    var message = this.data.wanbaList;
    for (let i in message) {
      if (i == index) {
        var darkStatus = false;
        if (message[i].checked == 0) {
          darkStatus = true
          message[i].checked = parseInt(message[i].checked) + 1
        } else {
          darkStatus = false
          message[i].checked = parseInt(message[i].checked) - 1
        }
        wx.showToast({
          title: darkStatus ? '取消收藏' : '收藏成功'
        })
      }
    }
    this.setData({
      wanbaList: message
    })
  },
  tabWanba: function(t) {
    this.setData({
      avatar: t.target.dataset.avatar,
      createtime: t.target.dataset.createtime,
      images: t.target.dataset.images,
      nickname: t.target.dataset.nickname,
      title: t.target.dataset.title,
      content: t.target.dataset.content,
      checked: t.target.dataset.checked,
      goodcount: t.target.dataset.goodcount,
      postcount: t.target.dataset.postcount,
      isbest: t.target.dataset.isbest
    })
  },
  getWanba: function() {
    var that = this;
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      bid: 3
    }, function(e) {
      that.setData({
        wanbaList: e.result.list
      })
      // console.log(e.result.list)
      //   var noteArr = [];
      //   for (var i = 0; i < e.result.list.length; i++) {
      //     var content = e.result.list[i].content == null ? '' : e.result.list[i].content;
      //     noteArr.push(content);
      //   }
      //   for (let i = 0; i < noteArr.length; i++) {
      //     s.wxParse('content' + i, 'html', noteArr[i], that);
      //     if (i === noteArr.length - 1) {
      //       s.wxParseTemArray("noteTemArray", 'content', noteArr.length, that)
      //     }
      //   }
    })
  },
  showAll: function(e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var content = e.currentTarget.dataset.content;
    // console.log(content)
    wx.navigateTo({
      url: './detail/detail?content=' + escape(content),
    })
  },
  bindCommit: function(event) {
    //console.log(event)
    var id = event.target.dataset.id;
    wx.navigateTo({
      url: './comment_list/comment_list?id=' + id,
    })
  },
  toprimary:function(){
    wx.navigateTo({
      url: './primary/primary',
    })
  }
})