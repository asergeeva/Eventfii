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