CREATE DATABASE eventfii;
USE eventfii;

CREATE TABLE ef_users (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  fname     VARCHAR(150) NOT NULL,
  lname     VARCHAR(150) NOT NULL,
  email     VARCHAR(1000) NOT NULL,
  about     VARCHAR(5000),
  verified  TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE ef_events (
  id                INTEGER PRIMARY KEY AUTO_INCREMENT,
  created           TIMESTAMP NOT NULL,
  organizer         VARCHAR(1000) NOT NULL REFERENCES ef_users(uname),
  title             VARCHAR(1000) NOT NULL,
  url               VARCHAR(5000) NOT NULL,
  min_people        INTEGER NOT NULL,
  location_address  VARCHAR(5000) NOT NULL,
  location_city     VARCHAR(5000) NOT NULL,
  location_state    VARCHAR(5000) NOT NULL,
  location_lat      DOUBLE,
  location_long     DOUBLE,
  event_datetime    DATETIME NOT NULL,
  description       VARCHAR(5000),
  cost              FLOAT NOT NULL,
  cur_attendance    INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE ef_attendance (
  event_id          INTEGER NOT NULL REFERENCES ef_events(id),
  user_id           INTEGER NOT NULL REFERENCES ef_users(id)
);

CREATE TABLE ef_event_messages (
  id                INTEGER PRIMARY KEY AUTO_INCREMENT,
  created           TIMESTAMP NOT NULL,
  message           VARCHAR(160) NOT NULL,
  event_id          INTEGER NOT NULL REFERENCES ef_events(id)
);