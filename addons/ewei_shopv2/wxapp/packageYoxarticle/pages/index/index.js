var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    articleList:[],
    article_title:"",
    article_sys:{} ,
    catearticle:[],
    currentTopItem: "0",
  },
  onLoad: function (options) {
    var that=this;
    that.getArticle();
    that.getcatearticle();
  },
  //切换顶部标签
  switchTab2: function (e) {
    var id = e.currentTarget.dataset.id;
    console.log(id)
    this.setData({
      currentTopItem: e.currentTarget.dataset.index,
    });
    this.getArticle(id);
    this.checkCor();
  },
  // 判断当前滚动超过一屏时，设置tab标题滚动条。
  checkCor: function () {
    if (this.data.currentTopItem > "4") {
      this.setData({
        scrollLeft: 500
      })
    } else {
      this.setData({
        scrollLeft: 0
      })
    }
  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  tabarticleList:function(t){
    this.setData({
      article_title: t.target.dataset.article_title
    })
  },
  getArticle:function(id){
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    var that=this;
    e.get("yoxarticle/list/getlist", {
      isajax: 1,
      cateid: id
    }, function (e) {
     that.setData({
       articleList: e.data.list,
       article_sys:e.data.article_sys
     })
      //console.log(that.data.articleList);
      //console.log(that.data.article_sys);
      wx.hideToast();
    })
  },
  todetail:function(e){
    var that=this;
    var id=e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../detail/detail?id='+id,
    })
  },
  getcatearticle: function () {
    var that = this;
    e.get("yoxarticle/list", {
      isajax: 1
    }, function (e) {
      that.setData({
        catearticle: e.data.list
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