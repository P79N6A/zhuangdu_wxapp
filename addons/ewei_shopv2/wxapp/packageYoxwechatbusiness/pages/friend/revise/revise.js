var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    remark_name:"",
    uid:"",
    text:"",
    btnflag:false
  },
  onLoad: function (options) {
    console.log(options.remark_name)
    console.log(options.uid)
    var that = this;
    that.setData({
      remark_name:options.remark_name,
      uid:options.uid
    })
  },
  bindtext:function(e){
    console.log(e.detail.value)
    if (e.detail.value!==""){
      this.setData({
        btnflag: true
      })
    }else{
      this.setData({
        btnflag: false
      })
    }
    this.setData({
      text:e.detail.value
    })
  },
  formsubmit:function(){
    var that=this;
    var value = that.data.text;
    console.log(value)
    var uid = that.data.uid;
    e.get("yoxwechatbusiness/frient/frient_edit", {
      isajax: 1,
      frient_uid:uid,
      remark_name:value
    }, function (e) {
      wx.showToast({
        title: "保存成功",
        icon: 'success',
        duration: (10000 <= 0) ? 10000 : 10000,
        success:function(){
          wx.reLaunch({
            url: '../index/index',
          })
        }
      });
    })
  }
})