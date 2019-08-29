var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    bankList:[],
    name:"",
    idcard:"",
    bank_name:"",
    subbranch:""
  },
  onLoad: function (options) {
    var that=this;
    that.getbankList();
  },
  tabbankList:function(t){
    this.setData({
      name:t.target.dataset.name,
      idcard: t.target.dataset.idcard,
      bank_name: t.target.dataset.bank_name,
      subbranch: t.target.dataset.subbranch
    })
  },
  getbankList:function(){
    var that=this;
    e.get("yoxwechatbusiness/bank_card.bank_card_list", {
      isajax: 1
    }, function (e) {
      that.setData({
        bankList:e.data.list
      })
    })
  }
})