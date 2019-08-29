var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));

Page({
  data: {
    curNav: 0,
    curIndex: 0,
    merch_user:{},
    list:{},
    merchname:"",  //商家名
    thumb:"",     //图
    title:""     //标题
  },
  onLoad: function(options) {
    this.getBrand();
    this.getList();
  },
  // 左侧按钮
  left: function(e) {
    console.log(e)
    var that = this;
    var id = e.currentTarget.dataset.id;
    that.getList(id);
    var index = e.currentTarget.dataset.index;
    that.setData({
      curNav: index,
      curIndex: index,
    })
  },
  tabBrand:function(t){
    this.setData({
      merchname:t.target.dataset.merchname
    })
  },
  tabList:function(t){
    this.setData({
      thumb:t.target.dataset.thumb,
      title:t.target.dataset.title
    })
  },
  getBrand:function(){
    var that=this;
    e.get("merch/yoxmerch/merch_user_list", {
      isajax: 1
    }, function (e) {
      that.setData({
        merch_user:e.data.list
      })
    })
  },
  getList:function(id){
    var that = this;
    e.get("goods/get_list", {
      isajax: 1,
      merchid: id
    }, function (e) {
      that.setData({
        list:e.list
      })
    })
  }
})