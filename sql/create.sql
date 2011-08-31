CREATE TABLE ef_users (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  fname     VARCHAR(150),
  lname     VARCHAR(150),
  email     VARCHAR(500) NOT NULL UNIQUE,
  password  VARCHAR(5000),
  about     VARCHAR(5000),
  verified  TINYINT(1) NOT NULL DEFAULT 0,
  referrer  INTEGER REFERENCES ef_users(id),
  phone     VARCHAR(500),
  email2    VARCHAR(500),
  email3    VARCHAR(500),
  email4    VARCHAR(500),
  email5    VARCHAR(500),
  zip		    VARCHAR(15),
  pic		    VARCHAR(200),
  twitter 	VARCHAR(20),
  facebook  VARCHAR(50),
  notif_opt1 TINYINT(1) DEFAULT 1,
  notif_opt2 TINYINT(1) DEFAULT 1,
  notif_opt3 TINYINT(1) DEFAULT 1,
  reputation DOUBLE,
  fb_access_token VARCHAR(500),
  fb_session_key  VARCHAR(500)
) ENGINE=InnoDB;

CREATE TABLE ef_event_invites (
  invited  TIMESTAMP NOT NULL DEFAULT NOW(),
  hash_key VARCHAR(500) PRIMARY KEY,
  email_to VARCHAR(500) NOT NULL,
  event_id INTEGER NOT NULL REFERENCES ef_events(id)
) ENGINE=InnoDB;

CREATE TABLE ef_friendship (
  uid       INTEGER REFERENCES ef_users(id),
  fid       INTEGER REFERENCES ef_users(id),
  is_follow TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT pk_friendship PRIMARY KEY (uid, fid)
) ENGINE=InnoDB;

CREATE TABLE ef_events (
  id                  INTEGER PRIMARY KEY AUTO_INCREMENT,
  created             TIMESTAMP NOT NULL DEFAULT NOW(),
  organizer           INTEGER NOT NULL REFERENCES ef_users(id),
  title               VARCHAR(1000) NOT NULL,
  goal                INTEGER NOT NULL,
  location_name       VARCHAR(1000),
  location_address    VARCHAR(5000) NOT NULL,
  location_lat        DOUBLE,
  location_long       DOUBLE,
  event_datetime      DATETIME NOT NULL,
  event_end_datetime  DATETIME,
  event_deadline      DATE NOT NULL,
  type                INTEGER NOT NULL REFERENCES ef_event_type(tid),
  description         VARCHAR(5000),
  is_public           TINYINT(1) NOT NULL DEFAULT 1,
  is_active           TINYINT(1) DEFAULT 1,
  reach_goal          TINYINT(1) DEFAULT 1,
  twitter             VARCHAR(155)
) ENGINE=InnoDB;

CREATE TABLE fb_friends (
  user_id     INTEGER NOT NULL REFERENCES ef_users(id),
  fb_name     VARCHAR(500) NOT NULL,
  fb_id       VARCHAR(500) NOT NULL,
  CONSTRAINT PRIMARY KEY (user_id, fb_id)
) ENGINE=InnoDB;

CREATE TABLE ef_waitinglist (
  rsvp_time  TIMESTAMP DEFAULT NOW(),
  event_id   INTEGER NOT NULL REFERENCES ef_events(id),
  user_id    INTEGER NOT NULL REFERENCES ef_users(id),
  confidence FLOAT NOT NULL,
  CONSTRAINT pk_waitinglist PRIMARY KEY (event_id, user_id)
);

CREATE TABLE ef_addressbook (
  user_id       INTEGER NOT NULL REFERENCES ef_users(id),
  contact_id    INTEGER NOT NULL,
  contact_email VARCHAR(500) NOT NULL,
  group_name    VARCHAR(1000),
  CONSTRAINT pk_attendance PRIMARY KEY (user_id, contact_id)
) ENGINE=InnoDB;

CREATE TABLE ef_event_images (
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
  event_id  INTEGER NOT NULL REFERENCES ef_events(id)
) ENGINE=InnoDB;

CREATE TABLE ef_attendance (
  event_id          INTEGER NOT NULL REFERENCES ef_events(id),
  user_id           INTEGER NOT NULL REFERENCES ef_users(id),
  is_attending      TINYINT(1) NOT NULL DEFAULT 0,
  confidence        FLOAT NOT NULL DEFAULT 5,
  rsvp_time         TIMESTAMP DEFAULT NOW(),
  CONSTRAINT pk_attendance PRIMARY KEY (event_id, user_id)
) ENGINE=InnoDB;

CREATE TABLE ef_event_messages (
  created           TIMESTAMP NOT NULL DEFAULT NOW(),
  subject           VARCHAR(200),
  message           VARCHAR(5000) NOT NULL,
  delivery_time     DATETIME NOT NULL,
  event_id          INTEGER NOT NULL REFERENCES ef_events(id),
  recipient_group   INTEGER NOT NULL REFERENCES ef_recipient_groups(id),
  type              TINYINT NOT NULL,
  is_activated      TINYINT NOT NULL DEFAULT 0,
  CONSTRAINT pk_messages PRIMARY KEY (event_id, type)
) ENGINE=InnoDB;

CREATE TABLE ef_recipient_groups (
  id    INTEGER PRIMARY KEY AUTO_INCREMENT,
  name  VARCHAR(500) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ef_messages_type (
  id    INTEGER PRIMARY KEY AUTO_INCREMENT,
  name  VARCHAR(500) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ef_event_payments (
  id  INTEGER PRIMARY KEY AUTO_INCREMENT,
  uid INTEGER NOT NULL REFERENCES ef_users(id),
  eid INTEGER NOT NULL REFERENCES ef_events(id),
  ref VARCHAR(5000) NOT NULL,
  sig VARCHAR(5000) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ef_event_preapprovals (
  id     INTEGER PRIMARY KEY AUTO_INCREMENT,
  uid    INTEGER NOT NULL REFERENCES ef_users(id),
  eid    INTEGER NOT NULL REFERENCES ef_events(id),
  pkey   VARCHAR(5000) NOT NULL,
  pemail VARCHAR(1000) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ef_paypal_accounts (
  uid    INTEGER NOT NULL REFERENCES ef_users(id),
  pemail VARCHAR(1000) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ef_event_type (
  tid  INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(500) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ef_password_reset (
  hash_key VARCHAR(500) PRIMARY KEY,
  trequest TIMESTAMP NOT NULL DEFAULT NOW(),
  treset   TIMESTAMP,
  email    VARCHAR(500) NOT NULL REFERENCES ef_users(email)
) ENGINE=InnoDB;

CREATE TABLE ef_notyet (
  submit_time TIMESTAMP NOT NULL DEFAULT NOW(),
  email       VARCHAR(500) PRIMARY KEY
) ENGINE=InnoDB;