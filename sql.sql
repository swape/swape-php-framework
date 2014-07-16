--
-- Table structure for table `sf_user`
--

CREATE TABLE `sf_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) NOT NULL,
  `utoken` varchar(255) NOT NULL,
  `upass` varchar(255) NOT NULL,
  `lastseen` datetime NOT NULL,
  `ufullname` varchar(255) NOT NULL,
  `umail` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `uname` (`uname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
