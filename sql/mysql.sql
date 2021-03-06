#
# Table structure for table 'surnames_register'
#

CREATE TABLE  `surnames_register` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `surname` varchar(30) NOT NULL DEFAULT '',
  `notes` varchar(120) NOT NULL DEFAULT '',
  `comment_count` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `approved` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `changed_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`surname`, `name`, `email`),
  KEY `surname` (`surname`)
) ENGINE=MyISAM;
