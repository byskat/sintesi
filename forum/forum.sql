CREATE SCHEMA IF NOT EXISTS `forum` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `forum` ;

CREATE TABLE `forum_question` (
`id` int(4) NOT NULL auto_increment,
`topic` varchar(255) NOT NULL default '',
`detail` longtext NOT NULL,
`name` varchar(65) NOT NULL default '',
`email` varchar(65) NOT NULL default '',
`datetime` varchar(25) NOT NULL default '',
`view` int(4) NOT NULL default '0',
`reply` int(4) NOT NULL default '0',
PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `forum_answer` (
`question_id` int(4) NOT NULL default '0',
`a_id` int(4) NOT NULL default '0',
`a_name` varchar(65) NOT NULL default '',
`a_email` varchar(65) NOT NULL default '',
`a_answer` longtext NOT NULL,
`a_datetime` varchar(25) NOT NULL default '',
KEY `a_id` (`a_id`)
) ENGINE = InnoDB;