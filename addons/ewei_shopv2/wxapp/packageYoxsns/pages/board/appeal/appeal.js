var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery")),
  s = t.requirejs("wxParse/wxParse");


Page({
  data: {
    winHeight: "", //窗口高度
    currentnavTab: 0, //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    attented: true,
    zhongcaoshe: {},
    avatar: "",          //头像
    nickname: "",       //昵称
    content: "",       //内容
    images: "",
    createtime: "",   //时间
    goodcount: "",   //点赞
    title: "",      //标题
    postcount: "", //评论
    board_info: {},
    id: 4,
    uid: "",
    nametitle: "",
    items: [{
      "pagePath": "../../../../pages/index/index",
      "iconPath": "../../../../static/images/tabbar/icon-1.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-1-active.png",
      "text": "首页"
    },
    {
      "pagePath": "../../../../pages/shop/caregory/index",
      "iconPath": "../../../../static/images/tabbar/icon-10.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-10-active.png",
      "text": "妆度"
    },
    // {
    //   "pagePath": "../../../../pages/topage/ToHiZhuandu/ToHiZhuandu",
    //   "iconPath": "../../../../static/images/tabbar/icon-102.png",
    //   "selectedIconPath": "../../../../static/images/tabbar/icon-102.png",
    //   "text": "美妆助理"
    // },
    {
      "pagePath": "../../../../packageYoxgroupbuy/pages/index/index",
      "iconPath": "../../../../static/images/tabbar/icon-9.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-9-active.png",
      "text": "咔咔团"
    },
    {
      "pagePath": "../../../../pages/topage/ToUser/ToUser",
      "iconPath": "../../../../static/images/tabbar/icon-5.png",
      "selectedIconPath": "../../../../static/images/tabbar/icon-5-active.png",
      "text": "我的"
    },
    ],
    currentTab: 5,
    listArr: []
  },
  // 滚动切换标签样式
  switchnavTab: function (e) {
    this.setData({
      currentnavTab: e.detail.current
    });
  },
  // 点击标题切换当前页时改变样式
  swichnav: function (e) {
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
  // checkCor: function() {
  // if (this.data.currentTab > 4) {
  //   this.setData({
  //     scrollLeft: 300
  //   })
  // } else {
  //   this.setData({
  //     scrollLeft: 0
  //   })
  // }
  // },
  attent: function () {
    var attented = this.data.attented;
    this.setData({
      attented: true
    })
    wx.setStorageSync('attent', attented);
  },
  previewImg(e) {
    let index = e.currentTarget.dataset.index;
    //console.log(index)
    var types = e.currentTarget.dataset.types;
    //console.log(types)
    var imglist = e.currentTarget.dataset.imglist;
    //console.log(imglist)
    //let zhongcaoshe = this.data.zhongcaoshe;
    // for (var i = 0; i < this.data.zhongcaoshe.length; i++) {
    // var images = this.data.zhongcaoshe[i].images
    // for (var j = 0; j < images.length; j++) {
    wx.previewImage({
      current: types,
      urls: imglist,
    })
      // }
    // }
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
  noattent: function () {
    var attented = this.data.attented;
    this.setData({
      attented: !this.data.attented
    })
    wx.removeStorageSync('attent');
  },
  tabZhongcaoshe: function (t) {
    this.setData({
      avatar: t.target.dataset.avatar,
      nickname: t.target.dataset.nickname,
      title: t.target.dataset.title,
      images: t.target.dataset.images,
      createtime: t.target.dataset.createtime,
      postcount: t.target.dataset.postcount,
      goodcount: t.target.dataset.goodcount,
      uid: t.target.dataset.uid
    });
  },
  // tabName: function (t) {
  //   this.setData({
  //     id: t.target.dataset.id,
  //     nametitle: t.target.dataset.title
  //   })
  // },
  touser: function (event) {
    console.log(event)
    var that = this;
    var mainid = that.data.id;
    console.log(mainid)
    var id = event.currentTarget.dataset.id;
    console.log(id)
    var uid = event.currentTarget.dataset.uid;
    console.log(uid)
    wx.navigateTo({
      url: '../user?uid=' + uid + "&mainid=" + mainid + "&id=" + id,
    })
  },
  onCollectionTap: function (event, id) {
    var that = this;
    var id = that.data.id;
    console.log(id)
    var title = that.data.title;
    // var index = event.currentTarget.dataset.index;
    var commitUser_id = event.currentTarget.dataset.id;
    var zhongcaoshe = that.data.zhongcaoshe;
    e.get("yoxsns/post/like", {
      bid: 4,
      pid: commitUser_id
    }, function (e) {
      if (e.status == 0) {
        wx.showModal({
          title: '',
          content: e.result.message,
          success(res) {
            return;
          }
        })
      }
      if (e.result.isgood == 1) { //如果是没点赞+1
        wx.showToast({
          title: '点赞成功'
        })
      } else if (e.result.isgood == 0) {
        wx.showToast({
          title: '取消点赞'
        })
      }
      that.getZhongcaoshe(id, title);
      that.setData({
        zhongcaoshe: zhongcaoshe
      })
    })
  },
  tocommentlist: function (event) {
    //console.log(event)
    var that = this;
    var mainid = that.data.id;
    var id = event.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../lists?id=' + id + "&mainid=" + mainid,
    })
  },
  onDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../post/post?id=' + id,
    })
  },
  toprimary: function (mainid) {
    var that = this;
    var mainid = that.data.id;
    console.log(mainid)
    wx.navigateTo({
      url: '../primary/primary?mainid=' + mainid,
    })
  },
  getZhongcaoshe: function (id, title) {
    var t = this;
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      page: 1,
      bid: 4
    }, function (e) {
      // for (let i = 0; i < e.result.list.length; i++) {
      //   s.wxParse('content' + i, 'html', e.result.list[i].content, t);
      //   if (i === e.result.list.length - 1) {
      //     s.wxParseTemArray("replyTemArray", 'content', e.result.list.length, t)
      //   }
      // }
      // let list=t.data.listArr;
      // list.map((item,index,arr)=>{
      //   arr[index].content=e.result.list.id;
      // })
      t.setData({
        zhongcaoshe: e.result.list
      })
      // var replyArr = [];
      // replyArr.push(e.result.list[0].content);
      // replyArr.push(e.result.list[1].content);
      // replyArr.push(e.result.list[2].content);
      // replyArr.push(e.result.list[3].content);
      // replyArr.push(e.result.list[4].content);
    })
    // e.get("yoxsns/board", {
    //   isajax: 1,
    //   id: id,
    //   title: title
    // }, function (e) {
    //   t.setData({
    //     board_info: e.result.data
    //   })
    //   //console.log(e.result.data.title)
    //   wx.setNavigationBarTitle({
    //     title: e.result.data.title,
    //   });
    //   // console.log(setData)
    // })
  },
  onLoad: function (options) {
    var that = this;
    //console.log(options.title)
    //  高度自适应
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
    that.getZhongcaoshe();
    // //console.log(options.title)
    // that.setData({
    //   id: options.id,
    //   //title: options.title
    // })
  },
  footerTap: t.footerTap
})