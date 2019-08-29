var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    winHeight: "",//窗口高度
    currentTab: 0, //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    article:[],
    articleName:"",
    articleAdd_name:"",
    articleUpdate_time:"",
    articleThumbs:"",
    video:[],
    add_time_format:"",
    name:"",
    videos:"",
    add_time:""
  },
  // 滚动切换标签样式
  switchTab: function (e) {
    this.setData({
      currentTab: e.detail.current
    });
    // this.checkCor();
  },
  // 点击标题切换当前页时改变样式
  swichNav: function (e) {
    var cur = e.target.dataset.current;
    if (this.data.currentTaB == cur) { return false; }
    else {
      this.setData({
        currentTab: cur
      })
    }
  },
  //判断当前滚动超过一屏时，设置tab标题滚动条。
  // checkCor: function () {
  //   if (this.data.currentTab > 4) {
  //     this.setData({
  //       scrollLeft: 300
  //     })
  //   } else {
  //     this.setData({
  //       scrollLeft: 0
  //     })
  //   }
  // },
  tabVideo:function(t){
    this.setData({
      name:t.target.dataset.name,
      videos:t.target.dataset.videos,
      add_time:t.target.dataset.add_time,
      add_time_format: t.target.dataset.add_time_format
    })
  },
  previewImg(e) {
    console.log(e);
    wx.navigateTo({
      url: 'detail/index?id='+e.target.dataset.id
    })
    // let index = e.currentTarget.dataset.index;
    // var thumbs = e.currentTarget.dataset.thumbs;
    // var new_thumbs = "https://zdu.igdlrj.com/attachment/"+thumbs;
    // console.log(new_thumbs)
    // var article = this.data.article;
    // var arr = [];
    // for(var i=0;i<article.length;i++){
    //   var thumbs_item = "https://zdu.igdlrj.com/attachment/"+article[i].thumbs;
    //   arr.push(thumbs_item)
    // }
    // console.log(arr)
    // wx.previewImage({
    //   current: arr[index],
    //   urls: arr,
    // })
  },
  getVideo:function(){
    var that = this;
    e.get("yoxarticlevideo/video", {
      isajax: 1
    }, function (e) {
      that.setData({
        video:e.data.list
      })
    })
  },
  tabArticle:function(t){
    this.setData({
      articleName:t.target.dataset.name,
      articleThumbs:t.target.dataset.thumbs
    })
  },
  getArticle:function(){
    var that = this;
    e.get("yoxarticlevideo/article", {
      isajax: 1
    }, function (e) {
      that.setData({
        article:e.data.list
      })
    })
  },
  //视频播放开始播放
  videoPlay: function (obj) {
    //暂停上一条视频的播放
    if (this.videoContext) {
      console.log(this.videoContext);
      this.videoContext.pause();
    }
    this.videoContext = wx.createVideoContext(obj.currentTarget.dataset.id);
  },

  //视频结束播放
  videoEndPlay: function (obj) {
    this.videoContext.seek(0);
  },
  onLoad: function (options) {
    var that = this;
    //  高度自适应
    console.log(options.cateid)
    if (options.cateid==2){
      that.setData({
        currentTab: 1
      })
    }
    wx.getSystemInfo({
      success: function (res) {
        var clientHeight = res.windowHeight,
          clientWidth = res.windowWidth,
          rpxR = 750 / clientWidth;
        var calc = clientHeight * rpxR;
        console.log(calc)
        that.setData({
          winHeight: calc
        });
      }
    });
    that.getArticle();
    that.getVideo();
  },
  footerTap: t.footerTap
})