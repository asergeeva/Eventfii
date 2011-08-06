DROP TABLE ef_launch_signups;
CREATE TABLE ef_launch_signups (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  email     VARCHAR(500) UNIQUE,
  dislike   VARCHAR(1000)
) ENGINE=InnoDB;