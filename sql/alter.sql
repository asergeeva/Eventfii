ALTER TABLE ef_events ADD COLUMN url_alias VARCHAR(250) UNIQUE;
ALTER TABLE ef_users ADD COLUMN url_alias VARCHAR(250) UNIQUE;

UPDATE ef_events SET url_alias = HEX(403 + id);
UPDATE ef_users SET url_alias = HEX(505 + id);

-- 9/5/2011 --
ALTER TABLE ef_events MODIFY event_deadline DATE;
ALTER TABLE ef_events MODIFY is_active TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE ef_events MODIFY reach_goal TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE ef_events MODIFY description VARCHAR(5000) NOT NULL;

-- 9/6/2011 --
ALTER TABLE ef_events MODIFY event_deadline DATE NOT NULL;

-- 9/7/2011 --
ALTER TABLE ef_events ADD COLUMN global_ref VARCHAR(500) NOT NULL;
UPDATE ef_events SET global_ref = MD5('global-event-' + id);

-- 9/9/2011 --
ALTER TABLE ef_users ADD COLUMN user_cookie VARCHAR(500) NOT NULL;
UPDATE ef_users SET user_cookie = MD5('cookie-user-' + id);

-- 10/2/2011 --
ALTER TABLE fb_friends DROP COLUMN fname;
ALTER TABLE fb_friends DROP COLUMN lname;