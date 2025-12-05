/* ========================
   1) USER
   ======================== */

-- Note: Replace 'FAKE_HASHED_PASSWORD' with a real hash from password_hash(...)
INSERT INTO USERS (USER_ID, EMAIL, FIRST_NAME, LAST_NAME, ROLE)
VALUES
(1, 'bilbo@example.com', 'Bilbo', 'Baggins', 'AUTHOR');


/* ========================
   2) STORY
   ======================== */

INSERT INTO STORIES (STORY_ID, USER_ID, TITLE, SYNOPSIS, GENRE)
VALUES
(1, 1,
 'The Hobbit',
 'A reluctant hobbit joins a company of dwarves and a wizard to reclaim a lost mountain kingdom and its treasure from the dragon Smaug.',
 'Fantasy');


/* ========================
   3) LOCATIONS (5)
   ======================== */

INSERT INTO LOCATIONS (LOCATION_ID, STORY_ID, USER_ID, NAME, DESCRIPTION)
VALUES
(1, 1, 1, 'Bag End (Hobbiton)',
 'Bilbo Baggins'' cozy hobbit-hole in Hobbiton, where the adventure begins.'),
(2, 1, 1, 'The Shire Roads',
 'The well-trodden roads leading out of the Shire as the company departs on their journey.'),
(3, 1, 1, 'Goblin Tunnels',
 'Dark tunnels beneath the Misty Mountains, lair of goblins and the creature Gollum.'),
(4, 1, 1, 'Mirkwood Forest',
 'A vast, dark forest filled with spiders, elves, and many hidden dangers.'),
(5, 1, 1, 'The Lonely Mountain',
 'The ancient dwarven kingdom of Erebor, now guarded by the dragon Smaug.');


/* ========================
   4) CHARACTERS (5)
   ======================== */

INSERT INTO CHARACTERS (CHARACTER_ID, STORY_ID, USER_ID, NAME, AGE, DESCRIPTION, NOTES)
VALUES
(1, 1, 1, 'Bilbo Baggins', '50',
 'A comfort-loving hobbit unexpectedly drawn into an epic quest.',
 'Initially reluctant, but grows into a brave and clever burglar.'),
(2, 1, 1, 'Gandalf the Grey', 'Unknown',
 'A wandering wizard who orchestrates the quest.',
 'Often disappears at critical moments, but his guidance shapes the journey.'),
(3, 1, 1, 'Thorin Oakenshield', 'Middle-aged',
 'Leader of the company of dwarves seeking to reclaim Erebor.',
 'Proud and determined, sometimes blinded by his desire for the treasure.'),
(4, 1, 1, 'Smaug', 'Ancient',
 'A powerful dragon who hoards the treasure under the Lonely Mountain.',
 'Cunning, prideful, and devastatingly destructive.'),
(5, 1, 1, 'Gollum', 'Very old',
 'A strange, isolated creature living under the Misty Mountains.',
 'Obsessed with the One Ring and speaking in riddles.');


/* ========================
   5) TIMELINE ENTRIES (10)
   5 PLOT, 5 EVENT
   ======================== */

INSERT INTO TIMELINE
    (TIMELINE_ID, STORY_ID, USER_ID, TITLE, SUMMARY, LOCATION_ID, SEQUENCE_NO, ENTRY_TYPE)
VALUES
-- 1 PLOT
(1, 1, 1,
 'An Unexpected Party',
 'Gandalf arrives at Bag End with a company of dwarves, inviting Bilbo on an adventure.',
 1, 1, 'PLOT'),

-- 2 EVENT
(2, 1, 1,
 'Bilbo Signs the Contract',
 'After much hesitation, Bilbo signs the dwarves’ contract to act as their burglar.',
 1, 2, 'EVENT'),

-- 3 PLOT
(3, 1, 1,
 'The Company Sets Out',
 'Bilbo and the dwarves leave the Shire and begin their journey toward the Lonely Mountain.',
 2, 3, 'PLOT'),

-- 4 EVENT
(4, 1, 1,
 'Captured by Goblins',
 'The company is captured by goblins in the Misty Mountains.',
 3, 4, 'EVENT'),

-- 5 PLOT
(5, 1, 1,
 'Riddles in the Dark',
 'Bilbo becomes separated and plays a perilous riddle game with Gollum in the dark tunnels.',
 3, 5, 'PLOT'),

-- 6 EVENT
(6, 1, 1,
 'Escape from the Goblin Tunnels',
 'Bilbo uses the Ring to escape unseen and rejoins the company outside the mountains.',
 3, 6, 'EVENT'),

-- 7 PLOT
(7, 1, 1,
 'Into Mirkwood',
 'The company enters the gloomy depths of Mirkwood Forest, low on supplies and morale.',
 4, 7, 'PLOT'),

-- 8 EVENT
(8, 1, 1,
 'Spiders of Mirkwood',
 'Bilbo rescues the dwarves from giant spiders, proving his growing bravery.',
 4, 8, 'EVENT'),

-- 9 PLOT
(9, 1, 1,
 'Arrival at the Lonely Mountain',
 'The company finally reaches the Lonely Mountain and begins to search for the secret door.',
 5, 9, 'PLOT'),

-- 10 EVENT
(10, 1, 1,
 'Bilbo Confronts Smaug',
 'Bilbo sneaks into the dragon’s lair, converses with Smaug, and discovers his weak spot.',
 5, 10, 'EVENT');


/* ========================
   6) TIMELINE_CHARACTERS
   Associate characters with each timeline entry
   ======================== */

INSERT INTO TIMELINE_CHARACTERS (TIMELINE_ID, CHARACTER_ID, STORY_ID)
VALUES
-- ENTRY 1: An Unexpected Party (Bilbo, Gandalf, Thorin)
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),

-- ENTRY 2: Bilbo Signs the Contract (Bilbo, Thorin)
(2, 1, 1),
(2, 3, 1),

-- ENTRY 3: The Company Sets Out (Bilbo, Gandalf, Thorin)
(3, 1, 1),
(3, 2, 1),
(3, 3, 1),

-- ENTRY 4: Captured by Goblins (Bilbo, Gandalf, Thorin)
(4, 1, 1),
(4, 2, 1),
(4, 3, 1),

-- ENTRY 5: Riddles in the Dark (Bilbo, Gollum)
(5, 1, 1),
(5, 5, 1),

-- ENTRY 6: Escape from the Goblin Tunnels (Bilbo, Gandalf, Thorin)
(6, 1, 1),
(6, 2, 1),
(6, 3, 1),

-- ENTRY 7: Into Mirkwood (Bilbo, Thorin)
(7, 1, 1),
(7, 3, 1),

-- ENTRY 8: Spiders of Mirkwood (Bilbo, Thorin)
(8, 1, 1),
(8, 3, 1),

-- ENTRY 9: Arrival at the Lonely Mountain (Bilbo, Thorin, Smaug)
(9, 1, 1),
(9, 3, 1),
(9, 4, 1),

-- ENTRY 10: Bilbo Confronts Smaug (Bilbo, Smaug)
(10, 1, 1),
(10, 4, 1);
