var t = getApp(), e = t.requirejs("core"), a = (t.requirejs("icons"), t.requirejs("jquery"));
Page({
  data: {

  },
  onLoad: function (options) {
     wx.redirectTo({      //关闭当前页面，跳转到应用内的某个页面（这个跳转有个坑，就是跳转页面后页面会闪烁一下，完全影响了我自己的操作体验，太缺德了。）
          url:"/pages/custom/index?pageid=5"
     })
  }
})