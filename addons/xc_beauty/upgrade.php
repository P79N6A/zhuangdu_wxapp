<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_xc_beauty_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `address` longtext COMMENT '地址',
  `map` longtext COMMENT '地址',
  `content` longtext COMMENT '地址',
  `status` int(11) DEFAULT '-1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`status`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='地址';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext COMMENT '详情',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `type` int(11) DEFAULT NULL COMMENT '类型（1普通文章2优惠活动文章）',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `btn` varchar(255) DEFAULT NULL COMMENT '按钮文字',
  `link_type` int(11) DEFAULT '1' COMMENT '模式',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='文章';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `bimg` varchar(255) DEFAULT NULL COMMENT '图片',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `appid` varchar(255) DEFAULT NULL COMMENT '小程序id',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='轮播图';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `xkey` varchar(50) DEFAULT NULL COMMENT '关键字',
  `content` longtext COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`xkey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='配置';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `plan_date` varchar(50) DEFAULT NULL COMMENT '时间',
  `order` int(11) DEFAULT '0' COMMENT '订单量',
  `amount` varchar(50) DEFAULT NULL COMMENT '金额',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `store` int(11) DEFAULT '-1' COMMENT '门店',
  `type` int(11) DEFAULT '1' COMMENT '类型（1月份2日期）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='统计';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '优惠价格',
  `condition` varchar(50) DEFAULT NULL COMMENT '满足条件',
  `times` longtext COMMENT '有效期',
  `total` int(11) DEFAULT '-1' COMMENT '总量',
  `type` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_discuss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `score` int(11) DEFAULT NULL COMMENT '评价（1满意2一般3不满意）',
  `content` longtext COMMENT '详情',
  `imgs` longtext COMMENT '图片集',
  `tip` int(11) DEFAULT '-1' COMMENT '匿名',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `type` int(11) DEFAULT '1' COMMENT '类型（1服务项目2技师）',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_discuss_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论日志';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '团长',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `team` longtext COMMENT '队伍',
  `status` int(11) DEFAULT '-1' COMMENT '状态（-1拼团中1成功2失败）',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `failtime` varchar(50) DEFAULT NULL COMMENT '失败天数',
  `total` int(11) DEFAULT '0' COMMENT '人数',
  `team_total` int(11) DEFAULT '0' COMMENT '团队人数',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`pid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团购';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_moban_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `nickname` varchar(500) DEFAULT NULL COMMENT '呢称',
  `status` int(11) DEFAULT '-1' COMMENT '-1未使用  1已使用',
  `createtime` int(11) DEFAULT NULL COMMENT '发布日期',
  `ident` varchar(50) DEFAULT NULL COMMENT '标识',
  `headimgurl` varchar(500) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='绑定模版消息用户';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `simg` varchar(255) DEFAULT NULL COMMENT '图片',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自定义导航';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `store` int(11) DEFAULT NULL COMMENT '门店',
  `plan_date` varchar(50) DEFAULT NULL COMMENT '日期',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约时间';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_online_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '发送者用户id',
  `type` int(11) DEFAULT NULL COMMENT '类型1文本2图片',
  `content` longtext COMMENT '内容',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `duty` int(11) DEFAULT '1' COMMENT '身份1客户2客服',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`type`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客服记录';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_onlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `member` int(11) DEFAULT NULL COMMENT '未读条数',
  `type` int(11) DEFAULT NULL COMMENT '类型',
  `content` longtext COMMENT '内容',
  `updatetime` varchar(50) DEFAULT NULL COMMENT '更新时间',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`createtime`,`member`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客服';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号',
  `wx_out_trade_no` varchar(50) DEFAULT NULL COMMENT '微信订单号',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `kind` varchar(255) DEFAULT NULL COMMENT '种类',
  `total` int(11) DEFAULT NULL COMMENT '数量',
  `plan_date` varchar(50) DEFAULT NULL COMMENT '预约时间',
  `userinfo` longtext COMMENT '用户信息',
  `amount` varchar(50) DEFAULT NULL COMMENT '应付款',
  `o_amount` varchar(50) DEFAULT NULL COMMENT '实付款',
  `status` int(11) DEFAULT '-1' COMMENT '支付状态（-1待支付1已支付2退款',
  `use` int(11) DEFAULT '-1' COMMENT '使用',
  `discuss` int(11) DEFAULT '-1' COMMENT '评论',
  `pay_type` int(11) DEFAULT NULL COMMENT '支付方式（1微信支付2余额支付）',
  `content` longtext COMMENT '备注',
  `coupon_id` int(11) DEFAULT NULL COMMENT '优惠券id',
  `coupon_price` varchar(50) DEFAULT NULL COMMENT '优惠价格',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `form_id` varchar(255) DEFAULT NULL,
  `canpay` varchar(50) DEFAULT NULL COMMENT '余额支付',
  `wxpay` varchar(50) DEFAULT NULL COMMENT '微信支付',
  `refund_content` longtext COMMENT '退款理由',
  `refund_status` int(11) DEFAULT '-1' COMMENT '退款状态',
  `order_type` int(11) DEFAULT '1' COMMENT '1购买2充值3团购',
  `money` varchar(50) DEFAULT NULL COMMENT '钱包',
  `score` int(11) DEFAULT NULL COMMENT '获得积分数',
  `discount` varchar(50) DEFAULT NULL COMMENT '折扣',
  `group` int(11) DEFAULT NULL COMMENT '团购id',
  `one_openid` varchar(50) DEFAULT NULL COMMENT '一级推荐人',
  `one_amount` varchar(50) DEFAULT NULL COMMENT '一级佣金',
  `two_openid` varchar(50) DEFAULT NULL COMMENT '二级推荐人',
  `two_amount` varchar(50) DEFAULT NULL COMMENT '二级佣金',
  `three_openid` varchar(50) DEFAULT NULL COMMENT '三级推荐人',
  `three_amount` varchar(50) DEFAULT NULL COMMENT '三级佣金',
  `store` int(11) DEFAULT NULL COMMENT '门店',
  `member` int(11) DEFAULT NULL COMMENT '店员',
  `gift` varchar(50) DEFAULT NULL COMMENT '赠送金额',
  `charge_id` varchar(255) DEFAULT NULL,
  `service_type` int(11) DEFAULT NULL COMMENT '服务方式（1上门服务2到店服务）',
  `can_use` int(11) DEFAULT '1' COMMENT '使用次数',
  `is_use` int(11) DEFAULT '0' COMMENT '已使用',
  `buy_type` int(11) DEFAULT '1' COMMENT '买单方式（1自助付款2商家待扣）',
  `recharge_type` int(11) DEFAULT '1' COMMENT '充值方式（1会员充值2管理员充值）',
  `recharge_openid` varchar(50) DEFAULT NULL COMMENT '待充的用户id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '单价',
  `flash` int(11) DEFAULT '-1' COMMENT '限时抢购',
  `flash_price` decimal(10,2) DEFAULT NULL COMMENT '抢购价格',
  `failtime` datetime DEFAULT NULL COMMENT '失效时间',
  `failstatus` int(11) DEFAULT '1' COMMENT '失效处理状态',
  `wq_out_trade_no` varchar(50) DEFAULT NULL,
  `he_log` longtext COMMENT '核销记录',
  `can_member` int(11) DEFAULT '1' COMMENT '预约人数',
  `form_id2` varchar(255) DEFAULT NULL,
  `member_discuss` int(11) DEFAULT '-1' COMMENT '人员评论',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`out_trade_no`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `midflytime` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='排班';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pai_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `weeknum` int(11) DEFAULT NULL COMMENT '1-7，表示周一到周天',
  `time1start` varchar(50) DEFAULT NULL COMMENT '开始时间',
  `time1end` varchar(50) DEFAULT NULL,
  `time2start` varchar(50) DEFAULT NULL,
  `time2end` varchar(50) DEFAULT NULL,
  `time3start` varchar(50) DEFAULT NULL,
  `time3end` varchar(50) DEFAULT NULL,
  `time4start` varchar(50) DEFAULT NULL,
  `time4end` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`,`weeknum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='排班详情';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pick_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配货分类';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pick_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `store` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号',
  `pid` longtext COMMENT '产品',
  `total` int(11) DEFAULT '0' COMMENT '数量',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '总价',
  `status` int(11) DEFAULT '-1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pick_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL COMMENT '分类',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `unit` varchar(50) DEFAULT NULL COMMENT '单位',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配货';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '类型',
  `cid` int(11) DEFAULT NULL COMMENT '优惠券',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `simg` varchar(255) DEFAULT NULL COMMENT '图片',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `times` int(11) DEFAULT '0' COMMENT '概率',
  `member` int(11) DEFAULT '-1' COMMENT '数量',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`type`,`status`,`sort`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='奖品';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_rotate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `times` int(11) DEFAULT '0' COMMENT '签到次数',
  `plan_date` varchar(50) DEFAULT NULL COMMENT '签到日期',
  `status` int(11) DEFAULT '-1' COMMENT '是否已抽奖',
  `rotated` int(11) DEFAULT '0' COMMENT '抽奖次数',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='抽奖';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_rotate_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '优惠券id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `type` int(11) DEFAULT '1' COMMENT '类型',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `cid` int(11) DEFAULT NULL COMMENT '奖品id',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖记录';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `status` int(11) DEFAULT '1' COMMENT '1获得2消费',
  `score` varchar(50) DEFAULT NULL COMMENT '积分',
  `over` varchar(50) DEFAULT NULL COMMENT '剩余积分',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分记录';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `cid` int(11) DEFAULT NULL COMMENT '分类',
  `simg` varchar(255) DEFAULT NULL COMMENT '封面',
  `bimg` longtext COMMENT '图片',
  `price` varchar(50) DEFAULT NULL COMMENT '价格',
  `o_price` varchar(50) DEFAULT NULL COMMENT '原价',
  `kind` longtext COMMENT '种类',
  `discuss` int(11) DEFAULT '0' COMMENT '评价总人数',
  `discuss_total` int(11) DEFAULT '0' COMMENT '评价总数',
  `good_total` int(11) DEFAULT '0' COMMENT '满意总数',
  `middle_total` int(11) DEFAULT '0' COMMENT '一般总数',
  `bad_total` int(11) DEFAULT '0' COMMENT '不满意总数',
  `content` longtext COMMENT '详情',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `group_status` int(11) DEFAULT '-1' COMMENT '团购状态',
  `group_price` varchar(50) DEFAULT NULL COMMENT '团购价格',
  `group_total` int(11) DEFAULT '0' COMMENT '团购数',
  `group_limit` int(11) DEFAULT NULL COMMENT '团购限制天数',
  `group_index` int(11) DEFAULT '-1' COMMENT '团购首页显示',
  `group_number` int(11) DEFAULT NULL COMMENT '团购人数',
  `type` int(11) DEFAULT NULL COMMENT '分销方法',
  `level_one` varchar(50) DEFAULT NULL COMMENT '一级分销',
  `level_two` varchar(50) DEFAULT NULL COMMENT '二级分销',
  `level_three` varchar(50) DEFAULT NULL COMMENT '三级分销',
  `store_status` int(11) DEFAULT '1' COMMENT '门店状态',
  `store` longtext COMMENT '适用门店',
  `home` int(11) DEFAULT '1' COMMENT '家',
  `shop` int(11) DEFAULT '1' COMMENT '店',
  `service_time` varchar(50) DEFAULT NULL COMMENT '服务时间',
  `can_use` int(11) DEFAULT '1' COMMENT '核销次数',
  `sold` int(11) DEFAULT '0' COMMENT '已售数',
  `content_type` int(11) DEFAULT '1' COMMENT '详情模式',
  `content2` longtext COMMENT '详情2',
  `parameter` longtext COMMENT '参数',
  `flash_status` int(11) DEFAULT '-1' COMMENT '限时状态',
  `flash_price` decimal(10,2) DEFAULT '0.00' COMMENT '抢购价格',
  `flash_start` datetime DEFAULT NULL COMMENT '开始时间',
  `flash_end` datetime DEFAULT NULL COMMENT '结束时间',
  `flash_member` int(11) DEFAULT NULL COMMENT '库存',
  `flash_index` int(11) DEFAULT '-1' COMMENT '首页显示',
  `flash_order` int(11) DEFAULT '0' COMMENT '每人限买单数',
  `flash_shu` int(11) DEFAULT '0' COMMENT '每单限购数',
  `sale_status` int(11) DEFAULT '-1' COMMENT '折扣状态',
  `group_stock` int(11) DEFAULT '-1' COMMENT '团购库存',
  `group_head_status` int(11) DEFAULT '-1' COMMENT '团长优惠',
  `group_head_price` varchar(50) DEFAULT NULL COMMENT '团长优惠价格',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`cid`,`status`,`sort`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='列表';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_service_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `bimg` varchar(255) DEFAULT NULL COMMENT '图片',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `index` int(11) DEFAULT '-1' COMMENT '首页显示',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='分类';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号',
  `amount` varchar(50) DEFAULT NULL COMMENT '金额',
  `level` int(11) DEFAULT NULL COMMENT '等级',
  `status` int(11) DEFAULT '1' COMMENT '状态（-1等待1成功2失败）',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`createtime`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销订单';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `total` int(11) DEFAULT '0' COMMENT '数量',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `simg` varchar(255) DEFAULT NULL COMMENT '图标',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `address` longtext COMMENT '地址',
  `map` longtext COMMENT '地址',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `plan_date` varchar(50) DEFAULT NULL COMMENT '营业时间',
  `content` longtext COMMENT '详情',
  `code` varchar(255) DEFAULT NULL COMMENT '买单二维码',
  `sms` varchar(50) DEFAULT NULL COMMENT '短信接收手机号',
  `machine_code` varchar(255) DEFAULT NULL COMMENT '打印机终端号',
  `msign` varchar(255) DEFAULT NULL COMMENT '打印机终端密钥',
  `sn` varchar(255) DEFAULT NULL COMMENT '打印机编号',
  `print_status` int(11) DEFAULT '-1' COMMENT '打印状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='门店管理';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_store_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `simg` varchar(255) DEFAULT NULL COMMENT '头像',
  `cid` int(11) DEFAULT '0' COMMENT '店面',
  `task` varchar(255) DEFAULT NULL COMMENT '职称',
  `service` longtext COMMENT '服务项目',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `tag` varchar(255) DEFAULT NULL COMMENT '标签',
  `content` longtext COMMENT '个人简介',
  `zan` int(11) DEFAULT '0' COMMENT '点赞人数',
  `discuss` int(11) DEFAULT '0' COMMENT '评论数',
  `pai_status` int(11) DEFAULT '-1' COMMENT '单双周状态',
  `pai1` int(11) DEFAULT NULL COMMENT '排版',
  `pai2` int(11) DEFAULT NULL COMMENT '排版2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='店员管理';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_store_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `price` varchar(50) DEFAULT NULL COMMENT '价格',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `home` int(11) DEFAULT '1' COMMENT '上门服务',
  `shop` int(11) DEFAULT '1' COMMENT '到店服务',
  `can_use` int(11) DEFAULT '1' COMMENT '核销次数',
  `member` varchar(50) DEFAULT NULL COMMENT '抵扣预约人数',
  `sale_status` int(11) DEFAULT '-1' COMMENT '折扣状态',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='门店服务';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `week` int(11) DEFAULT NULL COMMENT '星期',
  `content` longtext COMMENT '详情',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约时间';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_times_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `member` int(11) DEFAULT NULL COMMENT '人员',
  `plan_date` varchar(50) DEFAULT NULL COMMENT '日期',
  `total` int(11) DEFAULT '0' COMMENT '已预约数量',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约时间记录';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_user_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `cid` int(11) DEFAULT NULL COMMENT '优惠券id',
  `status` int(11) DEFAULT '-1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户优惠券';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `nick` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `money` varchar(50) DEFAULT '0' COMMENT '余额',
  `card` int(11) DEFAULT '-1' COMMENT '会员卡状态（-1未激活1激活）',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `password` varchar(50) DEFAULT NULL COMMENT '支付密码',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `score` int(11) DEFAULT '0' COMMENT '积分',
  `share` varchar(50) DEFAULT NULL COMMENT '推荐人openid',
  `level_one` int(11) DEFAULT '0' COMMENT '一级数量',
  `level_two` int(11) DEFAULT '0' COMMENT '二级数量',
  `level_three` int(11) DEFAULT '0' COMMENT '三级数量',
  `share_amount` varchar(50) DEFAULT '0' COMMENT '累计佣金',
  `share_o_amount` varchar(50) DEFAULT '0' COMMENT '可提现佣金',
  `share_t_amount` varchar(50) DEFAULT '0' COMMENT '已提现佣金',
  `share_empty` varchar(50) DEFAULT '0' COMMENT '无效佣金',
  `shop` int(11) DEFAULT '-1' COMMENT '店铺管理',
  `store` int(11) DEFAULT NULL COMMENT '绑定门店',
  `shop_id` int(11) DEFAULT NULL COMMENT '子管理员门店id',
  `card_name` varchar(50) DEFAULT NULL COMMENT '会员等级',
  `card_price` varchar(50) DEFAULT NULL COMMENT '会员折扣',
  `card_amount` decimal(10,2) DEFAULT '0.00' COMMENT '消费金额',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户信息';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pay_type` int(11) DEFAULT '1' COMMENT '提现方式（1微信2支付宝）',
  `username` varchar(50) DEFAULT NULL COMMENT '账号',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `amount` varchar(50) DEFAULT NULL COMMENT '金额',
  `money` varchar(50) DEFAULT NULL COMMENT '余额',
  `status` int(11) DEFAULT '-1' COMMENT '状态（-1待处理1成功2失败）',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `order_type` int(11) DEFAULT '1' COMMENT '提现类型(1余额提现2佣金提现)',
  `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号',
  `wx_out_trade_no` varchar(50) DEFAULT NULL COMMENT '微信订单号',
  `content` longtext COMMENT '错误详情',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`createtime`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现申请';
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_zan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '技师id',
  `status` int(11) DEFAULT '-1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点赞记录';
";
pdo_run($sql);
if(!pdo_fieldexists('xc_beauty_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_address',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `name` varchar(50) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xc_beauty_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `mobile` varchar(50) DEFAULT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('xc_beauty_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `address` longtext COMMENT '地址';");
}
if(!pdo_fieldexists('xc_beauty_address',  'map')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `map` longtext COMMENT '地址';");
}
if(!pdo_fieldexists('xc_beauty_address',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `content` longtext COMMENT '地址';");
}
if(!pdo_fieldexists('xc_beauty_address',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `status` int(11) DEFAULT '-1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_address',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_address')." ADD KEY `uniacid` (`uniacid`,`openid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_article',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_article',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_article',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_article',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `content` longtext COMMENT '详情';");
}
if(!pdo_fieldexists('xc_beauty_article',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_article',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `type` int(11) DEFAULT NULL COMMENT '类型（1普通文章2优惠活动文章）';");
}
if(!pdo_fieldexists('xc_beauty_article',  'link')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `link` varchar(255) DEFAULT NULL COMMENT '链接';");
}
if(!pdo_fieldexists('xc_beauty_article',  'btn')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `btn` varchar(255) DEFAULT NULL COMMENT '按钮文字';");
}
if(!pdo_fieldexists('xc_beauty_article',  'link_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `link_type` int(11) DEFAULT '1' COMMENT '模式';");
}
if(!pdo_indexexists('xc_beauty_article',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD KEY `uniacid` (`uniacid`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_banner',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_banner',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_banner',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_banner',  'bimg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `bimg` varchar(255) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('xc_beauty_banner',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_banner',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_banner',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_banner',  'link')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `link` varchar(255) DEFAULT NULL COMMENT '链接';");
}
if(!pdo_fieldexists('xc_beauty_banner',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `appid` varchar(255) DEFAULT NULL COMMENT '小程序id';");
}
if(!pdo_indexexists('xc_beauty_banner',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_config',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_config')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_config',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_config')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_config',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_config')." ADD `name` varchar(50) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xc_beauty_config',  'xkey')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_config')." ADD `xkey` varchar(50) DEFAULT NULL COMMENT '关键字';");
}
if(!pdo_fieldexists('xc_beauty_config',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_config')." ADD `content` longtext COMMENT '内容';");
}
if(!pdo_indexexists('xc_beauty_config',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_config')." ADD KEY `uniacid` (`uniacid`,`xkey`);");
}
if(!pdo_fieldexists('xc_beauty_count',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_count',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_count',  'plan_date')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `plan_date` varchar(50) DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('xc_beauty_count',  'order')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `order` int(11) DEFAULT '0' COMMENT '订单量';");
}
if(!pdo_fieldexists('xc_beauty_count',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `amount` varchar(50) DEFAULT NULL COMMENT '金额';");
}
if(!pdo_fieldexists('xc_beauty_count',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_count',  'store')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `store` int(11) DEFAULT '-1' COMMENT '门店';");
}
if(!pdo_fieldexists('xc_beauty_count',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1月份2日期）';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `name` varchar(50) DEFAULT NULL COMMENT '优惠价格';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'condition')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `condition` varchar(50) DEFAULT NULL COMMENT '满足条件';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'times')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `times` longtext COMMENT '有效期';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `total` int(11) DEFAULT '-1' COMMENT '总量';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `type` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_coupon',  'score')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD `score` int(11) DEFAULT NULL;");
}
if(!pdo_indexexists('xc_beauty_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_coupon')." ADD KEY `uniacid` (`uniacid`,`sort`,`status`);");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `pid` int(11) DEFAULT NULL COMMENT '产品id';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'score')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `score` int(11) DEFAULT NULL COMMENT '评价（1满意2一般3不满意）';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `content` longtext COMMENT '详情';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'imgs')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `imgs` longtext COMMENT '图片集';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'tip')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `tip` int(11) DEFAULT '-1' COMMENT '匿名';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_discuss',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1服务项目2技师）';");
}
if(!pdo_indexexists('xc_beauty_discuss',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD KEY `uniacid` (`uniacid`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_discuss_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_discuss_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss_log')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_discuss_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss_log')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_discuss_log',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss_log')." ADD `pid` int(11) DEFAULT NULL COMMENT '产品id';");
}
if(!pdo_fieldexists('xc_beauty_discuss_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss_log')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_discuss_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss_log')." ADD KEY `uniacid` (`uniacid`,`pid`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_group',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_group',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_group',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '团长';");
}
if(!pdo_fieldexists('xc_beauty_group',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `pid` int(11) DEFAULT NULL COMMENT '产品id';");
}
if(!pdo_fieldexists('xc_beauty_group',  'team')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `team` longtext COMMENT '队伍';");
}
if(!pdo_fieldexists('xc_beauty_group',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `status` int(11) DEFAULT '-1' COMMENT '状态（-1拼团中1成功2失败）';");
}
if(!pdo_fieldexists('xc_beauty_group',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_group',  'failtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `failtime` varchar(50) DEFAULT NULL COMMENT '失败天数';");
}
if(!pdo_fieldexists('xc_beauty_group',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `total` int(11) DEFAULT '0' COMMENT '人数';");
}
if(!pdo_fieldexists('xc_beauty_group',  'team_total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD `team_total` int(11) DEFAULT '0' COMMENT '团队人数';");
}
if(!pdo_indexexists('xc_beauty_group',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_group')." ADD KEY `uniacid` (`uniacid`,`openid`,`pid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `openid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `nickname` varchar(500) DEFAULT NULL COMMENT '呢称';");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `status` int(11) DEFAULT '-1' COMMENT '-1未使用  1已使用';");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `createtime` int(11) DEFAULT NULL COMMENT '发布日期';");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'ident')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `ident` varchar(50) DEFAULT NULL COMMENT '标识';");
}
if(!pdo_fieldexists('xc_beauty_moban_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_moban_user')." ADD `headimgurl` varchar(500) DEFAULT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('xc_beauty_nav',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_nav',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_nav',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_nav',  'simg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `simg` varchar(255) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('xc_beauty_nav',  'link')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `link` varchar(255) DEFAULT NULL COMMENT '链接';");
}
if(!pdo_fieldexists('xc_beauty_nav',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_nav',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_nav',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_nav',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_nav')." ADD KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_online',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_online',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_online',  'store')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD `store` int(11) DEFAULT NULL COMMENT '门店';");
}
if(!pdo_fieldexists('xc_beauty_online',  'plan_date')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD `plan_date` varchar(50) DEFAULT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('xc_beauty_online',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_online',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_online',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online')." ADD KEY `uniacid` (`uniacid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `pid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '发送者用户id';");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `type` int(11) DEFAULT NULL COMMENT '类型1文本2图片';");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `content` longtext COMMENT '内容';");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_online_log',  'duty')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD `duty` int(11) DEFAULT '1' COMMENT '身份1客户2客服';");
}
if(!pdo_indexexists('xc_beauty_online_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_online_log')." ADD KEY `uniacid` (`uniacid`,`type`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `member` int(11) DEFAULT NULL COMMENT '未读条数';");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `type` int(11) DEFAULT NULL COMMENT '类型';");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `content` longtext COMMENT '内容';");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `updatetime` varchar(50) DEFAULT NULL COMMENT '更新时间';");
}
if(!pdo_fieldexists('xc_beauty_onlines',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_onlines',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_onlines')." ADD KEY `uniacid` (`uniacid`,`createtime`,`member`);");
}
if(!pdo_fieldexists('xc_beauty_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_order',  'out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('xc_beauty_order',  'wx_out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `wx_out_trade_no` varchar(50) DEFAULT NULL COMMENT '微信订单号';");
}
if(!pdo_fieldexists('xc_beauty_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `pid` int(11) DEFAULT NULL COMMENT '产品id';");
}
if(!pdo_fieldexists('xc_beauty_order',  'kind')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `kind` varchar(255) DEFAULT NULL COMMENT '种类';");
}
if(!pdo_fieldexists('xc_beauty_order',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `total` int(11) DEFAULT NULL COMMENT '数量';");
}
if(!pdo_fieldexists('xc_beauty_order',  'plan_date')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `plan_date` varchar(50) DEFAULT NULL COMMENT '预约时间';");
}
if(!pdo_fieldexists('xc_beauty_order',  'userinfo')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `userinfo` longtext COMMENT '用户信息';");
}
if(!pdo_fieldexists('xc_beauty_order',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `amount` varchar(50) DEFAULT NULL COMMENT '应付款';");
}
if(!pdo_fieldexists('xc_beauty_order',  'o_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `o_amount` varchar(50) DEFAULT NULL COMMENT '实付款';");
}
if(!pdo_fieldexists('xc_beauty_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `status` int(11) DEFAULT '-1' COMMENT '支付状态（-1待支付1已支付2退款';");
}
if(!pdo_fieldexists('xc_beauty_order',  'use')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `use` int(11) DEFAULT '-1' COMMENT '使用';");
}
if(!pdo_fieldexists('xc_beauty_order',  'discuss')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `discuss` int(11) DEFAULT '-1' COMMENT '评论';");
}
if(!pdo_fieldexists('xc_beauty_order',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `pay_type` int(11) DEFAULT NULL COMMENT '支付方式（1微信支付2余额支付）';");
}
if(!pdo_fieldexists('xc_beauty_order',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `content` longtext COMMENT '备注';");
}
if(!pdo_fieldexists('xc_beauty_order',  'coupon_id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `coupon_id` int(11) DEFAULT NULL COMMENT '优惠券id';");
}
if(!pdo_fieldexists('xc_beauty_order',  'coupon_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `coupon_price` varchar(50) DEFAULT NULL COMMENT '优惠价格';");
}
if(!pdo_fieldexists('xc_beauty_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_order',  'form_id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `form_id` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_order',  'canpay')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `canpay` varchar(50) DEFAULT NULL COMMENT '余额支付';");
}
if(!pdo_fieldexists('xc_beauty_order',  'wxpay')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `wxpay` varchar(50) DEFAULT NULL COMMENT '微信支付';");
}
if(!pdo_fieldexists('xc_beauty_order',  'refund_content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `refund_content` longtext COMMENT '退款理由';");
}
if(!pdo_fieldexists('xc_beauty_order',  'refund_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `refund_status` int(11) DEFAULT '-1' COMMENT '退款状态';");
}
if(!pdo_fieldexists('xc_beauty_order',  'order_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `order_type` int(11) DEFAULT '1' COMMENT '1购买2充值3团购';");
}
if(!pdo_fieldexists('xc_beauty_order',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `money` varchar(50) DEFAULT NULL COMMENT '钱包';");
}
if(!pdo_fieldexists('xc_beauty_order',  'score')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `score` int(11) DEFAULT NULL COMMENT '获得积分数';");
}
if(!pdo_fieldexists('xc_beauty_order',  'discount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `discount` varchar(50) DEFAULT NULL COMMENT '折扣';");
}
if(!pdo_fieldexists('xc_beauty_order',  'group')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `group` int(11) DEFAULT NULL COMMENT '团购id';");
}
if(!pdo_fieldexists('xc_beauty_order',  'one_openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `one_openid` varchar(50) DEFAULT NULL COMMENT '一级推荐人';");
}
if(!pdo_fieldexists('xc_beauty_order',  'one_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `one_amount` varchar(50) DEFAULT NULL COMMENT '一级佣金';");
}
if(!pdo_fieldexists('xc_beauty_order',  'two_openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `two_openid` varchar(50) DEFAULT NULL COMMENT '二级推荐人';");
}
if(!pdo_fieldexists('xc_beauty_order',  'two_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `two_amount` varchar(50) DEFAULT NULL COMMENT '二级佣金';");
}
if(!pdo_fieldexists('xc_beauty_order',  'three_openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `three_openid` varchar(50) DEFAULT NULL COMMENT '三级推荐人';");
}
if(!pdo_fieldexists('xc_beauty_order',  'three_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `three_amount` varchar(50) DEFAULT NULL COMMENT '三级佣金';");
}
if(!pdo_fieldexists('xc_beauty_order',  'store')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `store` int(11) DEFAULT NULL COMMENT '门店';");
}
if(!pdo_fieldexists('xc_beauty_order',  'member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `member` int(11) DEFAULT NULL COMMENT '店员';");
}
if(!pdo_fieldexists('xc_beauty_order',  'gift')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `gift` varchar(50) DEFAULT NULL COMMENT '赠送金额';");
}
if(!pdo_fieldexists('xc_beauty_order',  'charge_id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `charge_id` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_order',  'service_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `service_type` int(11) DEFAULT NULL COMMENT '服务方式（1上门服务2到店服务）';");
}
if(!pdo_fieldexists('xc_beauty_order',  'can_use')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `can_use` int(11) DEFAULT '1' COMMENT '使用次数';");
}
if(!pdo_fieldexists('xc_beauty_order',  'is_use')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `is_use` int(11) DEFAULT '0' COMMENT '已使用';");
}
if(!pdo_fieldexists('xc_beauty_order',  'buy_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `buy_type` int(11) DEFAULT '1' COMMENT '买单方式（1自助付款2商家待扣）';");
}
if(!pdo_fieldexists('xc_beauty_order',  'recharge_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `recharge_type` int(11) DEFAULT '1' COMMENT '充值方式（1会员充值2管理员充值）';");
}
if(!pdo_fieldexists('xc_beauty_order',  'recharge_openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `recharge_openid` varchar(50) DEFAULT NULL COMMENT '待充的用户id';");
}
if(!pdo_fieldexists('xc_beauty_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `price` decimal(10,2) DEFAULT NULL COMMENT '单价';");
}
if(!pdo_fieldexists('xc_beauty_order',  'flash')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `flash` int(11) DEFAULT '-1' COMMENT '限时抢购';");
}
if(!pdo_fieldexists('xc_beauty_order',  'flash_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `flash_price` decimal(10,2) DEFAULT NULL COMMENT '抢购价格';");
}
if(!pdo_fieldexists('xc_beauty_order',  'failtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `failtime` datetime DEFAULT NULL COMMENT '失效时间';");
}
if(!pdo_fieldexists('xc_beauty_order',  'failstatus')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `failstatus` int(11) DEFAULT '1' COMMENT '失效处理状态';");
}
if(!pdo_fieldexists('xc_beauty_order',  'wq_out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `wq_out_trade_no` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_order',  'he_log')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `he_log` longtext COMMENT '核销记录';");
}
if(!pdo_fieldexists('xc_beauty_order',  'can_member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `can_member` int(11) DEFAULT '1' COMMENT '预约人数';");
}
if(!pdo_fieldexists('xc_beauty_order',  'form_id2')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `form_id2` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_order',  'member_discuss')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `member_discuss` int(11) DEFAULT '-1' COMMENT '人员评论';");
}
if(!pdo_indexexists('xc_beauty_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD KEY `uniacid` (`uniacid`,`out_trade_no`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_pai',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_pai',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_pai',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_pai',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_pai',  'midflytime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD `midflytime` datetime DEFAULT NULL COMMENT '修改时间';");
}
if(!pdo_indexexists('xc_beauty_pai',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai')." ADD KEY `uniacid` (`uniacid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `pid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'weeknum')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `weeknum` int(11) DEFAULT NULL COMMENT '1-7，表示周一到周天';");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time1start')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time1start` varchar(50) DEFAULT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time1end')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time1end` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time2start')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time2start` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time2end')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time2end` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time3start')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time3start` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time3end')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time3end` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time4start')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time4start` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'time4end')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `time4end` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_pai_detail',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_pai_detail',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pai_detail')." ADD KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`,`weeknum`);");
}
if(!pdo_fieldexists('xc_beauty_pick_class',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_pick_class',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pick_class',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_pick_class',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_pick_class',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_pick_class',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_pick_class',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_class')." ADD KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'store')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `store` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `pid` longtext COMMENT '产品';");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `total` int(11) DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `amount` decimal(10,2) DEFAULT '0.00' COMMENT '总价';");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `status` int(11) DEFAULT '-1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_pick_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_pick_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_order')." ADD KEY `uniacid` (`uniacid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `cid` int(11) DEFAULT NULL COMMENT '分类';");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格';");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `unit` varchar(50) DEFAULT NULL COMMENT '单位';");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_pick_service',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_pick_service',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_pick_service')." ADD KEY `cid` (`cid`,`uniacid`,`sort`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_prize',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `type` int(11) DEFAULT NULL COMMENT '类型';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `cid` int(11) DEFAULT NULL COMMENT '优惠券';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'simg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `simg` varchar(255) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'times')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `times` int(11) DEFAULT '0' COMMENT '概率';");
}
if(!pdo_fieldexists('xc_beauty_prize',  'member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `member` int(11) DEFAULT '-1' COMMENT '数量';");
}
if(!pdo_indexexists('xc_beauty_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD KEY `uniacid` (`uniacid`,`type`,`status`,`sort`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'times')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `times` int(11) DEFAULT '0' COMMENT '签到次数';");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'plan_date')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `plan_date` varchar(50) DEFAULT NULL COMMENT '签到日期';");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `status` int(11) DEFAULT '-1' COMMENT '是否已抽奖';");
}
if(!pdo_fieldexists('xc_beauty_rotate',  'rotated')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD `rotated` int(11) DEFAULT '0' COMMENT '抽奖次数';");
}
if(!pdo_indexexists('xc_beauty_rotate',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate')." ADD KEY `uniacid` (`uniacid`,`openid`,`status`);");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `pid` int(11) DEFAULT NULL COMMENT '优惠券id';");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `title` varchar(255) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `type` int(11) DEFAULT '1' COMMENT '类型';");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_rotate_log',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD `cid` int(11) DEFAULT NULL COMMENT '奖品id';");
}
if(!pdo_indexexists('xc_beauty_rotate_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_rotate_log')." ADD KEY `uniacid` (`uniacid`,`openid`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_score',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_score',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_score',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_score',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `title` varchar(50) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xc_beauty_score',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `status` int(11) DEFAULT '1' COMMENT '1获得2消费';");
}
if(!pdo_fieldexists('xc_beauty_score',  'score')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `score` varchar(50) DEFAULT NULL COMMENT '积分';");
}
if(!pdo_fieldexists('xc_beauty_score',  'over')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `over` varchar(50) DEFAULT NULL COMMENT '剩余积分';");
}
if(!pdo_fieldexists('xc_beauty_score',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_score',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_score')." ADD KEY `uniacid` (`uniacid`,`openid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_service',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_service',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_service',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_service',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `cid` int(11) DEFAULT NULL COMMENT '分类';");
}
if(!pdo_fieldexists('xc_beauty_service',  'simg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `simg` varchar(255) DEFAULT NULL COMMENT '封面';");
}
if(!pdo_fieldexists('xc_beauty_service',  'bimg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `bimg` longtext COMMENT '图片';");
}
if(!pdo_fieldexists('xc_beauty_service',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `price` varchar(50) DEFAULT NULL COMMENT '价格';");
}
if(!pdo_fieldexists('xc_beauty_service',  'o_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `o_price` varchar(50) DEFAULT NULL COMMENT '原价';");
}
if(!pdo_fieldexists('xc_beauty_service',  'kind')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `kind` longtext COMMENT '种类';");
}
if(!pdo_fieldexists('xc_beauty_service',  'discuss')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `discuss` int(11) DEFAULT '0' COMMENT '评价总人数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'discuss_total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `discuss_total` int(11) DEFAULT '0' COMMENT '评价总数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'good_total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `good_total` int(11) DEFAULT '0' COMMENT '满意总数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'middle_total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `middle_total` int(11) DEFAULT '0' COMMENT '一般总数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'bad_total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `bad_total` int(11) DEFAULT '0' COMMENT '不满意总数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `content` longtext COMMENT '详情';");
}
if(!pdo_fieldexists('xc_beauty_service',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_service',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_service',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_status` int(11) DEFAULT '-1' COMMENT '团购状态';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_price` varchar(50) DEFAULT NULL COMMENT '团购价格';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_total` int(11) DEFAULT '0' COMMENT '团购数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_limit')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_limit` int(11) DEFAULT NULL COMMENT '团购限制天数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_index')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_index` int(11) DEFAULT '-1' COMMENT '团购首页显示';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_number')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_number` int(11) DEFAULT NULL COMMENT '团购人数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `type` int(11) DEFAULT NULL COMMENT '分销方法';");
}
if(!pdo_fieldexists('xc_beauty_service',  'level_one')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `level_one` varchar(50) DEFAULT NULL COMMENT '一级分销';");
}
if(!pdo_fieldexists('xc_beauty_service',  'level_two')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `level_two` varchar(50) DEFAULT NULL COMMENT '二级分销';");
}
if(!pdo_fieldexists('xc_beauty_service',  'level_three')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `level_three` varchar(50) DEFAULT NULL COMMENT '三级分销';");
}
if(!pdo_fieldexists('xc_beauty_service',  'store_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `store_status` int(11) DEFAULT '1' COMMENT '门店状态';");
}
if(!pdo_fieldexists('xc_beauty_service',  'store')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `store` longtext COMMENT '适用门店';");
}
if(!pdo_fieldexists('xc_beauty_service',  'home')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `home` int(11) DEFAULT '1' COMMENT '家';");
}
if(!pdo_fieldexists('xc_beauty_service',  'shop')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `shop` int(11) DEFAULT '1' COMMENT '店';");
}
if(!pdo_fieldexists('xc_beauty_service',  'service_time')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `service_time` varchar(50) DEFAULT NULL COMMENT '服务时间';");
}
if(!pdo_fieldexists('xc_beauty_service',  'can_use')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `can_use` int(11) DEFAULT '1' COMMENT '核销次数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'sold')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `sold` int(11) DEFAULT '0' COMMENT '已售数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'content_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `content_type` int(11) DEFAULT '1' COMMENT '详情模式';");
}
if(!pdo_fieldexists('xc_beauty_service',  'content2')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `content2` longtext COMMENT '详情2';");
}
if(!pdo_fieldexists('xc_beauty_service',  'parameter')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `parameter` longtext COMMENT '参数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_status` int(11) DEFAULT '-1' COMMENT '限时状态';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_price` decimal(10,2) DEFAULT '0.00' COMMENT '抢购价格';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_start')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_start` datetime DEFAULT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_end')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_end` datetime DEFAULT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_member` int(11) DEFAULT NULL COMMENT '库存';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_index')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_index` int(11) DEFAULT '-1' COMMENT '首页显示';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_order')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_order` int(11) DEFAULT '0' COMMENT '每人限买单数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'flash_shu')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_shu` int(11) DEFAULT '0' COMMENT '每单限购数';");
}
if(!pdo_fieldexists('xc_beauty_service',  'sale_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `sale_status` int(11) DEFAULT '-1' COMMENT '折扣状态';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_stock')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_stock` int(11) DEFAULT '-1' COMMENT '团购库存';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_head_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_head_status` int(11) DEFAULT '-1' COMMENT '团长优惠';");
}
if(!pdo_fieldexists('xc_beauty_service',  'group_head_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_head_price` varchar(50) DEFAULT NULL COMMENT '团长优惠价格';");
}
if(!pdo_indexexists('xc_beauty_service',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD KEY `uniacid` (`uniacid`,`cid`,`status`,`sort`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'bimg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `bimg` varchar(255) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_service_class',  'index')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD `index` int(11) DEFAULT '-1' COMMENT '首页显示';");
}
if(!pdo_indexexists('xc_beauty_service_class',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_service_class')." ADD KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_share',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `title` varchar(255) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xc_beauty_share',  'out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('xc_beauty_share',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `amount` varchar(50) DEFAULT NULL COMMENT '金额';");
}
if(!pdo_fieldexists('xc_beauty_share',  'level')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `level` int(11) DEFAULT NULL COMMENT '等级';");
}
if(!pdo_fieldexists('xc_beauty_share',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `status` int(11) DEFAULT '1' COMMENT '状态（-1等待1成功2失败）';");
}
if(!pdo_fieldexists('xc_beauty_share',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_share')." ADD KEY `uniacid` (`uniacid`,`openid`,`createtime`,`status`);");
}
if(!pdo_fieldexists('xc_beauty_shop',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_shop',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_shop',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_shop',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `pid` int(11) DEFAULT NULL COMMENT '产品id';");
}
if(!pdo_fieldexists('xc_beauty_shop',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `total` int(11) DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('xc_beauty_shop',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_shop',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_shop',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_shop')." ADD KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_store',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_store',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_store',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `name` varchar(255) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_store',  'simg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `simg` varchar(255) DEFAULT NULL COMMENT '图标';");
}
if(!pdo_fieldexists('xc_beauty_store',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `mobile` varchar(50) DEFAULT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('xc_beauty_store',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `address` longtext COMMENT '地址';");
}
if(!pdo_fieldexists('xc_beauty_store',  'map')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `map` longtext COMMENT '地址';");
}
if(!pdo_fieldexists('xc_beauty_store',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_store',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_store',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_store',  'plan_date')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `plan_date` varchar(50) DEFAULT NULL COMMENT '营业时间';");
}
if(!pdo_fieldexists('xc_beauty_store',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `content` longtext COMMENT '详情';");
}
if(!pdo_fieldexists('xc_beauty_store',  'code')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `code` varchar(255) DEFAULT NULL COMMENT '买单二维码';");
}
if(!pdo_fieldexists('xc_beauty_store',  'sms')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `sms` varchar(50) DEFAULT NULL COMMENT '短信接收手机号';");
}
if(!pdo_fieldexists('xc_beauty_store',  'machine_code')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `machine_code` varchar(255) DEFAULT NULL COMMENT '打印机终端号';");
}
if(!pdo_fieldexists('xc_beauty_store',  'msign')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `msign` varchar(255) DEFAULT NULL COMMENT '打印机终端密钥';");
}
if(!pdo_fieldexists('xc_beauty_store',  'sn')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `sn` varchar(255) DEFAULT NULL COMMENT '打印机编号';");
}
if(!pdo_fieldexists('xc_beauty_store',  'print_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `print_status` int(11) DEFAULT '-1' COMMENT '打印状态';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `name` varchar(50) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'simg')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `simg` varchar(255) DEFAULT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `cid` int(11) DEFAULT '0' COMMENT '店面';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'task')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `task` varchar(255) DEFAULT NULL COMMENT '职称';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'service')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `service` longtext COMMENT '服务项目';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `tag` varchar(255) DEFAULT NULL COMMENT '标签';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `content` longtext COMMENT '个人简介';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'zan')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `zan` int(11) DEFAULT '0' COMMENT '点赞人数';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'discuss')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `discuss` int(11) DEFAULT '0' COMMENT '评论数';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'pai_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `pai_status` int(11) DEFAULT '-1' COMMENT '单双周状态';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'pai1')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `pai1` int(11) DEFAULT NULL COMMENT '排版';");
}
if(!pdo_fieldexists('xc_beauty_store_member',  'pai2')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `pai2` int(11) DEFAULT NULL COMMENT '排版2';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `name` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `price` varchar(50) DEFAULT NULL COMMENT '价格';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `sort` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'home')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `home` int(11) DEFAULT '1' COMMENT '上门服务';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'shop')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `shop` int(11) DEFAULT '1' COMMENT '到店服务';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'can_use')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `can_use` int(11) DEFAULT '1' COMMENT '核销次数';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `member` varchar(50) DEFAULT NULL COMMENT '抵扣预约人数';");
}
if(!pdo_fieldexists('xc_beauty_store_service',  'sale_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `sale_status` int(11) DEFAULT '-1' COMMENT '折扣状态';");
}
if(!pdo_indexexists('xc_beauty_store_service',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_times',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_times',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_times',  'week')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD `week` int(11) DEFAULT NULL COMMENT '星期';");
}
if(!pdo_fieldexists('xc_beauty_times',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD `content` longtext COMMENT '详情';");
}
if(!pdo_fieldexists('xc_beauty_times',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_times',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_times',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times')." ADD KEY `uniacid` (`uniacid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_times_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_times_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_times_log',  'member')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD `member` int(11) DEFAULT NULL COMMENT '人员';");
}
if(!pdo_fieldexists('xc_beauty_times_log',  'plan_date')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD `plan_date` varchar(50) DEFAULT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('xc_beauty_times_log',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD `total` int(11) DEFAULT '0' COMMENT '已预约数量';");
}
if(!pdo_fieldexists('xc_beauty_times_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_times_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_times_log')." ADD KEY `uniacid` (`uniacid`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_user_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_user_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_user_coupon',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_user_coupon',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD `cid` int(11) DEFAULT NULL COMMENT '优惠券id';");
}
if(!pdo_fieldexists('xc_beauty_user_coupon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD `status` int(11) DEFAULT '-1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_user_coupon',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_user_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_user_coupon')." ADD KEY `uniacid` (`uniacid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `avatar` varchar(255) DEFAULT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'nick')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `nick` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `status` int(11) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `money` varchar(50) DEFAULT '0' COMMENT '余额';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'card')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card` int(11) DEFAULT '-1' COMMENT '会员卡状态（-1未激活1激活）';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `mobile` varchar(50) DEFAULT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'password')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `password` varchar(50) DEFAULT NULL COMMENT '支付密码';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `name` varchar(50) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'score')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `score` int(11) DEFAULT '0' COMMENT '积分';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'share')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `share` varchar(50) DEFAULT NULL COMMENT '推荐人openid';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'level_one')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `level_one` int(11) DEFAULT '0' COMMENT '一级数量';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'level_two')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `level_two` int(11) DEFAULT '0' COMMENT '二级数量';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'level_three')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `level_three` int(11) DEFAULT '0' COMMENT '三级数量';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'share_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `share_amount` varchar(50) DEFAULT '0' COMMENT '累计佣金';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'share_o_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `share_o_amount` varchar(50) DEFAULT '0' COMMENT '可提现佣金';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'share_t_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `share_t_amount` varchar(50) DEFAULT '0' COMMENT '已提现佣金';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'share_empty')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `share_empty` varchar(50) DEFAULT '0' COMMENT '无效佣金';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'shop')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `shop` int(11) DEFAULT '-1' COMMENT '店铺管理';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'store')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `store` int(11) DEFAULT NULL COMMENT '绑定门店';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'shop_id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `shop_id` int(11) DEFAULT NULL COMMENT '子管理员门店id';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'card_name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card_name` varchar(50) DEFAULT NULL COMMENT '会员等级';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'card_price')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card_price` varchar(50) DEFAULT NULL COMMENT '会员折扣';");
}
if(!pdo_fieldexists('xc_beauty_userinfo',  'card_amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card_amount` decimal(10,2) DEFAULT '0.00' COMMENT '消费金额';");
}
if(!pdo_indexexists('xc_beauty_userinfo',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD KEY `uniacid` (`uniacid`,`status`,`createtime`);");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `pay_type` int(11) DEFAULT '1' COMMENT '提现方式（1微信2支付宝）';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'username')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `username` varchar(50) DEFAULT NULL COMMENT '账号';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `mobile` varchar(50) DEFAULT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `name` varchar(50) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `amount` varchar(50) DEFAULT NULL COMMENT '金额';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `money` varchar(50) DEFAULT NULL COMMENT '余额';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `status` int(11) DEFAULT '-1' COMMENT '状态（-1待处理1成功2失败）';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'order_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `order_type` int(11) DEFAULT '1' COMMENT '提现类型(1余额提现2佣金提现)';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'wx_out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `wx_out_trade_no` varchar(50) DEFAULT NULL COMMENT '微信订单号';");
}
if(!pdo_fieldexists('xc_beauty_withdraw',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `content` longtext COMMENT '错误详情';");
}
if(!pdo_indexexists('xc_beauty_withdraw',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD KEY `uniacid` (`uniacid`,`openid`,`createtime`,`status`);");
}
if(!pdo_fieldexists('xc_beauty_zan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_beauty_zan',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xc_beauty_zan',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('xc_beauty_zan',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD `pid` int(11) DEFAULT NULL COMMENT '技师id';");
}
if(!pdo_fieldexists('xc_beauty_zan',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD `status` int(11) DEFAULT '-1' COMMENT '状态';");
}
if(!pdo_fieldexists('xc_beauty_zan',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间';");
}
if(!pdo_indexexists('xc_beauty_zan',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xc_beauty_zan')." ADD KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`);");
}

?>