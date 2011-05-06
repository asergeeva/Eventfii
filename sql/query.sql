SELECT MAX(e.id) AS max_id FROM ef_events e;

SELECT * FROM ef_users e WHERE e.email = '' AND e.password = ''