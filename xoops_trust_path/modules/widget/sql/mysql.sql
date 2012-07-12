CREATE TABLE `{prefix}_{dirname}_instance` (
  `instance_id` int(11) unsigned NOT NULL  auto_increment,
  `title` varchar(255) NOT NULL,
  `category_id` mediumint(8) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `template` varchar(100) NOT NULL,
  `options` text NOT NULL,
  `posttime` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`instance_id`)) ENGINE=MyISAM;

