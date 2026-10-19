# SimpleFinance

A basic database web application that just focuses on a simple login. This is a starting point for building a web application using mariaDB on XAMPP. This is intended to be a learning tool only and is not intended for production use. Students are free to use this for ONLY school projects.

## Stack

The project was built using [xampp] (https://www.apachefriends.org/index.html)
XAMPP should be installed locally in order to run this program.

- Appache
- MariaDB
- PHP
- Pearl

## INSTRUCTIONS

These files must be hosted within a system that supports lamp stacks, preferably install this into a XAMPP and run locally. This project is not built to scale for a production based website, it intention is to be used as a teaching tool.

## PROJECT STRUCTURE

```
SimpleFinance/
├── index.php          Login page (the homepage, must stay at the top level)
├── pages/             Other public pages: create account, forgot password, login failed
├── dashboards/        Where users land after logging in: admin/, user/, guest/
├── templates/         Shared head, navbar and footer, plus blank_page.php to copy for new pages
├── php/               Code that runs behind the scenes: form handlers, login/session checks,
│                      database connection (config.php, path.php) and start.php
├── assets/            css/, js/ and img/
├── mockups/           Plain HTML design mockups (not part of the running app)
└── src/db/            schema.sql and seeds.sql
```

A page and the code that handles its form live in different folders: for example, `pages/create_account.php` shows the form and `php/create_account_submit.php` saves the new account.

Use the constants from `php/path.php` for links and includes instead of relative paths like `./` or `../`:

| Constant | Use it for | Example |
|---|---|---|
| `BASE_URL` | Links to pages | `<?php echo BASE_URL; ?>/pages/create_account.php` |
| `SRC_PATH` | Form actions / links to `php/` | `<?php echo SRC_PATH; ?>/logout.php` |
| `ASSET_URL` | CSS, JS, images | `<?php echo ASSET_URL; ?>/img/logo.svg` |
| `TEMPLATE_PATH` | Including the head/header/footer | `include_once TEMPLATE_PATH . "/header.php";` |
| `ROOT_PATH` / `ROOT_SRC_PATH` | Including other PHP files | `include_once ROOT_SRC_PATH . "/config.php";` |

## SETUP (do this once)

You need [XAMPP](https://www.apachefriends.org/index.html) installed (default location `C:\xampp`) and Git.

### 1. Get the code into htdocs

The folder **must** be named `SimpleFinance` and live in `C:\xampp\htdocs`, because the links in `php/path.php` expect `/SimpleFinance`.

```bash
cd C:/xampp/htdocs
git clone https://github.com/raeann-mclean/SimpleFinance.git
cd SimpleFinance
git checkout test

```




### 2. Start the servers

Open the **XAMPP Control Panel** and click **Start** next to **Apache** and **MySQL**. Both should turn green.
Check it works by opening http://localhost/ (you should see the XAMPP welcome page).

> If MySQL won't start, something else is probably using port 3306 (often another MySQL install). Stop that program and try again.

### 3. Create the login database

Open http://localhost/SimpleFinance/php/start.php

You should see "The database tables were successfully created" and "...successfully populated". This creates the `login_db` database with the Roles, Contacts, Users and Credentials tables and 3 sample users.

> A "Cannot modify header information" warning at the bottom is harmless.

### 4. Add the finance tables and fake data

1. Open http://localhost/phpmyadmin
2. Click **login_db** in the left sidebar.
3. Click the **Import** tab, choose `C:\xampp\htdocs\SimpleFinance\src\db\schema.sql`, and click **Import** at the bottom of the page.
4. Do the same again with `src\db\seeds.sql`.

Order matters: `start.php` first, then `schema.sql`, then `seeds.sql`.

### 5. Log in

Go to http://localhost/SimpleFinance and log in with one of the sample users created by `php/start.php` (`johndoe` = admin, `janesmith` = user, `charliebrown` = guest). Their passwords are in that file.

## LOOKING AT THE DATA

In phpMyAdmin, click **login_db**, then click a **table name** (e.g. `transactions`) and make sure the **Browse** tab is selected.
Don't click the column names under "Columns" in the sidebar: that opens a form for changing the column itself.

## RESETTING THE DATABASE

- **Fresh fake data only:** re-import `src/db/seeds.sql`. It clears the finance tables first, so you won't get duplicates.
- **Everything from scratch:** run `start.php` again, then re-import `schema.sql` and `seeds.sql`.
  `start.php` deletes **every** table in `login_db`, including the finance tables, so always re-import both files after running it.

After pulling changes that touch `src/db/`, re-import `schema.sql` and `seeds.sql` to stay in sync with the team.

## DATABASE LOGIN

XAMPP's default MySQL user is `root` with no password, which is what `php/config.php` and `php/start.php` use. If you've set a root password, change `DB_PASSWORD` in both files on your machine and **don't commit** that change.

## BOOTSTRAP

This is a popular HTML CSS, and JS library included in this project. To make updates simpler and faster, see the documentation for how to use it.
[bootstrap docs](https://getbootstrap.com/docs/5.3/getting-started/introduction/)
