-- Admins --
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Grady', 'Laksmono', 'grady@truersvp.com', MD5('laksmono'), 'I am Grady', 1);
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Fei', 'Xiao', 'fei@truersvp.com', MD5('xiao'), 'I am Fei', 1);
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Anna', 'Sergeeva', 'anna@truersvp.com', MD5('sergeeva'), 'I am Anna', 1);
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Scott', 'Sangster', 'scott@truersvp.com', MD5('sangster'), 'I am Scott', 1);

-- Message types --
INSERT INTO ef_messages_type (name) VALUES ('Email');
INSERT INTO ef_messages_type (name) VALUES ('SMS');

-- Event types --
-- Personal --
INSERT INTO ef_event_type (name) VALUES ('Birthday');
INSERT INTO ef_event_type (name) VALUES ('Other party');
INSERT INTO ef_event_type (name) VALUES ('Dinner');
INSERT INTO ef_event_type (name) VALUES ('Social gathering');
INSERT INTO ef_event_type (name) VALUES ('Shared travel/trip');
INSERT INTO ef_event_type (name) VALUES ('Wedding related');
-- Educational --
INSERT INTO ef_event_type (name) VALUES ('Club meetup');
INSERT INTO ef_event_type (name) VALUES ('Educational event');
INSERT INTO ef_event_type (name) VALUES ('Recruiting/career');
INSERT INTO ef_event_type (name) VALUES ('School-sponsored event');
INSERT INTO ef_event_type (name) VALUES ('Greek');
-- Professional --
INSERT INTO ef_event_type (name) VALUES ('Fund raiser');
INSERT INTO ef_event_type (name) VALUES ('Professional event/networking');
INSERT INTO ef_event_type (name) VALUES ('Meeting');
INSERT INTO ef_event_type (name) VALUES ('Club');
INSERT INTO ef_event_type (name) VALUES ('Conference');