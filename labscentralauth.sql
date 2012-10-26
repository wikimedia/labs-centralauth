CREATE TABLE labscentralauth(
  lactype VARCHAR(6) NOT NULL,
  lacwiki_instance VARCHAR(50) NOT NULL,
  lacstatus VARCHAR(10) NOT NULL,
  lacuserid INT(10) NULL,
  lactime INT(30) NULL,
  lacurl VARCHAR(255) NOT NULL,
  lacsystempath VARCHAR(255) NOT NULL,
  PRIMARY KEY(lactype, lacwiki_instance) )
  ENGINE = innodb;