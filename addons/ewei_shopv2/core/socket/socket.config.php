<?php

/**
 * socket server配置文件，重启后生效
 * https://www.bipimi.cn
 * 个人公众号 零零糖 欢迎关注
 */

// 开发模式开关
define('SOCKET_SERVER_DEBUG', true);

// 设置服务端IP
define('SOCKET_SERVER_IP', 'localhost');

// 设置服务端端口
define('SOCKET_SERVER_PORT', '9501');

// 设置是否启用SSL
define('SOCKET_SERVER_SSL', true);

// 设置SSL KEY文件路径
define('SOCKET_SERVER_SSL_KEY_FILE', '/www/wdlinux/nginx/conf/cert/zdu.igdlrj.com.key');// /www/wdlinux/nginx/conf/cert/zdu.igdlrj.com.key、/etc/letsencrypt/live/weengine.linglingtang.com/privkey.pem

// 设置SSL CERT文件路径
define('SOCKET_SERVER_SSL_CERT_FILE', '/www/wdlinux/nginx/conf/cert/zdu.igdlrj.com.crt');// /www/wdlinux/nginx/conf/cert/zdu.igdlrj.com.crt、/etc/letsencrypt/live/weengine.linglingtang.com/fullchain.pem

// 设置启动的worker进程数
define('SOCKET_SERVER_WORKNUM', 8);

// 设置客户端请求IP
define('SOCKET_CLIENT_IP', 'zdu.igdlrj.com');   //请将域名换成你自己的 zdu.igdlrj.com、weengine.linglingtang.com