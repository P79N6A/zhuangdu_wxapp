var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    certificate_list:[],
    avatar:"",
    nickname:"",
    certificate:[],
    merchname:"",
    add_time:""
  },
  onLoad: function (options) {
    var that=this;
    that.getcertificate_list();
  },
  tabcertificate_list:function(t){
    this.setData({
      avatar:t.target.dataset.avatar,
      nickname:t.target.dataset.nickname,
      merchname: t.target.dataset.merchname,
      add_time: t.target.dataset.add_time,
      certificate: t.target.dataset.certificate
    })
  },
  getcertificate_list:function(){
    var that=this;
    e.get("yoxwechatbusiness/certificate/certificate_list", {
      isajax: 1,
      page:1
    }, function (e) {
      for(var i=0;i<e.data.list.length;i++){
        e.data.list[i].add_time = a.formatTimeTwo(e.data.list[i].add_time, 'Y/M/D h:m:s')
      }
      that.setData({
        certificate_list:e.data.list
      })
    })
  }
})