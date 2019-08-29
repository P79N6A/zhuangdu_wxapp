<?php
/**
 * 图文视频
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
if(file_exists(EWEI_SHOPV2_PATH."plugin/yoxarticlevideo/install.lock")){
    die('应用已安装，如需重新安装请删除应用目录下的install.lock文件<br/>更多应用请关注作者公众号——零零糖<br/>爱你哟~');
}

//安装失败请手动在数据库执行以下代码
$sql="
INSERT INTO `ims_ewei_shop_plugin` SET displayorder=100,identity='yoxarticlevideo',category='help',name='图文视频专区',version='1.0',author='陈永鹏',status=1,thumb='../addons/ewei_shopv2/static/images/qa.jpg',desc='提供图文视频',iscom=0,deprecated=0,isv2=0;
DROP TABLE IF EXISTS `ims_ewei_shop_yoxarticlevideo_article`;
CREATE TABLE `ims_ewei_shop_yoxarticlevideo_article` (
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
  `displayorder` tinyint(2) DEFAULT '50',
  `add_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='视频专区文章|Yoper add';


";
pdo_run($sql);
file_put_contents(EWEI_SHOPV2_PATH."plugin/yoxarticlevideo/install.lock",''); 
die('安装成功.');