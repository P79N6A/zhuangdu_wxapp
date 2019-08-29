var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    inviteuid:"",
    flag:false
  },
  onLoad: function (t) {
    
  },
  onReady: function () {

  },
  onShow: function (t) {
    t = t || {};
    var a = this;
    console.log(JSON.stringify(t))
    if (JSON.stringify(t) !== "{}") {
      var scene = t.scene;
      console.log(scene.slice(12, 15))
      var endscene = scene.slice(12, 15)
      a.setData({
        inviteuid: endscene,
        flag: true
      })
      var inviteuid = a.data.inviteuid
      if (inviteuid !== "") {
        a.getyoxwechatbusiness();
      }
    }
  },
  getyoxwechatbusiness: function (uid, invitationcode){
    var that=this;
    e.get("yoxwechatbusiness/user/invited_result", {
      isajax: 1,
      inviteuid:uid,
      invitationcode: invitationcode
    }, function (e) {
      if (e.is_fill_info == 0){
        wx.reLaunch({
          url: '../personal_means/index/index',
        })
      }
    })
  }
})