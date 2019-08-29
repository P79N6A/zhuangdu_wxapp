<?php
/**
 * 精选朋友圈
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
if(file_exists(EWEI_SHOPV2_PATH."plugin/yoxfriendscircle/install.lock")){
    die('应用已安装，如需重新安装请删除应用目录下的install.lock文件<br/>更多应用请关注作者公众号——零零糖<br/>爱你哟~');
}

//安装失败请手动在数据库执行以下代码
$sql="
INSERT INTO `ims_ewei_shop_plugin` SET displayorder=100,identity='yoxfriendscircle',category='help',name='精选朋友圈',version='1.0',author='陈永鹏',status=1,thumb='../addons/ewei_shopv2/plugin/yoxfriendscircle/friendcircle.jpg',desc='仿朋友圈样式，提供微商素材',iscom=0,deprecated=0,isv2=0;
DROP TABLE IF EXISTS `ims_ewei_shop_yoxfriendscircle_article`;
CREATE TABLE `ims_ewei_shop_yoxfriendscircle_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号id',
  `merchid` int(11) DEFAULT '0' COMMENT '商户id',
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `cate_id` int(11) DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `thumbs` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `videos` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT '',
  `content` text NOT NULL,
  `url` varchar(255) DEFAULT '' COMMENT 'url',
  `is_featured` tinyint(4) DEFAULT '0' COMMENT '是否精选',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` tinyint(2) DEFAULT NULL,
  `add_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='精选朋友圈文章|Yoper add';

DROP TABLE IF EXISTS `ims_ewei_shop_yoxfriendscircle_attitude`;
CREATE TABLE `ims_ewei_shop_yoxfriendscircle_attitude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'ARTICLE',
  `target_id` int(11) DEFAULT NULL,
  `attitude` varchar(50) DEFAULT NULL COMMENT '态度 GOOD/BAD/SAD/LIKE',
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='精选朋友圈态度|Yoper add';

DROP TABLE IF EXISTS `ims_ewei_shop_yoxfriendscircle_carticle_cate`;
CREATE TABLE `ims_ewei_shop_yoxfriendscircle_carticle_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号id',
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `add_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='精选朋友圈分类|Yoper add';

DROP TABLE IF EXISTS `ims_ewei_shop_yoxfriendscircle_comment`;
CREATE TABLE `ims_ewei_shop_yoxfriendscircle_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(4) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1',
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='精选朋友圈评论 |Yoper add';

DROP TABLE IF EXISTS `ims_ewei_shop_yoxfriendscircle_follow`;
CREATE TABLE `ims_ewei_shop_yoxfriendscircle_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `followed_uid` int(11) DEFAULT '0' COMMENT '被关注',
  `followed_merchid` int(11) DEFAULT '0' COMMENT '被关注',
  `status` tinyint(2) DEFAULT '1',
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='精选朋友圈关注|Yoper add';


";
pdo_run($sql);
file_put_contents(EWEI_SHOPV2_PATH."plugin/yoxfriendscircle/install.lock",''); 
die('安装成功.');