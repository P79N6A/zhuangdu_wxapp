var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    real_name:"",
    mobile:"",
    qq:"",
    weixin:"",
    reside:"",
    address:"",
    imgs: []
  },
  onLoad: function (options) {

  },
  onReady: function () {

  },
  onShow: function () {

  },
  onHide: function () {

  },
  onUnload: function () {

  },
  realname_input:function(e){
    var real_name = e.detail.value;
    console.log(real_name)
    this.setData({
      real_name:real_name
    })
  },
  mobile_input:function(e){
    var mobile = e.detail.value;
    console.log(mobile)
    this.setData({
      mobile:mobile
    })
  },
  qq_input:function(e){
    var qq=e.detail.value;
    console.log(qq)
    this.setData({
      qq:qq
    })
  },
  weixin_input:function(e){
    var weixin=e.detail.value;
    console.log(weixin)
    this.setData({
      weixin:weixin
    })
  },
  reside_input:function(e){
    var reside=e.detail.value;
    console.log(reside)
      this.setData({
        reside:reside
      })
  },
  address_textarea:function(e){
    var address=e.detail.value;
    console.log(address)    
    this.setData({
      address:address
    })
  },
  getmembereditList: function () {
    var that = this;
    var mobile=that.data.mobile;
    var real_name=that.data.real_name;
    var qq=that.data.qq;
    var weixin=that.data.weixin;
    var reside = that.data.reside;
    var address=that.data.address;
    var imgs = that.data.imgs;
    e.get("yoxwechatbusiness/user/member_edit", { 
      isajax: 1, 
      mobile:mobile,
      realname:real_name,
      qq:qq,
      weixin:weixin,
      reside:reside,
      address:address,
      hand_held_id_card_imgs:imgs
      }, function (e) {
        wx.showToast({
          title: '保存成功',
          icon:'success',
          duration:2000
        })
    })
  },
  usersubmit:function(e){
    var that=this;
    that.getmembereditList();
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
  chooseImg() {
    let that = this;
    let len = this.data.imgs;
    if (len >= 9) {
      this.setData({
        lenMore: 1
      })
      return;
    }
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
            var imgs = that.data.imgs;
            console.log(o.files[0].url)
            var url = o.files[0].url;
            // if (0 != o.error) e.alert("上传失败");
            // else if ("function" == typeof t) {
            imgs.push(url)
            console.log(imgs)
            that.setData({
              imgs
            })
            console.log(that.data.imgs)
            if (imgs.length > 9) {
              wx.showModal({
                title: '提示',
                content: '最多只能有九张图片'
              })
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
  deleteImg(e) {
    let _index = e.currentTarget.dataset.index;
    let imgs = this.data.imgs;
    imgs.splice(_index, 1);
    this.setData({
      imgs
    })
  },
  previewImg(e) {
    let index = e.currentTarget.dataset.index;
    let imgs = this.data.imgs;
    wx.previewImage({
      current: imgs[index],
      urls: imgs,
    })
  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  }
})