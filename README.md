# SimpleFinance

A classroom personal-finance prototype with role-based login, searchable database transactions, persistent display preferences, and atomic account registration.

The application builds on a basic database login project. This is a starting point for building a web application using mariaDB on XAMPP. This is intended to be a learning tool only and is not intended for production use. Students are free to use this for ONLY school projects.

## Stack

The project was built using [xampp] (https://www.apachefriends.org/index.html)
XAMPP should be installed locally in order to run this program.

- Apache
- MariaDB
- PHP
- Perl

## INSTRUCTIONS

Run the files in a PHP and MariaDB environment, preferably XAMPP on a local computer. The project is a teaching tool.

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
git checkout codex/extra-credit-features

```




### 2. Start the servers

Open the **XAMPP Control Panel** and click **Start** next to **Apache** and **MySQL**. Both should turn green.
Check it works by opening http://localhost/ (you should see the XAMPP welcome page).

> If MySQL won't start, something else is probably using port 3306 (often another MySQL install). Stop that program and try again.

### 3. Create the login database

Open http://localhost/SimpleFinance/php/start.php

You should see "The database tables were successfully created" and "...successfully populated". This creates the `login_db` database with the Roles, Contacts, Users and Credentials tables and 3 sample users. The registration tables explicitly use InnoDB so rollback works.

### 4. Add the finance tables and fake data

1. Open http://localhost/phpmyadmin
2. Click **login_db** in the left sidebar.
3. Click the **Import** tab, choose `C:\xampp\htdocs\SimpleFinance\src\db\schema.sql`, and click **Import** at the bottom of the page.
4. Do the same again with `src\db\seeds.sql`.

Order matters: `start.php` first, then `schema.sql`, then `seeds.sql`.

### 5. Log in

Go to http://localhost/SimpleFinance and log in with one of the sample users created by `php/start.php` (`johndoe` = admin, `janesmith` = user, `charliebrown` = guest). The local demo credentials are listed below. Never use them for real accounts.

## LOOKING AT THE DATA

In phpMyAdmin, click **login_db**, then click a **table name** (e.g. `transactions`) and make sure the **Browse** tab is selected.
Don't click the column names under "Columns" in the sidebar: that opens a form for changing the column itself.

## RESETTING THE DATABASE

- **Fresh fake data only:** re-import `src/db/seeds.sql`. It clears the finance tables first, so you won't get duplicates.
- **Everything from scratch:** run `start.php` again, then re-import `schema.sql` and `seeds.sql`.
  `start.php` deletes **every** table in `login_db`, including the finance tables, so always re-import both files after running it.

After pulling changes that touch `src/db/`, re-import `schema.sql` and `seeds.sql` to stay in sync with the team.

## DATABASE LOGIN

XAMPP's default MySQL user is `root` with no password, which is the default in `php/db_settings.php`. If your database uses different credentials, set the optional `SF_DB_HOST`, `SF_DB_PORT`, `SF_DB_USER`, `SF_DB_PASSWORD`, and `SF_DB_NAME` environment variables. Keep passwords out of Git. The defaults remain `localhost:3306`, `root`, an empty password, and `login_db`. The bundled SQL imports use `login_db`.

## BOOTSTRAP

This is a popular HTML CSS, and JS library included in this project. To make updates simpler and faster, see the documentation for how to use it.
[bootstrap docs](https://getbootstrap.com/docs/5.3/getting-started/introduction/)


## Extra-credit features

| Requirement | Implementation | How to demonstrate |
|---|---|---|
| Working search | `mockups/transactions.php` queries MariaDB with prepared MySQLi parameters. It searches descriptions, categories, and account names, scoped to the signed-in user's accounts. | Sign in as Jane, open Transactions, search `Pet Care`, then clear the search. |
| Dark/light button | `templates/header.php` and `assets/js/main.js` toggle Bootstrap's `data-bs-theme`. The choice survives reloads using localStorage. | Click **Switch to dark mode**, reload, then click **Switch to light mode**. |
| Accessibility feature | **Use larger text** increases the root font size from 100% to 125% and persists the choice. A skip link, visible keyboard focus, explicit form labels, table headers, and result announcements support keyboard and screen-reader use. | Toggle larger text. Reload. Press Tab on a fresh page and Enter on **Skip to main content**. |
| Documented README | This `README.md` describes setup, tables, features, sample accounts, limitations, and verification. | Follow the setup and demo steps below. |
| MySQLi rollback | `php/account_service.php` wraps Contacts, Users, and Credentials inserts in `begin_transaction()`, `commit()`, and `rollback()`. A duplicate username fails the final insert and rolls back the preceding inserts. | Run the transaction test below, or submit an existing username in Create Account. |

### Local sample accounts

| Username | Password | Role |
|---|---|---|
| `johndoe` | `SCSU2024` | Administrator |
| `janesmith` | `SCSU2025` | User |
| `charliebrown` | `SCSU2026` | Guest |

These are fake, seeded classroom accounts from `php/start.php`. New registrations use `password_hash()` and `password_verify()`. Login retains compatibility with the original seeded hashes.

### Search behavior

1. Log in and open **Transactions** in the sidebar.
2. Submit a description, category, or account name using the **Search** button or Enter.
3. Jane's `Pet Care` search returns the dog-food and vet-visit transactions. `Trader` returns two grocery purchases.
4. **Clear search** restores all of the current user's transactions. Unknown terms display an empty-result message.
5. `%`, `_`, and `!` are treated as literal characters rather than SQL wildcards. Search values and displayed database values are escaped for HTML output.

The finance schema and seed import are required. A missing finance table produces a helpful error instead of an empty success state. Search has no write side effects.

### Accessibility and display preferences

Both display buttons are native buttons that support Enter and Space and expose their toggle state with `aria-pressed`. **Use standard text** restores normal font size. The first keyboard focus target is **Skip to main content**. At narrow widths, expand the navigation menu to reach the display controls. Preferences are local to the current browser. If browser storage is blocked, the buttons still work on the current page.

### Rollback verification

Use a local test database with the login tables initialized. From the project directory, run:

```bash
php tests/registration_transaction.php
```

With default Windows XAMPP, use:

```powershell
C:\xampp\php\php.exe tests/registration_transaction.php
```

The test creates a uniquely named account and checks that all three inserts commit. It then attempts a second registration using the same username. The duplicate fails at Credentials, after Contacts and Users have been inserted in the transaction. The test verifies that the row counts remain unchanged, proving rollback removed those partial records. It checks the new password hash and cleans up its own test account in a `finally` block. It never runs `start.php` or resets existing tables.

Expected output:

```text
PASS: successful registration commits all three rows
PASS: failed third INSERT rolls back Contacts and Users
PASS: new account password verifies
PASS: test fixture cleaned up
```

If upgrading an existing database, ensure Contacts, Users, and Credentials use InnoDB. Running the setup from scratch creates the correct engines but erases local data as described above.

### Short presentation demo

1. Sign in as `janesmith` and open Transactions.
2. Search for `Pet Care`, then point out the two saved results.
3. Switch to dark mode and reload to demonstrate persistence.
4. Toggle larger text, then return to standard text and light mode.
5. Clear search and try a term with no matches.
6. Sign out and try registering with the existing `janesmith` username. Explain the error and the rollback test.

### Current project limits

Transactions and search load real seeded database records. The overview balances, Accounts, Reports, and Settings forms remain interface prototypes. Account and transaction add/edit/delete operations and password recovery are not implemented. The sample database contains fake September and October 2026 records. This is an educational application, not a live banking service.
