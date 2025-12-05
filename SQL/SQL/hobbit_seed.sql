-- Sample data for Storyteller Database using J.R.R. Tolkien's "The Hobbit"
-- Assumes:
--   USER_ID = 1 already exists in USERS table
--   AUTO_INCREMENT for STORY_ID, CHARACTER_ID, LOCATION_ID, PLOT_POINT_ID all start at 1
-- Adjust IDs as needed if your database already contains data.

-- STORIES
INSERT INTO STORIES (USER_ID, TITLE, SYNOPSIS, GENRE)
VALUES 
(1, 
 'The Hobbit', 
 'Bilbo Baggins, a simple hobbit from the Shire, is swept into an adventure with thirteen dwarves and the wizard Gandalf to reclaim the Lonely Mountain from the dragon Smaug.',
 'Fantasy');

-- CHARACTERS
INSERT INTO CHARACTERS (STORY_ID, NAME, AGE, DESCRIPTION, NOTES)
VALUES
(1, 'Bilbo Baggins', '50', 'A hobbit recruited as the company’s burglar.', 'Protagonist'),
(1, 'Gandalf the Grey', 'Unknown', 'A wise and powerful wizard who guides the company.', 'Mentor figure'),
(1, 'Thorin Oakenshield', '195', 'Leader of the dwarven company seeking to reclaim Erebor.', 'Dwarf king'),
(1, 'Smaug', 'Unknown', 'A massive, cunning dragon who hoards Erebor’s treasure.', 'Antagonist'),
(1, 'Gollum', 'Over 500', 'A creature living deep inside the Misty Mountains.', 'Keeper of the One Ring'),
(1, 'Balin', '178', 'One of the more senior dwarves and Thorin’s advisor.', 'Dwarf'),
(1, 'Bard the Bowman', '35', 'A skilled archer from Lake-town who ultimately slays Smaug.', 'Hero of Lake-town');

-- LOCATIONS
INSERT INTO LOCATIONS (STORY_ID, NAME, DESCRIPTION)
VALUES
(1, 'The Shire', 'Bilbo’s peaceful homeland.'),
(1, 'Rivendell', 'Elrond’s elven refuge and safe haven.'),
(1, 'Misty Mountains', 'Treacherous mountain range filled with goblins and dangers.'),
(1, 'Mirkwood', 'A dark, sprawling forest home to giant spiders and elves.'),
(1, 'Lake-town (Esgaroth)', 'A human settlement built over the water.'),
(1, 'The Lonely Mountain (Erebor)', 'The lost dwarven kingdom and Smaug’s lair.');

-- PLOT POINTS
INSERT INTO PLOT_POINTS (STORY_ID, TITLE, SUMMARY, SEQUENCE_NO, LOCATION_ID)
VALUES
(1, 'An Unexpected Party', 
 'Gandalf arranges for Bilbo to join Thorin’s company as their burglar.', 
 1, 1),

(1, 'Arrival in Rivendell', 
 'The company rests in Elrond’s house and gains key guidance for their map.', 
 2, 2),

(1, 'Goblin Capture in the Misty Mountains', 
 'Goblins attack the company, capturing them underground.', 
 3, 3),

(1, 'Riddles in the Dark', 
 'Bilbo meets Gollum and wins the One Ring through a game of riddles.', 
 4, 3),

(1, 'Spiders of Mirkwood', 
 'The company is attacked by giant spiders; Bilbo proves his bravery.', 
 5, 4),

(1, 'Elvenking’s Halls', 
 'The dwarves are imprisoned by the Elvenking but later escape in barrels.', 
 6, 4),

(1, 'Smaug the Magnificent', 
 'Bilbo confronts Smaug inside the Lonely Mountain.', 
 7, 6),

(1, 'The Death of Smaug & Bard’s Rise', 
 'Smaug attacks Lake-town but is slain by Bard.', 
 8, 5);

-- PLOT_POINT_CHARACTERS
-- Mapping:
--   1 = An Unexpected Party
--   2 = Arrival in Rivendell
--   3 = Goblin Capture in the Misty Mountains
--   4 = Riddles in the Dark
--   5 = Spiders of Mirkwood
--   6 = Elvenking’s Halls
--   7 = Smaug the Magnificent
--   8 = The Death of Smaug & Bard’s Rise
-- Characters (CHARACTER_ID):
--   1 = Bilbo Baggins
--   2 = Gandalf the Grey
--   3 = Thorin Oakenshield
--   4 = Smaug
--   5 = Gollum
--   6 = Balin
--   7 = Bard the Bowman

-- An Unexpected Party (Bilbo, Gandalf, Thorin, Balin)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(1, 1), (1, 2), (1, 3), (1, 6);

-- Rivendell (Bilbo, Gandalf, Thorin)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(2, 1), (2, 2), (2, 3);

-- Goblin Capture (Bilbo, Gandalf, Thorin, Balin)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(3, 1), (3, 2), (3, 3), (3, 6);

-- Riddles in the Dark (Bilbo, Gollum)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(4, 1), (4, 5);

-- Spiders of Mirkwood (Bilbo, Thorin, Balin)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(5, 1), (5, 3), (5, 6);

-- Elvenking’s Halls (Bilbo, Thorin, Balin)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(6, 1), (6, 3), (6, 6);

-- Smaug Encounter (Bilbo, Smaug)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(7, 1), (7, 4);

-- Death of Smaug (Bard, Smaug)
INSERT INTO plot_point_characters (PLOT_POINT_ID, CHARACTER_ID) VALUES
(8, 7), (8, 4);
