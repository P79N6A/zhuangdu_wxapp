var a = getApp(),
  e = a.requirejs("core");
a.requirejs("jquery"), a.requirejs("foxui");
Page({
  data: {
    id: "",
    page_valueList: [],
    page_value: "",
    page: 'mainPage',
    detailList: [],
    url: "",
    x: "",
    y: "",
    x1: "",
    y1: "",
    text: "",
    screenHeight: 0,
    screenWidth: 0,
    tempImageSrc: "",
    imageheight: 0,
    imagewidth: 0,
    imgViewHeight: 0,
    imgWidth: 0,
    imgHeight: 0,
    imgTop: 0,
    imgLeft: 0,
    isCroper: false,
    allColor: ['#000000', '#7f7f7f', '#880015', '#ed1c24', '#ff7f27', '#fff200', '#22b14c', '#00a2e8', '#ffaec9', '#a349a4', '#ffffff', '#c3c3c3'],
    //添加文字
    isChooseFontSize: false,
    isChooseFontColor: false,
    isChooseFontPattern: false,
    allText: {},
    startText: {},
    inputFocus: false,
    isstart: false,
    isurl:false,
    newTextL: "",
    newTextT: "",
    fontSize:"",
    fontColor:"",
    fontStyle:"",
    fontWeight:"",
    isstyle:false,
    textList:[],
    auto_height:"",
    textListindex:0
  },
  onLoad: function(options) {
    var that = this;
    wx.getSystemInfo({
      success: function(res) {
        that.setData({
          screenHeight: res.windowHeight - 160,
          screenWidth: res.windowWidth,
        });
      }
    });
    var url = JSON.parse(options.page_value)
    var textgroupList=url.textgroupList;
    that.device = a.globalData.myDevice
    that.deviceRatio = that.device.windowWidth / 750
    that.imgViewHeight = that.device.windowHeight - 160 * that.deviceRatio
    that.setData({
      id: options.id,
      url: url.background.url,
      page_valueList: url,
      imgViewHeight: that.imgViewHeight
    })
    //console.log(that.data.imgViewHeight)
    that.getdiyedit(options.id, options.page_value)
    //console.log(that.data.page_valueList)
    var textList = that.data.page_valueList.textgroupList;
    //console.log(textList)
    that.setData({
      textList:textList
    })
    console.log(that.data.textList)
  },
  toMainPage() {
    this.setData({
      page: 'mainPage'
    })
  },
  //添加文字
  toTextPage() {
    this.setData({
      page: 'textPage'
    })
  },
  focusInput() {
    this.setData({
      inputFocus: !this.data.inputFocus,
    })
  },
  startinput(e) {
    if (e.detail.value == "") {
      e.detail.value = "点击输入文字"
    }
    //console.log(e.detail.value.length)
    var text_length = e.detail.value.length
    var current_width = parseInt(text_length) * 100;
    let textLists=this.data.textList;
    const index=this.data.textListindex;
    textLists.forEach(function(val,i){
      if (index == i) {
        //console.log(i)
        textLists[i].text = e.detail.value
      }
    })
    //console.log(current_width)
    this.setData({
      auto_height: current_width,
      textList:textLists
    })
  },
  inputText(e) {
    var allText = this.data.allText
    allText.text = e.detail.value
    if (allText.text.length == 0) {
      allText.text = "点击输入文字"
    }
    this.setData({
      allText: allText
    })
  },
  textMoveStart(e) {
    //console.log(e)
    var index=e.currentTarget.dataset.index;
    console.log(index)
    this.setData({
      textListindex:index
    })
    this.textX = e.touches[0].clientX
    this.textY = e.touches[0].clientY
  },
  textMove(e) {
    //console.log(e)
    var allText = this.data.allText
    var dragLengthX = (e.touches[0].clientX - this.textX)
    //console.log(dragLengthX)
    var dragLengthY = (e.touches[0].clientY - this.textY)
    //console.log(dragLengthY)
    var minTextL = 0
    var minTextT = 0
    //console.log(this.deviceRatio)
    var maxTextL = (750 - 100) * this.deviceRatio
    //console.log(maxTextL)
    //console.log(this.imgViewHeight)
    var maxTextT = this.imgViewHeight - 40 * this.deviceRatio
    //console.log(maxTextT)
    var newTextL = parseFloat(allText.x1) + dragLengthX
    var newTextT = parseFloat(allText.y1) + dragLengthY
    //console.log(newTextT)
    if (newTextL < minTextL) newTextL = minTextL
    if (newTextL > maxTextL) newTextL = maxTextL
    if (newTextT < minTextT) newTextT = minTextT
    if (newTextT > maxTextT) newTextT = maxTextT

    //console.log(newTextL)
    //console.log(newTextT)
    allText.x1 = newTextL+"px"
    allText.y1 = newTextT+"px"
    this.setData({
      allText: allText,
      isChooseFontSize: false,
      isChooseFontColor: false,
      isChooseFontPattern: false
    })
    this.textX = e.touches[0].clientX
    this.textY = e.touches[0].clientY
  },
  chooseaddText() {
    var fontsize = (this.fontsize ? this.fontsize : 14)+"px";
    var x1 = ((750 - 200) * this.deviceRatio / 2)+"px";
    var y1 = (this.imgViewHeight / 2 - (375.00000000000006 / 2) + 20)+"px"
    var textList = this.data.textList
    console.log(textList.length)
    var allText ={
      index: textList.length,
      text: "点击输入文字",
      color: this.color ? this.color : 'rgba(255,255,255)',
      fontsize: fontsize,
      fontstyle: 'normal',
      fontweight: 'normal',
      x1: x1,
      y1: y1,
      isTextActive: true,
    }
    // console.log(fontsize)
    // console.log(x1)
    // console.log(y1)
    var newTextList=this.data.textList
    console.log(newTextList)
    newTextList.push(allText)
    console.log(allText.index)
    this.setData({
      allText: allText,
      textListindex:allText.index,
      textList: newTextList,
      isChooseFontSize: false,
      isChooseFontColor: false,
      isChooseFontPattern: false
    })
  },
  cancelAddText() {
    var allText = this.data.allText
    allText.isTextActive = false
    this.setData({
      allText: allText,
      inputFocus: false,
      isChooseFontSize: false,
      isChooseFontColor: false,
      isChooseFontPattern: false
    })
  },
  competeAddText() {
    var that = this
    var page_valueList = that.data.page_valueList;
    var url = page_valueList.background.url;
    console.log(url)

    wx.getImageInfo({
      src:url,
      success(res){
        //console.log(res.width)
        //console.log(res.height)
        var img_width=res.width;
        var img_height=res.height;
        that.setData({
          imagewidth:img_width,
          imageheight:img_height
        })
      }
    })
    var allText = that.data.allText
    if (allText.text == "点击输入文字" || allText.text == "") {
      this.cancelAddText()
    } else {
      wx.showLoading({
        title: '保存文字',
        mask: true,
      })
      allText.isTextActive = false
      // if (self.initRatio < 1) { //解决问题：小图或者过度裁剪后的图添加文字时文字虚化
      //   initRatio = 1
      // }
      var imagewidth = that.data.screenWidth
      var imageheight = that.data.screenHeight+200
      //console.log(imageheight)
      var ctx = wx.createCanvasContext('tempCanvas')
      wx.createSelectorQuery().select('#the-id').boundingClientRect(function(rect){
        var posterWidth = rect.width;
        var posterHeight = rect.height;
        console.log(posterWidth)
        console.log(posterHeight)
      })
      ctx.drawImage(url, 0, 0, imagewidth, imageheight)
      var data_textList = that.data.textList;
      //console.log(data_textList)
      that.setData({
        allText: allText,
        inputFocus: false,
        isChooseFontSize: false,
        isChooseFontColor: false,
        isChooseFontPattern: false,
        imagewidth: imagewidth,
        imageheight: imageheight
      })
      for (var i = 0; i < data_textList.length; i++) {
        var x1 = parseInt(data_textList[i].x1);
        var y1 = parseInt(data_textList[i].y1)+20;
        //console.log(data_textList[i].fontsize)
        var fontsize = parseFloat(data_textList[i].fontsize)
        //console.log(fontsize)
        ctx.setFontSize(fontsize);
        console.log(data_textList[i].color)
        //console.log(data_textList[i].color)
        if (data_textList[i].color){
          ctx.setFillStyle(data_textList[i].color);
        }else{
          ctx.setFillStyle("white");
        }
        //console.log(data_textList[i].fontstyle)
        //console.log(data_textList[i].fontweight)
        ctx.font = data_textList[i].fontstyle + ' ' + data_textList[i].fontweight + ' ' + fontsize + 'px sans-serif'
        ctx.fillText(data_textList[i].text, x1, y1);
      }
      ctx.draw()
      that.setData({
        isurl: true
      })
      //console.log(that.data)
      //console.log(that.data.tempImageSrc)
        // textList.forEach(function(val){
        //   var x1 = parseInt(val.x1);
        //   var y1 = parseInt(val.y1);
        //   console.log("1"+x1);
        //   console.log("2"+y1);
        //   console.log("3"+val.text);
        //   //console.log(val.fontsize)
        //   // var fontsize = parseFloat(val.fontsize)
        //   //console.log(fontsize)
        //   // ctx.setFontSize(fontsize);
        //   //console.log(val.color)
        //   // ctx.setFillStyle(val.color);
        //   //console.log(val.fontstyle)
        //   //console.log(val.fontweight)
        //   // ctx.font = val.fontstyle + ' ' + val.fontweight + ' ' + fontsize + 'px sans-serif'
        //   ctx.fillText(val.text, val.x1, val.y1);
        // })
        // console.log(that.data.tempImageSrc)
        // var ctx = wx.createCanvasContext('tempCanvas')
        // ctx.drawImage(that.data.tempImageSrc, 0, 0, tempCanvasWidth, tempCanvasHeight)
        // ctx.setFillStyle(allText.color)
        // var canvasFontSize = Math.ceil(allText.fontsize * 1.5)
        // //console.log(canvasFontSize)
        // console.log(canvasFontSize)
        // console.log(allText.fontstyle)
        // console.log(allText.fontweight)
        // ctx.font = allText.fontstyle + ' ' + allText.fontweight + ' ' + canvasFontSize + 'px sans-serif'
        // ctx.setTextAlign('left')
        // ctx.setTextBaseline('top')
        // ctx.fillText(allText.text, (allText.textL) * 1.5, (allText.textT - 79) * 1.5)
        // ctx.draw()
        // that.setData({
        //   isurl:true
        // })
      //保存图片到临时路径
      saveImgUseTempCanvas(that, 100, null)
      console.log(that.data.tempImageSrc)
    }
  },
  chooseFontsize() {
    this.setData({
      isChooseFontSize: !this.data.isChooseFontSize,
      isChooseFontColor: false,
      isChooseFontPattern: false
    })
  },
  reset(){
    this.getdiyedit();
  },
  fontsizeSliderChange(e) {
    const index = this.data.textListindex;
    let textLists = this.data.textList;
    //console.log(textLists)
    //console.log(index)
    // var fontsize=textLists[index].fontsize;
    // fontsize=e.detail.value+"px";
    // textLists[index].fontsize=fontsize;
    textLists.forEach(function(val,i){
      if(index==i){
        //console.log(i)
        textLists[i].fontsize=e.detail.value+"px"
      }
    })  
    this.setData({
      textList: textLists
    })
    //console.log(this.data.textList)
    // this.fontsize = e.detail.value
    // var allText = this.data.allText
    // if (allText !== {} && (allText.isTextActive)) {
    //   allText.fontsize = this.fontsize
    //   this.setData({
    //     allText: allText
    //   })
    // }
  },
  chooseFontColor() {
    this.setData({
      isChooseFontSize: false,
      isChooseFontColor: !this.data.isChooseFontColor,
      isChooseFontPattern: false
    })
  },
  fontColorChange(e) {
    console.log(e)
    var color = e.target.dataset.selected
    console.log(color)
    const index = this.data.textListindex;
    console.log(index)
    let textLists = this.data.textList;
    textLists.forEach(function(val,i){
      if(index==i){
        textLists[i].color=color
      }
    })
    this.setData({
      textList: textLists
    })
    console.log(this.data.textList)
    // var allText = this.data.allText
    // if (allText && (allText.isTextActive)) {
    //   allText.color = this.color
    //   this.setData({
    //     allText: allText
    //   })
    // }
  },
  chooseFontPattern() {
    this.setData({
      isChooseFontSize: false,
      isChooseFontColor: false,
      isChooseFontPattern: !this.data.isChooseFontPattern
    })
  },
  fontStyleChange(e) {
    const index = this.data.textListindex;
    var fontstyle = e.detail.value ? 'oblique' : 'normal'
    let textLists = this.data.textList;
    textLists.forEach(function(val,i){
      if(index==i){
        console.log(fontstyle)
        console.log(textLists[i].fontstyle)
        textLists[i].fontstyle=fontstyle
      }
    })
    this.setData({
      textList: textLists
    })
    // var allText = this.data.allText
    // if (allText !== {} && (allText.isTextActive)) {
    //   allText.fontstyle = this.fontstyle
    //   this.setData({
    //     allText: allText
    //   })
    // }
  },
  fontWeightChange(e) {
    const index = this.data.textListindex;
    var fontweight = e.detail.value ? 'bold' : 'normal'
    let textLists = this.data.textList;
    textLists.forEach(function (val, i) {
      if (index == i) {
        console.log(textLists[i].fontweight)
        console.log(fontweight)
        textLists[i].fontweight = fontweight
      }
    })
    this.setData({
      textList: textLists
    })
    // var allText = this.data.allText
    // if (allText !== {} && (allText.isTextActive)) {
    //   allText.fontweight = this.fontweight
    //   this.setData({
    //     allText: allText
    //   })
    // }
  },
  textToMainPage() {
    loadImgOnImage(this)
    this.setData({
      allText: [],
      page: 'mainPage'
    })
  },
  //保存照片
  saveImgToPhone: function() {
    console.log(this.data.tempImageSrc)
    wx.previewImage({
      urls: [this.data.tempImageSrc], // 需要预览的图片http链接列表        
    })
  },
  onReady: function() {

  },
  onShow: function() {

  },
  onHide: function() {

  },
  onUnload: function() {

  },
  tabdiyedit: function(t) {
    this.setData({
      page_value: t.target.dataset.page_value
    })
  },
  getdiyedit: function(id, page_value) {
    var that = this;
    var id = that.data.id;
    var page_valueList = that.data.page_valueList
    e.get("yoxdiy/template_edit", {
      isajax: 1,
      id: id,
      page_value: page_valueList
    }, function(e) {
      if(e.status==0){
        wx.showModal({
          title: '',
          content: e.result.message,
          success(res){
            wx.navigateBack({
              delta: 1
            })
            return;
          }
        })
      }
      e.data.page_value = JSON.parse(e.data.page_value)
      that.setData({
        detailList: e.data,
      })
      //console.log(that.data.detailList.page_value.background.url)
    })
  },
  starttext(e) {
    //console.log(this.data.detailList.page_value.textgroupList)
    for (var i = 0; i < this.data.detailList.page_value.textgroupList.length;i++){
      this.data.detailList.page_value.textgroupList[i].textX = e.touches[0].clientX
      this.data.detailList.page_value.textgroupList[i].textY = e.touches[0].clientY
    }
  },
  starttextMove: function(e) {
    var textList = this.data.textList
    console.log(this.textX)
    console.log(this.textY)
    var dragLengthX = (e.touches[0].clientX - this.textX)
    console.log(dragLengthX)
    var dragLengthY = (e.touches[0].clientY - this.textY)
    console.log(dragLengthY)
    var minTextL = 0
    var minTextT = 0
    console.log(this.deviceRatio)
    var maxTextL = (750 - 100) * this.deviceRatio
    console.log(maxTextL)
    console.log(this.imgViewHeight)
    var maxTextT = this.imgViewHeight - 40 * this.deviceRatio
    console.log(maxTextT)
    var textLists = this.data.textList;
    var index = this.data.textListindex;
    // textLists.forEach(function(val,i){
    //   if (index == i) {
    //     console.log(fontweight)
    //     textLists[i].fontweight = fontweight
    //   }
    // })
    var newTextL = parseFloat(textLists[index].x1) + dragLengthX
    var newTextT = parseFloat(textLists[index].y1) + dragLengthY
    //console.log(newTextT)
    if (newTextL < minTextL) newTextL = minTextL
    if (newTextL > maxTextL) newTextL = maxTextL
    if (newTextT < minTextT) newTextT = minTextT
    if (newTextT > maxTextT) newTextT = maxTextT

    console.log(newTextL)
    console.log(newTextT)
    textLists.forEach(function(val,i){
      if (index == i) {
        textLists[i].x1 = newTextL + "px"
        textLists[i].y1 = newTextT + "px"
      }
    })
    this.setData({
      textList: textLists,
      isChooseFontSize: false,
      isChooseFontColor: false,
      isChooseFontPattern: false
    })
    this.textX = e.touches[0].clientX
    this.textY = e.touches[0].clientY
  },
  onPullDownRefresh: function() {

  },
  onReachBottom: function() {

  },
  onShareAppMessage: function() {

  }
})
function chooseImage(self) {
  wx.chooseImage({
    count: 1,
    success: function (res) {
      var tempFilePaths = res.tempFilePaths;
      wx.uploadFile({
        url: '',
        filePath: '',
        name: '',
      })
      self.setData({
        imageNotChoosed: false,
        tempImageSrc: tempFilePaths[0],
        originImageSrc: tempFilePaths[0],
      })
      loadImgOnImage(self)
    },
    fail: function (res) {
      self.setData({
        imageNotChoosed: true
      })
    }
  })
}
function loadImgOnImage(self) {
  //console.log(self.data.tempImageSrc)
  wx.getImageInfo({
    src: self.data.tempImageSrc,
    success: function(res) {
      self.oldScale = 1
      self.initRatio = res.height / self.imgViewHeight //转换为了px 图片原始大小/显示大小
      if (self.initRatio < res.width / (750 * self.deviceRatio)) {
        self.initRatio = res.width / (750 * self.deviceRatio)
      }
      //图片显示大小
      self.scaleWidth = (res.width / self.initRatio)
      self.scaleHeight = (res.height / self.initRatio)

      self.initScaleWidth = self.scaleWidth
      self.initScaleHeight = self.scaleHeight
      self.startX = 750 * self.deviceRatio / 2 - self.scaleWidth / 2;
      self.startY = self.imgViewHeight / 2 - self.scaleHeight / 2;
      self.setData({
        imgWidth: self.scaleWidth,
        imgHeight: self.scaleHeight,
        imgTop: self.startY,
        imgLeft: self.startX
      })
      wx.hideLoading();
    }
  })
}

function loadImgOnCanvas(self) {
  wx.getImageInfo({
    src: self.data.tempImageSrc,
    success: function(res) {
      self.initRatio = res.height / self.imgViewHeight //转换为了px 图片原始大小/显示大小
      if (self.initRatio < res.width / (750 * self.deviceRatio)) {
        self.initRatio = res.width / (750 * self.deviceRatio)
      }
      //图片显示大小
      self.scaleWidth = (res.width / self.initRatio)
      self.scaleHeight = (res.height / self.initRatio)

      self.initScaleWidth = self.scaleWidth
      self.initScaleHeight = self.scaleHeight
      self.startX = -self.scaleWidth / 2;
      self.startY = -self.scaleHeight / 2;
      self.ctx = wx.createCanvasContext('myCanvas')
      self.ctx.translate((750 * self.deviceRatio) / 2, self.imgViewHeight / 2) //原点移至中心，保证图片居中显示
      self.ctx.drawImage(self.data.tempImageSrc, self.startX, self.startY, self.scaleWidth, self.scaleHeight)
      self.ctx.draw()
    }
  })
}

// function throttle(fn, miniTimeCell) {
//   var timer = null,
//     previous = null;

//   return function () {
//     var now = +new Date(),
//       context = this,
//       args = arguments;
//     if (!previous) previous = now;
//     var remaining = now - previous;
//     if (miniTimeCell && remaining >= miniTimeCell) {
//       fn.apply(context, args);
//       previous = now;
//     }
//   }
// }
// const fn = throttle(drawOnTouchMove, 100)

// function drawOnTouchMove(self, e) {
//   let { minScale, maxScale } = self.data
//   let [touch0, touch1] = e.touches
//   let xMove, yMove, newDistance, xDistance, yDistance

//   if (e.timeStamp - self.timeOneFinger < 100) {//touch时长过短，忽略
//     return
//   }

//   // 单指手势时触发
//   if (e.touches.length === 1) {
//     //计算单指移动的距离
//     xMove = touch0.clientX - self.touchX
//     yMove = touch0.clientY - self.touchY
//     //转换移动距离到正确的坐标系下
//     self.imgLeft = self.startX + xMove
//     self.imgTop = self.startY + yMove

//     self.setData({
//       imgTop: self.imgTop,
//       imgLeft: self.imgLeft
//     })
//   }
//   // 两指手势触发
//   if (e.touches.length >= 2) {
//     // self.timeMoveTwo = e.timeStamp
//     // 计算二指最新距离
//     xDistance = touch1.clientX - touch0.clientX
//     yDistance = touch1.clientY - touch0.clientY
//     newDistance = Math.sqrt(xDistance * xDistance + yDistance * yDistance)

//     //  使用0.005的缩放倍数具有良好的缩放体验
//     self.newScale = self.oldScale + 0.005 * (newDistance - self.oldDistance)

//     //  设定缩放范围
//     self.newScale <= minScale && (self.newScale = minScale)
//     self.newScale >= maxScale && (self.newScale = maxScale)

//     self.scaleWidth = self.newScale * self.initScaleWidth
//     self.scaleHeight = self.newScale * self.initScaleHeight

//     self.imgLeft = self.deviceRatio * 750 / 2 - self.newScale * self.initLeft
//     self.imgTop = self.imgViewHeight / 2 - self.newScale * self.initTop
//     self.setData({
//       imgTop: self.imgTop,
//       imgLeft: self.imgLeft,
//       imgWidth: self.scaleWidth,
//       imgHeight: self.scaleHeight
//     })
//   }
// }

function saveImgUseTempCanvas(that, delay, fn) {
  wx.showToast({
    title: '图片生成中...',
    icon: 'loading',
    duration: 1000
  });
  setTimeout(function() {
    wx.canvasToTempFilePath({
      x: 0,
      y: 0,
      width: that.data.tempCanvasWidth,
      height: that.data.tempCanvasHeight,
      destWidth: that.data.tempCanvasWidth,
      destHeight: that.data.tempCanvasHeight,
      canvasId: 'tempCanvas',
      success: function(res) {
        console.log(res.tempFilePath)
        that.setData({
          tempImageSrc: res.tempFilePath
        })
        if (fn) {
          fn(self)
        }
      }
    })
  }, delay)
}
function drawTextVertical(context, text, x, y, sizes) {
  var arrText = text.split('');
  var arrWidth = arrText.map(function (letter) {
    return sizes;
  });

  var align = context.textAlign;
  var baseline = context.textBaseline;

  // if (align == 'left') {
  //     x = x + Math.max.apply(null, arrWidth) / 2;
  // } else if (align == 'right') {
  //     x = x - Math.max.apply(null, arrWidth) / 2;
  // }
  // if (baseline == 'bottom' || baseline == 'alphabetic' || baseline == 'ideographic') {
  //     y = y - arrWidth[0] / 2;
  // } else if (baseline == 'top' || baseline == 'hanging') {
  //     y = y + arrWidth[0] / 2;
  // }

  // context.textAlign = 'center';
  // context.textBaseline = 'middle';

  // 开始逐字绘制
  arrText.forEach(function (letter, index) {
    // 确定下一个字符的纵坐标位置
    var letterWidth = arrWidth[index];
    // 是否需要旋转判断
    var code = letter.charCodeAt(0);
    if (code <= 256) {
      context.translate(x, y);
      // 英文字符，旋转90°
      context.rotate(90 * Math.PI / 180);
      context.translate(-x, -y);
    } else if (index > 0 && text.charCodeAt(index - 1) < 256) {
      // y修正
      y = y + arrWidth[index - 1] / 2;
    }

    context.fillText(letter, x, y);
    // 旋转坐标系还原成初始态
    context.setTransform(1, 0, 0, 1, 0, 0);
    // 确定下一个字符的纵坐标位置
    var letterWidth = arrWidth[index];
    y = y + letterWidth;

  });
  // 水平垂直对齐方式还原
  context.textAlign = align;
  context.textBaseline = baseline;
}

function imageUtil(e) {
  var imageSize = {};
  var originalWidth = e.detail.width;//图片原始宽
  var originalHeight = e.detail.height;//图片原始高
  var originalScale = originalHeight / originalWidth;//图片高宽比
  console.log('originalWidth: ' + originalWidth)
  console.log('originalHeight: ' + originalHeight)
  //获取屏幕宽高
  wx.getSystemInfo({
    success: function (res) {
      var windowWidth = res.windowWidth;
      var windowHeight = res.windowHeight;
      var windowscale = windowHeight / windowWidth;//屏幕高宽比
      console.log('windowWidth: ' + windowWidth)
      console.log('windowHeight: ' + windowHeight)
      if (originalScale < windowscale) {//图片高宽比小于屏幕高宽比
        //图片缩放后的宽为屏幕宽
        imageSize.imageWidth = windowWidth;
        imageSize.imageHeight = (windowWidth * originalHeight) / originalWidth;
      } else {//图片高宽比大于屏幕高宽比
        //图片缩放后的高为屏幕高
        imageSize.imageHeight = windowHeight;
        imageSize.imageWidth = (windowHeight * originalWidth) / originalHeight;
      }

    }
  })
  console.log('缩放后的宽: ' + imageSize.imageWidth)
  console.log('缩放后的高: ' + imageSize.imageHeight)
  return imageSize;
}
