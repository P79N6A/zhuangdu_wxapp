<?php 
$sql="CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `class_name` varchar(255) DEFAULT '' COMMENT '分类名称',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `class_status` int(5) DEFAULT '1' COMMENT '分类状态：1启用，2删除',
  `circle_id` int(11) DEFAULT '0' COMMENT '0：广场分类，非0为圈子ID且为圈子分类',
  `uniacid` int(11) DEFAULT '0',
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='内容分类表';
CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '内容ID',
  `circle_id` int(11) DEFAULT '0' COMMENT '圈子ID',
  `class_id` int(11) DEFAULT '0' COMMENT '分类ID',
  `member_id` int(11) DEFAULT '0' COMMENT '会员ID',
  `content` text COMMENT '图片，视频地址',
  `content_status` int(5) DEFAULT '1' COMMENT '0：删除，1：显示，2隐藏',
  `content_class` int(5) DEFAULT NULL COMMENT '1：广场内容，2圈子内容',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `clnb` int(11) DEFAULT '0' COMMENT '收藏数量',
  `donnb` int(11) DEFAULT '0' COMMENT '下载数量',
  `sharenb` int(11) DEFAULT '0' COMMENT '分享数量',
  `text` text COMMENT '文本内容',
  `uniacid` int(11) DEFAULT '0',
  `fictitious_clnb` int(11) DEFAULT '0' COMMENT '虚拟收藏量',
  `fictitious_donnb` int(11) DEFAULT '0' COMMENT '虚拟下载数量',
  `fictitious_sharenb` int(11) DEFAULT '0' COMMENT '虚拟分享数量',
  `type` varchar(255) DEFAULT 'img' COMMENT 'img:图片，video:视频，voice：音频',
  `grouping_id` int(11) DEFAULT '0' COMMENT '用户分组ID，看查看内容根据此ID区分',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_day_sign` (
  `sign_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日签ID',
  `sign_title` varchar(255) DEFAULT NULL COMMENT '日签标题',
  `sign_content` text COMMENT '日签内容',
  `sign_img` varchar(255) DEFAULT NULL COMMENT '日签图片',
  `display_time` int(11) DEFAULT NULL COMMENT '指定显示时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `sign_status` tinyint(5) DEFAULT '1' COMMENT '0：删除，1：显示',
  `uniacid` int(11) DEFAULT '0',
  PRIMARY KEY (`sign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_grouping` (
  `grouping_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `grouping_name` varchar(255) DEFAULT '0' COMMENT '分组名称',
  `grouping_passwd` varchar(255) DEFAULT '0' COMMENT '分组邀请码',
  `create_time` int(11) DEFAULT '0' COMMENT '分组创建时间',
  `uniacid` int(11) DEFAULT '0',
  PRIMARY KEY (`grouping_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员ID',
  `open_id` varchar(225) DEFAULT NULL COMMENT '小程序获取的微信openid',
  `create_time` int(11) DEFAULT '0' COMMENT '注册时间',
  `member_status` tinyint(5) DEFAULT '1' COMMENT '0：删除：1：正常，2：黑名单',
  `member_name` varchar(255) DEFAULT NULL COMMENT '会员名称',
  `member_head_portrait` tinytext COMMENT '会员头像',
  `member_mobile` varchar(255) DEFAULT NULL COMMENT '会员手机',
  `member_is_bind` tinyint(5) DEFAULT '0' COMMENT '0：未绑定，1：已绑定',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `uniacid` int(11) DEFAULT NULL,
  `is_system` int(5) DEFAULT '0' COMMENT '0,不是系统管理员，1，是系统管理员，前台可上传广场内容',
  `grouping_id` int(11) DEFAULT '0' COMMENT '用户分组ID，看查看内容根据此ID区分',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';
CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_operation_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `identity` varchar(255) DEFAULT '0' COMMENT 'user:用户',
  `operation` varchar(255) DEFAULT '0' COMMENT 'xz:下载,sz：收藏',
  `content_id` int(11) DEFAULT '0' COMMENT '内容ID',
  `member_id` int(11) DEFAULT '0' COMMENT '会员id',
  `content_class` int(5) DEFAULT '0' COMMENT '1：广场内容，2：圈子内容',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `uniacid` int(11) DEFAULT NULL,
  `status` int(5) DEFAULT NULL COMMENT '1收藏，2取消，3下载',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏，下载';
CREATE TABLE IF NOT EXISTS `ims_myxs_fodder_system` (
  `system_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统设置ID',
  `system_code` varchar(255) DEFAULT '0' COMMENT '设置识别码，sms:短信',
  `system_content` text COMMENT '设置内容',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `system` text COMMENT '基础默认设置',
  `uniacid` int(11) DEFAULT '0',
  PRIMARY KEY (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists("myxs_fodder_class", "class_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_class")." ADD   `class_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID';");
}
if(!pdo_fieldexists("myxs_fodder_class", "class_name")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_class")." ADD   `class_name` varchar(255) DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists("myxs_fodder_class", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_class")." ADD   `create_time` int(11) DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_fieldexists("myxs_fodder_class", "class_status")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_class")." ADD   `class_status` int(5) DEFAULT '1' COMMENT '分类状态：1启用，2删除';");
}
if(!pdo_fieldexists("myxs_fodder_class", "circle_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_class")." ADD   `circle_id` int(11) DEFAULT '0' COMMENT '0：广场分类，非0为圈子ID且为圈子分类';");
}
if(!pdo_fieldexists("myxs_fodder_class", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_class")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("myxs_fodder_content", "content_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `content_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '内容ID';");
}
if(!pdo_fieldexists("myxs_fodder_content", "circle_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `circle_id` int(11) DEFAULT '0' COMMENT '圈子ID';");
}
if(!pdo_fieldexists("myxs_fodder_content", "class_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `class_id` int(11) DEFAULT '0' COMMENT '分类ID';");
}
if(!pdo_fieldexists("myxs_fodder_content", "member_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `member_id` int(11) DEFAULT '0' COMMENT '会员ID';");
}
if(!pdo_fieldexists("myxs_fodder_content", "content")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `content` text COMMENT '图片，视频地址';");
}
if(!pdo_fieldexists("myxs_fodder_content", "content_status")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `content_status` int(5) DEFAULT '1' COMMENT '0：删除，1：显示，2隐藏';");
}
if(!pdo_fieldexists("myxs_fodder_content", "content_class")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `content_class` int(5) DEFAULT NULL COMMENT '1：广场内容，2圈子内容';");
}
if(!pdo_fieldexists("myxs_fodder_content", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `create_time` int(11) DEFAULT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists("myxs_fodder_content", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `update_time` int(11) DEFAULT NULL COMMENT '更新时间';");
}
if(!pdo_fieldexists("myxs_fodder_content", "clnb")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `clnb` int(11) DEFAULT '0' COMMENT '收藏数量';");
}
if(!pdo_fieldexists("myxs_fodder_content", "donnb")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `donnb` int(11) DEFAULT '0' COMMENT '下载数量';");
}
if(!pdo_fieldexists("myxs_fodder_content", "sharenb")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `sharenb` int(11) DEFAULT '0' COMMENT '分享数量';");
}
if(!pdo_fieldexists("myxs_fodder_content", "text")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `text` text COMMENT '文本内容';");
}
if(!pdo_fieldexists("myxs_fodder_content", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("myxs_fodder_content", "fictitious_clnb")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `fictitious_clnb` int(11) DEFAULT '0' COMMENT '虚拟收藏量';");
}
if(!pdo_fieldexists("myxs_fodder_content", "fictitious_donnb")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `fictitious_donnb` int(11) DEFAULT '0' COMMENT '虚拟下载数量';");
}
if(!pdo_fieldexists("myxs_fodder_content", "fictitious_sharenb")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `fictitious_sharenb` int(11) DEFAULT '0' COMMENT '虚拟分享数量';");
}
if(!pdo_fieldexists("myxs_fodder_content", "type")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `type` varchar(255) DEFAULT 'img' COMMENT 'img:图片，video:视频，voice：音频';");
}
if(!pdo_fieldexists("myxs_fodder_content", "grouping_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_content")." ADD   `grouping_id` int(11) DEFAULT '0' COMMENT '用户分组ID，看查看内容根据此ID区分';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "sign_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `sign_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日签ID';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "sign_title")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `sign_title` varchar(255) DEFAULT NULL COMMENT '日签标题';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "sign_content")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `sign_content` text COMMENT '日签内容';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "sign_img")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `sign_img` varchar(255) DEFAULT NULL COMMENT '日签图片';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "display_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `display_time` int(11) DEFAULT NULL COMMENT '指定显示时间';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `create_time` int(11) DEFAULT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "sign_status")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `sign_status` tinyint(5) DEFAULT '1' COMMENT '0：删除，1：显示';");
}
if(!pdo_fieldexists("myxs_fodder_day_sign", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_day_sign")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("myxs_fodder_grouping", "grouping_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_grouping")." ADD   `grouping_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分组ID';");
}
if(!pdo_fieldexists("myxs_fodder_grouping", "grouping_name")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_grouping")." ADD   `grouping_name` varchar(255) DEFAULT '0' COMMENT '分组名称';");
}
if(!pdo_fieldexists("myxs_fodder_grouping", "grouping_passwd")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_grouping")." ADD   `grouping_passwd` varchar(255) DEFAULT '0' COMMENT '分组邀请码';");
}
if(!pdo_fieldexists("myxs_fodder_grouping", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_grouping")." ADD   `create_time` int(11) DEFAULT '0' COMMENT '分组创建时间';");
}
if(!pdo_fieldexists("myxs_fodder_grouping", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_grouping")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("myxs_fodder_member", "member_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `member_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员ID';");
}
if(!pdo_fieldexists("myxs_fodder_member", "open_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `open_id` varchar(225) DEFAULT NULL COMMENT '小程序获取的微信openid';");
}
if(!pdo_fieldexists("myxs_fodder_member", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `create_time` int(11) DEFAULT '0' COMMENT '注册时间';");
}
if(!pdo_fieldexists("myxs_fodder_member", "member_status")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `member_status` tinyint(5) DEFAULT '1' COMMENT '0：删除：1：正常，2：黑名单';");
}
if(!pdo_fieldexists("myxs_fodder_member", "member_name")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `member_name` varchar(255) DEFAULT NULL COMMENT '会员名称';");
}
if(!pdo_fieldexists("myxs_fodder_member", "member_head_portrait")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `member_head_portrait` tinytext COMMENT '会员头像';");
}
if(!pdo_fieldexists("myxs_fodder_member", "member_mobile")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `member_mobile` varchar(255) DEFAULT NULL COMMENT '会员手机';");
}
if(!pdo_fieldexists("myxs_fodder_member", "member_is_bind")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `member_is_bind` tinyint(5) DEFAULT '0' COMMENT '0：未绑定，1：已绑定';");
}
if(!pdo_fieldexists("myxs_fodder_member", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `update_time` int(11) DEFAULT NULL COMMENT '更新时间';");
}
if(!pdo_fieldexists("myxs_fodder_member", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("myxs_fodder_member", "is_system")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `is_system` int(5) DEFAULT '0' COMMENT '0,不是系统管理员，1，是系统管理员，前台可上传广场内容';");
}
if(!pdo_fieldexists("myxs_fodder_member", "grouping_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_member")." ADD   `grouping_id` int(11) DEFAULT '0' COMMENT '用户分组ID，看查看内容根据此ID区分';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "log_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "identity")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `identity` varchar(255) DEFAULT '0' COMMENT 'user:用户';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "operation")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `operation` varchar(255) DEFAULT '0' COMMENT 'xz:下载,sz：收藏';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "content_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `content_id` int(11) DEFAULT '0' COMMENT '内容ID';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "member_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `member_id` int(11) DEFAULT '0' COMMENT '会员id';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "content_class")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `content_class` int(5) DEFAULT '0' COMMENT '1：广场内容，2：圈子内容';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `create_time` int(11) DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("myxs_fodder_operation_log", "status")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_operation_log")." ADD   `status` int(5) DEFAULT NULL COMMENT '1收藏，2取消，3下载';");
}
if(!pdo_fieldexists("myxs_fodder_system", "system_id")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_system")." ADD   `system_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统设置ID';");
}
if(!pdo_fieldexists("myxs_fodder_system", "system_code")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_system")." ADD   `system_code` varchar(255) DEFAULT '0' COMMENT '设置识别码，sms:短信';");
}
if(!pdo_fieldexists("myxs_fodder_system", "system_content")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_system")." ADD   `system_content` text COMMENT '设置内容';");
}
if(!pdo_fieldexists("myxs_fodder_system", "create_time")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_system")." ADD   `create_time` int(11) DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_fieldexists("myxs_fodder_system", "system")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_system")." ADD   `system` text COMMENT '基础默认设置';");
}
if(!pdo_fieldexists("myxs_fodder_system", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("myxs_fodder_system")." ADD   `uniacid` int(11) DEFAULT '0';");
}

 ?>