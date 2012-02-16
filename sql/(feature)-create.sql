CREATE TABLE IF NOT EXISTS `ef_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ; 

CREATE TABLE IF NOT EXISTS `ef_stock_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` tinytext,
  `thumb` tinytext,
  `stock_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;