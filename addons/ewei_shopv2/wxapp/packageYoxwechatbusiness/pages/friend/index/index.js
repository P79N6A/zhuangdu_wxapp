var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    friendList:[],
    add_time_format:"",
    frient_avatar:"",
    frient_nickname:"",
    remark_name:""
  },
  onLoad: function (options) {
    var that = this;
    that.getfriend();
  },
  tabfriend:function(t){
    this.setData({
      add_time_format: t.target.dataset.add_time_format,
      frient_avatar:t.target.dataset.avatar,
      frient_nickname:t.target.dataset.frient_nickname,
      remark_name: t.target.dataset.remark_name
    })
  },
  getfriend: function () {
    var that = this;
    e.get("yoxwechatbusiness/frient/frient_list", {
      isajax: 1
    }, function (e) {
      that.setData({
        friendList:e.data.list
      })
    })
  },
  revise:function(e){
    var remark_name = e.currentTarget.dataset.remark_name;
    var uid = e.currentTarget.dataset.uid;
    wx.navigateTo({
      url: '../revise/revise?remark_name=' + remark_name+"&uid="+uid,
    })
  },
  delete_item:function(event){
    var id = event.currentTarget.dataset.id;
    console.log(id)
    var that=this;
    wx.showModal({
      title: '提示',
      content: '是否删除该好友',
      success(res){
        if (res.confirm) {
          e.get("yoxwechatbusiness/frient/frient_delete", {
            isajax: 1,
            id: id
          }, function (e) {
            that.getfriend();
          })
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
  }
})