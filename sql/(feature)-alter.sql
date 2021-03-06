ALTER TABLE `ef_events` ADD `total_rsvps` INT( 11 ) NULL AFTER `location_long`;

ALTER TABLE `ef_events` ADD `image` TINYTEXT NULL;

ALTER TABLE `ef_event_images` ADD `image` TINYTEXT NULL AFTER `event_id` , ADD `owner_id` INT( 11 ) NULL AFTER `image` , ADD `created_at` DATETIME NULL AFTER `owner_id`;


/*we think so that this column is missing. Please run this query and then check.*/
ALTER TABLE `ef_attendance` ADD `referenced_by` INT( 11 ) NULL AFTER `rsvp_time` ;