CDATA[CREATE TABLE IF NOT EXISTS `ims_fx_activity` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`uniacid` int(10) NOT NULL,
`title` varchar(255) NOT NULL,
`pagetitle` varchar(50) NOT NULL,
`freetitle` varchar(32) NOT NULL,
`aprice` decimal(10,2) NOT NULL,
`marketprice` decimal(10,2) NOT NULL,
`sharetitle` varchar(200) NOT NULL,
`sharepic` varchar(255) NOT NULL,
`sharedesc` varchar(255) NOT NULL,
`kefu` text NOT NULL,
`unitintro` text NOT NULL,
`tel` varchar(13) NOT NULL,
`intro` text NOT NULL,
`detail` mediumtext NOT NULL,
`starttime` timestamp,
`endtime` timestamp,
`joinstime` timestamp,
`joinetime` timestamp,
`thumb` varchar(255) NOT NULL,
`atlas` text NOT NULL,
`gnum` int(11) NOT NULL,
`lng` varchar(255) NOT NULL,
`lat` varchar(255) NOT NULL,
`adinfo` varchar(32),
`addname` varchar(50) NOT NULL,
`address` varchar(255) NOT NULL,
`prize` text NOT NULL,
`form` text NOT NULL,
`displayorder` int(4) NOT NULL,
`limitnum` int(11) NOT NULL,
`team` tinyint(2) NOT NULL,
`hasoption` tinyint(2) NOT NULL,
`show` tinyint(2) NOT NULL,
`midkey` varchar(50) NOT NULL,
`smsnotify` varchar(50) NOT NULL,
`smsswitch` tinyint(2) NOT NULL,
`trueread` int(11) NOT NULL,
`trueshare` int(11) NOT NULL,
`parentid` int(11) NOT NULL,
`childid` int(11) NOT NULL,
`recommend` tinyint(2) NOT NULL,
`viewauth` tinyint(2) NOT NULL,
`review` tinyint(2) NOT NULL,
`openids` text NOT NULL,
`tmplmsg` text NOT NULL,
`merchantid` int(11) NOT NULL,
`storeids` text,
`hasstore` tinyint(2) NOT NULL,
`atype` tinyint(2) NOT NULL,
`agreement` text NOT NULL,
`info` text NOT NULL,
`falsedata` text NOT NULL,
`hasonline` tinyint(2) NOT NULL,
`unitstr` varchar(32) NOT NULL,
`gnumshow` tinyint(2) NOT NULL,
`costcredit` int(11),
`switch` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_activity_favorite` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`activityid` int(11) NOT NULL,
`openid` varchar(200) NOT NULL,
`uid` int(11) NOT NULL,
`favo` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_activity_records` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`openid` varchar(255) NOT NULL,
`activityid` int(11) NOT NULL,
`buynum` int(11) NOT NULL,
`orderno` varchar(50) NOT NULL,
`price` varchar(45) NOT NULL,
`aprice` varchar(45) NOT NULL,
`freight` varchar(45) NOT NULL,
`vipdeduct` varchar(45) NOT NULL,
`paytime` timestamp,
`uniontid` varchar(50) NOT NULL,
`transid` varchar(50) NOT NULL,
`remark` varchar(1024) NOT NULL,
`payprice` varchar(45) NOT NULL,
`paytype` varchar(45) NOT NULL,
`nickname` varchar(255) NOT NULL,
`realname` varchar(255) NOT NULL,
`mobile` varchar(255) NOT NULL,
`gender` varchar(10) NOT NULL,
`pic` varchar(255) NOT NULL,
`headimgurl` varchar(255) NOT NULL,
`msg` text NOT NULL,
`jointime` timestamp NOT NULL,
`status` int(2) NOT NULL,
`ishexiao` int(2) NOT NULL,
`hexiaoma` varchar(50) NOT NULL,
`sendtime` timestamp,
`veropenid` varchar(200) NOT NULL,
`specs` text NOT NULL,
`specitems` text NOT NULL,
`operation` varchar(50) NOT NULL,
`optionid` int(11) NOT NULL,
`optionname` varchar(200) NOT NULL,
`review` tinyint(2) NOT NULL,
`signin` int(11) NOT NULL,
`merchantid` int(11) NOT NULL,
`storeid` int(11) NOT NULL,
`marketing` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`advname` varchar(50) NOT NULL,
`link` varchar(255) NOT NULL,
`thumb` varchar(255) NOT NULL,
`displayorder` int(11) NOT NULL,
`enabled` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_weid` (`uniacid`),
KEY `indx_enabled` (`enabled`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`enabled` tinyint(1) unsigned NOT NULL,
`visible_level` int(11) NOT NULL,
`open` int(11),
`color` varchar(10) NOT NULL,
`redirect` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_form` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(50) NOT NULL,
`description` varchar(1000) NOT NULL,
`displaytype` varchar(3) NOT NULL,
`content` text NOT NULL,
`activityid` int(11) NOT NULL,
`displayorder` int(11) NOT NULL,
`essential` tinyint(3) unsigned NOT NULL,
`fieldstype` varchar(32) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_form_data` (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
`activityid` int(11) NOT NULL,
`recordid` int(11) NOT NULL,
`formid` int(11) NOT NULL,
`data` varchar(800) NOT NULL,
`displayorder` int(11) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
`formid` int(11),
`title` varchar(255),
`thumb` varchar(255),
`show` int(11),
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`uniacid`),
KEY `indx_formid` (`formid`),
KEY `indx_show` (`show`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_marketing` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`activityid` int(11),
`type` int(11),
`value` text,
PRIMARY KEY (`id`),
KEY `aid` (`activityid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_member_blacklist` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
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
`salenum` int(11) NOT NULL,
`open` int(11) NOT NULL,
`uname` varchar(45) NOT NULL,
`password` varchar(145) NOT NULL,
`uid` int(11) NOT NULL,
`messageopenid` text NOT NULL,
`openid` varchar(150) NOT NULL,
`goodsnum` int(11) NOT NULL,
`percent` varchar(100) NOT NULL,
`allsalenum` int(11),
`falsenum` int(11),
`tag` text,
`storename` varchar(50),
`lng` varchar(145),
`lat` varchar(145),
`adinfo` varchar(32),
`kefuimg` varchar(225),
`follownum` int(11) NOT NULL,
`followno` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_merchant_account` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`merchantid` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`amount` decimal(10,2) NOT NULL,
`updatetime` varchar(45) NOT NULL,
`no_money` decimal(10,2) NOT NULL,
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
`follow` tinyint(1) NOT NULL,
`createtime` varchar(115) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_merchant_mcert` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`openid` varchar(255) NOT NULL,
`mid` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
`detail` longtext NOT NULL,
`createtime` int(11) unsigned NOT NULL,
`endtime` int(11) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_merchant_money_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`merchantid` int(11),
`money` decimal(10,2),
`createtime` varchar(145),
`recordsid` int(11),
`type` int(11),
`detail` text,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_merchant_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`merchantid` int(11) NOT NULL,
`money` varchar(45) NOT NULL,
`uid` int(11) NOT NULL,
`createtime` varchar(45) NOT NULL,
`uniacid` int(11) NOT NULL,
`orderno` varchar(100),
`commission` varchar(100),
`percent` varchar(100),
`get_money` varchar(100),
`type` int(11),
`updatetime` varchar(145),
`status` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_refund_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`type` int(11) NOT NULL,
`activityid` int(11) NOT NULL,
`payfee` varchar(100) NOT NULL,
`refundfee` varchar(100) NOT NULL,
`transid` varchar(115) NOT NULL,
`refund_id` varchar(115) NOT NULL,
`refundername` varchar(100) NOT NULL,
`refundermobile` varchar(100) NOT NULL,
`activityname` varchar(100) NOT NULL,
`createtime` varchar(45) NOT NULL,
`status` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`recordid` varchar(45) NOT NULL,
`merchantid` int(11) NOT NULL,
`remark` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_saler` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`storeid` varchar(225) NOT NULL,
`uniacid` int(11) NOT NULL,
`openid` text NOT NULL,
`nickname` varchar(145) NOT NULL,
`avatar` varchar(225) NOT NULL,
`status` tinyint(3) NOT NULL,
`merchantid` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_storeid` (`storeid`),
KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_spec` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(50) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`activityid` int(11) NOT NULL,
`displayorder` int(11) NOT NULL,
`essential` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_spec_item` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`specid` int(11),
`title` varchar(255),
`thumb` varchar(255),
`show` int(11),
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`uniacid`),
KEY `indx_specid` (`specid`),
KEY `indx_show` (`show`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_spec_option` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`activityid` int(10),
`title` varchar(50),
`thumb` varchar(60),
`aprice` decimal(10,2),
`marketprice` decimal(10,2),
`costprice` decimal(10,2),
`gnum` int(11),
`falsenum` int(11),
`displayorder` int(11),
`specs` text,
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`activityid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_store` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`storename` varchar(255),
`address` varchar(255),
`tel` varchar(255),
`lat` varchar(255),
`lng` varchar(255),
`adinfo` varchar(32),
`status` tinyint(3),
`createtime` varchar(45) NOT NULL,
`merchantid` int(11) NOT NULL,
`storehours` varchar(100),
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fx_user_node` (
`id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(20) NOT NULL,
`url` varchar(300) NOT NULL,
`do` varchar(255) NOT NULL,
`ac` varchar(32),
`op` varchar(32),
`ac_id` int(11),
`do_id` int(6) unsigned NOT NULL,
`remark` varchar(255) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`level` tinyint(3) unsigned NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`flag` int(11),
PRIMARY KEY (`id`),
KEY `level` (`level`),
KEY `pid` (`do_id`),
KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
`name` varchar(50) NOT NULL,
`value` decimal(10,2) NOT NULL,
`value_first` decimal(10,2) NOT NULL,
`is_first_num` tinyint(3) unsigned NOT NULL,
`thumb` varchar(225) NOT NULL,
`credit` int(11) unsigned NOT NULL,
`description` text NOT NULL,
`detail` text NOT NULL,
`createtime` int(10) unsigned NOT NULL,
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
`buyuid` int(10) unsigned NOT NULL,
`openid` varchar(100) NOT NULL,
`fid` int(11) unsigned NOT NULL,
`cid` int(10) unsigned NOT NULL,
`value` decimal(10,2) NOT NULL,
`value_first` decimal(10,2) NOT NULL,
`fee` decimal(10,2) NOT NULL,
`pay_fee` decimal(10,2) NOT NULL,
`orderno` varchar(50) NOT NULL,
`buynum` int(10) NOT NULL,
`is_first` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`enable` tinyint(1) NOT NULL,
`createtime` int(11) unsigned NOT NULL,
`end_time` int(11) unsigned NOT NULL,
`cycletype` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;