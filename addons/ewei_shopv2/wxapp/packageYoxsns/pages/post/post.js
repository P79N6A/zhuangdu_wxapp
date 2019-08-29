var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery")),
  s = t.requirejs("wxParse/wxParse");

Page({
  data: {
    detail:{},
    content: "" //内容
  },
  onLoad: function(options) {
    var that = this;
    this.getContent(options.id);
  },
  tabContent:function(t){
    this.setData({
      content: t.target.dataset.content
    })
  },
  getContent: function(id) {
    var that = this;
    e.get("yoxsns/post", {
      id: id,
      isajax:1
    }, function(e) {
      that.setData({
        detail:e.result.data.post
      })
       //console.log(e.result.data.post)
      s.wxParse("wxParseData", "html", e.result.data.post.content, that, "10")
    })
  }
});