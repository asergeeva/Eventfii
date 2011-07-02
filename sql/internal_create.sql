CREATE TABLE ef_admin_users (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  fname     VARCHAR(150),
  lname     VARCHAR(150),
  email     VARCHAR(500) NOT NULL UNIQUE,
  password  VARCHAR(5000)
) ENGINE=InnoDB;

INSERT INTO ef_admin_users (fname, lname, email, password) VALUES ('Grady', 'Laksmono', 'grady@truersvp.com', MD5('laksmono'));
INSERT INTO ef_admin_users (fname, lname, email, password) VALUES ('Anna', 'Sergeeva', 'anna@truersvp.com', MD5('sergeeva'));
INSERT INTO ef_admin_users (fname, lname, email, password) VALUES ('Fei', 'Xiao', 'fei@truersvp.com', MD5('xiao'));
INSERT INTO ef_admin_users (fname, lname, email, password) VALUES ('Scott', 'Sangster', 'scott@truersvp.com', MD5('sangster'));