-- Added By Pankaj 
CREATE TABLE IF NOT EXISTS `region_mast` (
  `id` char(36) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `sap_code` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_region_name` (`name`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `respforce`.`team_region` (
`id` varchar( 36 ) NOT NULL ,
`team_id` varchar( 36 ) DEFAULT NULL ,
`user_id` varchar( 36 ) DEFAULT NULL ,
`date_modified` datetime DEFAULT NULL ,
`deleted` tinyint( 1 ) DEFAULT '0',
PRIMARY KEY ( `id` ) ,
KEY `idx_team_id` ( `team_id` ) ,
KEY `idx_user_id` ( `user_id` )
) ENGINE = MYISAM DEFAULT CHARSET = utf8;


ALTER TABLE `team_region` CHANGE `user_id` `region_id` VARCHAR( 36 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 

ALTER TABLE `prospect_lists` CHANGE `domain_name` `list_type_value` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 


ALTER TABLE `prospect_lists` ADD `start_date` DATE NULL AFTER `list_type_value` ,
ADD `end_date` DATE NULL AFTER `start_date` 



CREATE TABLE IF NOT EXISTS `team_city` (
  `id` varchar(36) NOT NULL,
  `team_id` varchar(36) DEFAULT NULL,
  `city_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_team_id` (`team_id`),
  KEY `idx_city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS `team_brand` (
  `id` varchar(36) NOT NULL,
  `team_id` varchar(36) DEFAULT NULL,
  `brand_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_team_id` (`team_id`),
  KEY `idx_user_id` (`brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `campaigns` ADD `product_id` VARCHAR( 36 ) NULL 

ALTER TABLE `experience_mast` ADD `exp_min` INT( 3 ) NULL AFTER `name` 
ALTER TABLE `experience_mast` ADD `exp_max` INT( 3 ) NULL AFTER `name` 

ALTER TABLE `brands` ADD `price` DECIMAL( 15, 2 ) NULL DEFAULT NULL AFTER `prod_hier_desc` 

ALTER TABLE `teams` CHANGE `level` `level_id` VARCHAR( 36 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `experience` `experience_id` VARCHAR( 36 ) NULL DEFAULT NULL ,
CHANGE `language` `language_id` VARCHAR( 36 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 

--added  Jion table of campaign table
CREATE TABLE IF NOT EXISTS `campaign_vendor` (
  `id` varchar(36) NOT NULL,
  `campaign_id` varchar(36) DEFAULT NULL,
  `vendor_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_campaign_id` (`campaign_id`),
  KEY `idx_vendor_id` (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `campaign_vendor_cstm` (
  `id_c` char(36) NOT NULL,
  `assigned_team_id_c` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id_c`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

ALTER TABLE `calls` CHANGE `brand_id` `campaign_id` CHAR( 36 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 



ALTER TABLE `calls` ADD `not_interested_region` VARCHAR( 50 ) NULL AFTER `id` ,
ADD `call_back_date` DATE NULL AFTER `not_interested_region` ,
ADD `call_back_time` TIME NULL AFTER `call_back_date` 



ALTER TABLE `campaign_vendor` ADD `percentage` DOUBLE( 10, 2 ) NOT NULL AFTER `date_modified` ,
ADD `date_entered` DATETIME NOT NULL AFTER `percentage` ;

ALTER TABLE `campaign_vendor` ADD `assigned_user_id` VARCHAR( 36 ) NULL 


ALTER TABLE `campaign_vendor` ADD `created_by` VARCHAR( 36 ) NULL 

ALTER TABLE  `leads` CHANGE  `level`  `level` CHAR( 36 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL


CREATE TABLE IF NOT EXISTS `prospect_lists` (
  `id` char(36) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `description` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `parent_type` varchar(25) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `populate_lead_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_prospect_list_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--Added by Pankaj

ALTER TABLE `campaigns` ADD `vendor_file_status` INT NOT NULL DEFAULT '0'

ALTER TABLE `campaigns` ADD `send_email` INT( 10 ) NOT NULL DEFAULT '0'

--Added By Yogesh
CREATE TABLE IF NOT EXISTS `campaigns_leads` (
  `id` varchar(36) NOT NULL,
  `campaign_id` varchar(36) DEFAULT NULL,
  `lead_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_lead_id` (`lead_id`),
  KEY `idx_campaign_id` (`campaign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `team_state` (
  `id` varchar(36) NOT NULL,
  `team_id` varchar(36) DEFAULT NULL,
  `state_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_team_id` (`team_id`),
  KEY `idx_state_id` (`state_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8


ALTER TABLE `teams` CHANGE `email` `email` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL



CREATE TABLE IF NOT EXISTS `team_language` (
  `id` varchar(36) NOT NULL,
  `team_id` varchar(36) DEFAULT NULL,
  `language_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_team_id` (`team_id`),
  KEY `idx_level_id` (`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `team_experience` (
  `id` varchar(36) NOT NULL,
  `team_id` varchar(36) DEFAULT NULL,
  `experience_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_team_id` (`team_id`),
  KEY `idx_state_id` (`experience_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE IF NOT EXISTS `team_level` (
  `id` varchar(36) NOT NULL,
  `team_id` varchar(36) DEFAULT NULL,
  `level_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_team_id` (`team_id`),
  KEY `idx_level_id` (`level_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

-- 05-12-12
CREATE TABLE IF NOT EXISTS `lead_import_schedulers_times` (
  `id` char(36) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date_entered` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `scheduler_id` char(36) NOT NULL,
  `execute_time` datetime NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'ready',
  `tot_csv_record` int(10) DEFAULT NULL,
  `tot_inserted_record` int(10) DEFAULT NULL,
  `tot_updated_record` int(10) DEFAULT NULL,
  `log_file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_scheduler_id` (`scheduler_id`,`execute_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--Pankaj
CREATE TABLE IF NOT EXISTS `brands_faq` (
  `id` varchar(36) CHARACTER SET utf8 NOT NULL,
  `brand_id` varchar(36) CHARACTER SET utf8 DEFAULT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `created_by` varchar(36) CHARACTER SET utf8 NOT NULL,
  `date_entered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) CHARACTER SET utf8 DEFAULT NULL,
  `assigned_user_id` char(36) CHARACTER SET utf8 DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',  
  PRIMARY KEY (`id`),
  KEY `idx_brand_id_del` (`id`,`deleted`)  
) ENGINE=MyISAM DEFAULT CHARSET=latin1

ALTER TABLE `brands` ADD faq TEXT utf8_general_ci NULL DEFAULT NULL;


CREATE TABLE IF NOT EXISTS `lead_brand_sold` (
  `id` varchar(36) CHARACTER SET utf8 NOT NULL,
  `lead_id` varchar(36) CHARACTER SET utf8 DEFAULT NULL,
  `brand_id` varchar(36) CHARACTER SET utf8 NOT NULL,
  `created_by` varchar(36) CHARACTER SET utf8 NOT NULL,
  `date_entered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) CHARACTER SET utf8 DEFAULT NULL,
  `assigned_user_id` char(36) CHARACTER SET utf8 DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_lead_brand_sold_id_del` (`id`,`deleted`),
  KEY `idx_lead_brand_sold_assigned_del` (`deleted`,`assigned_user_id`),
  KEY `idx_lead_brand_sold_lead_id` (`lead_id`),
  KEY `idx_lead_brand_sold_id` (`brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;