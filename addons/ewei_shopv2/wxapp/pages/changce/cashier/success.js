var o = getApp(), e = o.requirejs("core");

Page({
    data: {
        vid: 0,
        verifycode: "",
        loading: !1,
        show: !1,
        order: [],
        approot: o.globalData.approot
    },
    onLoad: function(o) {
        var e = (o = o || {}).logid;
        this.setData({
            logid: e
        }), this.showsucc();
    },
    showsucc: function() {
        var o = this;
        e.get("changce/cashier/success", {
            logid: o.data.logid
        }, function(a) {
            if (!a.log) return e.alert(a.error), !1;
            o.setData({
                log: a.log,
                member: a.member,
                show: !0
            });
        });
    },
    callme: function(o) {
        wx.makePhoneCall({
            phoneNumber: o.target.id
        });
    },
    phone: function(o) {
        e.phone(o);
    }
});