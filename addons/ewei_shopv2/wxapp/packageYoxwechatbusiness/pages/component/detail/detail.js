var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    ingredient: {},
    cn_name: "",   //成分名
    en_name:"",   //英文名
    general_characteristics:"",  //类目
    safety:"",   //安全
    stimulate:"", //刺激
    product_id:"",
    component:{},
    name:"",
    thumb:"",
    url:"",
    add_time:"",
    cn_nameList:[],
    product_id:null
  },
  onLoad: function (options) {
    var that=this;
    console.log(options.name)
    console.log(options.product_id)
    that.setData({
      name:options.name,
      product_id: options.product_id
    })
    that.getIngredient(options.product_id);
    that.getSameIngredient(options.product_id);
  },
  tabIngredient: function (t) {
    this.setData({
      cn_name: t.target.dataset.cn_name,
      en_name: t.target.dataset.en_name,
      general_characteristics: t.target.dataset.general_characteristics,
      safety: t.target.dataset.safety,
      stimulate: t.target.dataset.stimulate
    })
  },
  getIngredient: function (product_id) {
    var that = this;
    //var product_id = that.data.product_id;
    e.get("yoxwechatbusiness/ingredients/ingredient", { isajax: 1, product_id: product_id }, function (e) {
      for (var i = 0; i < e.data.ingredient_list.length;i++){
        var cn_nameList= e.data.ingredient_list[i].cn_name;
        // console.log(cn_nameList)
        that.setData({
          cn_nameList: e.data.ingredient_list[i].cn_name
        })
      }
      that.setData({
        ingredient: e.data.ingredient_list
      })
    })
  },
  tabSameIngredient: function(t){
    this.setData({
      name: t.target.dataset.name,
      thumb: t.target.dataset.thumb,
      url: t.target.dataset.url,
      add_time: t.target.dataset.add_time
    })
  },
  getSameIngredient: function (product_id){
    var that=this;
    e.get("yoxwechatbusiness/ingredients/same_ingredient_goods_list", { isajax: 1, product_id: product_id }, function (e) {
      for (var i = 0; i < e.data.same_ingredient_goods_list.length; i++) {
        e.data.same_ingredient_goods_list[i].add_time = a.formatTimeTwo(e.data.same_ingredient_goods_list[i].add_time, 'Y/M/D h:m:s')
      }
      that.setData({
        component: e.data.same_ingredient_goods_list
      })
      //console.log(e.data.same_ingredient_goods_list)
    })
  },
  linkTap:function(e){
    var id=e.currentTarget.dataset.id;
    //console.log(e)
    var url = e.currentTarget.dataset.url;
    //console.log(url)
    wx.navigateTo({
      url: '../link/link?url='+url,
    })
  }
})