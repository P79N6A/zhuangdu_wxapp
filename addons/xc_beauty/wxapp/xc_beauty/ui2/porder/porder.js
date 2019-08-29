var app = getApp(), common = require("../common/common.js");

function GetDateStr(t) {
    var a = new Date();
    a.setDate(a.getDate() + t);
    a.getFullYear();
    return a.getMonth() + 1 + "月" + a.getDate() + "日";
}

function getMyDay(t) {
    var a = new Date();
    return a.setDate(a.getDate() + t), 0 == a.getDay() && "周日", 1 == a.getDay() && "周一", 
    2 == a.getDay() && "周二", 3 == a.getDay() && "周三", 4 == a.getDay() && "周四", 5 == a.getDay() && "周五", 
    6 == a.getDay() && "周六", a.getDay();
}

function getMyDay2(t) {
    var a, e = new Date();
    return e.setDate(e.getDate() + t), 0 == e.getDay() && (a = "周日"), 1 == e.getDay() && (a = "周一"), 
    2 == e.getDay() && (a = "周二"), 3 == e.getDay() && (a = "周三"), 4 == e.getDay() && (a = "周四"), 
    5 == e.getDay() && (a = "周五"), 6 == e.getDay() && (a = "周六"), a;
}

function get_time(e) {
    var t = e.data.times, r = [];
    if (console.log(e.data.time_status), 1 == e.data.time_status && (null == e.data.group || "" == e.data.group) || 1 == e.data.group_time && "" != e.data.group && null != e.data.group) {
        for (var a = 0; a < t.length; a++) t[a].week == e.data.date[e.data.date_curr].week ? r = t[a].content : 7 == t[a].week && 0 == e.data.date[e.data.date_curr].week && (r = t[a].content);
        -1 != e.data.store_member || -1 == e.data.member_status ? app.util.request({
            url: "entry/wxapp/user",
            data: {
                op: "times_log",
                member: e.data.store_member,
                plan_date: e.data.date[e.data.date_curr].date,
                list: JSON.stringify(r),
                index: e.data.date[e.data.date_curr].index,
                week: e.data.date[e.data.date_curr].week
            },
            success: function(t) {
                var a = t.data;
                "" != a.data ? e.setData({
                    time_curr: -1,
                    time_list: a.data
                }) : e.setData({
                    time_curr: -1,
                    time_list: r
                });
            }
        }) : (console.log(r), e.setData({
            time_curr: -1,
            time_list: r
        }));
    }
}

function sign(t) {
    var a = t.data.time_curr, e = t.data.name, r = t.data.store_member, d = t.data.store, s = t.data.service_type, i = "";
    1 != t.data.time_status || "" != t.data.group && null != t.data.group || -1 == a && (i = "请选择时间"), 
    1 == t.data.group_time && "" != t.data.group && null != t.data.group && -1 == a && (i = "请选择时间"), 
    "" != e && null != e || (i = "请选择预约人"), 1 == t.data.member_status && -1 == r && (i = "请选择服务人员"), 
    -1 == d && (i = "请选择门店"), -1 == s && (i = "请选择服务方式"), "" == i ? t.setData({
        submit: !0
    }) : wx.showModal({
        title: "错误",
        content: i
    });
}

Page({
    data: {
        service_type: -1,
        total: 1,
        date_curr: 0,
        time_curr: -1,
        submit: !1,
        store: -1,
        store_member: -1,
        page: 1,
        pagesize: 20,
        isbottom: !1
    },
    store_on: function() {
        var r = this;
        1 == r.data.more_store && wx.getLocation({
            type: "wgs84",
            success: function(t) {
                var a = t.latitude, e = t.longitude;
                t.speed, t.accuracy;
                r.setData({
                    latitude: a,
                    longitude: e
                });
            },
            complete: function() {
                r.setData({
                    store_page: !0,
                    store_list: []
                });
                var t = {
                    op: "store",
                    id: r.data.service.id
                };
                null != r.data.latitude && "" != r.data.latitude && (t.latitude = r.data.latitude), 
                null != r.data.longitude && "" != r.data.longitude && (t.longitude = r.data.longitude), 
                app.util.request({
                    url: "entry/wxapp/order",
                    data: t,
                    success: function(t) {
                        var a = t.data;
                        "" != a.data && r.setData({
                            store_list: a.data
                        });
                    }
                });
            }
        });
    },
    store_close: function() {
        this.setData({
            store_page: !1
        });
    },
    store_choose: function(t) {
        var a = this, e = t.currentTarget.dataset.index;
        a.setData({
            store_name: a.data.store_list[e].name,
            store: a.data.store_list[e].id,
            store_page: !1
        }), 1 != a.data.service_type && a.setData({
            store_member: -1
        }), get_time(a);
    },
    tab: function(t) {
        var a = this, e = a.data.service, r = t.currentTarget.dataset.index;
        r != a.data.service_type && (1 == r ? 1 == parseInt(e.home) ? (a.setData({
            service_type: r,
            member_name: "店内安排"
        }), 1 == a.data.member_status && a.setData({
            store_member: -2
        }), get_time(a)) : wx.showModal({
            title: "提示",
            content: "暂不支持上门服务"
        }) : 2 == r && (1 == parseInt(e.shop) ? (a.setData({
            service_type: r,
            store_member: -1
        }), get_time(a)) : wx.showModal({
            title: "提示",
            content: "暂不支持到店服务"
        })));
    },
    up: function() {
        this.setData({
            total: this.data.total + 1
        });
    },
    down: function() {
        var t = this;
        1 < t.data.total && t.setData({
            total: t.data.total - 1
        });
    },
    date_choose: function(t) {
        var a = this, e = t.currentTarget.dataset.index;
        e != a.data.date_curr && (a.setData({
            date_curr: e,
            time_curr: -1
        }), get_time(a));
    },
    date_left: function() {
        var t = this;
        if (0 < t.data.date_curr) t.setData({
            date_curr: t.data.date_curr - 1,
            time_curr: -1
        }), get_time(t); else {
            var a = t.data.date;
            if (0 < a[t.data.date_curr].index) {
                var e = {};
                e.index = a[t.data.date_curr].index - 1, e.date = GetDateStr(e.index), e.week = getMyDay(e.index), 
                0 == e.index ? e.name = "今天" : e.name = getMyDay2(e.index), a.splice(a.length - 1, 1), 
                a.unshift(e), t.setData({
                    date: a,
                    time_curr: -1
                }), get_time(t);
            }
        }
    },
    date_right: function() {
        var t = this;
        if (t.data.date_curr < t.data.date.length - 1) t.setData({
            date_curr: t.data.date_curr + 1,
            time_curr: -1
        }), get_time(t); else {
            var a = t.data.date, e = {};
            e.index = a[t.data.date_curr].index + 1, e.date = GetDateStr(e.index), e.week = getMyDay(e.index), 
            0 == e.index ? e.name = "今天" : e.name = getMyDay2(e.index), a.splice(0, 1), a.push(e), 
            t.setData({
                date: a,
                time_curr: -1
            }), get_time(t);
        }
    },
    time_choose: function(t) {
        var a = t.currentTarget.dataset.index;
        a != this.data.time_curr && this.setData({
            time_curr: a
        });
    },
    member_on: function() {
        var e = this;
        if (-1 != e.data.store) {
            if (1 != e.data.service_type) {
                e.setData({
                    member_page: !0
                });
                var t = {
                    op: "store_member",
                    id: e.data.store,
                    page: e.data.page,
                    pagesize: e.data.pagesize
                };
                app.util.request({
                    url: "entry/wxapp/index",
                    data: t,
                    success: function(t) {
                        var a = t.data;
                        "" != a.data ? e.setData({
                            member_list: a.data,
                            page: e.data.page + 1
                        }) : e.setData({
                            isbottom: !0
                        });
                    }
                });
            }
        } else wx.showModal({
            title: "提示",
            content: "请先选择门店"
        });
    },
    member_close: function() {
        this.setData({
            member_page: !1,
            member_list: [],
            page: 1,
            isbottom: !1
        });
    },
    member_choose: function(t) {
        var a = t.currentTarget.dataset.index, e = this.data.member_list;
        this.setData({
            store_member: e[a].id,
            member_name: e[a].name,
            member_page: !1,
            member_list: [],
            page: 1,
            isbottom: !1
        }), get_time(this);
    },
    submit: function(t) {
        var a = this;
        if (sign(a), a.data.submit) {
            var e = {
                id: a.data.id,
                total: a.data.total,
                name: a.data.name,
                mobile: a.data.mobile,
                service_type: a.data.service_type,
                store: a.data.store,
                member: a.data.store_member,
                order_type: 1,
                form_id: t.detail.formId
            };
            "" != a.data.address && null != a.data.address && "undefined" != a.data.address && (e.address = a.data.address), 
            "" != a.data.map && null != a.data.map && "undefined" != a.data.map && (e.map = JSON.stringify(a.data.map)), 
            null != a.data.kind && null != a.data.kind && (e.kind = a.data.kind), "" != a.data.group && null != a.data.group && (e.group = a.data.group), 
            "" != a.data.group_id && null != a.data.group_id && (e.group_id = a.data.group_id), 
            "" != a.data.flash && null != a.data.flash && (e.flash = a.data.flash), (1 == a.data.time_status && (null == a.data.group || "" == a.data.group) || 1 == a.data.group_time && null != a.data.group && "" != a.data.group) && (e.plan_date = a.data.date[a.data.date_curr].date + " " + a.data.time_list[a.data.time_curr].start + "-" + a.data.time_list[a.data.time_curr].end, 
            e.date = a.data.date[a.data.date_curr].date, null != a.data.time_list[a.data.time_curr].shop_member && "" != a.data.time_list[a.data.time_curr].shop_member && (e.shop_member = a.data.time_list[a.data.time_curr].shop_member), 
            null != a.data.time_list[a.data.time_curr].home_member && "" != a.data.time_list[a.data.time_curr].home_member && (e.home_member = a.data.time_list[a.data.time_curr].home_member)), 
            app.util.request({
                url: "entry/wxapp/setorder",
                data: e,
                success: function(t) {
                    var a = t.data;
                    "" != a.data && (wx.showToast({
                        title: "提交成功",
                        icon: "success",
                        duration: 2e3
                    }), setTimeout(function() {
                        wx.redirectTo({
                            url: "pay?&out_trade_no=" + a.data.out_trade_no
                        });
                    }, 2e3));
                }
            });
        }
    },
    onLoad: function(r) {
        var d = this;
        common.config(d), common.theme(d), "" != r.id && null != r.id && d.setData({
            id: r.id
        }), "" != r.kind && null != r.kind && d.setData({
            kind: r.kind
        }), "" != r.group && null != r.group && d.setData({
            group: r.group
        }), "" != r.group_id && null != r.group_id && d.setData({
            group_id: r.group_id
        }), "" != r.flash && null != r.flash && d.setData({
            flash: r.flash
        });
        for (var t = [], a = 0; a < 5; a++) {
            var e = {};
            e.index = a, e.date = GetDateStr(a), e.week = getMyDay(a), e.name = 0 == a ? "今天" : getMyDay2(a), 
            t.push(e);
        }
        d.setData({
            date: t
        }), app.util.request({
            url: "entry/wxapp/service",
            data: {
                op: "porder",
                id: r.id
            },
            success: function(t) {
                var a = t.data;
                if ("" != a.data) {
                    if ("" != a.data.service && null != a.data.service) {
                        if ("" != r.kind && null != r.kind && null != a.data.service.parameter && null != a.data.service.parameter) for (var e = 0; e < a.data.service.parameter.length; e++) a.data.service.parameter[e].name == r.kind && "" != a.data.service.parameter[e].price && null != a.data.service.parameter[e].price && (a.data.service.price = a.data.service.parameter[e].price);
                        -1 == a.data.service.home && d.setData({
                            service_type: 2
                        }), d.setData({
                            service: a.data.service
                        });
                    }
                    "" != a.data.times && null != a.data.times && (d.setData({
                        times: a.data.times
                    }), get_time(d)), "" != a.data.list && null != a.data.list && d.setData({
                        store_name: a.data.list.name,
                        store: a.data.list.id
                    }), d.setData({
                        more_store: a.data.more_store
                    });
                }
            }
        });
        var s = d.data.config, i = -1, n = -1, o = -1, u = -1;
        "" != s && null != s && ("" != s.member_status && null != s.member_status && (n = s.member_status), 
        "" != s.home_status && null != s.home_status && (i = s.home_status), "" != s.time_status && null != s.time_status && (o = s.time_status), 
        "" != s.group_time && null != s.group_time && (u = s.group_time)), d.setData({
            home_status: i,
            member_status: n,
            time_status: o,
            group_time: u
        }), -1 == i && d.setData({
            service_type: 2
        });
    },
    onReady: function() {},
    onShow: function() {
        var r = this;
        app.util.request({
            url: "entry/wxapp/service",
            showLoading: !1,
            data: {
                op: "address_default"
            },
            success: function(t) {
                var a = t.data;
                if ("" != a.data) {
                    var e = a.data.address;
                    "" != a.data.content && null != a.data.content && (e += a.data.content), r.setData({
                        name: a.data.name,
                        mobile: a.data.mobile,
                        address: e,
                        map: a.data.map
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
                id: e.data.store,
                page: e.data.page,
                pagesize: e.data.pagesize
            },
            success: function(t) {
                var a = t.data;
                "" != a.data ? e.setData({
                    member_list: e.data.member_list.concat(a.data),
                    page: e.data.page
                }) : e.setData({
                    isbottom: !0
                });
            }
        });
    }
});