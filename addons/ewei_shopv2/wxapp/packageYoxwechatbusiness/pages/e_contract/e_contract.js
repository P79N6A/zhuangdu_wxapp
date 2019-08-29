var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery")),
  s = t.requirejs("wxParse/wxParse");
Page({
  data: {
    contract:[]
  },
  onLoad: function (options) {
    var that=this;
    that.gete_contract();
  },
  gete_contract:function(){
    var that=this;
    e.get("yoxwechatbusiness/electronic_contract/edit", {
      isajax: 1
    }, function (e) {
      that.setData({
        contract: e.data
      })
      s.wxParse("wxParseData", "html", e.data.content, that, "10")
    })
  }
})