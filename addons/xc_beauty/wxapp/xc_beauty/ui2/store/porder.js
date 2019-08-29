var app = getApp(), common = require("../common/common.js");

function GetDateStr(a) {
    var t = new Date();
    t.setDate(t.getDate() + a);
    t.getFullYear();
    return t.getMonth() + 1 + "月" + t.getDate() + "日";
}

function getMyDay(a) {
    var t = new Date();
    return t.setDate(t.getDate() + a), 0 == t.getDay() && "周日", 1 == t.getDay() && "周一", 
    2 == t.getDay() && "周二", 3 == t.getDay() && "周三", 4 == t.getDay() && "周四", 5 == t.getDay() && "周五", 
    6 == t.getDay() && "周六", t.getDay();
}

function getMyDay2(a) {
    var t, e = new Date();
    return e.setDate(e.getDate() + a), 0 == e.getDay() && (t = "周日"), 1 == e.getDay() && (t = "周一"), 
    2 == e.getDay() && (t = "周二"), 3 == e.getDay() && (t = "周三"), 4 == e.getDay() && (t = "周四"), 
    5 == e.getDay() && (t = "周五"), 6 == e.getDay() && (t = "周六"), t;
}

function get_time(d) {
    var i = d.data.times, r = [], s = !1;
    d.setData({
        time_list: s,
        tip: s
    }), 1 == d.data.online_time && app.util.request({
        url: "entry/wxapp/user",
        showLoading: !1,
        data: {
            op: "plan_date",
            plan_date: d.data.date[d.data.date_curr].date,
            id: d.data.id
        },
        success: function(a) {
            var t = a.data;
            if ("" != t.data) {
                if (1 == t.data.status) for (var e = 0; e < i.length; e++) i[e].week == d.data.date[d.data.date_curr].week ? r = i[e].content : 7 == i[e].week && 0 == d.data.date[d.data.date_curr].week && (r = i[e].content); else s = !0;
                "" != d.data.member_id && null != d.data.member_id ? app.util.request({
                    url: "entry/wxapp/user",
                    data: {
                        op: "times_log",
                        member: d.data.member_id,
                        plan_date: d.data.date[d.data.date_curr].date,
                        list: JSON.stringify(r),
                        index: d.data.date[d.data.date_curr].index,
                        week: d.data.date[d.data.date_curr].week
                    },
                    success: function(a) {
                        var t = a.data;
                        "" != t.data ? d.setData({
                            tip: s,
                            time_curr: -1,
                            time_list: t.data
                        }) : d.setData({
                            tip: s,
                            time_curr: -1,
                            time_list: r
                        });
                    }
                }) : d.setData({
                    time_curr: -1,
                    time_list: r,
                    tip: s
                });
            }
        }
    });
}

function sign(a) {
    var t = a.data.time_curr, e = a.data.name, d = (a.data.mobile, a.data.member_id), i = a.data.service_id, r = a.data.service_type, s = "";
    1 == a.data.online_time && -1 == t && (s = "请选择时间"), "" != e && null != e || (s = "请选择预约人"), 
    "" != d && null != d || (s = "请选择服务人员"), "" != i && null != i || (s = "请选择服务项目"), 
    -1 == r && (s = "请选择服务方式"), "" == s ? a.setData({
        submit: !0
    }) : wx.showModal({
        title: "错误",
        content: s
    });
}

Page({
    data: {
        pagePath: "../store/porder",
        date_curr: 0,
        page: 1,
        pagesize: 20,
        isbottom: !1,
        submit: !1,
        time_curr: -1,
        service_type: -1
    },
    tab: function(a) {
        var t = this, e = (t.data.service_home, a.currentTarget.dataset.index);
        e != t.data.service_type && (1 == e ? t.setData({
            member_id: -2,
            member_name: "店内安排"
        }) : t.setData({
            member_id: "",
            member_name: ""
        }), t.setData({
            service_type: e
        }), get_time(t));
    },
    qie: function() {
        var d = this;
        1 == d.data.more_store && -1 != d.data.id && (d.setData({
            store_page: !0,
            store_list: []
        }), wx.getLocation({
            type: "wgs84",
            success: function(a) {
                var t = a.latitude, e = a.longitude;
                a.speed, a.accuracy;
                d.setData({
                    latitude: t,
                    longitude: e
                });
            },
            complete: function() {
                var a = {
                    op: "store",
                    page: d.data.page,
                    pagesize: d.data.pagesize
                };
                null != d.data.latitude && "" != d.data.latitude && (a.latitude = d.data.latitude), 
                null != d.data.longitude && "" != d.data.longitude && (a.longitude = d.data.longitude), 
                app.util.request({
                    url: "entry/wxapp/order",
                    data: a,
                    success: function(a) {
                        var t = a.data;
                        "" != t.data && (d.setData({
                            store_list: t.data
                        }), get_time(d));
                    }
                });
            }
        }));
    },
    store_close: function() {
        this.setData({
            store_page: !1
        });
    },
    store_choose: function(a) {
        var t = this, e = a.currentTarget.dataset.index;
        t.setData({
            list: t.data.store_list[e],
            id: t.data.store_list[e].id,
            store_page: !1
        }), 1 != t.data.service_type && t.setData({
            member_id: ""
        }), get_time(t);
    },
    call: function(a) {
        var t = this;
        null != t.data.id && "" != t.data.id && (-1 == t.data.id ? wx.makePhoneCall({
            phoneNumber: t.data.map.content.mobile
        }) : wx.makePhoneCall({
            phoneNumber: t.data.list.mobile
        }));
    },
    map: function(a) {
        var t = this;
        null != t.data.id && "" != t.data.id && (-1 == t.data.id ? wx.openLocation({
            latitude: parseFloat(t.data.map.content.latitude),
            longitude: parseFloat(t.data.map.content.longitude),
            name: t.data.map.content.address,
            address: t.data.map.content.address,
            scale: 28
        }) : wx.openLocation({
            latitude: parseFloat(t.data.list.map.latitude),
            longitude: parseFloat(t.data.list.map.longitude),
            name: t.data.list.address,
            address: t.data.list.address,
            scale: 28
        }));
    },
    date_choose: function(a) {
        var t = this, e = a.currentTarget.dataset.index;
        e != t.data.date_curr && (t.setData({
            date_curr: e,
            time_curr: -1
        }), get_time(t));
    },
    date_left: function() {
        var a = this;
        if (0 < a.data.date_curr) a.setData({
            date_curr: a.data.date_curr - 1,
            time_curr: -1
        }), get_time(a); else {
            var t = a.data.date;
            if (0 < t[a.data.date_curr].index) {
                var e = {};
                e.index = t[a.data.date_curr].index - 1, e.date = GetDateStr(e.index), e.week = getMyDay(e.index), 
                0 == e.index ? e.name = "今天" : e.name = getMyDay2(e.index), t.splice(t.length - 1, 1), 
                t.unshift(e), a.setData({
                    date: t,
                    time_curr: -1
                }), get_time(a);
            }
        }
    },
    date_right: function() {
        var a = this;
        if (a.data.date_curr < a.data.date.length - 1) a.setData({
            date_curr: a.data.date_curr + 1,
            time_curr: -1
        }), get_time(a); else {
            var t = a.data.date, e = {};
            e.index = t[a.data.date_curr].index + 1, e.date = GetDateStr(e.index), e.week = getMyDay(e.index), 
            0 == e.index ? e.name = "今天" : e.name = getMyDay2(e.index), t.splice(0, 1), t.push(e), 
            a.setData({
                date: t,
                time_curr: -1
            }), get_time(a);
        }
    },
    time_choose: function(a) {
        var t = a.currentTarget.dataset.index;
        t != this.data.time_curr && this.setData({
            time_curr: t
        });
    },
    member_on: function() {
        var e = this;
        if ("" != e.data.id && null != e.data.id) {
            if (1 != e.data.service_type) {
                e.setData({
                    member_page: !0
                });
                var a = {
                    op: "store_member",
                    id: e.data.id,
                    page: e.data.page,
                    pagesize: e.data.pagesize
                };
                "" != e.data.service_id && null != e.data.service_id && (a.service = e.data.service_id), 
                app.util.request({
                    url: "entry/wxapp/index",
                    data: a,
                    success: function(a) {
                        var t = a.data;
                        "" != t.data ? e.setData({
                            member_list: t.data,
                            page: e.data.page
                        }) : e.setData({
                            isbottom: !0
                        });
                    }
                });
            }
        } else wx.showModal({
            title: "提示",
            content: "请先选择门店",
            success: function(a) {
                a.confirm ? console.log("用户点击确定") : a.cancel && console.log("用户点击取消");
            }
        });
    },
    member_close: function() {
        this.setData({
            member_page: !1,
            page: 1,
            isbottom: !1,
            member_list: []
        });
    },
    member_choose: function(a) {
        var t = a.currentTarget.dataset.index, e = this.data.member_list;
        this.setData({
            member_id: e[t].id,
            member_name: e[t].name,
            member_page: !1,
            page: 1,
            isbottom: !1,
            member_list: []
        }), get_time(this);
    },
    service_on: function() {
        var e = this;
        if ("" != e.data.id && null != e.data.id) {
            e.setData({
                shadow: !0,
                service_page: !0
            });
            var a = {
                op: "store_service",
                id: e.data.id
            };
            "" != e.data.member_id && null != e.data.member_id && (a.member = e.data.member_id), 
            -1 != e.data.service_type && (a.service_type = e.data.service_type), app.util.request({
                url: "entry/wxapp/index",
                data: a,
                success: function(a) {
                    var t = a.data;
                    "" != t.data && e.setData({
                        service_list: t.data
                    });
                }
            });
        } else wx.showModal({
            title: "提示",
            content: "请先选择门店",
            success: function(a) {
                a.confirm ? console.log("用户点击确定") : a.cancel && console.log("用户点击取消");
            }
        });
    },
    service_close: function() {
        this.setData({
            shadow: !1,
            service_page: !1,
            service_list: []
        });
    },
    service_choose: function(a) {
        var t = a.currentTarget.dataset.index, e = this.data.service_list;
        this.setData({
            shadow: !1,
            service_page: !1,
            service_id: e[t].id,
            service_name: e[t].name,
            service_home: e[t],
            service_list: []
        });
    },
    reset: function(a) {
        var t = a.currentTarget.dataset.index;
        1 == t ? (this.setData({
            member_id: "",
            member_name: ""
        }), get_time(this)) : 2 == t && this.setData({
            service_id: "",
            service_name: ""
        });
    },
    submit: function() {
        var a = this;
        if (sign(a), a.data.submit) {
            var t = {
                id: a.data.service_id,
                total: 1,
                name: a.data.name,
                mobile: a.data.mobile,
                store: a.data.id,
                member: a.data.member_id,
                service_type: a.data.service_type,
                order_type: 4
            };
            1 == a.data.online_time && (t.plan_date = a.data.date[a.data.date_curr].date + " " + a.data.time_list[a.data.time_curr].start + "-" + a.data.time_list[a.data.time_curr].end, 
            t.date = a.data.date[a.data.date_curr].date, null != a.data.time_list[a.data.time_curr].shop_member && "" != a.data.time_list[a.data.time_curr].shop_member && (t.shop_member = a.data.time_list[a.data.time_curr].shop_member), 
            null != a.data.time_list[a.data.time_curr].home_member && "" != a.data.time_list[a.data.time_curr].home_member && (t.home_member = a.data.time_list[a.data.time_curr].home_member)), 
            "" != a.data.address && null != a.data.address && "undefined" != a.data.address && (t.address = a.data.address), 
            "" != a.data.map && null != a.data.map && "undefined" != a.data.map && (t.map = JSON.stringify(a.data.map)), 
            app.util.request({
                url: "entry/wxapp/setorder",
                data: t,
                success: function(a) {
                    var t = a.data;
                    "" != t.data && (wx.showToast({
                        title: "提交成功",
                        icon: "success",
                        duration: 2e3
                    }), setTimeout(function() {
                        wx.navigateTo({
                            url: "../porder/pay?&out_trade_no=" + t.data.out_trade_no
                        });
                    }, 2e3));
                }
            });
        }
    },
    onLoad: function(e) {
        var d = this;
        common.config(d), common.theme(d);
        for (var a = [], t = 0; t < 5; t++) {
            (s = {}).index = t, s.date = GetDateStr(t), s.week = getMyDay(t), s.name = 0 == t ? "今天" : getMyDay2(t), 
            a.push(s);
        }
        if (d.setData({
            date: a
        }), "" != e.id && null != e.id) d.setData({
            id: e.id
        }); else {
            var i = app.map, r = -1;
            null != i && "" != i && "" != i.content && null != i.content && 1 == i.content.store && (r = ""), 
            d.setData({
                id: r,
                map: i
            });
        }
        var s = {
            op: "store_order"
        };
        "" != d.data.id && null != d.data.id && -1 != d.data.id && (s.id = d.data.id), app.util.request({
            url: "entry/wxapp/service",
            data: s,
            success: function(a) {
                var t = a.data;
                "" != t.data && ("" != t.data.list && null != t.data.list && d.setData({
                    list: t.data.list,
                    id: t.data.list.id
                }), "" != e.member_id && null != e.member_id && "" != e.member_name && null != e.member_name && d.setData({
                    member_id: e.member_id,
                    member_name: e.member_name
                }), "" != t.data.times && null != t.data.times && (d.setData({
                    times: t.data.times
                }), get_time(d)), d.setData({
                    more_store: t.data.more_store
                }));
            }
        });
        var n = d.data.config, m = -1, o = -1;
        "" != n && null != n && ("" != n.home_status && null != n.home_status && (m = n.home_status), 
        "" != n.online_time && null != n.online_time && (o = n.online_time)), d.setData({
            home_status: m,
            online_time: o
        }), -1 == m && d.setData({
            service_type: 2
        });
    },
    onReady: function() {},
    onShow: function() {
        var d = this;
        app.util.request({
            url: "entry/wxapp/service",
            showLoading: !1,
            data: {
                op: "address_default"
            },
            success: function(a) {
                var t = a.data;
                if ("" != t.data) {
                    var e = t.data.address;
                    "" != t.data.content && null != t.data.content && (e += t.data.content), "" != t.data.map && null != t.data.map && d.setData({
                        map: t.data.map
                    }), d.setData({
                        name: t.data.name,
                        mobile: t.data.mobile,
                        address: e
                    });
                }
            }
        });
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        var e = this;
        !e.data.isbottom && e.data.member_page && app.util.request({
            url: "entry/wxapp/index",
            data: {
                op: "store_member",
                id: e.data.id,
                page: e.data.page,
                pagesize: e.data.pagesize
            },
            success: function(a) {
                var t = a.data;
                "" != t.data ? e.setData({
                    member_list: e.data.member_list.concat(t.data),
                    page: e.data.page
                }) : e.setData({
                    isbottom: !0
                });
            }
        });
    }
});