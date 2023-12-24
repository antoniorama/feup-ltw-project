-- Create the User table
CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE
);

-- Create the Client table, which references the User table
CREATE TABLE Client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL UNIQUE REFERENCES User(id)
);

-- Create the Agent table, which also references the User table
CREATE TABLE Agent (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE REFERENCES User(id),
    client_id INTEGER NOT NULL UNIQUE REFERENCES Client(id)
);

-- Create the AgentDepartment table to represent the relationship between Agent and Department
CREATE TABLE AgentDepartment (
    agent_id INTEGER NOT NULL REFERENCES Agent(id),
    department_id INTEGER NOT NULL REFERENCES Department(id),
    PRIMARY KEY (agent_id, department_id)
);

-- Create the Admin table, which also references the User table
CREATE TABLE Admin (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE REFERENCES User(id),
    agent_id INTEGER NOT NULL UNIQUE REFERENCES Agent(id)
);

-- Create the Department table
CREATE TABLE Department (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

-- Create the Status table
CREATE TABLE Status (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

-- Create the Ticket table, which references the Client, Agent, Department, and Status tables
CREATE TABLE Ticket (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    client_id INTEGER NOT NULL REFERENCES Client(id),
    agent_id INTEGER REFERENCES Agent(id),
    department_id INTEGER REFERENCES Department(id),
    status_id INTEGER NOT NULL REFERENCES Status(id),
    priority INTEGER NOT NULL CHECK (priority IN (1, 2, 3))
);

CREATE TABLE TicketHashtag (
    ticket_id INTEGER NOT NULL REFERENCES Ticket(id),
    hashtag_id INTEGER NOT NULL REFERENCES Hashtag(id),
    PRIMARY KEY (ticket_id, hashtag_id)
);


CREATE TABLE Hashtag (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);


CREATE TABLE ChatMessage (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ticket_id INTEGER NOT NULL REFERENCES Ticket(id),
    sender_id INTEGER NOT NULL REFERENCES User(id),
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Question (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL
);

-- Trigger to delete corresponding records in Client, Agent, and Admin tables when a User is deleted
CREATE TRIGGER delete_user_cascade
AFTER DELETE ON User
FOR EACH ROW
BEGIN
    DELETE FROM Client WHERE user_id = OLD.id;
    DELETE FROM Agent WHERE user_id = OLD.id;
    DELETE FROM Admin WHERE user_id = OLD.id;
END;
