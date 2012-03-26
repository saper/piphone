

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `pass` varchar(64) NOT NULL COMMENT 'PASSWORD()-encrypted password',
  `email` varchar(255) NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


INSERT INTO user SET 
       `login`='admin', `pass`=PASSWORD('admin'), 
       `email`='admin@lqdn.fr', `enabled`=1, `admin`=1;

