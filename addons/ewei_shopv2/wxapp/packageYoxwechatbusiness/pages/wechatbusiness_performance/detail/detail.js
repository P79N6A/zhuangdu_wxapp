var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    id:""
  },
  onLoad: function (options) {
    console.log(options.id)
    var that=this;
    that.setData({
      id:options.id
    })
  },
  getcommissiondetail:function(){
    var that=this;
    var id = that.data.id
    e.get("yoxwechatbusiness.commission.edit", { isajax: 1,id:id }, function (e) {
      that.setData({
      })
    })
  }
})