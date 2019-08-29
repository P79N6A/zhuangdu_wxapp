var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery")),
  s = t.requirejs("wxParse/wxParse");
Page({
  data: {
    topTabItems: ["关注", "广场", "话题"],
    currentTopItem: "0",
    winHeight: "",
    sliderList: [{
        selected: true,
        imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg'
      },
      {
        selected: false,
        imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/kVXtaktzY5VtisSYSKQS8QK5ZgVyAy.jpg'
      },
      {
        selected: false,
        imageSource: 'https://zdu.igdlrj.com/attachment/images/2/2018/12/o6QlfHWH606XcbxBL7btt6CC0C6wNl.jpg'
      },
    ],
    attentiongroup2List: [],
    attentiongroup2List2: [],
    attentiongroup2List3: [],
    group3List: [{
        title: "彩妆",
        Engtitle: "COSMETICS"
      },
      {
        title: "护肤",
        Engtitle: "SKIN CARE"
      },
      {
        title: "药妆",
        Engtitle: "MEDICINAL METICS"
      }
    ],
    topicList: [{
        title: "#三亚·创时代#",
        attention: "关注",
        futitle: "妆度2019开启品牌新篇章",
        imgUrl: ["", "", "", ""],
        attentionumber: "19655",
        tie: "21425"
      },
      {
        title: "#三亚·创时代#",
        attention: "关注",
        futitle: "妆度2019开启品牌新篇章",
        imgUrl: ["", "", "", ""],
        attention: "19655",
        tie: "21425"
      },
      {
        title: "#三亚·创时代#",
        attention: "关注",
        futitle: "妆度2019开启品牌新篇章",
        imgUrl: ["", "", "", ""],
        attention: "19655",
        tie: "21425"
      }
    ],
    wanbaList: [],
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
    isfollow: "",
    noteTemArray:[]
  },
  onLoad: function(options) {
    var that = this;
    that.getfollowlist();

    function sliceArray(array, size) {
      var result = [];
      for (var x = 0; x < Math.ceil(array.length / size); x++) {
        var start = x * size;
        var end = start + size;
        result.push(array.slice(start, end));
      }
      return result;
    }
    var attentiongroup2List = that.data.attentiongroup2List;
    console.log(attentiongroup2List)
    var attentiongroup2List = sliceArray(attentiongroup2List, 5);
    console.log(attentiongroup2List)
    that.setData({
      attentiongroup2List2: attentiongroup2List[0],
      attentiongroup2List3: attentiongroup2List[1]
    })
    wx.getSystemInfo({
      success: function(res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR + 2300;
        // console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
  },
  //切换顶部标签
  switchTab2: function(e) {
    if (this.needLoadNewDataAfterSwiper()) {
      this.getWanba();
    }
    if (this.needLoadNewDataAfterSwiper()) {
      this.getfollowlist();
    }
    if (this.needLoadNewDataAfterSwiper()) {
      this.gettopic();
    }
    this.setData({
      currentTopItem: e.currentTarget.dataset.idx
    });
  },
  toscheme:function(e){
    var id = e.currentTarget.dataset.id;
    console.log(id)
    wx.navigateTo({
      url: '../board/board?id='+id,
    })
  },
  //swiperChange
  bindChange: function(e) {
    var that = this;
    if (that.needLoadNewDataAfterSwiper()) {
      that.getWanba();
    }
    if (that.needLoadNewDataAfterSwiper()) {
      that.getfollowlist();
    }
    if (that.needLoadNewDataAfterSwiper()) {
      that.gettopic();
    }
    that.setData({
      currentTopItem: e.detail.current
    });
  },
  //轮播图绑定change事件，修改图标的属性是否被选中
  switchTab: function(e) {
    var sliderList = this.data.sliderList;
    var i, item;
    for (i = 0; item = sliderList[i]; ++i) {
      item.selected = e.detail.current == i;
    }
    this.setData({
      sliderList: sliderList
    });
  },
  onReady: function() {

  },
  needLoadNewDataAfterSwiper: function() {
    return this.data.wanbaList.length > 0 ? false : true;
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
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that = this;
    e.get("yoxsns/board/getlist", {
      isajax: 1,
      bid: 3
    }, function(e) {
      that.setData({
        wanbaList: e.result.list
      })
      wx.hideToast();
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
  onCollectionTap: function(event) {
    var that = this;
    var wanbaList = that.data.wanbaList;
    var id = event.currentTarget.dataset.id;
    console.log(id)
    e.get("yoxsns/post/like", {
      bid: 3,
      pid: id
    }, function(e) {
      that.getWanba();
      that.setData({
        wanbaList: wanbaList
      })
    })
  },
  gettopic: function() {
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that = this;
    e.get("yoxsns/board/getlist", {
      isajax: 1
    }, function(e) {
      console.log(e.result.list)
      var tolist = e.result.list;
      console.log(tolist)
      for (let i = 0; i < tolist.length; i++) {
        s.wxParse('content' + i, 'html', tolist[i].content, that);
        if (i === tolist.length - 1) {
          s.wxParseTemArray("noteTemArray", 'content', tolist.length, that)
        }
      }
      console.log(tolist)
      let list = that.data.noteTemArray;
      for(let i=0;i<tolist.length;i++){
        list[i]['a']=tolist[i]['title'];
      }
      list.map((item,index,arr)=>{
        arr[index][0].id=tolist[index]['id'];
        arr[index][0].title=tolist[index]['title'];
        arr[index][0].createtime=tolist[index]['createtime'];
        arr[index][0].images=tolist[index]['images'];
        arr[index][0].isbest=tolist[index]['isbest'];
        arr[index][0].postcount = tolist[index]['postcount'];
      });
      that.setData({
        topicList: list
      })
      console.log(that.data.topicList)
      wx.hideToast();
    })
  },
  getfollowlist: function() {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxsns/board/get_boardlist", {
      isajax: 1,
      page: 1
    }, function(e) {
      that.setData({
        attentiongroup2List: e.result.list.slice(0, 4),
        attentiongroup2List3: e.result.list.slice(4, 10)
      })
      wx.hideToast();
    })
  },
  getfollow: function(event) {
    console.log(event)
    var bid = event.currentTarget.dataset.id;
    var that = this;
    e.get("yoxsns/board/follow", {
      isajax: 1,
      bid: bid
    }, function(e) {
      that.getfollowlist();
    })
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

  }
})