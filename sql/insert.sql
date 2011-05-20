INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Grady', 'Laksmono', 'laksmono@usc.edu', MD5('12345'), 'I am Grady', 1);
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Fei', 'Xiao', 'xiao@usc.edu', MD5('12345'), 'I am Fei', 1);
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('Anna', 'Sergeeva', 'sergeeva@usc.edu', MD5('12345'), 'I am Anna', 1);
INSERT INTO ef_users (fname, lname, email, password, about, verified) VALUES('John', 'Doe', 'jdoe@usc.edu', MD5('12345'), 'I am John', 1);

INSERT INTO ef_events (created, organizer, title, url, min_spot, max_spot, location_address, event_datetime, event_deadline, description, gets, cost) 
  VALUES (NOW(), 1, 'Pub Crawl in Downtown', 'http://eventfii.com/event/1', 50, 300, '5334 7th Street, Los Angeles, CA 90007', '2011-08-14 21:00:00', '2011-07-29', 'We''re all tired of the local bar near campus.. Let''s go to downtown and have some real organized fun.', 40);
INSERT INTO ef_events (created, organizer, title, url, min_spot, max_spot, location_address, event_datetime, event_deadline, description, gets, cost) 
  VALUES (NOW(), 1, 'Steve Aoki & A-trak at Club Avalon', 'http://eventfii.com/event/2', 5, 100, '2600 East 1st Street, Los Angeles, CA 90033', '2011-06-20 22:00:00', '2011-05-31 24:00:00', '2011-06-15', 'Going to be a fun concert. Who is in?', 35);
INSERT INTO ef_events (created, organizer, title, url, min_spot, max_spot, location_address, event_datetime, event_deadline, description, gets, cost) 
  VALUES (NOW(), 1, 'Lake Tahoe Summer Camping Trip', 'http://eventfii.com/event/3', 5, 25, '', '2011-05-31 10:00:00', '2011-05-25', 'We all need some bonding time with nature. Plus it''s a great way to kick off the summer!', 75);
INSERT INTO ef_events (created, organizer, title, url, min_spot, max_spot, location_address, event_datetime, event_deadline, description, gets, cost) 
  VALUES (NOW(), 1, 'Electric Daisy Carnival', 'http://eventfii.com/event/4', 8, 50, '', '2011-06-24 19:00:00', '2011-05-31', 'It''s going to sell out quick. Hurry!', 350);
INSERT INTO ef_events (created, organizer, title, url, min_spot, max_spot, location_address, event_datetime, event_deadline, description, gets, cost) 
  VALUES (NOW(), 1, 'Birthday Dinner for Anna', 'http://eventfii.com/event/5', 10, 30, 'Octopus Restaurant, Downtown LA', '2011-07-25 19:00:00', '2011-07-24', 'Celebrating the birth of a special person', 30);