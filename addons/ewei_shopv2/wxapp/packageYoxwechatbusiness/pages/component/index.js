var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
var formatTime = require("../../../utils/util.js");

Page({
  data: {
    product_id: "",
    component: [],
    add_time_format: "",
    name: "",
    url: "",
    type: "",
    thumb: "",
    cn_name: "",
    ischecked:false,
    searchValue: "",
    rawData: [],
    ingredients: [],
    show: false, //控制下拉列表的显示隐藏，false隐藏、true显示
    selectArray: [{
        "text": "妆品"
      },
      {
        "text": "成分"
      }
    ],
    index: 0, //选择的下拉列 表下标,
    componentflag: false,
    ingredientflag: true,
    checked: false,
    checkedtotal:0,
    checkedproduct_ids:[],
    checkedproduct_names:[],
    componentcheckedList:[],
    ingredienthidden:true,
    componenthidden:false
  },
  onLoad: function(options) {
    var that = this;
    console.log(options.searchValue)
     that.setData({
       searchValue:options.searchValue     
      })
    that.getComponent(options.searchValue);
    that.getIngredients();
  },
  //切换隐藏和显示 
  toggleBtn: function(event) {
    // var that = this;
    // that.getIngredient(event.currentTarget.dataset.id);
    // var toggleBtnVal = that.data.product_id;
    var itemId = event.currentTarget.dataset.id;
    var name = event.currentTarget.dataset.name;
    var thumb = event.currentTarget.dataset.thumb;
    //console.log(name)
    // if (toggleBtnVal == itemId) {
    //     that.setData({
    //     product_id: "0",
    //     uhide: false
    //   })
    //   } else {
    //     that.setData({
    //     product_id: itemId,
    //     uhide: true
    //     }) 
    //   }

    wx.navigateTo({
      url: './component_detail/component_detail?product_id=' + itemId + "&name=" + name + "&thumb=" + thumb,
    })
  },
  tabComponent: function(t) {
    this.setData({
      time: t.target.dataset.add_time_format,
      name: t.target.dataset.name,
      url: t.target.dataset.url,
      thumb: t.target.dataset.thumb,
      ischecked: t.target.dataset.ischecked
    })
  },
  getComponent: function(searchValue) {
    if (searchValue == '' || searchValue == 'null' || searchValue == 'undefined'){
      searchValue = '' 
    }
    var that = this;
    //console.log(that.data.searchValue)
    // var searchValue = that.data.searchValue;
    // console.log(searchValue)
    e.get("yoxwechatbusiness/ingredients/product", {
      isajax: 1,
      keyword: searchValue
    }, function(result) {
      if (result.status == 0) {
        wx.showModal({
          title: '',
          content: e.result.message,
          success(res) {
            wx.navigateBack({
              delta: 1
            })
            return;
          }
        })
      }
      that.setData({
        component: result.data.list,
        rawData: result.data.list
      })
      //console.log(result.data.list)
    })
  },
  getIngredients: function(searchValue) {
    var that = this;
    var searchValue = that.data.searchValue;
    e.get("yoxwechatbusiness/ingredients/ingredient_list", {
      isajax: 1,
      keyword: searchValue
    }, function(result) {
      that.setData({
        ingredients: result.data.list,
        cn_name: result.data.cn_name
      })
    })
  },
  find: function(e) {
    this.setData({
      searchValue: e.detail.value,
      component: this.data.rawData
    })
    //console.log(this.data.searchValue)
  },
  search: function(e) {
    // 模糊搜索功能
    var that = this;
    var searchValue = that.data.searchValue;
    //console.log(that.data.type)
    if (that.data.type == "") {
      that.data.type == "妆品"
      that.getComponent(searchValue);
    }
    if (that.data.type == "妆品") {
      that.getComponent(searchValue);
      that.setData({
        componenthidden : false,
        ingredienthidden : true
      })
    } else if (that.data.type == "成分") {
      that.getIngredients(searchValue);
      that.setData({
        componenthidden : true,
        ingredienthidden : false
      })
    }
    // var keyword = new RegExp(this.data.searchValue);
    // var arr = this.data.component;
    // //console.log(arr)
    // var component = [];
    // for (var i = 0; i < arr.length; i++) {
    //   if (arr[i]['name'].match(keyword) != null) {
    //     component.push(arr[i]);
    //   }
    // }
    // this.setData({
    //   component
    // });
  },
  // 点击下拉显示框
  selectTap() {
    this.setData({
      show: !this.data.show,
    });
  },
  // 点击下拉列表
  optionTap(e) {
    let Index = e.currentTarget.dataset.index; //获取点击的下拉列表的下标
    this.setData({
      index: Index,
      show: !this.data.show
    });
  },
  gettype: function(e) {
    // console.log(e.detail)
    var that=this;
    if (e.detail.text == "") {
      e.detail.text == "妆品"
    }
    if (e.detail.text == "妆品") {
      // that.getComponent(searchValue);
      that.setData({
        componenthidden : false,
        ingredienthidden : true
      })
    } else if (e.detail.text == "成分") {
      // that.getIngredients(searchValue);
      that.setData({
        componenthidden : true,
        ingredienthidden : false
      })
    }
    this.setData({
      type: e.detail.text
    })
    console.log(e.detail.text)
  },
  checkboxChange: function(e) {
    console.log(e)
    var params=e.detail.value;
    console.log(params)
    var string = params.toString();
    console.log(string)
    var array = string.split(',')
    console.log(array)
    var that = this;
    var id = e.currentTarget.dataset.id;
    that.data.checkedproduct_ids.push(JSON.parse(id));
    var name = e.currentTarget.dataset.name;
    that.data.checkedproduct_names.push(name);
    var component = that.data.component;
    var index = e.currentTarget.dataset.index;
    // var ischecked = e.currentTarget.dataset.ischecked?false:true;
    // if (component[index].ischecked=true){
    //   component[index].ischecked = false;
    // }else{
    //   component[index].ischecked = true;
    // }
    //console.log(index, ischecked)
    if (array[1]){
      that.data.checkedtotal+=1;
    }else{
      that.data.checkedtotal-=1;
    }
    console.log(that.data.checkedtotal)
    if (that.data.checkedtotal > 2){
      wx.showModal({
        content: '最多比较两个妆品',
      })
      return;
    }
    if (that.data.checkedtotal==2) {
    wx.showModal({
      title: '',
      content: '去进行对比',
      success(res) {
        if (res.confirm) {
          wx.navigateTo({
            url: './componentPK/componentPK?id0=' + that.data.checkedproduct_ids[0] + "&id1=" + that.data.checkedproduct_ids[1] + "&name0=" + that.data.checkedproduct_names[0] + "&name1=" + that.data.checkedproduct_names[1],
          })
        }
      }
    })
  }
},
  tosearch_history:function(){
    wx.navigateTo({
      url: './search_history/search_history',
    })
},
checkboxChange0:function(){

},
onReady: function() {},
onShow: function() {},
onHide: function() {},
onUnload: function() {},
onPullDownRefresh: function() {},
onReachBottom: function() {},
onShareAppMessage: function() {}
})