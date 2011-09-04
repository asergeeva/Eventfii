ALTER TABLE ef_events ADD COLUMN url_alias VARCHAR(250) UNIQUE;
ALTER TABLE ef_users ADD COLUMN url_alias VARCHAR(250) UNIQUE;

UPDATE ef_events SET url_alias = HEX(400 + id + 3);
UPDATE ef_users SET url_alias = HEX(500 + id + 5);