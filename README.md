# TicketCare - Web Languages and Technologies	

TicketCare is a support ticketing system website developed using only core web technologies: **HTML**, **CSS**, **PHP**, **JavaScript**, **AJAX**, and **SQLite** for handling the database management.

### Set up
- There is a default database already set up.
- Check populate_database_test.sql for user credentials.

### Features
- All users are able to register, log in, log out and edit their profile.
- Clients are able to submit tickets.
- Clients are able to list the tickets that they have submitted (opened and closed)
- Clients and agents are able to talk in the ticket's chat.
- Agents are able to list tickets from their departments and filters them in various ways.
- Change information of their tickets (department, assigned agent, status, hashtags).
- Use FAQs to answer tickets.
- Admins are able to change users type and assign agents to departments.
- Admins are able to create and delete departments.

### Technologies used
- HTML
- CSS
- JavaScript
- PHP
- SQL (sqlite)
- No frameworks

### Security
- Passwords encrypted with unique salt
- SQLi (100%)
- XSS (not in every page)
- CSRF

### Notes
- Only login and register pages are fully responsive (to mobile).
- Extra: Agents can have more than 1 department
- Admins are also agents and clients (access other panels with link).
- It is not recommended to ban users !!!

António Rama, Pedro Marcelino, José Veiga
