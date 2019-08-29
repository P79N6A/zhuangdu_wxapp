var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    buyeruid:24,
    goodslist_json:"{goods:[['goodsid':1,'total':1]['goodsid':2,'total':1]",
    status:1
  },
  onLoad: function (options) {
    console.log(options.status)
  },
  getorder_manual: function (buyeruid, goodslist_json, remark){
    var that=this;

    e.get("yoxwechatbusiness/order/create_order_manual", {
      isajax: 1,
      buyeruid: that.data.buyeruid,
      goodslist_json: that.data.goodslist_json,
      status: that.data.status,
      remark: remark
    }, function (e) {
      if (e.status == 0) {
        wx.showModal({
          title: '',
          content: e.result.message,
          success(res) {
            return;
          }
        })
      }
    })
  },
  getset_order_shipped:function(){
    var that=this;
    e.get("yoxwechatbusiness/order/set_order_shipped", {
      isajax: 1,
      id: id,
      order_goods_id: order_goods_id,
      express: express,
      expresscom: expresscom,
      expresssn: expresssn,
      big_codes: big_codes,
      middle_codes: middle_codes
    }, function (e) {
     
    })
  },
  addorder:function(){
    this.getorder_manual();
  },
  set_shipped:function(){
    this.getset_order_shipped();
  }
})