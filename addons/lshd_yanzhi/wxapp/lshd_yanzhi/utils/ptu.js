var e = getApp();

function facemerge(l, a) {
    console.log(a), e.globalData.shujufanhui = null, wx.uploadFile({
        url: e.util.url("entry/wxapp/facemerge", {
            m: "lshd_yanzhi",
            model: l,
            ids: e.globalData.userid
        }),
        filePath: a,
        name: "file",
        success: function(l) {
            e.globalData.shujufanhui = l.data, console.log(l.data);
        },
        fail: function(e) {
            console.log(e);
        }
    });
}

function facedecoration(l, a) {
    e.globalData.shujufanhui = null, wx.uploadFile({
        url: e.util.url("entry/wxapp/facedecoration", {
            m: "lshd_yanzhi",
            model: l,
            ids: e.globalData.userid
        }),
        filePath: a,
        name: "file",
        success: function(l) {
            e.globalData.shujufanhui = l.data, console.log(l.data);
        },
        fail: function(e) {
            console.log(e);
        }
    });
}

function imgfilter(l, a) {
    e.globalData.shujufanhui = null, wx.uploadFile({
        url: e.util.url("entry/wxapp/imgfilter", {
            m: "lshd_yanzhi",
            model: l,
            ids: e.globalData.userid
        }),
        filePath: a,
        name: "file",
        success: function(l) {
            e.globalData.shujufanhui = l.data, console.log(l.data);
        },
        fail: function(e) {
            console.log(e);
        }
    });
}

function facesticker(l, a) {
    e.globalData.shujufanhui = null, wx.uploadFile({
        url: e.util.url("entry/wxapp/facesticker", {
            m: "lshd_yanzhi",
            model: l,
            ids: e.globalData.userid
        }),
        filePath: a,
        name: "file",
        success: function(l) {
            e.globalData.shujufanhui = l.data, console.log(l.data);
        },
        fail: function(e) {
            console.log(e);
        }
    });
}

function delectFile() {
    wx.request({
        url: e.util.url("entry/wxapp/delimg", {
            m: "lshd_yanzhi"
        }),
        data: {
            ids: e.globalData.userid
        },
        success: function(e) {
            console.log("删除文件," + e.data);
        },
        complete: function() {}
    });
}

module.exports = {
    facemerge: facemerge,
    facedecoration: facedecoration,
    imgfilter: imgfilter,
    facesticker: facesticker,
    delectFile: delectFile
};