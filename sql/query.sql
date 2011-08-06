ALTER TABLE ef_users ADD COLUMN notif_opt1 TINYINT(1) DEFAULT 1;
ALTER TABLE ef_users ADD COLUMN notif_opt2 TINYINT(1) DEFAULT 1;
ALTER TABLE ef_users ADD COLUMN notif_opt3 TINYINT(1) DEFAULT 1;

ALTER TABLE ef_users ADD COLUMN twitter 	VARCHAR(20);

UPDATE ef_users SET twitter = 'glaksmono' WHERE id = 6;

ALTER TABLE ef_messages MODIFY subject VARCHAR(200);