如何使用本应用插件
1.复制 yoxwechatbusiness 代码,放在addons/ewei_shopv2/plugin目录下.
2.在表 ims_ewei_shop_plugin确认是否有 identity为yoxwechatbusiness的记录，没有则 加一条记录  SQL:INSERT INTO `ims_ewei_shop_plugin` (`identity`,`category`,`name`,`version`,`author`,`status`,`thumb`) VALUES ('yoxwechatbusiness','help','微商','1.0','优拓','1','../addons/ewei_shopv2/static/images/qa.jpg');
3.清除缓存，微擎后台->系统->缓存->更新缓存，勾选数据缓存，提交.
