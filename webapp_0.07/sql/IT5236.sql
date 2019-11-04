CREATE TABLE doList (
  listID int(32) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  complete tinyint(1) DEFAULT NULL,
  listItem varchar(100) NOT NULL,
  finishDate date DEFAULT NULL
);
