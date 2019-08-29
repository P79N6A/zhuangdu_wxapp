var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({

  data: {
      navList:[
        {
          id:1,
          imgUrl:"images/01.png",
          title:"我要开播"
        },
        {
          id: 2,
          imgUrl: "images/02.png",
          title: "发布动态"
        },
        {
          id: 3,
          imgUrl: "images/03.png",
          title: "语音直播"
        },
        {
          id: 4,
          imgUrl: "images/04.png",
          title: "拍个视频"
        }
      ]
  },
  onLoad: function (options) {

  },
  tonavdetail:function(e){
    var id = e.currentTarget.dataset.id;
    if(id==1){
      wx.navigateTo({
        url: '../create_class/create_class',
      })
    }
  }
})