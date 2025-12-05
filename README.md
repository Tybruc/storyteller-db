# Storyteller Database  
*A multi-page PHP & MySQL web application for writers to organize and develop stories.*

## Overview
The **Storyteller Database** is a full-stack web application built as my Captone Project.  
It allows writers and content creators to manage the essential components of a story, including:

- Stories  
- Characters  
- Locations  
- Plot Points  
- Timeline Events  

The system provides a clean user interface for creating, updating, organizing, and visualizing narrative elements.

---

## Technologies Used
**Frontend**
- HTML5, CSS3, JavaScript  
- Modal-based CRUD UI  
- Drag-and-drop ordering for timeline and plot point sequences  

**Backend**
- PHP 8+  
- MySQL (InnoDB, foreign keys, prepared statements)  
- Session-based authentication  

**Hosting**
- Namecheap Shared Hosting (cPanel / phpMyAdmin)  
- 

---

## Features

### User System
- Login + registration  
- Session-based authentication  
- Story selection preserved across pages  
- Forgot & reset password UI prompts
- Conditional new user guided UI after registration

### Story Management
- Create a new story  
- Update & delete stories  
- Dashboard interface for navigating story components 
- Views with or with out story selected
- Story selected shows story data and stats 

### Character Management
- Create, update, delete characters   
- Modal-driven editing  
- Story mode to manage story characters
- User mode to manage user characters w/o Story Selected
- Displays most recently updated record & # of characters 
- Character data displayed with table UI

### Location Management
- Create, update, delete characters   
- Modal-driven editing  
- Story mode to manage story characters
- User mode to manage user characters w/o Story Selected
- Displays most recently updated record & # of characters 
 

### New / Update Timeline Entry Management
- Create/edit timeline entries 
- Modular pages to create/update entry data
- Entry Summary page for review and page navigation
- Sequence auto-updates after saving  
- For plot points and events

### Timeline (Advanced UI)
- Draggable row ordering  
- Save Order button appears when changes detected  
- Unsaved changes modal  
- New entries default to sequence 0 so they appear at the top  
- Integrated modals for CRUD operations 

### Visual Timeline (Visualization)
- Displays events and plot points in sequence
- For display purposes only
- Displays upto date information from the Timeline Table.  

---

## Project Structure

/public_html/storyteller/
│── index.php
│── includes/
│ ├── data_functions.php
│ ├── db_config.php
│ ├── error_log.txt
│ ├── footer.php
│ ├── functions.php
│ ├── header.php
│ ├── init_html.php
│ ├── init.php
│ ├── sessions.php
│ └── test_functions.php
│── pages/
│ ├── characters.php
│ ├── dashboard.php
│ ├── help.php
│ ├── locations.php
│ ├── login.php
│ ├── new_characters.php
│ ├── new_entry.php
│ ├── new_locations.php
│ ├── new_story.php
│ ├── new_summary.php
│ ├── stories.php
│ ├── timeline.php
│ └── visual_timeline.php
│── crud/
│ ├── characters.php
│ ├── locations.php
│ ├── login.php
│ ├── story.php
│ └── timeline.php
│── partials/
│ │──  modals/
│ │ ├── character_modals.php
│ │ ├── header_modals.php
│ │ ├── location_modals.php
│ │ ├── story_modals.php
│ │ └── timeline_modals.php
│ └── tables/
│── assets/
│──  modals/
│ │──  css/
│ │ ├── login.css
│ │ ├── modal.css
│ │ ├── page.css
│ │ ├── style.css
│ │ └── visual_timeline.css
│ │──  js/
│ │ ├── modal.js
│ │ └── script.js
│ └── images/
└── README.md




---

## 🗄 Database Schema

The database uses **normalized InnoDB tables** with cascading deletes for improved referential integrity.

### Core Tables:
- `USERS`
- `STORIES`
- `CHARACTERS`
- `LOCATIONS`
- `PLOT_POINTS`
- `TIMELINE_EVENTS`

Each table includes:

- `STORY_ID` foreign key → `STORIES(STORY_ID)`
- Auto-incrementing primary keys
- Timestamps (`CREATED_AT`, `UPDATED_AT`)
- Cascade delete behavior to maintain clean relational data

See **storyteller-tables.sql** for full schema.

---

## Installation / Deployment

### 1. Requirements
- PHP 8+
- MySQL 5.7+ or MariaDB 10.x
- Apache or shared hosting with PHP support  
- phpMyAdmin or similar DB manager

### 2. Database Setup
1. Create a new MySQL database  
2. Import `storyteller_db.sql`  
3. Update `/config/db.php` with host/user/password settings

### 3. Upload Files
Upload the project to `public_html/storyteller/` (or root).

### 4. Access the App
Visit:

https://yourdomain.com/storyteller/


Log in with a created account to begin.

---

## Capstone Requirements Mapping
This project demonstrates:

- Full CRUD operations  
- Structured relational database design  
- Secure login system  
- Modular PHP architecture  
- Front-end behavior (modals, dynamic reordering, JS UI enhancements)  
- Hosting + deployment  
- Documentation + version control using GitHub  

---

## Future Enhancements (Planned)
- REST API layer for SPA or mobile app version  
- AI-assisted character / plot point generation  
- Visual timeline view (Gantt-style)  
- Story export to PDF, Markdown, Scrivener  
- Multiple UI Themes  
- User-to-user collaborative story mode  
- User driven form and table creation

---

## Author
**Ty Curneen**  
Capstone Project – Computer Technology / Web Development  
CIS2089551 Capstone - Prof Julie Schneider
Red Rocks Community College (2025)

---

## License
This project is licensed under the MIT License.  
See the `LICENSE` file for details.
