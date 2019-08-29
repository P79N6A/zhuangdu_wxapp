<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_api_user_auth` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`imgUrl` varchar(255) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`imgid` int(11) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `ims_api_user_class` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sort` int(50) DEFAULT NULL DEFAULT '0',
`icon` varchar(255) DEFAULT NULL,
`csname` varchar(255) NOT NULL,
`content` varchar(255) DEFAULT NULL,
`display` varchar(11) NOT NULL,
`uniacid` int(11) DEFAULT NULL,
`recomme` int(11) DEFAULT NULL DEFAULT '0',
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_api_user_collection` (
`oid` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`uid` int(11) DEFAULT NULL,
`tid` int(11) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`otime` int(11) DEFAULT NULL,
PRIMARY KEY (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_api_user_config` (
`key` varchar(200) NOT NULL,
`value` text NOT NULL,
`num` int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_api_user_formid` (
`fid` int(11) NOT NULL AUTO_INCREMENT,
`formid` varchar(255) NOT NULL,
`openid` varchar(255) NOT NULL,
`display` int(5) NOT NULL DEFAULT '0',
`uniacid` int(11) DEFAULT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `ims_api_user_gz` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`openid` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
`uniacid` int(11) DEFAULT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `ims_api_user_moto` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) DEFAULT NULL,
`imgUrl` varchar(255) DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `ims_api_user_news` (
`nid` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) DEFAULT NULL,
`content` text DEFAULT NULL,
`uniacid` int(11) DEFAULT NULL,
`addtime` int(11) DEFAULT NULL,
PRIMARY KEY (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
if(pdo_tableexists('api_user_auth')) {
	if(!pdo_fieldexists('api_user_auth',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_auth')) {
	if(!pdo_fieldexists('api_user_auth',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth')." ADD `openid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth')) {
	if(!pdo_fieldexists('api_user_auth',  'imgUrl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth')." ADD `imgUrl` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth')) {
	if(!pdo_fieldexists('api_user_auth',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth')) {
	if(!pdo_fieldexists('api_user_auth',  'imgid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth')." ADD `imgid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth')) {
	if(!pdo_fieldexists('api_user_auth',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth')." ADD `time` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'auid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `auid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `openid` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'nickName')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `nickName` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'avatarUrl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `avatarUrl` varchar(300) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'username')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `username` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'userid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `userid` varchar(20) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `display` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_auth_list')) {
	if(!pdo_fieldexists('api_user_auth_list',  'autime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_auth_list')." ADD `autime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'bid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `bid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `sort` int(50) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'icon')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `icon` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'url')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `url` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'btime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `btime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'appid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `appid` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_banner')) {
	if(!pdo_fieldexists('api_user_banner',  'appstatr')) {
		pdo_query("ALTER TABLE ".tablename('api_user_banner')." ADD `appstatr` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `rid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `openid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'xingming')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `xingming` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'weixin')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `weixin` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'youxiang')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `youxiang` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'shouji')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `shouji` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'dianhua')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `dianhua` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'zhiwei')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `zhiwei` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'gongsi')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `gongsi` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'imgurl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `imgurl` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_cdr')) {
	if(!pdo_fieldexists('api_user_cdr',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_cdr')." ADD `time` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `sort` int(50) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'icon')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `icon` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'csname')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `csname` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `content` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `display` varchar(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'recomme')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `recomme` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_class')) {
	if(!pdo_fieldexists('api_user_class',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_class')." ADD `time` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_collection')) {
	if(!pdo_fieldexists('api_user_collection',  'oid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_collection')." ADD `oid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_collection')) {
	if(!pdo_fieldexists('api_user_collection',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_collection')." ADD `openid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_collection')) {
	if(!pdo_fieldexists('api_user_collection',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_collection')." ADD `uid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_collection')) {
	if(!pdo_fieldexists('api_user_collection',  'tid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_collection')." ADD `tid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_collection')) {
	if(!pdo_fieldexists('api_user_collection',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_collection')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_collection')) {
	if(!pdo_fieldexists('api_user_collection',  'otime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_collection')." ADD `otime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_config')) {
	if(!pdo_fieldexists('api_user_config',  'key')) {
		pdo_query("ALTER TABLE ".tablename('api_user_config')." ADD `key` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_config')) {
	if(!pdo_fieldexists('api_user_config',  'value')) {
		pdo_query("ALTER TABLE ".tablename('api_user_config')." ADD `value` text NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_config')) {
	if(!pdo_fieldexists('api_user_config',  'num')) {
		pdo_query("ALTER TABLE ".tablename('api_user_config')." ADD `num` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_formid')) {
	if(!pdo_fieldexists('api_user_formid',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_formid')." ADD `fid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_formid')) {
	if(!pdo_fieldexists('api_user_formid',  'formid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_formid')." ADD `formid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_formid')) {
	if(!pdo_fieldexists('api_user_formid',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_formid')." ADD `openid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_formid')) {
	if(!pdo_fieldexists('api_user_formid',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_formid')." ADD `display` int(5) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_formid')) {
	if(!pdo_fieldexists('api_user_formid',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_formid')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_formid')) {
	if(!pdo_fieldexists('api_user_formid',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_formid')." ADD `time` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `openid` varchar(200) DEFAULT NULL COMMENT '会员UID';");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `title` varchar(255) DEFAULT NULL COMMENT '内容标题';");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'photo')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `photo` varchar(255) DEFAULT NULL COMMENT '内容封面';");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `content` text DEFAULT NULL COMMENT '服务介绍';");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'checkedstart')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `checkedstart` varchar(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'numbers')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `numbers` varchar(100) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'userid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `userid` text DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group')) {
	if(!pdo_fieldexists('api_user_group',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group')." ADD `time` int(11) DEFAULT NULL COMMENT '发布时间';");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'gid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `gid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `openid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'imgUrl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `imgUrl` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'random')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `random` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_img')) {
	if(!pdo_fieldexists('api_user_group_img',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_img')." ADD `time` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'nid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `nid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'gid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `gid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'nickName')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `nickName` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'avatarUrl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `avatarUrl` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `openid` varchar(180) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `content` text DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'random')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `random` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_group_news')) {
	if(!pdo_fieldexists('api_user_group_news',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_group_news')." ADD `addtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_gz')) {
	if(!pdo_fieldexists('api_user_gz',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_gz')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_gz')) {
	if(!pdo_fieldexists('api_user_gz',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_gz')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_gz')) {
	if(!pdo_fieldexists('api_user_gz',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_gz')." ADD `openid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_gz')) {
	if(!pdo_fieldexists('api_user_gz',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('api_user_gz')." ADD `avatar` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_gz')) {
	if(!pdo_fieldexists('api_user_gz',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_gz')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_gz')) {
	if(!pdo_fieldexists('api_user_gz',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_gz')." ADD `time` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `mid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'official')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `official` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'op')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `op` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `openid` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `nickname` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `avatar` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `content` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `display` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_message')) {
	if(!pdo_fieldexists('api_user_message',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_message')." ADD `addtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'cid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `cid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'indes')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `indes` varchar(100) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `openid` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'avatarUrl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `avatarUrl` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `mobile` varchar(20) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'nickName')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `nickName` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'user_zc')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `user_zc` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'user_gs')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `user_gs` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'longitude')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `longitude` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'latitude')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `latitude` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'user_weixin')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `user_weixin` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'signature')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `signature` text DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'user_vip')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `user_vip` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'paytime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `paytime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'overtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `overtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `display` int(11) DEFAULT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'shareurl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `shareurl` varchar(200) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'heat')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `heat` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'report')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `report` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'uptime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `uptime` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'backgro')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `backgro` int(11) NOT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'auth')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `auth` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'examine')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `examine` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'groupid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `groupid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_more')) {
	if(!pdo_fieldexists('api_user_more',  'video')) {
		pdo_query("ALTER TABLE ".tablename('api_user_more')." ADD `video` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_moto')) {
	if(!pdo_fieldexists('api_user_moto',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_moto')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_moto')) {
	if(!pdo_fieldexists('api_user_moto',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_moto')." ADD `openid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_moto')) {
	if(!pdo_fieldexists('api_user_moto',  'imgUrl')) {
		pdo_query("ALTER TABLE ".tablename('api_user_moto')." ADD `imgUrl` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_moto')) {
	if(!pdo_fieldexists('api_user_moto',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_moto')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_moto')) {
	if(!pdo_fieldexists('api_user_moto',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_moto')." ADD `time` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `mid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `uid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'op')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `op` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `openid` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'fromid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `fromid` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `nickname` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `avatar` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `content` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'r_content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `r_content` varchar(255) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'rtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `rtime` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `display` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_ms')) {
	if(!pdo_fieldexists('api_user_ms',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_ms')." ADD `addtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_news')) {
	if(!pdo_fieldexists('api_user_news',  'nid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_news')." ADD `nid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_news')) {
	if(!pdo_fieldexists('api_user_news',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_news')." ADD `title` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_news')) {
	if(!pdo_fieldexists('api_user_news',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_news')." ADD `content` text DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_news')) {
	if(!pdo_fieldexists('api_user_news',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_news')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_news')) {
	if(!pdo_fieldexists('api_user_news',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_news')." ADD `addtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'op')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `op` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'jid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `jid` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `openid` varchar(150) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `nickname` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `avatar` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `content` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'dis')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `dis` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_opinion')) {
	if(!pdo_fieldexists('api_user_opinion',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_opinion')." ADD `time` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'plid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `plid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `title` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `openid` varchar(40) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'username')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `username` varchar(180) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'uniontid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `uniontid` varchar(128) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'fee')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `fee` decimal(10,2) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'status')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `status` tinyint(4) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'orderid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `orderid` varchar(200) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_pay')) {
	if(!pdo_fieldexists('api_user_pay',  'pay_time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_pay')." ADD `pay_time` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `rid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'photo')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `photo` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `title` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `content` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'price')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `price` varchar(100) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'hotnum')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `hotnum` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward')) {
	if(!pdo_fieldexists('api_user_reward',  'rtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward')." ADD `rtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'lid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `lid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `sid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'img')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `img` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `title` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'orderid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `orderid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `openid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'username')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `username` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'price')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `price` varchar(100) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'sopenid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `sopenid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'hotnum')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `hotnum` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'status')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `status` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_reward_log')) {
	if(!pdo_fieldexists('api_user_reward_log',  'srtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_reward_log')." ADD `srtime` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'id')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `openid` varchar(200) DEFAULT NULL COMMENT '会员UID';");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'title')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `title` varchar(255) DEFAULT NULL COMMENT '内容标题';");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'photo')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `photo` varchar(255) DEFAULT NULL COMMENT '内容封面';");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'content')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `content` text DEFAULT NULL COMMENT '服务介绍';");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_shop')) {
	if(!pdo_fieldexists('api_user_shop',  'time')) {
		pdo_query("ALTER TABLE ".tablename('api_user_shop')." ADD `time` int(11) DEFAULT NULL COMMENT '发布时间';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'did')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `did` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'dtitle')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `dtitle` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'dnumber')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `dnumber` int(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'dcontent')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `dcontent` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'x')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `x` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'y')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `y` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `openid` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `avatar` varchar(255) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `nickname` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'seid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `seid` int(11) DEFAULT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'dmoney')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `dmoney` varchar(100) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'noliyou')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `noliyou` varchar(255) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'display')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `display` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'pay')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `pay` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `uniacid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'sptime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `sptime` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'dqtime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `dqtime` int(11) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('api_user_trade')) {
	if(!pdo_fieldexists('api_user_trade',  'ctime')) {
		pdo_query("ALTER TABLE ".tablename('api_user_trade')." ADD `ctime` int(11) DEFAULT NULL;");
	}	
}
