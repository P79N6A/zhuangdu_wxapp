<?php
/**
 * 早起挑战
 * @author Yoper 优拓科技
 * @e-mail chen.yong.peng@foxmail.com
 * @欢迎关注公众号 零零糖
 * @Wechat Yoperman 要进技术群加微信
 * @ www.linglingtang.com
 */
if(file_exists(EWEI_SHOPV2_PATH."plugin/yoxhotsearch/install.lock")){
    die('应用已安装，如需重新安装请删除应用目录下的install.lock文件<br/>更多应用请关注作者公众号——零零糖<br/>爱你哟~');
}

//安装失败请手动在数据库执行以下代码
$sql="
INSERT INTO `ims_ewei_shop_plugin` (`displayorder`, `identity`, `category`, `name`, `version`, `author`,`status`,`thumb`,`desc`,`iscom`,`deprecated`,`isv2`) VALUES ('100', 'yoxwakeupchallenge', 'help', '早起挑战', '1.0', '优拓',1,'../addons/ewei_shopv2/plugin/yoxdiy/yoxwakechallenge.jpg','',0,0,1)

";
pdo_run($sql);
file_put_contents(EWEI_SHOPV2_PATH."plugin/yoxwakeupllenge/install.lock",''); 
die('安装成功.');