var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
    protect_skin:["祛痘","美白","祛皱"],
    cosmetics:["眼影","睫毛","化妆","口红","眼线","唇釉","腮红"],
    sun_cream:["防晒指数","夏季防晒","小技巧"],
    component:["小分子","美白成分","中药成分"],
    cateList:[],
    name:""
  },
  onLoad: function (options) {
    var that=this;
    that.getcate();
  },
  tabcate:function(t){
    this.setData({
      name:t.target.dataset.name
    })
  },
  getcate:function(){
    var that=this;
    e.get("yoxwechatbusiness/course/course_cate_list", {
      isajax: 1
    }, function (e) {
      that.setData({
        cateList:e.data.list
      })
    })
  }
})