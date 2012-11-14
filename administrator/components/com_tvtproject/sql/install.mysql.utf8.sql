DROP TABLE IF EXISTS `#__tvtproject`;
 
CREATE TABLE `#__tvtproject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `publish` int(1) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `params` TEXT NOT NULL DEFAULT '',
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__tvtproject` (`name`) VALUES
        ('Dự án 1'),
        ('Dự án 2');
