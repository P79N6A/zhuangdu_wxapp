var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    tip1List: [{
        imgUrl: "./images/01.png",
        title: "实名认证才能成为主播"
      }, {
        imgUrl: "./images/02.png",
        title: "认证用户权益更多"
      },
      {
        imgUrl: "./images/03.png",
        title: "认证不涉及金钱账户"
      }, {
        imgUrl: "./images/04.png",
        title: "认证信息严格保密"
      }
    ],
    certifyList: ["绑定手机号" ,"绑定微信号","上传手持身份证正反面"]
  }
})