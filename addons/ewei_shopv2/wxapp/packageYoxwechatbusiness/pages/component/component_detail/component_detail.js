var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    thumb:"",
    name:"",
    commentLists:[],
    comment:"",
    add_time_format: "",
    avatar: "",
    product_name: "",
    product_thumb: "",
    focus: false,
    flag: false,
    content: "",
    btnflag: false,
    product_id:""
  },
  onLoad: function (options) {
    var that=this;
    console.log(options.product_id)
    //console.log(options.name)
    //console.log(options.thumb)
    that.getcommentList(options.product_id)
    that.setData({
      thumb:options.thumb,
      name:options.name,
      product_id: options.product_id
    })
  },
  detailchange: function (product_id,name){
    var that=this;
    var product_id = that.data.product_id;
    console.log(product_id)
    var name=that.data.name
    console.log(name)
    wx.navigateTo({
      url: '../detail/detail?product_id=' + product_id+"&name="+name,
    })
  },
  tabcommentList:function(t){
    this.setData({
      comment:t.target.dataset.comment,
      add_time_format: t.target.dataset.add_time_format,
      avatar: t.target.dataset.avatar,
      product_name: t.target.dataset.product_name,
      product_thumb: t.target.dataset.product_thumb
    })
  },
  getcommentList: function (product_id){
    var that=this;
    // var product_id = that.data.product_id
    e.get("yoxfriendscircle/comment/comment_list", {
      isajax: 1,
      target_id: product_id,
      type: "PRODUCT"
    }, function (e) {
      that.setData({
        commentLists:e.data.list
      })
    })
  },
  formsubmit: function (event) {
    var that = this;
    //console.log(event)
    var content = that.data.content
    //console.log(content)
    var commitUser_id = event.detail.target.dataset.id;
    var commentLists = that.data.commentLists;
    e.get("yoxfriendscircle/comment/edit", {
      isajax: 1,
      type: "PRODUCT",
      target_id: commitUser_id,
      comment: content
    }, function (e) {
      //console.log(e)
      that.getcommentList(commitUser_id)
      that.setData({
        commentLists: that.data.commentLists,
        flag:!that.data.flag
      })
    })
  },
  textarea_bingblur: function (e) {
    // console.log(e.detail.value)
    var that = this;
    that.setData({
      flag: !that.data.flag
    })
  },
  bindshow:function(e){
    var that=this;
    that.setData({
      focus:true,
      flag: !that.data.flag,
    })
  },
  comment_delete: function (event) {
    var that = this;
    console.log(event)
    var commitUser_id = event.currentTarget.dataset.id;
    console.log(commitUser_id)
    wx.showModal({
      content: '您确定要删除当前评论吗, 删除后将无法恢复!',
      success: function (res) {
        if (res.confirm) {
          e.get("yoxfriendscircle/comment/delete", {
            isajax: 1,
            id: commitUser_id
          }, function (e) {
            console.log(e)
            that.getcommentList(that.data.product_id)
            that.setData({
              commentLists: that.data.commentLists
            })
          })
        }
      }
    })
  },
  commentSubmit: function (e) {
    //console.log(e)
    var that = this;
    console.log(e.detail.value)
    if (e.detail.value == "") {
      //console.log('a')
      that.setData({
        btnflag: false
      })
    } else {
      that.setData({
        btnflag: true,
        content: e.detail.value
      })
    }
    //console.log(that.data.content)
  }
})