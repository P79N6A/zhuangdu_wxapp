var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    uid:"",
    user:[]
  },
  onLoad: function (options) {
    var that=this;
    console.log(options.uid)
    that.setData({
      uid:options.uid
    })
    that.getmembereditList(options.uid);
  },
  getmembereditList:function(uid){
    var that=this;
    e.get("yoxwechatbusiness/user/member_edit", { isajax: 1, uid: uid }, function (e) {
      that.setData({
        user:e.data
      })
    })
  }
})