-- Message types --
INSERT INTO ef_messages_type (name) VALUES ('Email');
INSERT INTO ef_messages_type (name) VALUES ('SMS');
INSERT INTO ef_messages_type (name) VALUES ('Followup');

-- Recipient groups --
INSERT INTO ef_recipient_groups (name) VALUES ('All Attendees');
INSERT INTO ef_recipient_groups (name) VALUES ('Absolutely Attending');
INSERT INTO ef_recipient_groups (name) VALUES ('Pretty sure, 50/50, Not likely');
INSERT INTO ef_recipient_groups (name) VALUES ('Not Attending');
INSERT INTO ef_recipient_groups (name) VALUES ('No Response Yet');

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