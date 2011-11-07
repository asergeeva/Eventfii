-- COUNT RSVP RECEIVED ORDERED BY DATE --
SELECT event_id, COUNT(confidence), DATE(rsvp_time) FROM ef_attendance WHERE confidence <> 5 GROUP BY DATE(rsvp_time);