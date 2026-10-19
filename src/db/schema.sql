-- ======================================================================
-- Simple Finance - finance tables
--
-- Run AFTER php/start.php (it creates login_db and the Users table these
-- tables reference). Note: start.php drops every table in login_db, so
-- re-run this file and seeds.sql after running start.php again.
--
--   mysql -u root login_db < src/db/schema.sql
-- ======================================================================

USE login_db;

-- Drop in reverse dependency order for a clean install
DROP TABLE IF EXISTS Budgets;
DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Accounts;

/* Accounts - a user's bank / credit / cash accounts */
CREATE TABLE Accounts(
    account_id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    account_name varchar(255) NOT NULL,
    account_type ENUM('checking','savings','credit','cash') NOT NULL,
    starting_balance decimal(12,2) NOT NULL DEFAULT 0.00,
    created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(account_id),
    FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE);

/* Categories - user_id NULL means a default category shared by everyone */
CREATE TABLE Categories(
    category_id int NOT NULL AUTO_INCREMENT,
    user_id int NULL,
    category_name varchar(255) NOT NULL,
    category_type ENUM('income','expense') NOT NULL,
    PRIMARY KEY(category_id),
    FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE);

/* Transactions - amount is always positive; the category says income or expense */
CREATE TABLE Transactions(
    transaction_id int NOT NULL AUTO_INCREMENT,
    account_id int NOT NULL,
    category_id int NOT NULL,
    amount decimal(12,2) NOT NULL CHECK (amount > 0),
    transaction_date date NOT NULL,
    description varchar(255),
    created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(transaction_id),
    FOREIGN KEY(account_id) REFERENCES Accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY(category_id) REFERENCES Categories(category_id),
    INDEX(transaction_date));

/* Budgets - spending limit for one category over a date range (usually a month) */
CREATE TABLE Budgets(
    budget_id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    category_id int NOT NULL,
    amount_limit decimal(12,2) NOT NULL CHECK (amount_limit > 0),
    start_date date NOT NULL,
    end_date date NOT NULL,
    PRIMARY KEY(budget_id),
    UNIQUE(user_id, category_id, start_date),
    FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY(category_id) REFERENCES Categories(category_id),
    CHECK (end_date >= start_date));
