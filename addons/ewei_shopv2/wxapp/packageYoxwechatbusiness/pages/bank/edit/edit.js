var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    input_name:"",
    input_backname:"",
    subbranch:"",
    card:""
  },
  onLoad: function (options) {
    var that = this;
  },
  getbankedit:function(){
    var that=this;
    var input_name = that.data.input_name;
    var input_backname = that.data.input_backname;
    var subbranch = that.data.subbranch;
    var card = that.data.card;
    e.get("yoxwechatbusiness/bank_card.bank_card_edit", {
      isajax: 1,
      name: input_name,
      idcard: card,
      bank_name: input_backname,
      subbranch: subbranch
    }, function (e) {
      that.setData({
      })
    })
  }, 
  getinput_name:function(e){
    var that=this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      input_name: e.detail.value
    })
  },
  getinput_backname:function(e){
    var that = this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      input_backname: e.detail.value
    })
  },
  getcard:function(e){
    var that = this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      card: e.detail.value
    })
  },
  getsubbranch:function(e){
    var that = this;
    console.log(e)
    console.log(e.detail.value)
    that.setData({
      subbranch: e.detail.value
    })
  },
  formSubmit(e){
    this.getbankedit();
  }
})