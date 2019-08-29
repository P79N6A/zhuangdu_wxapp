 <?php
 function upsql(){
     if(!pdo_fieldexists('xc_beauty_discuss', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1服务项目2技师）'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'member_discuss')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `member_discuss` int(11) DEFAULT '-1' COMMENT '人员评论'");
     }
     if(!pdo_tableexists('xc_beauty_zan')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_zan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '技师id',
  `status` int(11) DEFAULT '-1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点赞记录';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_beauty_store_member', 'tag')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `tag` varchar(255) DEFAULT NULL COMMENT '标签'");
     }
     if(!pdo_fieldexists('xc_beauty_store_member', 'zan')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `zan` int(11) DEFAULT '0' COMMENT '点赞人数'");
     }
     if(!pdo_fieldexists('xc_beauty_store_member', 'content')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `content` longtext COMMENT '个人简介'");
     }
     if(!pdo_fieldexists('xc_beauty_store_member', 'discuss')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `discuss` int(11) DEFAULT '0' COMMENT '评论数'");
     }
     if(!pdo_fieldexists('xc_beauty_discuss', 'status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_discuss')." ADD `status` int(11) DEFAULT '1' COMMENT '状态'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'form_id2')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `form_id2` varchar(255) DEFAULT NULL");
     }
     if(!pdo_fieldexists('xc_beauty_withdraw', 'out_trade_no')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号'");
     }
     if(!pdo_fieldexists('xc_beauty_withdraw', 'wx_out_trade_no')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `wx_out_trade_no` varchar(50) DEFAULT NULL COMMENT '微信订单号'");
     }
     if(!pdo_fieldexists('xc_beauty_withdraw', 'content')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_withdraw')." ADD `content` longtext COMMENT '错误详情'");
     }
     if(!pdo_fieldexists('xc_beauty_store_service', 'sale_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `sale_status` int(11) DEFAULT '-1' COMMENT '折扣状态'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'sale_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `sale_status` int(11) DEFAULT '-1' COMMENT '折扣状态'");
     }
     if(!pdo_fieldexists('xc_beauty_store', 'sms')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `sms` varchar(50) DEFAULT NULL COMMENT '短信接收手机号'");
     }
     if(!pdo_fieldexists('xc_beauty_store', 'machine_code')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `machine_code` varchar(255) DEFAULT NULL COMMENT '打印机终端号'");
     }
     if(!pdo_fieldexists('xc_beauty_store', 'msign')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `msign` varchar(255) DEFAULT NULL COMMENT '打印机终端密钥'");
     }
     if(!pdo_fieldexists('xc_beauty_store', 'sn')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `sn` varchar(255) DEFAULT NULL COMMENT '打印机编号'");
     }
     if(!pdo_fieldexists('xc_beauty_store', 'print_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `print_status` int(11) DEFAULT '-1' COMMENT '打印状态'");
     }
     if(!pdo_fieldexists('xc_beauty_store_service', 'member')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_service')." ADD `member` varchar(50) DEFAULT NULL COMMENT '抵扣预约人数'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'can_member')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `can_member` int(11) DEFAULT '1' COMMENT '预约人数'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'he_log')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `he_log` longtext COMMENT '核销记录'");
     }
     if(!pdo_fieldexists('xc_beauty_prize', 'member')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_prize')." ADD `member` int(11) DEFAULT '-1' COMMENT '数量'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'wq_out_trade_no')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `wq_out_trade_no` varchar(50) DEFAULT NULL");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'failtime')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `failtime` datetime DEFAULT NULL COMMENT '失效时间'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'failstatus')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `failstatus` int(11) DEFAULT '1' COMMENT '失效处理状态'");
     }
     if(!pdo_fieldexists('xc_beauty_count', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_count')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1月份2日期）'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'flash')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `flash` int(11) DEFAULT '-1' COMMENT '限时抢购'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'flash_price')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `flash_price` decimal(10,2) DEFAULT NULL COMMENT '抢购价格'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_status` int(11) DEFAULT '-1' COMMENT '限时状态'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_price')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_price` decimal(10,2) DEFAULT '0.00' COMMENT '抢购价格'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_start')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_start` datetime DEFAULT NULL COMMENT '开始时间'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_end')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_end` datetime DEFAULT NULL COMMENT '结束时间'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_member')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_member` int(11) DEFAULT NULL COMMENT '库存'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_index')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_index` int(11) DEFAULT '-1' COMMENT '首页显示'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_order')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_order` int(11) DEFAULT '0' COMMENT '每人限买单数'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'flash_shu')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `flash_shu` int(11) DEFAULT '0' COMMENT '每单限购数'");
     }
     if(!pdo_fieldexists('xc_beauty_userinfo', 'card_name')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card_name` varchar(50) DEFAULT NULL COMMENT '会员等级'");
     }
     if(!pdo_fieldexists('xc_beauty_userinfo', 'card_price')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card_price` varchar(50) DEFAULT NULL COMMENT '会员折扣'");
     }
     if(!pdo_fieldexists('xc_beauty_userinfo', 'card_amount')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_userinfo')." ADD `card_amount` decimal(10,2) DEFAULT '0.00' COMMENT '消费金额'");
     }
     if(!pdo_fieldexists('xc_beauty_article', 'link_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_article')." ADD `link_type` int(11) DEFAULT '1' COMMENT '模式'");
     }
     if(!pdo_tableexists('xc_beauty_shop')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `total` int(11) DEFAULT '0' COMMENT '数量',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_beauty_pick_service')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pick_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL COMMENT '分类',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `unit` varchar(50) DEFAULT NULL COMMENT '单位',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配货';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_beauty_pick_order')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pick_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `store` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `out_trade_no` varchar(50) DEFAULT NULL COMMENT '订单号',
  `pid` longtext COMMENT '产品',
  `total` int(11) DEFAULT '0' COMMENT '数量',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '总价',
  `status` int(11) DEFAULT '-1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_beauty_pick_class')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pick_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配货分类';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_beauty_order', 'price')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `price` decimal(10,2) DEFAULT NULL COMMENT '单价'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'parameter')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `parameter` longtext COMMENT '参数'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'content_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `content_type` int(11) DEFAULT '1' COMMENT '详情模式'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'content2')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `content2` longtext COMMENT '详情2'");
     }

     if(!pdo_fieldexists('xc_beauty_banner', 'appid')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_banner')." ADD `appid` varchar(255) DEFAULT NULL COMMENT '小程序id'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'sold')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `sold` int(11) DEFAULT '0' COMMENT '已售数'");
     }
     if(!pdo_fieldexists('xc_beauty_store', 'code')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store')." ADD `code` varchar(255) DEFAULT NULL COMMENT '买单二维码'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'recharge_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `recharge_type` int(11) DEFAULT '1' COMMENT '充值方式（1会员充值2管理员充值）'");
     }
     if(!pdo_fieldexists('xc_beauty_order', 'recharge_openid')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_order')." ADD `recharge_openid` varchar(50) DEFAULT NULL COMMENT '待充的用户id'");
     }
     //2018-08-10
     if(!pdo_fieldexists('xc_beauty_store_member', 'pai_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `pai_status` int(11) DEFAULT '-1' COMMENT '单双周状态'");
     }
     if(!pdo_fieldexists('xc_beauty_store_member', 'pai1')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `pai1` int(11) DEFAULT NULL COMMENT '排版'");
     }
     if(!pdo_fieldexists('xc_beauty_store_member', 'pai2')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_store_member')." ADD `pai2` int(11) DEFAULT NULL COMMENT '排版2'");
     }
     if(!pdo_tableexists('xc_beauty_pai')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `midflytime` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='排班';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_beauty_pai_detail')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_pai_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `weeknum` int(11) DEFAULT NULL COMMENT '1-7，表示周一到周天',
  `time1start` varchar(50) DEFAULT NULL COMMENT '开始时间',
  `time1end` varchar(50) DEFAULT NULL,
  `time2start` varchar(50) DEFAULT NULL,
  `time2end` varchar(50) DEFAULT NULL,
  `time3start` varchar(50) DEFAULT NULL,
  `time3end` varchar(50) DEFAULT NULL,
  `time4start` varchar(50) DEFAULT NULL,
  `time4end` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`status`,`createtime`,`weeknum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='排班详情';
EOT;
         pdo_run($sql);
     }
     //2018-08-22
     if(!pdo_fieldexists('xc_beauty_service', 'group_stock')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_stock` int(11) DEFAULT '-1' COMMENT '团购库存'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'group_head_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_head_status` int(11) DEFAULT '-1' COMMENT '团长优惠'");
     }
     if(!pdo_fieldexists('xc_beauty_service', 'group_head_price')) {
         pdo_query("ALTER TABLE ".tablename('xc_beauty_service')." ADD `group_head_price` varchar(50) DEFAULT NULL COMMENT '团长优惠价格'");
     }
     if(!pdo_tableexists('xc_beauty_moban_user')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_moban_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `nickname` varchar(500) DEFAULT NULL COMMENT '呢称',
  `status` int(11) DEFAULT '-1' COMMENT '-1未使用  1已使用',
  `createtime` int(11) DEFAULT NULL COMMENT '发布日期',
  `ident` varchar(50) DEFAULT NULL COMMENT '标识',
  `headimgurl` varchar(500) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='绑定模版消息用户';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_beauty_onlines')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_onlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `member` int(11) DEFAULT NULL COMMENT '未读条数',
  `type` int(11) DEFAULT NULL COMMENT '类型',
  `content` longtext COMMENT '内容',
  `updatetime` varchar(50) DEFAULT NULL COMMENT '更新时间',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`createtime`,`member`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客服';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_beauty_online_log')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_beauty_online_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '发送者用户id',
  `type` int(11) DEFAULT NULL COMMENT '类型1文本2图片',
  `content` longtext COMMENT '内容',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `duty` int(11) DEFAULT '1' COMMENT '身份1客户2客服',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`type`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客服记录';
EOT;
         pdo_run($sql);
     }

     $json = array('status' => 1, 'msg' => '更新成功');
     echo json_encode($json);
 }

