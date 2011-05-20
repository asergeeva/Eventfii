CREATE TABLE ef_users (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  fname     VARCHAR(150) NOT NULL,
  lname     VARCHAR(150) NOT NULL,
  email     VARCHAR(1000) NOT NULL,
  password  VARCHAR(5000) NOT NULL,
  about     VARCHAR(5000),
  verified  TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE ef_events (
  id                INTEGER PRIMARY KEY AUTO_INCREMENT,
  created           TIMESTAMP NOT NULL,
  organizer         INTEGER NOT NULL REFERENCES ef_users(id),
  title             VARCHAR(1000) NOT NULL,
  url               VARCHAR(5000) NOT NULL,
  min_spot          INTEGER NOT NULL,
  max_spot          INTEGER DEFAULT 0,
  location_address  VARCHAR(5000) NOT NULL,
  location_lat      DOUBLE,
  location_long     DOUBLE,
  event_datetime    DATETIME NOT NULL,
  event_deadline    DATE NOT NULL,
  description       VARCHAR(5000),
  gets              VARCHAR(5000),
  cost              FLOAT NOT NULL,
  is_public         TINYINT(1) NOT NULL DEFAULT 1,
  is_collected      TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE ef_event_images (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  event_id  INTEGER NOT NULL REFERENCES ef_events(id)
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

CREATE TABLE ef_event_payments (
  id  INTEGER PRIMARY KEY AUTO_INCREMENT,
  uid INTEGER NOT NULL REFERENCES ef_users(id),
  eid INTEGER NOT NULL REFERENCES ef_events(id),
  ref VARCHAR(5000) NOT NULL,
  sig VARCHAR(5000) NOT NULL
);

CREATE TABLE ef_event_preapprovals (
  id     INTEGER PRIMARY KEY AUTO_INCREMENT,
  uid    INTEGER NOT NULL REFERENCES ef_users(id),
  eid    INTEGER NOT NULL REFERENCES ef_events(id),
  pkey   VARCHAR(5000) NOT NULL,
  pemail VARCHAR(1000) NOT NULL
);

CREATE TABLE ef_paypal_accounts (
  uid    INTEGER NOT NULL REFERENCES ef_users(id),
  pemail VARCHAR(1000) NOT NULL
);