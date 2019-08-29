<?php 
$sql="CREATE TABLE IF NOT EXISTS `ims_fx_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) NOT NULL,
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `title` varchar(255) NOT NULL COMMENT '活动标题',
  `pagetitle` varchar(50) NOT NULL COMMENT '标题名称',
  `freetitle` varchar(32) NOT NULL COMMENT '免费活动标题',
  `aprice` decimal(10,2) NOT NULL COMMENT '活动金额',
  `marketprice` decimal(10,2) NOT NULL COMMENT '年卡价格',
  `sharetitle` varchar(200) NOT NULL COMMENT '分享标题',
  `sharepic` varchar(255) NOT NULL COMMENT '分享图片',
  `sharedesc` varchar(255) NOT NULL COMMENT '分享描述',
  `kefu` text NOT NULL COMMENT '客服信息',
  `unitintro` text NOT NULL COMMENT '主办单位介绍',
  `tel` varchar(13) NOT NULL COMMENT '联系电话',
  `intro` text NOT NULL COMMENT '简要概述',
  `detail` mediumtext NOT NULL COMMENT '详细内容',
  `starttime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '开始时间',
  `endtime` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `joinstime` timestamp NULL DEFAULT NULL COMMENT '报名开始时间',
  `joinetime` timestamp NULL DEFAULT NULL COMMENT '报名结束时间',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `atlas` text NOT NULL COMMENT '图集',
  `quota` int(11) NOT NULL COMMENT '活动名额',
  `lng` varchar(255) NOT NULL COMMENT '经度',
  `lat` varchar(255) NOT NULL COMMENT '纬度',
  `adinfo` varchar(32) DEFAULT NULL,
  `addname` varchar(50) NOT NULL COMMENT '地点名称',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `prize` text NOT NULL COMMENT '奖励设置',
  `form` text NOT NULL COMMENT '固定表单',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `teamquota` int(11) NOT NULL COMMENT '团队名额数',
  `team` tinyint(2) NOT NULL DEFAULT '0',
  `hasoption` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启规格',
  `hasreview` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启报名审核',
  `show` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `midkey` varchar(50) NOT NULL COMMENT '商家二维码key',
  `smsnotify` varchar(50) NOT NULL COMMENT '短信通知模板ID',
  `smsswitch` tinyint(2) NOT NULL DEFAULT '0' COMMENT '短信开关',
  `hascancel` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启取消报名',
  `hasavatar` tinyint(2) NOT NULL DEFAULT '1' COMMENT '开启用户头像列表显示',
  `falsenum` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟名额',
  `falsename` text NOT NULL COMMENT '虚拟昵称',
  `falsehead` text NOT NULL COMMENT '虚拟头像',
  `falseread` int(11) NOT NULL COMMENT '虚拟阅读量',
  `falseshare` int(11) NOT NULL COMMENT '虚拟转发',
  `trueread` int(11) NOT NULL DEFAULT '0' COMMENT '真实阅读量',
  `trueshare` int(11) NOT NULL DEFAULT '0' COMMENT '真实转发量',
  `parentid` int(11) NOT NULL COMMENT '一级分类ID',
  `childid` int(11) NOT NULL COMMENT '二级分类IDID',
  `recommend` tinyint(2) NOT NULL DEFAULT '0' COMMENT '推荐',
  `hassignin` tinyint(2) NOT NULL DEFAULT '0' COMMENT '签到开关',
  `viewauth` tinyint(2) NOT NULL DEFAULT '0' COMMENT '访问权限:0公开1私密2粉丝可见',
  `review` tinyint(2) NOT NULL DEFAULT '1' COMMENT '发布审核状态：0为待审核，1为已审核',
  `showjoin` tinyint(2) NOT NULL DEFAULT '1' COMMENT '显示报名人数:0为不显示，1为显示',
  `openids` text NOT NULL COMMENT '管理员openid',
  `tmplmsg` text NOT NULL COMMENT '自定义消息标题备注',
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `storeids` text COMMENT '门店ID',
  `hasstore` tinyint(2) NOT NULL DEFAULT '0' COMMENT '位置自定义开启',
  `atype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '活动类型',
  `hasjoin` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否允许重复报名',
  `agreement` text NOT NULL COMMENT '报名协议',
  `info` text NOT NULL COMMENT '报名须知',
  `falsedata` text NOT NULL COMMENT '虚拟数据',
  `hasonline` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否线上活动',
  `unitstr` varchar(32) NOT NULL COMMENT '库存单位',
  `quotashow` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否显示库存',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_activity_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `activityid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_activity_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `activityid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '1',
  `favo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_activity_records` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT '公众号ID',
  `openid` varchar(255) NOT NULL COMMENT '微信用户id',
  `activityid` int(11) NOT NULL COMMENT '活动ID',
  `teamnum` int(11) NOT NULL DEFAULT '1' COMMENT '团队人数',
  `orderno` varchar(50) NOT NULL COMMENT '订单编号',
  `price` varchar(45) NOT NULL COMMENT '应付金额',
  `aprice` varchar(45) NOT NULL COMMENT '活动金额',
  `freight` varchar(45) NOT NULL COMMENT '运费',
  `vipdeduct` varchar(45) NOT NULL COMMENT '高级vip优惠金额',
  `paytime` timestamp NULL DEFAULT NULL COMMENT '支付成功时间',
  `uniontid` varchar(50) NOT NULL COMMENT '商户单号',
  `transid` varchar(50) NOT NULL COMMENT '交易单号',
  `remark` varchar(1024) NOT NULL COMMENT '备注',
  `payprice` varchar(45) NOT NULL COMMENT '实付金额',
  `paytype` varchar(45) NOT NULL COMMENT '支付方式',
  `nickname` varchar(255) NOT NULL COMMENT '用户昵称',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '手机',
  `gender` varchar(10) NOT NULL COMMENT '性别',
  `pic` varchar(255) NOT NULL COMMENT '照片',
  `headimgurl` varchar(255) NOT NULL COMMENT '粉丝头像',
  `msg` text NOT NULL COMMENT '留言',
  `jointime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '报名时间',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '报名状态：0未支付,1支付，2免费，3已消费，4已签收，5已取消，6待退款，7已退款',
  `ishexiao` int(2) NOT NULL DEFAULT '0' COMMENT '0未核销1已核销',
  `hexiaoma` varchar(50) NOT NULL COMMENT '核销码',
  `sendtime` timestamp NULL DEFAULT NULL COMMENT '核销时间',
  `veropenid` varchar(200) NOT NULL COMMENT '核销员onpenid',
  `specs` text NOT NULL,
  `specitems` text NOT NULL,
  `operation` varchar(50) NOT NULL COMMENT '操作员',
  `optionid` int(11) NOT NULL COMMENT '规格ID',
  `optionname` varchar(200) NOT NULL COMMENT '规格名称',
  `review` tinyint(2) NOT NULL DEFAULT '1' COMMENT '审核状态：0为待审核，1为已审核',
  `signin` int(11) NOT NULL DEFAULT '0' COMMENT '签到',
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `storeid` int(11) NOT NULL COMMENT '门店ID',
  `marketing` text NOT NULL COMMENT '优惠数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `advname` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `visible_level` int(11) NOT NULL,
  `open` int(11) DEFAULT '0',
  `color` varchar(10) NOT NULL COMMENT '颜色',
  `redirect` varchar(255) NOT NULL COMMENT '跳转连接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` varchar(3) NOT NULL,
  `content` text NOT NULL,
  `activityid` int(11) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `essential` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fieldstype` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_form_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `activityid` int(11) NOT NULL,
  `recordid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_form_data_common` (
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `realname` varchar(10) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `birthyear` smallint(6) unsigned NOT NULL,
  `birthmonth` tinyint(3) unsigned NOT NULL,
  `birthday` tinyint(3) unsigned NOT NULL,
  `constellation` varchar(10) NOT NULL,
  `zodiac` varchar(5) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `idcard` varchar(30) NOT NULL,
  `studentid` varchar(50) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `nationality` varchar(30) NOT NULL,
  `resideprovince` varchar(30) NOT NULL,
  `residecity` varchar(30) NOT NULL,
  `residedist` varchar(30) NOT NULL,
  `graduateschool` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `education` varchar(10) NOT NULL,
  `occupation` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `revenue` varchar(10) NOT NULL,
  `affectivestatus` varchar(30) NOT NULL,
  `lookingfor` varchar(255) NOT NULL,
  `bloodtype` varchar(5) NOT NULL,
  `height` varchar(5) NOT NULL,
  `weight` varchar(5) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `msn` varchar(30) NOT NULL,
  `taobao` varchar(30) NOT NULL,
  `site` varchar(30) NOT NULL,
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  `age` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_form_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`),
  KEY `indx_specid` (`formid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_marketing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `activityid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1折扣2满减3抵扣',
  `value` text COMMENT '设置的值',
  PRIMARY KEY (`id`),
  KEY `aid` (`activityid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_member_blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `logo` varchar(225) NOT NULL,
  `industry` varchar(45) NOT NULL,
  `address` varchar(115) NOT NULL,
  `linkman_name` varchar(145) NOT NULL,
  `linkman_mobile` varchar(145) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `createtime` varchar(115) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `detail` varchar(1222) NOT NULL,
  `salenum` int(11) NOT NULL COMMENT '商家销量',
  `open` int(11) NOT NULL COMMENT '是否分配商家权限',
  `uname` varchar(45) NOT NULL,
  `password` varchar(145) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `messageopenid` text NOT NULL,
  `openid` varchar(150) NOT NULL,
  `goodsnum` int(11) NOT NULL,
  `percent` varchar(100) NOT NULL,
  `allsalenum` int(11) DEFAULT NULL,
  `falsenum` int(11) DEFAULT NULL,
  `tag` text,
  `storename` varchar(50) DEFAULT NULL,
  `lng` varchar(145) DEFAULT NULL,
  `lat` varchar(145) DEFAULT NULL,
  `adinfo` varchar(32) DEFAULT NULL,
  `kefuimg` varchar(225) DEFAULT NULL,
  `follownum` int(11) NOT NULL DEFAULT '0' COMMENT '关注量',
  `followno` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟关注量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_merchant_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `uniacid` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `amount` decimal(10,2) NOT NULL COMMENT '交易总金额',
  `updatetime` varchar(45) NOT NULL COMMENT '上次结算时间',
  `no_money` decimal(10,2) NOT NULL COMMENT '目前未结算金额',
  `no_money_doing` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_merchant_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `follow` tinyint(1) NOT NULL DEFAULT '0',
  `createtime` varchar(115) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_merchant_money_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `merchantid` int(11) DEFAULT NULL COMMENT '商家ID',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '变动金额',
  `createtime` varchar(145) DEFAULT NULL COMMENT '变动时间',
  `recordsid` int(11) DEFAULT NULL COMMENT '订单ID',
  `type` int(11) DEFAULT NULL COMMENT '1线下支付2核销成功纳入可结算金额3取消核销4商家结算5退款',
  `detail` text COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_merchant_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家id',
  `money` varchar(45) NOT NULL COMMENT '本次结算金额',
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `createtime` varchar(45) NOT NULL COMMENT '结算时间',
  `uniacid` int(11) NOT NULL,
  `orderno` varchar(100) DEFAULT NULL,
  `commission` varchar(100) DEFAULT NULL,
  `percent` varchar(100) DEFAULT NULL,
  `get_money` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `updatetime` varchar(145) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_refund_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1手机端2Web端3最后一人退款4部分退款',
  `activityid` int(11) NOT NULL COMMENT '商品ID',
  `payfee` varchar(100) NOT NULL COMMENT '支付金额',
  `refundfee` varchar(100) NOT NULL COMMENT '退还金额',
  `transid` varchar(115) NOT NULL COMMENT '订单编号',
  `refund_id` varchar(115) NOT NULL COMMENT '微信退款单号',
  `refundername` varchar(100) NOT NULL COMMENT '退款人姓名',
  `refundermobile` varchar(100) NOT NULL COMMENT '退款人电话',
  `activityname` varchar(100) NOT NULL COMMENT '商品名称',
  `createtime` varchar(45) NOT NULL COMMENT '退款时间',
  `status` int(11) NOT NULL COMMENT '0未成功1成功',
  `uniacid` int(11) NOT NULL,
  `recordid` varchar(45) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) NOT NULL DEFAULT '',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` text NOT NULL,
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `merchantid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `activityid` int(11) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `essential` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_spec_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activityid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `aprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `quota` int(11) DEFAULT '0',
  `falsenum` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`activityid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `adinfo` varchar(32) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `createtime` varchar(45) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `storehours` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_user_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL,
  `do` varchar(255) NOT NULL,
  `ac` varchar(32) DEFAULT NULL,
  `op` varchar(32) DEFAULT NULL,
  `ac_id` int(11) DEFAULT NULL,
  `do_id` int(6) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `flag` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`do_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL,
  `nodes` text NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_yearcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '年卡名称',
  `value` decimal(10,2) NOT NULL COMMENT '面值',
  `value_first` decimal(10,2) NOT NULL COMMENT '首次激活面值',
  `is_first_num` tinyint(3) unsigned NOT NULL COMMENT '首次激活允许累计',
  `thumb` varchar(225) NOT NULL COMMENT '年封面图片',
  `credit` int(11) unsigned NOT NULL COMMENT '赠送积分/年',
  `description` text NOT NULL COMMENT '专属特权',
  `detail` text NOT NULL COMMENT '使用须知',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_yearcard_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `realname` varchar(100) NOT NULL,
  `gender` tinyint(1) unsigned NOT NULL,
  `relation` varchar(32) NOT NULL,
  `birthyear` smallint(6) unsigned NOT NULL,
  `birthmonth` tinyint(3) NOT NULL,
  `birthday` tinyint(3) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fx_yearcard_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `buyuid` int(10) unsigned NOT NULL COMMENT '开卡用户ID',
  `openid` varchar(100) NOT NULL,
  `fid` int(11) unsigned NOT NULL COMMENT '子用户ID',
  `cid` int(10) unsigned NOT NULL COMMENT '年卡ID',
  `value` decimal(10,2) NOT NULL COMMENT '面值',
  `value_first` decimal(10,2) NOT NULL COMMENT '首次激活面值',
  `fee` decimal(10,2) NOT NULL COMMENT '支付金额',
  `pay_fee` decimal(10,2) NOT NULL,
  `orderno` varchar(50) NOT NULL COMMENT '支付订单号',
  `buynum` int(10) NOT NULL DEFAULT '0' COMMENT '一次购买购买数量',
  `is_first` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是首次激活',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0未支付，1已支付，2已退款',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用：1启用，2禁用',
  `createtime` int(11) unsigned NOT NULL COMMENT '最后修改时间',
  `end_time` int(11) unsigned NOT NULL COMMENT '到期时间',
  `cycletype` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '周期：1周，2月，3年',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists("fx_activity", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists("fx_activity", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists("fx_activity", "title")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `title` varchar(255) NOT NULL COMMENT '活动标题';");
}
if(!pdo_fieldexists("fx_activity", "pagetitle")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `pagetitle` varchar(50) NOT NULL COMMENT '标题名称';");
}
if(!pdo_fieldexists("fx_activity", "freetitle")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `freetitle` varchar(32) NOT NULL COMMENT '免费活动标题';");
}
if(!pdo_fieldexists("fx_activity", "aprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `aprice` decimal(10;");
}
if(!pdo_fieldexists("fx_activity", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD 2) NOT NULL COMMENT '活动金额';");
}
if(!pdo_fieldexists("fx_activity", "marketprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `marketprice` decimal(10;");
}
if(!pdo_fieldexists("fx_activity", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD 2) NOT NULL COMMENT '年卡价格';");
}
if(!pdo_fieldexists("fx_activity", "sharetitle")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `sharetitle` varchar(200) NOT NULL COMMENT '分享标题';");
}
if(!pdo_fieldexists("fx_activity", "sharepic")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `sharepic` varchar(255) NOT NULL COMMENT '分享图片';");
}
if(!pdo_fieldexists("fx_activity", "sharedesc")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `sharedesc` varchar(255) NOT NULL COMMENT '分享描述';");
}
if(!pdo_fieldexists("fx_activity", "kefu")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `kefu` text NOT NULL COMMENT '客服信息';");
}
if(!pdo_fieldexists("fx_activity", "unitintro")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `unitintro` text NOT NULL COMMENT '主办单位介绍';");
}
if(!pdo_fieldexists("fx_activity", "tel")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `tel` varchar(13) NOT NULL COMMENT '联系电话';");
}
if(!pdo_fieldexists("fx_activity", "intro")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `intro` text NOT NULL COMMENT '简要概述';");
}
if(!pdo_fieldexists("fx_activity", "detail")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `detail` mediumtext NOT NULL COMMENT '详细内容';");
}
if(!pdo_fieldexists("fx_activity", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `starttime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '开始时间';");
}
if(!pdo_fieldexists("fx_activity", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `endtime` timestamp NULL DEFAULT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists("fx_activity", "joinstime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `joinstime` timestamp NULL DEFAULT NULL COMMENT '报名开始时间';");
}
if(!pdo_fieldexists("fx_activity", "joinetime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `joinetime` timestamp NULL DEFAULT NULL COMMENT '报名结束时间';");
}
if(!pdo_fieldexists("fx_activity", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `thumb` varchar(255) NOT NULL COMMENT '缩略图';");
}
if(!pdo_fieldexists("fx_activity", "atlas")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `atlas` text NOT NULL COMMENT '图集';");
}
if(!pdo_fieldexists("fx_activity", "quota")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `quota` int(11) NOT NULL COMMENT '活动名额';");
}
if(!pdo_fieldexists("fx_activity", "lng")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `lng` varchar(255) NOT NULL COMMENT '经度';");
}
if(!pdo_fieldexists("fx_activity", "lat")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `lat` varchar(255) NOT NULL COMMENT '纬度';");
}
if(!pdo_fieldexists("fx_activity", "adinfo")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `adinfo` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_activity", "addname")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `addname` varchar(50) NOT NULL COMMENT '地点名称';");
}
if(!pdo_fieldexists("fx_activity", "address")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `address` varchar(255) NOT NULL COMMENT '详细地址';");
}
if(!pdo_fieldexists("fx_activity", "prize")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `prize` text NOT NULL COMMENT '奖励设置';");
}
if(!pdo_fieldexists("fx_activity", "form")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `form` text NOT NULL COMMENT '固定表单';");
}
if(!pdo_fieldexists("fx_activity", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("fx_activity", "teamquota")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `teamquota` int(11) NOT NULL COMMENT '团队名额数';");
}
if(!pdo_fieldexists("fx_activity", "team")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `team` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_activity", "hasoption")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hasoption` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启规格';");
}
if(!pdo_fieldexists("fx_activity", "hasreview")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hasreview` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启报名审核';");
}
if(!pdo_fieldexists("fx_activity", "show")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `show` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists("fx_activity", "midkey")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `midkey` varchar(50) NOT NULL COMMENT '商家二维码key';");
}
if(!pdo_fieldexists("fx_activity", "smsnotify")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `smsnotify` varchar(50) NOT NULL COMMENT '短信通知模板ID';");
}
if(!pdo_fieldexists("fx_activity", "smsswitch")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `smsswitch` tinyint(2) NOT NULL DEFAULT '0' COMMENT '短信开关';");
}
if(!pdo_fieldexists("fx_activity", "hascancel")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hascancel` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启取消报名';");
}
if(!pdo_fieldexists("fx_activity", "hasavatar")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hasavatar` tinyint(2) NOT NULL DEFAULT '1' COMMENT '开启用户头像列表显示';");
}
if(!pdo_fieldexists("fx_activity", "falsenum")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `falsenum` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟名额';");
}
if(!pdo_fieldexists("fx_activity", "falsename")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `falsename` text NOT NULL COMMENT '虚拟昵称';");
}
if(!pdo_fieldexists("fx_activity", "falsehead")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `falsehead` text NOT NULL COMMENT '虚拟头像';");
}
if(!pdo_fieldexists("fx_activity", "falseread")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `falseread` int(11) NOT NULL COMMENT '虚拟阅读量';");
}
if(!pdo_fieldexists("fx_activity", "falseshare")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `falseshare` int(11) NOT NULL COMMENT '虚拟转发';");
}
if(!pdo_fieldexists("fx_activity", "trueread")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `trueread` int(11) NOT NULL DEFAULT '0' COMMENT '真实阅读量';");
}
if(!pdo_fieldexists("fx_activity", "trueshare")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `trueshare` int(11) NOT NULL DEFAULT '0' COMMENT '真实转发量';");
}
if(!pdo_fieldexists("fx_activity", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `parentid` int(11) NOT NULL COMMENT '一级分类ID';");
}
if(!pdo_fieldexists("fx_activity", "childid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `childid` int(11) NOT NULL COMMENT '二级分类IDID';");
}
if(!pdo_fieldexists("fx_activity", "recommend")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `recommend` tinyint(2) NOT NULL DEFAULT '0' COMMENT '推荐';");
}
if(!pdo_fieldexists("fx_activity", "hassignin")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hassignin` tinyint(2) NOT NULL DEFAULT '0' COMMENT '签到开关';");
}
if(!pdo_fieldexists("fx_activity", "viewauth")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `viewauth` tinyint(2) NOT NULL DEFAULT '0' COMMENT '访问权限:0公开1私密2粉丝可见';");
}
if(!pdo_fieldexists("fx_activity", "review")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `review` tinyint(2) NOT NULL DEFAULT '1' COMMENT '发布审核状态：0为待审核，1为已审核';");
}
if(!pdo_fieldexists("fx_activity", "showjoin")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `showjoin` tinyint(2) NOT NULL DEFAULT '1' COMMENT '显示报名人数:0为不显示，1为显示';");
}
if(!pdo_fieldexists("fx_activity", "openids")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `openids` text NOT NULL COMMENT '管理员openid';");
}
if(!pdo_fieldexists("fx_activity", "tmplmsg")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `tmplmsg` text NOT NULL COMMENT '自定义消息标题备注';");
}
if(!pdo_fieldexists("fx_activity", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `merchantid` int(11) NOT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists("fx_activity", "storeids")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `storeids` text COMMENT '门店ID';");
}
if(!pdo_fieldexists("fx_activity", "hasstore")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hasstore` tinyint(2) NOT NULL DEFAULT '0' COMMENT '位置自定义开启';");
}
if(!pdo_fieldexists("fx_activity", "atype")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `atype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '活动类型';");
}
if(!pdo_fieldexists("fx_activity", "hasjoin")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hasjoin` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否允许重复报名';");
}
if(!pdo_fieldexists("fx_activity", "agreement")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `agreement` text NOT NULL COMMENT '报名协议';");
}
if(!pdo_fieldexists("fx_activity", "info")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `info` text NOT NULL COMMENT '报名须知';");
}
if(!pdo_fieldexists("fx_activity", "falsedata")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `falsedata` text NOT NULL COMMENT '虚拟数据';");
}
if(!pdo_fieldexists("fx_activity", "hasonline")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `hasonline` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否线上活动';");
}
if(!pdo_fieldexists("fx_activity", "unitstr")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `unitstr` varchar(32) NOT NULL COMMENT '库存单位';");
}
if(!pdo_fieldexists("fx_activity", "quotashow")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity")." ADD   `quotashow` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否显示库存';");
}
if(!pdo_fieldexists("fx_activity_collect", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_collect")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_activity_collect", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_collect")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_collect", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_collect")." ADD   `activityid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_collect", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_collect")." ADD   `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_favorite", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_favorite")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_activity_favorite", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_favorite")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_favorite", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_favorite")." ADD   `activityid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_favorite", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_favorite")." ADD   `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_favorite", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_favorite")." ADD   `uid` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fx_activity_favorite", "favo")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_favorite")." ADD   `favo` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_activity_records", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists("fx_activity_records", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_records", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `uniacid` int(11) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists("fx_activity_records", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `openid` varchar(255) NOT NULL COMMENT '微信用户id';");
}
if(!pdo_fieldexists("fx_activity_records", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `activityid` int(11) NOT NULL COMMENT '活动ID';");
}
if(!pdo_fieldexists("fx_activity_records", "teamnum")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `teamnum` int(11) NOT NULL DEFAULT '1' COMMENT '团队人数';");
}
if(!pdo_fieldexists("fx_activity_records", "orderno")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `orderno` varchar(50) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists("fx_activity_records", "price")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `price` varchar(45) NOT NULL COMMENT '应付金额';");
}
if(!pdo_fieldexists("fx_activity_records", "aprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `aprice` varchar(45) NOT NULL COMMENT '活动金额';");
}
if(!pdo_fieldexists("fx_activity_records", "freight")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `freight` varchar(45) NOT NULL COMMENT '运费';");
}
if(!pdo_fieldexists("fx_activity_records", "vipdeduct")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `vipdeduct` varchar(45) NOT NULL COMMENT '高级vip优惠金额';");
}
if(!pdo_fieldexists("fx_activity_records", "paytime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `paytime` timestamp NULL DEFAULT NULL COMMENT '支付成功时间';");
}
if(!pdo_fieldexists("fx_activity_records", "uniontid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `uniontid` varchar(50) NOT NULL COMMENT '商户单号';");
}
if(!pdo_fieldexists("fx_activity_records", "transid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `transid` varchar(50) NOT NULL COMMENT '交易单号';");
}
if(!pdo_fieldexists("fx_activity_records", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `remark` varchar(1024) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists("fx_activity_records", "payprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `payprice` varchar(45) NOT NULL COMMENT '实付金额';");
}
if(!pdo_fieldexists("fx_activity_records", "paytype")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `paytype` varchar(45) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists("fx_activity_records", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `nickname` varchar(255) NOT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists("fx_activity_records", "realname")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists("fx_activity_records", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机';");
}
if(!pdo_fieldexists("fx_activity_records", "gender")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `gender` varchar(10) NOT NULL COMMENT '性别';");
}
if(!pdo_fieldexists("fx_activity_records", "pic")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `pic` varchar(255) NOT NULL COMMENT '照片';");
}
if(!pdo_fieldexists("fx_activity_records", "headimgurl")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `headimgurl` varchar(255) NOT NULL COMMENT '粉丝头像';");
}
if(!pdo_fieldexists("fx_activity_records", "msg")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `msg` text NOT NULL COMMENT '留言';");
}
if(!pdo_fieldexists("fx_activity_records", "jointime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `jointime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '报名时间';");
}
if(!pdo_fieldexists("fx_activity_records", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `status` int(2) NOT NULL DEFAULT '0' COMMENT '报名状态：0未支付;");
}
if(!pdo_fieldexists("fx_activity_records", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD 1支付，2免费，3已消费，4已签收，5已取消，6待退款，7已退款';");
}
if(!pdo_fieldexists("fx_activity_records", "ishexiao")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `ishexiao` int(2) NOT NULL DEFAULT '0' COMMENT '0未核销1已核销';");
}
if(!pdo_fieldexists("fx_activity_records", "hexiaoma")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `hexiaoma` varchar(50) NOT NULL COMMENT '核销码';");
}
if(!pdo_fieldexists("fx_activity_records", "sendtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `sendtime` timestamp NULL DEFAULT NULL COMMENT '核销时间';");
}
if(!pdo_fieldexists("fx_activity_records", "veropenid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `veropenid` varchar(200) NOT NULL COMMENT '核销员onpenid';");
}
if(!pdo_fieldexists("fx_activity_records", "specs")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `specs` text NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_records", "specitems")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `specitems` text NOT NULL;");
}
if(!pdo_fieldexists("fx_activity_records", "operation")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `operation` varchar(50) NOT NULL COMMENT '操作员';");
}
if(!pdo_fieldexists("fx_activity_records", "optionid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `optionid` int(11) NOT NULL COMMENT '规格ID';");
}
if(!pdo_fieldexists("fx_activity_records", "optionname")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `optionname` varchar(200) NOT NULL COMMENT '规格名称';");
}
if(!pdo_fieldexists("fx_activity_records", "review")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `review` tinyint(2) NOT NULL DEFAULT '1' COMMENT '审核状态：0为待审核，1为已审核';");
}
if(!pdo_fieldexists("fx_activity_records", "signin")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `signin` int(11) NOT NULL DEFAULT '0' COMMENT '签到';");
}
if(!pdo_fieldexists("fx_activity_records", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `merchantid` int(11) NOT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists("fx_activity_records", "storeid")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `storeid` int(11) NOT NULL COMMENT '门店ID';");
}
if(!pdo_fieldexists("fx_activity_records", "marketing")) {
 pdo_query("ALTER TABLE ".tablename("fx_activity_records")." ADD   `marketing` text NOT NULL COMMENT '优惠数据';");
}
if(!pdo_fieldexists("fx_adv", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_adv", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_adv", "advname")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `advname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fx_adv", "link")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fx_adv", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fx_adv", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_adv", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   `enabled` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fx_adv", "indx_weid")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists("fx_adv", "indx_enabled")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   KEY `indx_enabled` (`enabled`);");
}
if(!pdo_fieldexists("fx_adv", "indx_displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_adv")." ADD   KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists("fx_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists("fx_category", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists("fx_category", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID;");
}
if(!pdo_fieldexists("fx_category", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD 0为第一级';");
}
if(!pdo_fieldexists("fx_category", "isrecommand")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_category", "description")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists("fx_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("fx_category", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists("fx_category", "visible_level")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `visible_level` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_category", "open")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `open` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_category", "color")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `color` varchar(10) NOT NULL COMMENT '颜色';");
}
if(!pdo_fieldexists("fx_category", "redirect")) {
 pdo_query("ALTER TABLE ".tablename("fx_category")." ADD   `redirect` varchar(255) NOT NULL COMMENT '跳转连接';");
}
if(!pdo_fieldexists("fx_form", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_form", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form", "title")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_form", "description")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists("fx_form", "displaytype")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `displaytype` varchar(3) NOT NULL;");
}
if(!pdo_fieldexists("fx_form", "content")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `content` text NOT NULL;");
}
if(!pdo_fieldexists("fx_form", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `activityid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_form", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_form", "essential")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `essential` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_form", "fieldstype")) {
 pdo_query("ALTER TABLE ".tablename("fx_form")." ADD   `fieldstype` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data")." ADD   `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_form_data", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data")." ADD   `activityid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data", "recordid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data")." ADD   `recordid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data", "formid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data")." ADD   `formid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data", "data")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data")." ADD   `data` varchar(800) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data")." ADD   `displayorder` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_form_data_common", "rid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "email")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `email` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "realname")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `realname` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "qq")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `qq` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "gender")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `gender` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "birthyear")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `birthyear` smallint(6) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "birthmonth")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `birthmonth` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "birthday")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `birthday` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "constellation")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `constellation` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "zodiac")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `zodiac` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "telephone")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `telephone` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "idcard")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `idcard` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "studentid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `studentid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "grade")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `grade` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "address")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "zipcode")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `zipcode` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "nationality")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `nationality` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "resideprovince")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `resideprovince` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "residecity")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `residecity` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "residedist")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `residedist` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "graduateschool")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `graduateschool` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "company")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `company` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "education")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `education` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "occupation")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `occupation` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "position")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `position` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "revenue")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `revenue` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "affectivestatus")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `affectivestatus` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "lookingfor")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `lookingfor` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "bloodtype")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `bloodtype` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "height")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `height` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "weight")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `weight` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "alipay")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `alipay` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "msn")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `msn` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "taobao")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `taobao` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "site")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `site` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "bio")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `bio` text NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "interest")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `interest` text NOT NULL;");
}
if(!pdo_fieldexists("fx_form_data_common", "age")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_data_common")." ADD   `age` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_item", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_form_item", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_item", "formid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `formid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_form_item", "title")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_form_item", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_form_item", "show")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_form_item", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_form_item", "indx_weid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists("fx_form_item", "indx_specid")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   KEY `indx_specid` (`formid`);");
}
if(!pdo_fieldexists("fx_form_item", "indx_show")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   KEY `indx_show` (`show`);");
}
if(!pdo_fieldexists("fx_form_item", "indx_displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_form_item")." ADD   KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists("fx_marketing", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_marketing")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_marketing", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_marketing")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_marketing", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_marketing")." ADD   `activityid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_marketing", "type")) {
 pdo_query("ALTER TABLE ".tablename("fx_marketing")." ADD   `type` int(11) DEFAULT NULL COMMENT '1折扣2满减3抵扣';");
}
if(!pdo_fieldexists("fx_marketing", "value")) {
 pdo_query("ALTER TABLE ".tablename("fx_marketing")." ADD   `value` text COMMENT '设置的值';");
}
if(!pdo_fieldexists("fx_marketing", "aid")) {
 pdo_query("ALTER TABLE ".tablename("fx_marketing")." ADD   KEY `aid` (`activityid`);");
}
if(!pdo_fieldexists("fx_member_blacklist", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_member_blacklist")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_member_blacklist", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_member_blacklist")." ADD   `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_member_blacklist", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_member_blacklist")." ADD   `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id';");
}
if(!pdo_fieldexists("fx_merchant", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_merchant", "name")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `name` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "logo")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `logo` varchar(225) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "industry")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `industry` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "address")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `address` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "linkman_name")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `linkman_name` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "linkman_mobile")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `linkman_mobile` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `createtime` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "detail")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `detail` varchar(1222) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "salenum")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `salenum` int(11) NOT NULL COMMENT '商家销量';");
}
if(!pdo_fieldexists("fx_merchant", "open")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `open` int(11) NOT NULL COMMENT '是否分配商家权限';");
}
if(!pdo_fieldexists("fx_merchant", "uname")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `uname` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "password")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `password` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `uid` int(11) NOT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists("fx_merchant", "messageopenid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `messageopenid` text NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `openid` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "goodsnum")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `goodsnum` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "percent")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `percent` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "allsalenum")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `allsalenum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "falsenum")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `falsenum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "tag")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `tag` text;");
}
if(!pdo_fieldexists("fx_merchant", "storename")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `storename` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "lng")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `lng` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "lat")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `lat` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "adinfo")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `adinfo` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "kefuimg")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `kefuimg` varchar(225) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant", "follownum")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `follownum` int(11) NOT NULL DEFAULT '0' COMMENT '关注量';");
}
if(!pdo_fieldexists("fx_merchant", "followno")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant")." ADD   `followno` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟关注量';");
}
if(!pdo_fieldexists("fx_merchant_account", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_merchant_account", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `merchantid` int(11) NOT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists("fx_merchant_account", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_account", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `uid` int(11) NOT NULL COMMENT '操作员id';");
}
if(!pdo_fieldexists("fx_merchant_account", "amount")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `amount` decimal(10;");
}
if(!pdo_fieldexists("fx_merchant_account", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD 2) NOT NULL COMMENT '交易总金额';");
}
if(!pdo_fieldexists("fx_merchant_account", "updatetime")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `updatetime` varchar(45) NOT NULL COMMENT '上次结算时间';");
}
if(!pdo_fieldexists("fx_merchant_account", "no_money")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `no_money` decimal(10;");
}
if(!pdo_fieldexists("fx_merchant_account", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD 2) NOT NULL COMMENT '目前未结算金额';");
}
if(!pdo_fieldexists("fx_merchant_account", "no_money_doing")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD   `no_money_doing` decimal(10;");
}
if(!pdo_fieldexists("fx_merchant_account", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_account")." ADD 2) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_fans", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_merchant_fans", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_fans", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_fans", "muid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `muid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_fans", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_fans", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_fans", "follow")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `follow` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_merchant_fans", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_fans")." ADD   `createtime` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_money_record", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_merchant_money_record", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_money_record", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `merchantid` int(11) DEFAULT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists("fx_merchant_money_record", "money")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `money` decimal(10;");
}
if(!pdo_fieldexists("fx_merchant_money_record", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD 2) DEFAULT '0.00' COMMENT '变动金额';");
}
if(!pdo_fieldexists("fx_merchant_money_record", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `createtime` varchar(145) DEFAULT NULL COMMENT '变动时间';");
}
if(!pdo_fieldexists("fx_merchant_money_record", "recordsid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `recordsid` int(11) DEFAULT NULL COMMENT '订单ID';");
}
if(!pdo_fieldexists("fx_merchant_money_record", "type")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `type` int(11) DEFAULT NULL COMMENT '1线下支付2核销成功纳入可结算金额3取消核销4商家结算5退款';");
}
if(!pdo_fieldexists("fx_merchant_money_record", "detail")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_money_record")." ADD   `detail` text COMMENT '详情';");
}
if(!pdo_fieldexists("fx_merchant_record", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_merchant_record", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `merchantid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists("fx_merchant_record", "money")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `money` varchar(45) NOT NULL COMMENT '本次结算金额';");
}
if(!pdo_fieldexists("fx_merchant_record", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `uid` int(11) NOT NULL COMMENT '操作员id';");
}
if(!pdo_fieldexists("fx_merchant_record", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `createtime` varchar(45) NOT NULL COMMENT '结算时间';");
}
if(!pdo_fieldexists("fx_merchant_record", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "orderno")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `orderno` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `commission` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "percent")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `percent` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "get_money")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `get_money` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "type")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `type` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "updatetime")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `updatetime` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_merchant_record", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_merchant_record")." ADD   `status` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_refund_record", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_refund_record", "type")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `type` int(11) NOT NULL COMMENT '1手机端2Web端3最后一人退款4部分退款';");
}
if(!pdo_fieldexists("fx_refund_record", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `activityid` int(11) NOT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists("fx_refund_record", "payfee")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `payfee` varchar(100) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists("fx_refund_record", "refundfee")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `refundfee` varchar(100) NOT NULL COMMENT '退还金额';");
}
if(!pdo_fieldexists("fx_refund_record", "transid")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `transid` varchar(115) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists("fx_refund_record", "refund_id")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `refund_id` varchar(115) NOT NULL COMMENT '微信退款单号';");
}
if(!pdo_fieldexists("fx_refund_record", "refundername")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `refundername` varchar(100) NOT NULL COMMENT '退款人姓名';");
}
if(!pdo_fieldexists("fx_refund_record", "refundermobile")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `refundermobile` varchar(100) NOT NULL COMMENT '退款人电话';");
}
if(!pdo_fieldexists("fx_refund_record", "activityname")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `activityname` varchar(100) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists("fx_refund_record", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `createtime` varchar(45) NOT NULL COMMENT '退款时间';");
}
if(!pdo_fieldexists("fx_refund_record", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `status` int(11) NOT NULL COMMENT '0未成功1成功';");
}
if(!pdo_fieldexists("fx_refund_record", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_refund_record", "recordid")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `recordid` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists("fx_refund_record", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_refund_record", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fx_refund_record")." ADD   `remark` varchar(200) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists("fx_saler", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_saler", "storeid")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `storeid` varchar(225) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fx_saler", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_saler", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `openid` text NOT NULL;");
}
if(!pdo_fieldexists("fx_saler", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `nickname` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists("fx_saler", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `avatar` varchar(225) NOT NULL;");
}
if(!pdo_fieldexists("fx_saler", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `status` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_saler", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   `merchantid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_saler", "idx_storeid")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   KEY `idx_storeid` (`storeid`);");
}
if(!pdo_fieldexists("fx_saler", "idx_uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_saler")." ADD   KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists("fx_spec", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_spec", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_spec", "title")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fx_spec", "description")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists("fx_spec", "content")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `content` text NOT NULL;");
}
if(!pdo_fieldexists("fx_spec", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `activityid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec", "essential")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec")." ADD   `essential` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_item", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_spec_item", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_spec_item", "specid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `specid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_item", "title")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_spec_item", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_spec_item", "show")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_item", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_item", "indx_weid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists("fx_spec_item", "indx_specid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   KEY `indx_specid` (`specid`);");
}
if(!pdo_fieldexists("fx_spec_item", "indx_show")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   KEY `indx_show` (`show`);");
}
if(!pdo_fieldexists("fx_spec_item", "indx_displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_item")." ADD   KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists("fx_spec_option", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_spec_option", "activityid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `activityid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_option", "title")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists("fx_spec_option", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists("fx_spec_option", "aprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `aprice` decimal(10;");
}
if(!pdo_fieldexists("fx_spec_option", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD 2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fx_spec_option", "marketprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `marketprice` decimal(10;");
}
if(!pdo_fieldexists("fx_spec_option", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD 2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fx_spec_option", "costprice")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `costprice` decimal(10;");
}
if(!pdo_fieldexists("fx_spec_option", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD 2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fx_spec_option", "quota")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `quota` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_option", "falsenum")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `falsenum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_option", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_spec_option", "specs")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   `specs` text;");
}
if(!pdo_fieldexists("fx_spec_option", "indx_goodsid")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   KEY `indx_goodsid` (`activityid`);");
}
if(!pdo_fieldexists("fx_spec_option", "indx_displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_spec_option")." ADD   KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists("fx_store", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_store", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_store", "storename")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `storename` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_store", "address")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `address` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_store", "tel")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `tel` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_store", "lat")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `lat` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_store", "lng")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `lng` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists("fx_store", "adinfo")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `adinfo` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists("fx_store", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_store", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `createtime` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists("fx_store", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_store", "storehours")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   `storehours` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_store", "idx_uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists("fx_store", "idx_status")) {
 pdo_query("ALTER TABLE ".tablename("fx_store")." ADD   KEY `idx_status` (`status`);");
}
if(!pdo_fieldexists("fx_user_node", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_user_node", "name")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "url")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `url` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "do")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `do` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "ac")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `ac` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "op")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `op` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "ac_id")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `ac_id` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "do_id")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `do_id` int(6) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `remark` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "level")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `level` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `status` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_user_node", "flag")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   `flag` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fx_user_node", "level")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   KEY `level` (`level`);");
}
if(!pdo_fieldexists("fx_user_node", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   KEY `pid` (`do_id`);");
}
if(!pdo_fieldexists("fx_user_node", "name")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_node")." ADD   KEY `name` (`name`);");
}
if(!pdo_fieldexists("fx_user_role", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_role")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_user_role", "merchantid")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_role")." ADD   `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_user_role", "nodes")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_role")." ADD   `nodes` text NOT NULL;");
}
if(!pdo_fieldexists("fx_user_role", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_user_role")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_yearcard", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard", "name")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `name` varchar(50) NOT NULL COMMENT '年卡名称';");
}
if(!pdo_fieldexists("fx_yearcard", "value")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `value` decimal(10;");
}
if(!pdo_fieldexists("fx_yearcard", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD 2) NOT NULL COMMENT '面值';");
}
if(!pdo_fieldexists("fx_yearcard", "value_first")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `value_first` decimal(10;");
}
if(!pdo_fieldexists("fx_yearcard", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD 2) NOT NULL COMMENT '首次激活面值';");
}
if(!pdo_fieldexists("fx_yearcard", "is_first_num")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `is_first_num` tinyint(3) unsigned NOT NULL COMMENT '首次激活允许累计';");
}
if(!pdo_fieldexists("fx_yearcard", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `thumb` varchar(225) NOT NULL COMMENT '年封面图片';");
}
if(!pdo_fieldexists("fx_yearcard", "credit")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `credit` int(11) unsigned NOT NULL COMMENT '赠送积分/年';");
}
if(!pdo_fieldexists("fx_yearcard", "description")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `description` text NOT NULL COMMENT '专属特权';");
}
if(!pdo_fieldexists("fx_yearcard", "detail")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `detail` text NOT NULL COMMENT '使用须知';");
}
if(!pdo_fieldexists("fx_yearcard", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard")." ADD   `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists("fx_yearcard_friend", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "realname")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `realname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "gender")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `gender` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "relation")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `relation` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "birthyear")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `birthyear` smallint(6) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "birthmonth")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `birthmonth` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "birthday")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `birthday` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_friend", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_friend")." ADD   `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_record", "id")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fx_yearcard_record", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_record", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_record", "buyuid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `buyuid` int(10) unsigned NOT NULL COMMENT '开卡用户ID';");
}
if(!pdo_fieldexists("fx_yearcard_record", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_record", "fid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `fid` int(11) unsigned NOT NULL COMMENT '子用户ID';");
}
if(!pdo_fieldexists("fx_yearcard_record", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `cid` int(10) unsigned NOT NULL COMMENT '年卡ID';");
}
if(!pdo_fieldexists("fx_yearcard_record", "value")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `value` decimal(10;");
}
if(!pdo_fieldexists("fx_yearcard_record", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD 2) NOT NULL COMMENT '面值';");
}
if(!pdo_fieldexists("fx_yearcard_record", "value_first")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `value_first` decimal(10;");
}
if(!pdo_fieldexists("fx_yearcard_record", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD 2) NOT NULL COMMENT '首次激活面值';");
}
if(!pdo_fieldexists("fx_yearcard_record", "fee")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `fee` decimal(10;");
}
if(!pdo_fieldexists("fx_yearcard_record", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD 2) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists("fx_yearcard_record", "pay_fee")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `pay_fee` decimal(10;");
}
if(!pdo_fieldexists("fx_yearcard_record", "")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD 2) NOT NULL;");
}
if(!pdo_fieldexists("fx_yearcard_record", "orderno")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `orderno` varchar(50) NOT NULL COMMENT '支付订单号';");
}
if(!pdo_fieldexists("fx_yearcard_record", "buynum")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `buynum` int(10) NOT NULL DEFAULT '0' COMMENT '一次购买购买数量';");
}
if(!pdo_fieldexists("fx_yearcard_record", "is_first")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `is_first` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是首次激活';");
}
if(!pdo_fieldexists("fx_yearcard_record", "status")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0未支付，1已支付，2已退款';");
}
if(!pdo_fieldexists("fx_yearcard_record", "enable")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用：1启用，2禁用';");
}
if(!pdo_fieldexists("fx_yearcard_record", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `createtime` int(11) unsigned NOT NULL COMMENT '最后修改时间';");
}
if(!pdo_fieldexists("fx_yearcard_record", "end_time")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `end_time` int(11) unsigned NOT NULL COMMENT '到期时间';");
}
if(!pdo_fieldexists("fx_yearcard_record", "cycletype")) {
 pdo_query("ALTER TABLE ".tablename("fx_yearcard_record")." ADD   `cycletype` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '周期：1周，2月，3年';");
}

 ?>