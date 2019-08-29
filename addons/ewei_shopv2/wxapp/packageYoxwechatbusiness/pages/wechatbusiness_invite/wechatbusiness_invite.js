var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    inviteList:[],
    avatar:"",
    nickname:"",
    choose_id:2,
    showload:1,
    page:1,
    nolist:0
  },
  onLoad: function (options) {
    this.setData({
      choose_id: options.id
    });
    var that=this;
    that.getinviteList();
  },
  changeNav:function(e){
    var id = e.currentTarget.dataset.id;
    this.setData({
      choose_id: id,
      showload:1,
      inviteList:[],
      nolist:0
    });
    this.getinviteList(id);

  },

  tabinviteList:function(t){
    this.setData({
      avatar:t.target.dataset.avatar,
      nickname:t.target.dataset.nickname
    })
  },
  getinviteList:function(choose_id){
    var that=this;
    if (choose_id==1) {
      var url="yoxwechatbusiness/user/my_down_level_user";
      var status=1;
    }else if(choose_id==2){
      var url="yoxwechatbusiness/user/my_invite_user";
      var status=1;
    }else if(choose_id==3){
      var url="yoxwechatbusiness/user/my_down_level_user";
      var status=0;
    }else if(choose_id==4){
      var url="yoxwechatbusiness/user/my_invite_user";
      var status=0;
    }
    e.get("yoxwechatbusiness/user/my_invite_user", { isajax: 1,status:status,page:this.data.page}, function (e) {
      if (e.status == 0) {
        wx.showModal({
          title: '',
          content: '等级权限不够',
          success(res) {
            wx.navigateBack({
              delta: 1
            })
            return;
          }
        })
      }else{
          var inviteList=that.data.inviteList;
          that.setData({
            inviteList:inviteList.concat(e.data.list)
          });
      }
      if (that.data.inviteList.length<1) {
          var nolist=1;
      }else{
          var nolist=0;
      }
      that.setData({
        nolist:nolist,
        showload:0
      });
    })
  },

  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  onPullDownRefresh: function () {

  },

  onReachBottom: function() {
        this.getinviteList(this.data.choose_id);
  },
  onShareAppMessage: function () {

  }
})