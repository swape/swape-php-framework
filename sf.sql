 CREATE TABLE sf_user (
  uid int(11) NOT NULL AUTO_INCREMENT,
  uname varchar(255) NOT NULL,
  utoken varchar(255) NOT NULL,
  upass varchar(255) NOT NULL,
  lastseen datetime NOT NULL,
  ufullname varchar(255) NOT NULL,
  umail varchar(255) NOT NULL,
  PRIMARY KEY (uid),
  KEY uname (uname)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE sf_menu (
  uid int(11) NOT NULL,
  module_title varchar(255) NOT NULL,
  module_name varchar(255) NOT NULL,
  KEY uid (uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;