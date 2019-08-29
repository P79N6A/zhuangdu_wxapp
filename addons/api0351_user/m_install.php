<?php
pdo_query("DROP TABLE IF EXISTS `ims_api_user_auth`;
CREATE TABLE IF NOT EXISTS `ims_api_user_auth` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`imgUrl` varchar(255) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`imgid` int(11) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_auth_list`;
CREATE TABLE IF NOT EXISTS `ims_api_user_auth_list` (
`auid` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(150) DEFAULT NULL,
`nickName` varchar(150) DEFAULT NULL,
`avatarUrl` varchar(300) DEFAULT NULL,
`username` varchar(100) DEFAULT NULL,
`userid` varchar(20) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`display` int(11) DEFAULT NULL DEFAULT '0',
`autime` int(11) DEFAULT NULL,
PRIMARY KEY (`auid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_banner`;
CREATE TABLE IF NOT EXISTS `ims_api_user_banner` (
`bid` int(11) NOT NULL AUTO_INCREMENT,
`sort` int(50) DEFAULT NULL DEFAULT '0',
`icon` varchar(255) DEFAULT NULL,
`title` varchar(255) NOT NULL,
`url` varchar(255) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`btime` int(11) DEFAULT NULL,
`appid` varchar(150) DEFAULT NULL,
`appstatr` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_cdr`;
CREATE TABLE IF NOT EXISTS `ims_api_user_cdr` (
`rid` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`xingming` varchar(150) DEFAULT NULL,
`weixin` varchar(150) DEFAULT NULL,
`youxiang` varchar(200) DEFAULT NULL,
`shouji` varchar(50) DEFAULT NULL,
`dianhua` varchar(50) DEFAULT NULL,
`zhiwei` varchar(100) DEFAULT NULL,
`gongsi` varchar(200) DEFAULT NULL,
`imgurl` varchar(255) DEFAULT NULL,
`uniacid` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_class`;
CREATE TABLE IF NOT EXISTS `ims_api_user_class` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sort` int(50) DEFAULT NULL DEFAULT '0',
`icon` varchar(255) DEFAULT NULL,
`csname` varchar(255) NOT NULL,
`content` varchar(255) DEFAULT NULL,
`display` varchar(11) NOT NULL  DEFAULT '',
`uniacid` int(11) DEFAULT NULL,
`recomme` int(11) DEFAULT NULL DEFAULT '0',
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_collection`;
CREATE TABLE IF NOT EXISTS `ims_api_user_collection` (
`oid` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`uid` int(11) DEFAULT NULL,
`tid` int(11) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`otime` int(11) DEFAULT NULL,
PRIMARY KEY (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_config`;
CREATE TABLE IF NOT EXISTS `ims_api_user_config` (
`key` varchar(200) NOT NULL,
`value` text NOT NULL,
`num` int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_formid`;
CREATE TABLE IF NOT EXISTS `ims_api_user_formid` (
`fid` int(11) NOT NULL AUTO_INCREMENT,
`formid` varchar(255) NOT NULL,
`openid` varchar(255) NOT NULL,
`display` int(5) NOT NULL DEFAULT '0',
`uniacid` int(11) DEFAULT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_group`;
CREATE TABLE IF NOT EXISTS `ims_api_user_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(200) DEFAULT NULL COMMENT '会员UID',
`title` varchar(255) DEFAULT NULL COMMENT '内容标题',
`photo` varchar(255) DEFAULT NULL COMMENT '内容封面',
`content` text DEFAULT NULL COMMENT '服务介绍',
`uniacid` int(11) DEFAULT NULL,
`checkedstart` varchar(11) DEFAULT NULL DEFAULT '0',
`numbers` varchar(100) DEFAULT NULL DEFAULT '0',
`userid` text DEFAULT NULL,
`time` int(11) DEFAULT NULL COMMENT '发布时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_group_img`;
CREATE TABLE IF NOT EXISTS `ims_api_user_group_img` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`gid` int(11) DEFAULT NULL,
`openid` varchar(255) DEFAULT NULL,
`imgUrl` varchar(255) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`random` varchar(255) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_group_news`;
CREATE TABLE IF NOT EXISTS `ims_api_user_group_news` (
`nid` int(11) NOT NULL AUTO_INCREMENT,
`gid` int(11) DEFAULT NULL,
`nickName` varchar(150) DEFAULT NULL,
`avatarUrl` varchar(255) DEFAULT NULL,
`openid` varchar(180) DEFAULT NULL,
`content` text DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`random` varchar(255) DEFAULT NULL,
`addtime` int(11) DEFAULT NULL,
PRIMARY KEY (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_gz`;
CREATE TABLE IF NOT EXISTS `ims_api_user_gz` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`openid` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
`uniacid` int(11) DEFAULT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_message`;
CREATE TABLE IF NOT EXISTS `ims_api_user_message` (
`mid` int(11) NOT NULL AUTO_INCREMENT,
`official` int(11) DEFAULT NULL DEFAULT '0',
`op` varchar(200) DEFAULT NULL,
`openid` varchar(200) DEFAULT NULL,
`nickname` varchar(150) DEFAULT NULL,
`avatar` varchar(255) DEFAULT NULL,
`content` varchar(255) DEFAULT NULL,
`display` int(11) NOT NULL DEFAULT '0',
`uniacid` int(11) DEFAULT NULL,
`addtime` int(11) DEFAULT NULL,
PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_more`;
CREATE TABLE IF NOT EXISTS `ims_api_user_more` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`cid` int(11) NOT NULL,
`indes` varchar(100) DEFAULT NULL DEFAULT '0',
`openid` varchar(200) DEFAULT NULL,
`avatarUrl` varchar(255) DEFAULT NULL,
`mobile` varchar(20) DEFAULT NULL,
`nickName` varchar(150) DEFAULT NULL,
`user_zc` varchar(150) DEFAULT NULL,
`user_gs` varchar(255) DEFAULT NULL,
`longitude` varchar(100) DEFAULT NULL,
`latitude` varchar(100) DEFAULT NULL,
`user_weixin` varchar(100) DEFAULT NULL,
`signature` text DEFAULT NULL,
`uniacid` int(11) NOT NULL,
`user_vip` int(11) NOT NULL DEFAULT '0',
`paytime` int(11) DEFAULT NULL,
`overtime` int(11) DEFAULT NULL,
`display` int(11) DEFAULT NULL DEFAULT '1',
`shareurl` varchar(200) DEFAULT NULL DEFAULT '0',
`heat` int(11) DEFAULT NULL DEFAULT '0',
`report` int(11) DEFAULT NULL DEFAULT '0',
`uptime` int(11) NOT NULL,
`backgro` int(11) NOT NULL DEFAULT '1',
`auth` int(11) NOT NULL DEFAULT '0',
`examine` int(11) DEFAULT NULL DEFAULT '0',
`groupid` varchar(255) DEFAULT NULL,
`video` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_moto`;
CREATE TABLE IF NOT EXISTS `ims_api_user_moto` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`imgUrl` varchar(255) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_ms`;
CREATE TABLE IF NOT EXISTS `ims_api_user_ms` (
`mid` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) DEFAULT NULL,
`op` varchar(200) DEFAULT NULL,
`openid` varchar(200) DEFAULT NULL,
`fromid` varchar(150) DEFAULT NULL,
`nickname` varchar(150) DEFAULT NULL,
`avatar` varchar(255) DEFAULT NULL,
`content` varchar(255) DEFAULT NULL,
`r_content` varchar(255) DEFAULT NULL DEFAULT '0',
`rtime` int(11) DEFAULT NULL DEFAULT '0',
`display` int(11) NOT NULL DEFAULT '0',
`uniacid` int(11) DEFAULT NULL,
`addtime` int(11) DEFAULT NULL,
PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_news`;
CREATE TABLE IF NOT EXISTS `ims_api_user_news` (
`nid` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) DEFAULT NULL,
`content` text DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`addtime` int(11) DEFAULT NULL,
PRIMARY KEY (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_opinion`;
CREATE TABLE IF NOT EXISTS `ims_api_user_opinion` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`op` varchar(255) DEFAULT NULL,
`jid` int(11) DEFAULT NULL DEFAULT '0',
`openid` varchar(150) DEFAULT NULL,
`nickname` varchar(100) DEFAULT NULL,
`avatar` varchar(255) DEFAULT NULL,
`content` varchar(255) NOT NULL,
`uniacid` int(11) DEFAULT NULL,
`dis` int(11) DEFAULT NULL DEFAULT '0',
`time` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_pay`;
CREATE TABLE IF NOT EXISTS `ims_api_user_pay` (
`plid` int(11) NOT NULL AUTO_INCREMENT,
`title` int(11) DEFAULT NULL DEFAULT '0',
`openid` varchar(40) DEFAULT NULL,
`username` varchar(180) DEFAULT NULL,
`uniontid` varchar(128) DEFAULT NULL DEFAULT '0',
`fee` decimal(10,2) DEFAULT NULL,
`status` tinyint(4) DEFAULT NULL,
`orderid` varchar(200) DEFAULT NULL DEFAULT '0',
`uniacid` int(11) DEFAULT NULL,
`pay_time` int(11) DEFAULT NULL,
PRIMARY KEY (`plid`),
KEY `idx_openid` (`openid`),
KEY `idx_tid` (`uniontid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_reward`;
CREATE TABLE IF NOT EXISTS `ims_api_user_reward` (
`rid` int(11) NOT NULL AUTO_INCREMENT,
`photo` varchar(255) DEFAULT NULL,
`title` varchar(200) NOT NULL,
`content` varchar(255) DEFAULT NULL,
`price` varchar(100) DEFAULT NULL DEFAULT '0',
`hotnum` int(11) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`rtime` int(11) DEFAULT NULL,
PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_reward_log`;
CREATE TABLE IF NOT EXISTS `ims_api_user_reward_log` (
`lid` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) DEFAULT NULL,
`img` varchar(255) DEFAULT NULL,
`title` varchar(255) DEFAULT NULL,
`orderid` varchar(255) DEFAULT NULL,
`openid` varchar(255) NOT NULL,
`username` varchar(255) DEFAULT NULL,
`price` varchar(100) DEFAULT NULL DEFAULT '0',
`sopenid` varchar(255) DEFAULT NULL,
`hotnum` varchar(100) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`status` int(11) DEFAULT NULL DEFAULT '0',
`srtime` int(11) DEFAULT NULL,
PRIMARY KEY (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_shop`;
CREATE TABLE IF NOT EXISTS `ims_api_user_shop` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(200) DEFAULT NULL COMMENT '会员UID',
`title` varchar(255) DEFAULT NULL COMMENT '内容标题',
`photo` varchar(255) DEFAULT NULL COMMENT '内容封面',
`content` text DEFAULT NULL COMMENT '服务介绍',
`uniacid` int(11) DEFAULT NULL,
`time` int(11) DEFAULT NULL COMMENT '发布时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_api_user_trade`;
CREATE TABLE IF NOT EXISTS `ims_api_user_trade` (
`did` int(11) NOT NULL AUTO_INCREMENT,
`dtitle` varchar(200) NOT NULL,
`dnumber` int(100) DEFAULT NULL,
`dcontent` varchar(255) DEFAULT NULL,
`x` varchar(255) DEFAULT NULL,
`y` varchar(255) DEFAULT NULL,
`openid` varchar(255) DEFAULT NULL,
`avatar` varchar(255) DEFAULT NULL,
`nickname` varchar(100) DEFAULT NULL,
`seid` int(11) DEFAULT NULL DEFAULT '1',
`dmoney` varchar(100) DEFAULT NULL,
`noliyou` varchar(255) DEFAULT NULL DEFAULT '0',
`display` int(11) DEFAULT NULL DEFAULT '0',
`pay` int(11) DEFAULT NULL DEFAULT '0',
`uniacid` int(11) DEFAULT NULL,
`sptime` int(11) DEFAULT NULL DEFAULT '0',
`dqtime` int(11) DEFAULT NULL DEFAULT '0',
`ctime` int(11) DEFAULT NULL,
PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
