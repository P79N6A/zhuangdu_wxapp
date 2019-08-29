var t = getApp(),
  e = t.requirejs("core"),
  a = (t.requirejs("icons"), t.requirejs("jquery"), t.requirejs("util"));
//创建audio控件
const myaudio = wx.createInnerAudioContext();

Page({
  data: {
    userInfo: {},
    classes: [],
    message: "", //消息
    voice: [], //语音
    show_time_format: "",
    course_teacher: [],
    isplay: false,
    length: "",
    loveflag: false,
    commit_list: [],
    talk_value: "",
    commit_id: "",
    timer:'',
    commitflag:false,
    commit_length:'',
    id:"",
    chapter_id:"",
    statusflag:false,

  },
  onLoad: function(options) {
    var that = this;
    console.log(options.chapter_id)
    t.getUserInfo({
      success(res) {
        console.log(res.userInfo)
      }
    })
    // console.log(options.id)
    // console.log(options.name)
    wx.setNavigationBarTitle({
      title: options.name + " 直播室",
    })
    that.setData({
      timer : setInterval(function () {
        that.getClass(options.id, options.chapter_id);
      }, 5000),
      id:options.id,
      chapter_id: options.chapter_id
    })
  },
  tabClass: function(t) {
    this.setData({
      message: t.target.dataset.message,
      show_time_format: t.target.dataset.show_time_format
    })
  },
  following:function(e){
    var that=this;
    if (that.data.statusflag==true){
      that.unfollow();
    }else{
      that.getfollow();
    }
  },
  getfollow:function(){
    var that=this;
    var id = that.data.id;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxfriendscircle/attitude/edit", {
      isajax: 1,
      type: "MESSAGE",
      attitude: "FOLLOW",
      target_id: id
    }, function (e) {
       wx.showToast({
         title: e.message
       })
       that.setData({
         statusflag:true
       })
    })
  },
  unfollow:function(){
    var that = this;
    var id = that.data.id;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxfriendscircle/attitude/delete", {
      isajax: 1,
      type: "MESSAGE",
      attitude: "FOLLOW",
      target_id: id
    }, function (e) {
        wx.showToast({
          title: e.message
        })
        that.setData({
          statusflag:false
        })
    })
  },
  getClass: function (id, chapter_id) {
    var that = this;
    // wx.showToast({
    //   title: "请稍后",
    //   icon: 'loading',
    //   duration: (5000 <= 0) ? 5000 : 5000
    // });
    e.get("yoxwechatbusiness/course/course_message_list", {
      isajax: 1,
      course_id: id,
      chapter_id: chapter_id
    }, function(e) {
      that.setData({
        classes: e.data.list,
        length: e.data.list.length,
        course_teacher: e.data.course_teacher
      })
      wx.hideToast();
    })
  },
  //音频播放  
  audioPlay: function(e) {
    var that = this,
      id = e.currentTarget.dataset.id,
      voice = e.currentTarget.dataset.voice,
      voiceList = that.data.voice,
      classes = that.data.classes;
    console.log(voice)
    myaudio.src = voice;
    wx.showToast({
      title: '请稍候',
      icon: 'none',
      duration: 1000
    })
    setTimeout(function() {
      myaudio.play();
      //console.log(1)
    }, 10000)
    //切换显示状态
    that.setData({
      isplay: true,
      id:id
    })
    //开始监听
    myaudio.onPlay(() => {
      that.setData({
        voice: voice
      })
    })
    //结束监听
    myaudio.onEnded(() => {
      that.setData({
        voice: voice,
        isplay: true
      })
    })
  },
  // 音频停止
  audioStop: function(e) {
    var that = this,
      classes = that.data.classes,
      voice = e.currentTarget.dataset.voice;
    that.setData({
      isplay: false
    })
    myaudio.stop();
    //停止监听
    myaudio.onStop(() => {
      that.data.isplay = false;
      that.setData({
        voice: voice,
        isplay: false
      })
    })
    //结束监听
    myaudio.onEnded(() => {
      that.data.isplay = false;
      that.setData({
        voice: voice,
        isplay: false
      })
    })
  },
  getattitude: function(id) {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxfriendscircle/attitude/edit", {
      isajax: 1,
      type: "MESSAGE",
      attitude: "LIKE",
      target_id: id
    }, function(e) {
      wx.showToast({
        title: '点赞成功'
      })
    })
  },
  toadd:function(){
    var that=this;
    var id = that.data.id;
    wx.navigateTo({
      url: './add/add?id='+id,
    })
  },
  toupload:function(){
    var id = this.data.id;
    wx.navigateTo({
      url: './uploadfile/uploadfile?id='+id,
    })
  },
  topersonal:function(){
    wx.navigateTo({
      url: './personal/personal',
    })
  },
  getunlike: function(id) {
    var that = this;
    wx.showToast({
      title: "请稍后",
      icon: 'loading',
      duration: (5000 <= 0) ? 5000 : 5000
    });
    e.get("yoxfriendscircle/attitude/delete", {
      isajax: 1,
      type: "MESSAGE",
      attitude: "LIKE",
      target_id: id
    }, function(e) {
      wx.showToast({
        title: '取消点赞'
      })
    })
  },
  getcommit: function(id) {
    var that = this;
    e.get("yoxfriendscircle/comment/comment_list", {
      isajax: 1,
      type: "MESSAGE",
      target_id: id
    }, function(e) {
      that.setData({
        commit_list: e.data.list,
        commit_length: e.data.list.length
      })
    })
  },
  totransmit: function (event,videos, name, thumb, url){
    var that=this;
    var message = event.currentTarget.dataset.message;
    var image = event.currentTarget.dataset.image;
    e.get("yoxfriendscircle/edit", {
      isajax: 1,
      description: message,
      "thumbs[]": image,
      videos: videos,
      is_featured: 0,
      name:name,
      thumb: thumb,
      url: url
    }, function (e) {
      if(e.message=="ok"){
        wx.showToast({
          title: '转发成功',
          icon: 'success',
          duration: 5000
        })
      }
    })
  },
  lovetap: function(e) {
    var that = this;
    console.log(e)
    var id = e.currentTarget.dataset.id;
    if (e.currentTarget.dataset.islike == 1) {
      that.getunlike(id);
    } else if (e.currentTarget.dataset.islike == undefined || e.currentTarget.dataset.islike == 0) {
      that.getattitude(id);
    }
    that.getClass();
  },
  committap: function(e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    that.setData({
      commit_id: id,
      commitflag: !that.data.commitflag
    })
    that.getcommit(id);
  },
  close:function(){
    this.setData({
      commitflag:false
    })
  },
  talk_value: function(e) {
    this.setData({
      talk_value: e.detail.value
    })
  },
  send: function(event) {
    var that = this;
    console.log(event)
    var content = that.data.talk_value;
    console.log(content)
    var commitUser_id = that.data.commit_id;
    console.log(commitUser_id)
    var classes = that.data.classes;
    console.log(classes)
    e.get("yoxfriendscircle/comment/edit", {
      isajax: 1,
      type: "MESSAGE",
      target_id: commitUser_id,
      comment: content
    }, function(e) {
      //console.log(e)
      that.getcommit();
      that.setData({
        classes: that.data.classes
      })
    })
  },
  onUnload: function() {
    clearInterval(this.data.timer);
  }
})