-- ======================================================================
-- Simple Finance - fake data for the 3 sample users from php/start.php
--   1 johndoe (admin), 2 janesmith (user), 3 charliebrown (guest)
--
--   mysql -u root login_db < src/db/seeds.sql
-- ======================================================================

USE login_db;

DELETE FROM Budgets;
DELETE FROM Transactions;
DELETE FROM Categories;
DELETE FROM Accounts;

/* Accounts */
INSERT INTO Accounts (account_id, user_id, account_name, account_type, starting_balance) VALUES
    (1, 1, 'Everyday Checking', 'checking', 2450.00),
    (2, 1, 'Emergency Savings', 'savings', 8000.00),
    (3, 1, 'Rewards Visa',      'credit',   0.00),
    (4, 2, 'Main Checking',     'checking', 1320.50),
    (5, 2, 'High-Yield Savings','savings', 3500.00),
    (6, 3, 'Student Checking',  'checking', 410.25),
    (7, 3, 'Wallet',            'cash',      60.00);

/* Default categories (user_id NULL) plus one custom category for Jane */
INSERT INTO Categories (category_id, user_id, category_name, category_type) VALUES
    (1,  NULL, 'Salary',         'income'),
    (2,  NULL, 'Side Income',    'income'),
    (3,  NULL, 'Rent',           'expense'),
    (4,  NULL, 'Groceries',      'expense'),
    (5,  NULL, 'Utilities',      'expense'),
    (6,  NULL, 'Dining Out',     'expense'),
    (7,  NULL, 'Transportation', 'expense'),
    (8,  NULL, 'Entertainment',  'expense'),
    (9,  NULL, 'Shopping',       'expense'),
    (10, 2,    'Pet Care',       'expense');

/* Transactions - September and October 2026 */
INSERT INTO Transactions (account_id, category_id, amount, transaction_date, description) VALUES
    -- John
    (1, 1, 3200.00, '2026-09-01', 'Paycheck'),
    (1, 3, 1450.00, '2026-09-02', 'September rent'),
    (1, 5,  132.40, '2026-09-08', 'Electric bill'),
    (3, 4,   86.17, '2026-09-10', 'Stop & Shop'),
    (3, 6,   42.50, '2026-09-13', 'Pizza night'),
    (3, 7,   55.00, '2026-09-15', 'Gas'),
    (1, 1, 3200.00, '2026-09-15', 'Paycheck'),
    (3, 8,   15.99, '2026-09-20', 'Streaming subscription'),
    (3, 4,  112.63, '2026-09-24', 'Costco'),
    (1, 1, 3200.00, '2026-10-01', 'Paycheck'),
    (1, 3, 1450.00, '2026-10-02', 'October rent'),
    (3, 4,   94.28, '2026-10-05', 'Stop & Shop'),
    (1, 5,  118.75, '2026-10-08', 'Electric bill'),
    (3, 6,   68.20, '2026-10-11', 'Dinner with friends'),
    (3, 9,  149.99, '2026-10-14', 'Running shoes'),
    (1, 1, 3200.00, '2026-10-15', 'Paycheck'),
    (3, 7,   48.30, '2026-10-16', 'Gas'),
    -- Jane
    (4, 1, 2100.00, '2026-09-05', 'Paycheck'),
    (4, 3,  975.00, '2026-09-05', 'September rent'),
    (4, 4,   64.12, '2026-09-09', 'Trader Joe''s'),
    (4, 10,  48.99, '2026-09-12', 'Dog food'),
    (4, 2,  250.00, '2026-09-18', 'Tutoring'),
    (4, 6,   23.75, '2026-09-21', 'Brunch'),
    (4, 7,   90.00, '2026-09-28', 'Monthly train pass'),
    (4, 1, 2100.00, '2026-10-05', 'Paycheck'),
    (4, 3,  975.00, '2026-10-05', 'October rent'),
    (4, 4,   71.48, '2026-10-07', 'Trader Joe''s'),
    (4, 10, 120.00, '2026-10-10', 'Vet visit'),
    (4, 8,   32.00, '2026-10-12', 'Movie tickets'),
    (4, 5,   65.30, '2026-10-14', 'Internet'),
    -- Charlie
    (6, 2,  480.00, '2026-09-06', 'Campus job'),
    (6, 4,   38.40, '2026-09-08', 'Groceries'),
    (7, 6,   12.50, '2026-09-11', 'Coffee and bagel'),
    (6, 8,   20.00, '2026-09-19', 'Concert ticket'),
    (6, 2,  480.00, '2026-10-04', 'Campus job'),
    (6, 4,   41.15, '2026-10-06', 'Groceries'),
    (7, 6,    9.75, '2026-10-09', 'Food truck'),
    (6, 9,   35.60, '2026-10-13', 'Textbook');

/* Budgets - October 2026 */
INSERT INTO Budgets (user_id, category_id, amount_limit, start_date, end_date) VALUES
    (1, 4,  400.00, '2026-10-01', '2026-10-31'),
    (1, 6,  150.00, '2026-10-01', '2026-10-31'),
    (1, 9,  100.00, '2026-10-01', '2026-10-31'),
    (2, 4,  250.00, '2026-10-01', '2026-10-31'),
    (2, 10, 100.00, '2026-10-01', '2026-10-31'),
    (2, 8,   60.00, '2026-10-01', '2026-10-31'),
    (3, 4,  120.00, '2026-10-01', '2026-10-31'),
    (3, 6,   40.00, '2026-10-01', '2026-10-31');
