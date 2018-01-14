CREATE TABLE extension (
  id VARCHAR(50) NOT NULL,
  vendor VARCHAR(100) NOT NULL,
  package VARCHAR(100) NOT NULL,
  version VARCHAR(20) DEFAULT NULL,
  description VARCHAR(200) DEFAULT NULL,
  activation TINYINT(1) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL,
  deleted_at DATETIME DEFAULT NULL,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
