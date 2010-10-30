CREATE TABLE IF NOT EXISTS `prefix_adminset` (
      `adminset_id` int(11) unsigned NOT NULL auto_increment,
      `adminset_key` varchar(100) NOT NULL,
      `adminset_val` text character set utf8 NOT NULL,
      PRIMARY KEY  (`adminset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  IF NOT EXISTS `prefix_adminban` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) NOT NULL,
  `banwarn` int(11) NOT NULL default '0',
  `bandate` datetime NOT NULL,
  `banline` datetime default NULL,
  `bancomment` varchar(255) default NULL,
  `banunlim` tinyint(4) NOT NULL default '0',
  `banactive` TINYINT DEFAULT '0' NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        
CREATE TABLE IF NOT EXISTS `prefix_adminips` (
  `id` bigint(20) NOT NULL auto_increment,
  `ip1` bigint(20) default NULL,
  `ip2` bigint(20) default '0',
  `bandate` datetime NOT NULL,
  `banline` datetime default NULL,
  `bancomment` varchar(255) default NULL,
  `banunlim` tinyint(4) NOT NULL default '0',
  `banactive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`ip1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_templates` (
  `tpl_id` bigint(20) NOT NULL auto_increment,
  `tpl_title` varchar(200) NOT NULL,
  `tpl_name` varchar(200) NOT NULL,
  `tpl_description` text NOT NULL,
  `tpl_category` varchar(200) NOT NULL,
  `tpl_date_add` datetime NOT NULL,
  `tpl_date_edit` datetime NULL,
  `tpl_price` float(9,3) NOT NULL DEFAULT '0.000',
  `tpl_avatar` varchar(200),
  PRIMARY KEY  (`tpl_id`),
  KEY `tpl_category` (`tpl_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_tplusers` (
  `tpl_type` enum('personal','collective') NOT NULL DEFAULT 'personal',
  `target_id` bigint(20) NOT NULL,
  `tpl_id` bigint(20) NOT NULL,
  KEY `target_id` (`target_id`),
  KEY `tpl_type` (`tpl_type`),
  KEY `tpl_id` (`tpl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_tplfav` (
  `tpl_type` enum('personal','collective') NOT NULL DEFAULT 'personal',
  `target_id` bigint(20) NOT NULL,
  `tpl_id` bigint(20) NOT NULL,
  KEY `target_id` (`target_id`),
  KEY `tpl_type` (`tpl_type`),
  KEY `tpl_id` (`tpl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_widgets` (
  `wid_id` bigint(20) NOT NULL auto_increment,
  `wid_title` varchar(200) NOT NULL,
  `wid_name` varchar(200) NOT NULL,
  `wid_description` text NOT NULL,
  `wid_category` varchar(200) NOT NULL,
  `wid_date_add` datetime NOT NULL,
  `wid_date_edit` datetime NULL,
  `wid_price` float(9,3) NOT NULL DEFAULT '0.000',
  `wid_avatar` varchar(200),
  PRIMARY KEY  (`wid_id`),
  KEY `wid_category` (`wid_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_widusers` (
  `wid_type` enum('personal','collective') NOT NULL DEFAULT 'personal',
  `target_id` bigint(20) NOT NULL,
  `wid_id` bigint(20) NOT NULL,
  `wid_date_add` datetime NOT NULL,
  KEY `target_id` (`target_id`),
  KEY `wid_type` (`wid_type`),
  KEY `wid_id` (`wid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_widfav` (
  `wid_type` enum('personal','collective') NOT NULL DEFAULT 'personal',
  `target_id` bigint(20) NOT NULL,
  `wid_id` bigint(20) NOT NULL,
  `wid_priority` bigint(20) NOT NULL,
  KEY `target_id` (`target_id`),
  KEY `wid_type` (`wid_type`),
  KEY `wid_id` (`wid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_decor` (
  `dec_id` bigint(20) NOT NULL auto_increment,
  `dec_title` varchar(200) NOT NULL,
  `dec_description` text NOT NULL,
  `dec_position` varchar(200) NOT NULL,
  `dec_category` varchar(200) NOT NULL,
  `dec_date_add` datetime NOT NULL,
  `dec_date_edit` datetime NULL,
  `dec_price` float(9,3) NOT NULL DEFAULT '0.000',
  `dec_avatar` varchar(200),
  PRIMARY KEY  (`dec_id`),
  KEY `dec_category` (`dec_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_decusers` (
  `dec_type` enum('personal','collective') NOT NULL DEFAULT 'personal',
  `target_id` bigint(20) NOT NULL,
  `dec_id` bigint(20) NOT NULL,
  KEY `target_id` (`target_id`),
  KEY `dec_type` (`dec_type`),
  KEY `dec_id` (`dec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `prefix_decfav` (
  `dec_type` enum('personal','collective') NOT NULL DEFAULT 'personal',
  `target_id` bigint(20) NOT NULL,
  `dec_id` bigint(20) NOT NULL,
  KEY `target_id` (`target_id`),
  KEY `dec_type` (`dec_type`),
  KEY `dec_id` (`dec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELETE FROM `prefix_adminset` WHERE `adminset_key`='version';
INSERT INTO `prefix_adminset` (`adminset_key`, `adminset_val`) VALUE ('version', '1.4') ;
