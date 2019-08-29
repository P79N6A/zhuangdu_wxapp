<?php
/**
 * 热搜
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman
 * @ www.linglingtang.com
 */
if(file_exists(EWEI_SHOPV2_PATH."plugin/yoxhotsearch/install.lock")){
    die('应用已安装，如需重新安装请删除应用目录下的install.lock文件<br/>更多应用请关注作者公众号——零零糖<br/>爱你哟~');
}

//安装失败请手动在数据库执行以下代码
$sql="
INSERT INTO `ims_ewei_shop_plugin` SET displayorder=100,identity='yoxhotsearch',category='help',name='商品热搜',version='1.0',author='陈永鹏',status=1,thumb='../addons/ewei_shopv2/static/images/qa.jpg',desc='仿朋友圈样式，提供微商素材',iscom=0,deprecated=0,isv2=0;
DROP TABLE IF EXISTS `ims_ewei_shop_yoxhotsearch`;
CREATE TABLE `ims_ewei_shop_yoxhotsearch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'GOODS/SHOP',
  `keyword` varchar(50) DEFAULT NULL,
  `is_hot` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` tinyint(3) DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='热搜 | Yoper add';

";
pdo_run($sql);
file_put_contents(EWEI_SHOPV2_PATH."plugin/yoxhotsearch/install.lock",''); 
die('安装成功.');