var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    navList:["基本资料"],
    currentnavTab:0,
    baseflag:true,
    defaultflag:false,
    userflag:false,
    user_input:"",
    name_input:"",
    nameflag:false,
    phone_input:"",
    phoneflag:false,
    weixinflag:false,
    weixin_input:"",
    weixin2flag: false,
    weixin2_input: "",
    qqflag:false,
    qq_input:"",
    cardflag:false,
    card_input:"",
    pwdflag:false,
    pwd_input:"",
    addressflag:false,
    address_input:"",
    imgs1: [],
    imgs2: [],
    myinfo:[],
    province: '',
    city: '',
    area: '',
    show: false
  },
  onLoad: function (options) {
    var that=this;
    that.getuserinfo();
  },
  navbarTap: function (e) {
    console.log(e)
    this.setData({
      currentnavTab: e.currentTarget.dataset.index
    })
    if (e.currentTarget.dataset.index==0){
      this.setData({
        defaultflag: false,
        baseflag:true
      })
    }
    if (e.currentTarget.dataset.index==1){
      this.setData({
        defaultflag: true,
        baseflag: false
      })
    }
  },
  tobank:function(){
    wx.navigateTo({
      url: '../../bank/index/index',
    })
  },
  loading: function (t) {
    void 0 !== t && "" != t || (t = "加载中"), wx.showToast({
      title: t,
      icon: "loading",
      duration: 5e6
    });
  },
  hideLoading: function () {
    wx.hideToast();
  },
  getuserinfo:function(){
    var that=this;
    e.get("yoxwechatbusiness/user/my_info", {
      isajax: 1,
    }, function (e) {
      that.setData({
        myinfo:e.data,
        user_input:e.data.nickname,
        name_input:e.data.realname,
        qq_input:e.data.qq,
        card_input: e.data.idcard,
        phone_input:e.data.mobile,
        weixin_input:e.data.weixin,
        area:e.data.residedist,
        city:e.data.residecity,
        province:e.data.resideprovince,
        imgs1:e.data.id_card_imgs.zhengmian,
        imgs2:e.data.id_card_imgs.fanmian,
        address_input:e.data.address
      })
    })
  },
  chooseImg1() {
    let that = this;
    let len = this.data.imgs1;
    wx.chooseImage({
      success: function (n) {
        that.loading("正在上传...");
        var o = e.getUrl("util/uploader/upload", {
          file: "file"
        }),
          i = n.tempFilePaths;
        console.log(i)
        wx.uploadFile({
          url: o,
          filePath: i[0],
          name: "file",
          success: function (n) {
            that.hideLoading();
            var o = JSON.parse(n.data);
            var imgs1 = that.data.imgs1;
            console.log(o.files[0].url)
            var url = o.files[0].url;
            // if (0 != o.error) e.alert("上传失败");
            // else if ("function" == typeof t) {
            imgs1.push(url)
            console.log(imgs1)
            that.setData({
              imgs1
            })
            console.log(that.data.imgs1)
            if (imgs1.length > 2) {
              return;
            }
            // }
          },
          fail: function (n) {
            console.log(n)
          }
        });
      }
    });
  },
  chooseImg2() {
    let that = this;
    let len = this.data.imgs2;
    wx.chooseImage({
      success: function (n) {
        that.loading("正在上传...");
        var o = e.getUrl("util/uploader/upload", {
          file: "file"
        }),
          i = n.tempFilePaths;
        console.log(i)
        wx.uploadFile({
          url: o,
          filePath: i[0],
          name: "file",
          success: function (n) {
            that.hideLoading();
            var o = JSON.parse(n.data);
            var imgs2 = that.data.imgs2;
            console.log(o.files[0].url)
            var url = o.files[0].url;
            // if (0 != o.error) e.alert("上传失败");
            // else if ("function" == typeof t) {
            imgs2.push(url)
            console.log(imgs2)
            that.setData({
              imgs2
            })
            console.log(that.data.imgs2)
            if (imgs2.length > 2) {
              return;
            }
            // }
          },
          fail: function (n) {
            console.log(n)
          }
        });
      }
    });
  },
  deleteImg1(e) {
    let _index = e.currentTarget.dataset.index;
    let imgs1 = this.data.imgs1;
    imgs1.splice(_index, 1);
    this.setData({
      imgs1:imgs1,
    })
  },
  deleteImg2(e) {
    let _index = e.currentTarget.dataset.index;
    let imgs2 = this.data.imgs2;
    imgs2.splice(_index, 1);
    this.setData({
      imgs2: imgs2,
    })
  },
  previewImg1(e) {
    let index = e.currentTarget.dataset.index;
    let imgs1 = this.data.imgs1;
    wx.previewImage({
      current: imgs[index],
      urls: imgs1,
    })
  },
  previewImg2(e) {
    let index = e.currentTarget.dataset.index;
    let imgs2 = this.data.imgs2;
    wx.previewImage({
      current: imgs[index],
      urls: imgs2,
    })
  },
  getmember:function(uid){
    var that=this;
    console.log(that.data);
    var name_input = that.data.name_input;
    var phone_input = that.data.phone_input;
    var qq_input = that.data.qq_input;
    var pwd_input = that.data.pwd_input;
    var weixin_input = that.data.weixin_input;
    var address_input = that.data.address_input;
    var card_input = that.data.card_input;
    var imgs1 = that.data.imgs1;
    var imgs2 = that.data.imgs2;
    var imgs = that.data.imgs;
    var province = that.data.province;
    console.log(province)
    var city = that.data.city;
    console.log(city)
    var area = that.data.area;
    console.log(area)
    e.get("yoxwechatbusiness/user/member_edit", {
      isajax: 1,
      uid:uid,
      mobile: phone_input,
      realname: name_input,
      qq: qq_input,
      weixin: weixin_input,
      idcard: card_input,
      "reside[province]": province,
      "reside[city]": city,
      "reside[district]":area,
      "birth[year]":"",
      "birth[month]":"",
      "birth[day]":"",
      address: address_input,
      password: pwd_input,
      // hand_held_id_card_imgs: imgs1,
      "id_card_imgs[zhengmian]":imgs1,
      "id_card_imgs[fanmian]":imgs2
    }, function (e) {
      wx.showToast({
        title: '保存成功',
        icon: 'success',
        duration: 2000
      });
      wx.navigateBack({
        delta: 2
      });
    })
  },
  saveTap:function(){
    this.getmember();
  },
  show:function(){
    this.setData({
      userflag: !this.data.userflag
    })
  },
  show2: function () {
    this.setData({
      nameflag: !this.data.nameflag
    })
  },
  show3: function () {
    this.setData({
      phoneflag: !this.data.phoneflag
    })
  },
  show4: function () {
    this.setData({
      weixinflag: !this.data.weixinflag
    })
  },
  show5: function () {
    this.setData({
      qqflag: !this.data.qqflag
    })
  },
  show6:function(){
    this.setData({
      cardflag: !this.data.cardflag
    })
  },
  show7: function () {
    this.setData({
      weixin2flag: !this.data.weixin2flag
    })
  },
  show8: function () {
    this.setData({
      addressflag: !this.data.addressflag
    })
  },
  show9: function () {
    this.setData({
      pwdflag: !this.data.pwdflag
    })
  },
  userinput:function(e){
    this.setData({
      user_input:e.detail.value
    })
  },
  nameinput: function (e) {
    this.setData({
      name_input: e.detail.value
    })
  },
  phoneinput:function(e){
    this.setData({
      phone_input:e.detail.value
    })
  },
  weixininput: function (e) {
    this.setData({
      weixin_input: e.detail.value
    })
  },
  weixin2input:function(e) {
    this.setData({
      weixin2_input: e.detail.value
    })
  },
  qqinput:function(e){
    this.setData({
      qq_input : e.detail.value
    })
  },
  cardinput:function(e){
    this.setData({
      card_input: e.detail.value
    })
  },
  pwdinput: function (e) {
    this.setData({
      pwd_input: e.detail.value
    })
  },
  addressinput:function(e){
    this.setData({
      address_input: e.detail.value
    })
  },
  userblur:function(e){
    this.setData({
      userflag:false
    })
  },
  nameblur:function(e){
    this.setData({
      nameflag: false
    })
  },
  phoneblur:function(e){
    if (e.detail.value.length < 11) {
      wx.showModal({
        content: '电话号码位数不对',
        success(res) {
          if (res.confirm) {
            return;
          } else if (res.cancel) {
          }
        }
      })
    }
    this.setData({
      phoneflag:false
    })
  },
  weixinblur:function(e){
    this.setData({
      weixinflag:false
    })
  },
  qqblur:function(e){
    this.setData({
      qqflag:false
    })
  },
  cardblur: function (e) {
    if (e.detail.value.length < 1) {
      wx.showModal({
        content: '身份位数不对',
        success(res) {
          if (res.confirm) {
            return;
          } else if (res.cancel) {
          }
        }
      })
    }
    this.setData({
      cardflag: false
    })
  },
  pwdblur: function (e) {
    this.setData({
      pwdflag: false
    })
  },
  addressblur:function(e){
    this.setData({
      addressflag: false
    })
  },
  sureSelectAreaListener: function (e) {
    var that = this;
    that.setData({
      show: false,
      province: e.detail.currentTarget.dataset.province,
      city: e.detail.currentTarget.dataset.city,
      area: e.detail.currentTarget.dataset.area
    })
  },
  chooseAddress: function () {
    console.log("xuanzedizhi")
    var that = this;
    that.setData({
      show: true
    })
  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})