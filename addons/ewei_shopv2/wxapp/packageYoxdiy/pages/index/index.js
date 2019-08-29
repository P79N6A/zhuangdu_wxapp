var a = getApp(), e = a.requirejs("core");

a.requirejs("jquery"), a.requirejs("foxui");

Page({
    data: {
      diyList:[],
      page_valueList:[],
      name:"",
      winWidth: 0,
      winHeight: 0,
      x:"",
      y:"",
      x1:"",
      y1:"",
      text:""
    },
    onLoad: function(options) {
      var that = this
      /**
      * »ñÈ¡ÏµÍ³ÐÅÏ¢
      */
      wx.getSystemInfo({
        success: function (res) {
          that.setData({
            winWidth: res.windowWidth,
            winHeight: res.windowHeight
          });
        }
      });
      that.getdiyList();
    },
    tabdiyList:function(t){
      this.setData({
        name:t.target.dataset.name,
        page_valueList:t.target.dataset.page_value
      })
    },
    getdiyList:function(){
      var that=this;
      e.get("yoxdiy/template_list", {
        isajax: 1
      }, function (e) {
        for(var i=0;i<e.data.list.length;i++){
          //console.log(page_value)
          e.data.list[i].page_value = JSON.parse(e.data.list[i].page_value)
        }
        that.setData({
          diyList:e.data.list
        })
        //console.log(that.data.diyList)
      })
    },
  detailchange:function(e){
    var id=e.currentTarget.dataset.id;
    var page_value=e.currentTarget.dataset.page_value;
    //console.log(page_value)
    var page_valuechange=JSON.stringify(page_value);
    //console.log(page_valuechange)
    wx.navigateTo({
      url: '../detail/detail?id=' + id + "&page_value=" + page_valuechange,
    })
  }
});