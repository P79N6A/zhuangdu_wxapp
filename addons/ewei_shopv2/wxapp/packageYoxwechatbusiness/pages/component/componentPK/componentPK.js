// packageYoxwechatbusiness/pages/component/componentPK/componentPK.js
var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
Page({
  data: {
    componentList0:[],
    componentList1:[],
    name0:"",
    name1:"",
    id0:"",
    id1:"",
    cn_name:""
  },
  onLoad: function (options) {
    var that=this;
    console.log(options.id0)
    console.log(options.id1)
    that.setData({
      id0:options.id0,
      id1:options.id1
    })
    console.log(options.name0)
    console.log(options.name1)
    that.setData({
      name0:options.name0,
      name1:options.name1
    })
    that.getIngredients(options.id0);
    that.getIngredients(options.id1);
  },
  tabIngredients:function(t){
    this.setData({
      cn_name:t.target.dataset.cn_name
    })
  },
  getIngredients: function (product_id){
    var that=this;
    e.get("yoxwechatbusiness/ingredients/ingredient", { isajax: 1, product_id: product_id }, function (e) {
      that.setData({
        componentList0: e.data.ingredient_list,
        componentList1: e.data.ingredient_list
      })
      //console.log(e.data.ingredient_list)
    })
  }
})