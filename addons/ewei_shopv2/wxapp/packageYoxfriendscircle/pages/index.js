 var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {
    userInfo: {},
    friendscricle: {},
    banner_info: {},
    description: "",
    thumb: "",
    thumbs: [],
    videos: "",
    name: "",
    url: "",
    add_time_format: "",
    show: !0,
    commit_flag: false, // 控制点赞评论按钮(取消/点赞)
    cz_right: 0, // 点赞评论定位right
    cz_top: 80, // 点赞评论定位top
    commitUser_id: null, //点赞人的id
    animationData: {},
    is_featured: 0,
    like: {},
    commentList: {},
    stopBtn: true,
    target_id: 0,
    comment: "",
    page: 1, // 页码值 分页加载
    likeData: [], //存放点赞数据
    focus: false,
    flag: false,
    content: "",
    btnflag: false,
    is_like: 0,
    cz_flag: false,
    commit_id: null, //评论人
    comment_flag: false,
    like_flag: false,
    noMoretip: false, //false为有更多数据，true为数据加载完毕
    scrollTop: 0,
    scrollHeight: 0
  },
  onLoad: function(options) {
    var that = this;
    wx.getSystemInfo({
      success: function(res) {
        that.setData({
          scrollHeight: res.windowHeight
        });
      }
    });
    t.getUserInfo(function(userInfo) {
      //console.log(userInfo)
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    });
    t.url(e),
      this.getFriendscricle();
    commit_flag: (options.commit_flag == "true" ? true : false)
  },
  tabFriendscricle: function(t) {
    this.setData({
      description: t.target.dataset.description,
      thumb: t.target.dataset.thumb,
      thumbs: t.target.dataset.thumbs,
      url: t.target.dataset.url,
      add_time_format: t.target.dataset.add_time_format,
      comment: t.target.dataset.comment
    })
  },
  onReady: function() {},
  onShow: function() {},
  onShareAppMessage: function() {
    return e.onShareAppMessage();
  },
  getFriendscricle: function() {
    wx.showLoading({
      title: '加载中',
    })
    var that = this;
    var page = that.data.page;
    var friendscricle = that.data.friendscricle;
    var commentList = that.data.commentList;
    var allArr = [];
    var initArr = friendscricle ? friendscricle : {}; //获取已加载的商品
    var newArr = e.data.list; //获取新加载的商品
    // var lastPageLength = newArr.length;  			//新获取的商品数量
    e.get("yoxfriendscircle/friendscircle_list", {
      isajax: 1,
      page: page
    }, function(e) {
      wx.hideLoading();
      if (page = 1) { //如果是第一页
        allArr = e.data.list;
      } else {
        allArr = initArr.concat(newArr) //如果不是第一页，连接已加载与新加载商品
      }
      // if (lastPageLength < 10) {      //如果新加载的一页课程数量小于10，则没有下一页

      // }
      //console.log(e)
      that.setData({
        friendscricle: e.data.list,
        commentList: e.data.list.comment,
        banner_info: e.data.banner_info
      })
    })
  },
  showEditPage: function() {
    wx.navigateTo({
      url: './edit/edit'
    })
  },
  previewImg(e) {
    let index = e.currentTarget.dataset.index;
    let friendscricle = this.data.friendscricle;
    //console.log(friendscricle)
    for(var i=0;i<friendscricle.length;i++){
      let thumbs = "https://zdu.igdlrj.com/attachment/"+friendscricle[i].thumbs;
      var arr=[];
      arr.push(thumbs)
      console.log(arr)
      // for (var j=0;j<thumbs.length;j++){
        wx.previewImage({
          current: arr[index],
          urls: arr,
        })
      // }
    }
  },
  iconsShow: function(e) {
    var that = this;
    var index = 0;
    var commitUser_id = e.currentTarget.dataset.id;
    // console.log(commitUser_id)
    var friendscricle = that.data.friendscricle;
    var offsetTop = Math.floor(e.target.offsetTop);
    for (var item of friendscricle) {
      if (item.id == commitUser_id) {
        if (friendscricle[index].commit_flag == "" || friendscricle[index].commit_flag == undefined) {
          friendscricle[index].commit_flag = true;
        }
        index++;
      }
      // 赋值计算好的TOP值显示按钮
      that.setData({
        commitUser_id: commitUser_id,
        cz_top: offsetTop,
        commit_flag: true
      })
    }

    // 动画
    var animation = wx.createAnimation({
      duration: 500,
      timingFunction: 'linear',
      delay: 0,
    })

    // 0.3秒后滑动
    setTimeout(function() {
      animation.right(40).opacity(1).step()
      that.setData({
        animationData: animation.export()
      })
    }, 300)

    setTimeout(function() {
      that.setData({
        stopBtn: false
      })
    }, 500)
  },
  iconsHide: function(e) {
    var that = this;
    var commitUser_id = e.currentTarget.dataset.id;
    var friendscricle = that.data.friendscricle;
    var offsetTop = Math.floor(e.target.offsetTop);
    // 赋值计算好的TOP值显示按钮
    that.setData({
      commitUser_id: commitUser_id,
      cz_top: offsetTop,
      commit_flag: true
    })

    // 动画
    var animation = wx.createAnimation({
      duration: 500,
      timingFunction: 'linear',
      delay: 0,
    })

    setTimeout(function() {
      animation.right(40).opacity(0).step()
      that.setData({
        animationData: animation.export()
      })
    }, 300)

    setTimeout(function() {
      that.setData({
        stopBtn: true
      })
    }, 500)
  },
  bindLike: function(event) {

    // console.log(event)
    var that = this;
    var arr = [];
    // if (event.currentTarget.dataset.attitudes.list==null){
    //   event.currentTarget.dataset.attitudes.list=[];
    // }
    // for (var i = 0; i < event.currentTarget.dataset.attitudes.list.length; i++) {
    //   var nickname = event.currentTarget.dataset.attitudes.list[i].nickname;
    //   arr.push(event.currentTarget.dataset.attitudes.list[i].nickname)
    // }
    //console.log(arr)
    var commitUser_id = event.currentTarget.dataset.id;
    var friendscricle = that.data.friendscricle;
    if (event.currentTarget.dataset.attitudes.is_like == 1) {
      e.get("yoxfriendscircle/attitude/delete", {
        isajax: 1,
        type: "ARTICLE",
        target_id: commitUser_id
      }, function(e) {
        wx.showToast({
          title: '取消成功',
        })
        that.getFriendscricle()
        that.setData({
          friendscricle: that.data.friendscricle
        })
      })
    } else {
      e.get("yoxfriendscircle/attitude/edit", {
        isajax: 1,
        type: "ARTICLE",
        target_id: commitUser_id,
        attitude: "LIKE"
      }, function(e) {
        // for (var i = 0; i < arr.length; i++) {
        // console.log(e.data.nickname)
        // console.log(arr[i])
        // if (e.data.nickname == arr[i]) {
        // wx.showToast({
        // title: '已点赞过啦',
        // })
        // return;
        // } else {
        //  for(var i=0;i<friendscricle.length;i++){
        //    console.log(friendscricle[i])
        //    if (friendscricle[i].attitude.list==null){
        //      console.log("a")
        //      friendscricle[i].attitude.list=[]
        //    }
        //    for (var j = 0; j < friendscricle[i].attitude.list.length; i++) {
        //     //  console.log(friendscricle[i])
        //      //console.log(friendscricle[i].attitude)
        //      friendscricle[i].attitude.list[j]["nickname"]+","+e.data.nickname
        //     //  return;
        //    }
        //  }
        // }
        // }
        that.getFriendscricle()
        that.setData({
          friendscricle: that.data.friendscricle,
          like_flag: ! that.data.like_flag
        })
      })
    }
  },
  click_comment: function(e) {
    //console.log(event.currentTarget.dataset.id)
    var that = this;
    var commit_id = e.currentTarget.dataset.id;
    that.setData({
      focus: true,
      commit_id: commit_id,
      flag: !that.data.flag,
      comment_flag: !that.data.comment_flag
    })
  },
  formsubmit: function(event) {
    var that = this;
    console.log(event)
    var content = that.data.content;
    console.log(content)
    var commitUser_id = that.data.commit_id;
    console.log(commitUser_id)
    var friendscricle = that.data.friendscricle;
    console.log(friendscricle)
    e.get("yoxfriendscircle/comment/edit", {
      isajax: 1,
      type: "ARTICLE",
      target_id: commitUser_id,
      comment: content
    }, function(e) {
      //console.log(e)
      that.getFriendscricle();
      that.setData({
        friendscricle: that.data.friendscricle,
        flag:false
      })
    })
  },
  comment_delete: function(event) {
    var that = this;
    console.log(event)
    var commitUser_id = event.currentTarget.dataset.id;
    // console.log(commitUser_id)
    wx.showModal({
      content: '您确定要删除当前评论吗, 删除后将无法恢复!',
      success: function(res) {
        if (res.confirm) {
          e.get("yoxfriendscircle/comment/delete", {
            isajax: 1,
            id: commitUser_id
          }, function(e) {
            console.log(e)
            that.getFriendscricle()
            that.setData({
              friendscricle: that.data.friendscricle
            })
          })
        }
      }
    })
  },
  textarea_bingblur: function(e) {
    // console.log(e.detail.value)
    var that = this;
    // that.setData({
    //   flag: !that.data.flag
    // })
  },
  commentSubmit: function(e) {
    //console.log(e)
    var that = this;
    console.log(e.detail.value)
    if (e.detail.value == "") {
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
  },
  bindcopy: function(e) {
    var description = e.currentTarget.dataset.description;
    wx.setClipboardData({
      data: description,
      success: function(res) {
        wx.getClipboardData({
          success: function(res) {
            console.log(res.data)
          }
        })
      }
    })
  },
  bindsave: function(e) {
    var thumbs = e.currentTarget.dataset.thumbs;
    console.log(thumbs)
    for (var i = 0; i < thumbs.length; i++) {
      var thumb = "https://zdu.igdlrj.com/attachment/" + thumbs[i];
      console.log(thumb)
      wx.showLoading({
        title: '保存中...',
        mask: true
      });
      wx.downloadFile({
        url: thumb,
        success: function(res) {
          if (res.statusCode == 200) {
            let img = res.tempFilePath;
            wx.saveImageToPhotosAlbum({
              filePath: img,
              success: function(res) {
                wx.showToast({
                  title: '保存成功',
                  icon: 'success',
                  duration: 2000
                });
              },
              fail(res) {
                wx.showToast({
                  title: '保存失败',
                  icon: 'success',
                  duration: 2000
                })
              }
            })
          }
        }
      })
    }
  },
  // onPullDownRefresh: function() {
  //   //console.log(1)
  //   var page = this.data.page;
  //   console.log(page)
  //   if (!this.data.noMoretip) {
  //     page++
  //     this.setData({
  //       page: page
  //     })
  //   }
  //   this.getFriendscricle();
  //   setTimeout(function() {
  //     wx.stopPullDownRefresh(); //停止下拉刷新
  //   }, 1000);
  // },
  // onReachBottom: function(event) {
  //   //console.log(1)
  //   this.setData({
  //     page: 1,
  //     noMoretip: false,
  //     friendscricle: {}
  //   })
  //   this.getFriendscricle();
  // },
  scroll: function(event) {
    //该方法绑定了页面滚动时的事件，我这里记录了当前的position.y的值,为了请求数据之后把页面定位到这里来。
    this.setData({
      scrollTop: event.detail.scrollTop
    });
  }
})