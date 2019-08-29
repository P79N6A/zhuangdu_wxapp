<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_aaidybnt_testapi` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned NOT NULL,
`acid` int(10) unsigned NOT NULL,
`num` int(10) unsigned NOT NULL,
`content` varchar(1000) NOT NULL,
PRIMARY KEY (`id`),
KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('ims_aaidybnt_testapi')) {
	if(!pdo_fieldexists('ims_aaidybnt_testapi',  'id')) {
		pdo_query("ALTER TABLE `ims_aaidybnt_testapi` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_aaidybnt_testapi')) {
	if(!pdo_fieldexists('ims_aaidybnt_testapi',  'rid')) {
		pdo_query("ALTER TABLE `ims_aaidybnt_testapi` ADD `rid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_aaidybnt_testapi')) {
	if(!pdo_fieldexists('ims_aaidybnt_testapi',  'acid')) {
		pdo_query("ALTER TABLE `ims_aaidybnt_testapi` ADD `acid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_aaidybnt_testapi')) {
	if(!pdo_fieldexists('ims_aaidybnt_testapi',  'num')) {
		pdo_query("ALTER TABLE `ims_aaidybnt_testapi` ADD `num` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_aaidybnt_testapi')) {
	if(!pdo_fieldexists('ims_aaidybnt_testapi',  'content')) {
		pdo_query("ALTER TABLE `ims_aaidybnt_testapi` ADD `content` varchar(1000) NOT NULL;");
	}	
}
