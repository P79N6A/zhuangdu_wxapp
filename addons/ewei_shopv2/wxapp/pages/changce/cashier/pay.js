var e = getApp(), t = e.requirejs("core"), a = e.requirejs("jquery");

Page({
    data: {
        cashierid: 0,
        cashier: [],
        testNumber: "",
        show: 0
    },
    showkeybord: function(e) {
        this.setData({
            showKeyBord: 1
        });
    },
    closeKeyBord: function(e) {
        this.setData({
            showKeyBord: 0
        });
    },
    setNumber: function(e) {
        var t = e.currentTarget.dataset.type, a = e.currentTarget.dataset.number, s = this.data.testNumber;
        if (t && "delete" == t) i = s.slice(0, s.length - 1); else var i = s + a;
        this.setData({
            testNumber: i
        });
    },
    onLoad: function(e) {
        e = e || {};
        var a = decodeURIComponent(e.scene);
        if (!e.cashierid && a) {
            var s = t.str2Obj(a);
            e.cashierid = s.id, s.mid && (e.mid = s.mid);
        }
        this.setData({
            cashierid: e.cashierid
        }), this.getCashier(), this.showkeybord();
    },
    getCashier: function() {
        var e = this;
        t.get("changce/cashier/pay", {
            id: e.data.cashierid
        }, function(t) {
            e.setData({
                cashier: t.cashier,
                show: 1
            }), wx.setNavigationBarTitle({
                title: t.cashier.title
            });
        });
    },
    money: function(e) {
        var t = a.trim(e.detail.value);
        this.setData({
            testNumber: t
        });
    },
    pay: function() {
        var e = this;
        if (e.data.testNumber.length < 1 || isNaN(e.data.testNumber)) return t.alert("请输入数字金额！");
        t.post("changce/cashier/dopay", {
            id: e.data.cashierid,
            money: e.data.testNumber
        }, function(e) {
            0 == e.error && e.wechat.success ? t.pay(e.wechat.payinfo, function(a) {
                "requestPayment:ok" == a.errMsg && t.post("changce/cashier/paysuccess", {
                    logid: e.logid
                }, function(e) {
                    0 == e.error ? wx.navigateTo({
                        url: "/pages/changce/cashier/success?logid=" + e.logid
                    }) : t.alert(e.message);
                }, !0);
            }) : t.alert(e.message);
        });
    }
});