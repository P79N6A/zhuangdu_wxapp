var t, e = getApp();

Page({
    data: {
        appinfo: {},
        hasuserinfo: !1,
        erweima: "",
        getimage: "",
        userinfo: "",
        dateall: ""
    },
    onLoad: function(a) {
        t = this;
        var l = e.globalData.datealls;
        console.log(l), t.setData({
            dateall: l,
            appinfo: e.globalData.appinfo,
            userinfo: e.globalData.userinfo
        }), t.createNewImg();
    },
    baocun: function() {
        wx.saveImageToPhotosAlbum({
            filePath: this.data.imagePath,
            success: function(e) {
                console.log(e);
            }
        });
    },
    createNewImg: function() {
        var a = this, e = wx.createCanvasContext("mycanvas");
        e.fillRect(0, 0, 375, 540), e.drawImage("../../images/muban.png", 0, 0, 375, 540), 
        e.drawImage("../../images/minfo.png", 35, 370, 305, 60), e.drawImage(t.data.dateall.beautyimg, 225, 0, 150, 150), 
        e.setFontSize(24), e.setFillStyle("#333333"), e.setTextAlign("center"), e.fillText(t.data.appinfo.visit, 113, 468), 
        e.stroke(), e.setFontSize(15), e.setFillStyle("#fff"), e.setTextAlign("left"), e.fillText(t.data.dateall.age, 260, 388), 
        e.stroke(), e.setFontSize(15), e.setFillStyle("#fff"), e.setTextAlign("left"), e.fillText(t.data.dateall.sex, 260, 423), 
        e.stroke(), e.setFontSize(15), e.setFillStyle("#fff"), e.setTextAlign("left"), e.fillText(t.data.dateall.nickname, 90, 388), 
        e.stroke(), e.setFontSize(15), e.setFillStyle("#fff"), e.setTextAlign("left"), e.fillText(t.data.dateall.expression, 90, 423), 
        e.stroke(), e.save(), e.beginPath(), e.arc(188, 187, 100, 0, 2 * Math.PI, !1), e.clip(), 
        e.drawImage(t.data.dateall.headimg, 85, 85, 210, 210), e.closePath(), e.restore(), 
        e.beginPath(), e.drawImage("../../images/defen.png", 0, 245, 375, 95), e.drawImage("../../images/wecat.jpg", 270, 440, 90, 90), 
        e.setFontSize(22), e.setFillStyle("#fff"), e.setTextAlign("left"), e.fillText(t.data.dateall.beauty + "分", 158, 295), 
        e.stroke(), e.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "mycanvas",
                success: function(e) {
                    var t = e.tempFilePath;
                    a.setData({
                        imagePath: t,
                        maskHidden: !0
                    });
                },
                fail: function(e) {
                    console.log(e);
                }
            });
        }, 200);
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});