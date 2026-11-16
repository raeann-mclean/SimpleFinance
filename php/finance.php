<?php
/**
 * Finance helper functions
 *
 * Every query here takes the logged-in user's id and only returns that
 * user's data, so pages never need to write their own WHERE user_id checks.
 *
 * Include after php/session.php (which gives you $db_connection and $user_id):
 *   include_once (ROOT_SRC_PATH . '/finance.php');
 */

//======================================================================
// SMALL HELPERS
//======================================================================

/* Escape text before printing it into HTML */
function h($value) {
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/* 1234.5 -> "$1,234.50", -20 -> "-$20.00" */
function money($amount) {
  $amount = (float) $amount;
  return ($amount < 0 ? '-' : '') . '$' . number_format(abs($amount), 2);
}

/* "2026-10" -> "October 2026" */
function month_label($ym) {
  return date('F Y', strtotime($ym . '-01'));
}

/* Run a prepared SELECT and return every row as an associative array */
function db_all($db, $sql, $types = '', ...$params) {
  $stmt = $db->prepare($sql);
  if ($types !== '') {
    $stmt->bind_param($types, ...$params);
  }
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $rows;
}

/* Same as db_all but only the first row (or null) */
function db_one($db, $sql, $types = '', ...$params) {
  $rows = db_all($db, $sql, $types, ...$params);
  return $rows[0] ?? null;
}

/* Send the user back to a page with a message shown by php/error_rprt.php */
function redirect_with($url, $type, $text) {
  $_SESSION[$type] = $text; // 'success', 'error' or 'message'
  header("location: " . $url);
  exit();
}

//======================================================================
// CSRF PROTECTION (stops other sites from submitting our forms)
//======================================================================

function csrf_token() {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

/* Put inside every <form method="post"> */
function csrf_field() {
  return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

/* Call at the top of every form handler */
function csrf_check() {
  $sent = $_POST['csrf_token'] ?? '';
  return $sent !== '' && hash_equals(csrf_token(), $sent);
}

//======================================================================
// MONTH SELECTION (?month=2026-10 or ?month=all)
//======================================================================

/* Months that have transactions for this user, newest first, plus the current month */
function available_months($db, $user_id) {
  $rows = db_all($db,
    "SELECT DISTINCT DATE_FORMAT(t.transaction_date, '%Y-%m') AS ym
     FROM Transactions t
     JOIN Accounts a ON a.account_id = t.account_id
     WHERE a.user_id = ?
     ORDER BY ym DESC", "i", $user_id);
  $months = array_column($rows, 'ym');
  if (!in_array(date('Y-m'), $months)) {
    array_unshift($months, date('Y-m'));
  }
  return $months;
}

/**
 * Read ?month= from the URL. Falls back to the newest month that has
 * transactions (so the seed data shows up), or 'all' when $allow_all is true.
 */
function selected_month($db, $user_id, $allow_all = false) {
  $month = $_GET['month'] ?? '';
  if ($allow_all && $month === 'all') {
    return 'all';
  }
  if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
    return $month;
  }
  if ($allow_all) {
    return 'all';
  }
  $latest = db_one($db,
    "SELECT DATE_FORMAT(MAX(t.transaction_date), '%Y-%m') AS ym
     FROM Transactions t
     JOIN Accounts a ON a.account_id = t.account_id
     WHERE a.user_id = ?", "i", $user_id);
  return $latest['ym'] ?? date('Y-m');
}

/* First and last day of a "YYYY-MM" month */
function month_range($ym) {
  $start = $ym . '-01';
  return [$start, date('Y-m-t', strtotime($start))];
}

//======================================================================
// READ QUERIES
//======================================================================

/* The user's accounts with their current balance (starting balance + income - expenses) */
function get_accounts($db, $user_id) {
  return db_all($db,
    "SELECT a.account_id, a.account_name, a.account_type, a.starting_balance,
            a.starting_balance + COALESCE(SUM(
              CASE WHEN c.category_type = 'income' THEN t.amount ELSE -t.amount END
            ), 0) AS balance,
            COUNT(t.transaction_id) AS transaction_count
     FROM Accounts a
     LEFT JOIN Transactions t ON t.account_id = a.account_id
     LEFT JOIN Categories c ON c.category_id = t.category_id
     WHERE a.user_id = ?
     GROUP BY a.account_id, a.account_name, a.account_type, a.starting_balance
     ORDER BY a.account_id", "i", $user_id);
}

/* Default categories (user_id NULL) plus the user's own, income first */
function get_categories($db, $user_id) {
  return db_all($db,
    "SELECT category_id, category_name, category_type
     FROM Categories
     WHERE user_id IS NULL OR user_id = ?
     ORDER BY category_type DESC, category_name", "i", $user_id);
}

/* Transactions, newest first. Pass $start/$end (Y-m-d) to limit to a date range. */
function get_transactions($db, $user_id, $start = null, $end = null, $limit = null) {
  $sql = "SELECT t.transaction_id, t.transaction_date, t.description, t.amount,
                 t.account_id, t.category_id, a.account_name,
                 c.category_name, c.category_type
          FROM Transactions t
          JOIN Accounts a ON a.account_id = t.account_id
          JOIN Categories c ON c.category_id = t.category_id
          WHERE a.user_id = ?";
  $types = "i";
  $params = [$user_id];
  if ($start !== null && $end !== null) {
    $sql .= " AND t.transaction_date BETWEEN ? AND ?";
    $types .= "ss";
    array_push($params, $start, $end);
  }
  $sql .= " ORDER BY t.transaction_date DESC, t.transaction_id DESC";
  if ($limit !== null) {
    $sql .= " LIMIT " . (int) $limit;
  }
  return db_all($db, $sql, $types, ...$params);
}

/* ['income' => x, 'expense' => y] for a date range */
function period_totals($db, $user_id, $start, $end) {
  $row = db_one($db,
    "SELECT COALESCE(SUM(CASE WHEN c.category_type = 'income'  THEN t.amount END), 0) AS income,
            COALESCE(SUM(CASE WHEN c.category_type = 'expense' THEN t.amount END), 0) AS expense
     FROM Transactions t
     JOIN Accounts a ON a.account_id = t.account_id
     JOIN Categories c ON c.category_id = t.category_id
     WHERE a.user_id = ? AND t.transaction_date BETWEEN ? AND ?",
    "iss", $user_id, $start, $end);
  return ['income' => (float) $row['income'], 'expense' => (float) $row['expense']];
}

/* Expense totals per category for a date range, biggest first */
function spending_by_category($db, $user_id, $start, $end) {
  return db_all($db,
    "SELECT c.category_name, SUM(t.amount) AS total
     FROM Transactions t
     JOIN Accounts a ON a.account_id = t.account_id
     JOIN Categories c ON c.category_id = t.category_id
     WHERE a.user_id = ? AND c.category_type = 'expense'
       AND t.transaction_date BETWEEN ? AND ?
     GROUP BY c.category_id, c.category_name
     ORDER BY total DESC", "iss", $user_id, $start, $end);
}

/* Budgets that overlap a date range, with how much has been spent against each */
function get_budgets($db, $user_id, $start, $end) {
  return db_all($db,
    "SELECT b.budget_id, c.category_name, b.amount_limit, b.start_date, b.end_date,
            (SELECT COALESCE(SUM(t.amount), 0)
             FROM Transactions t
             JOIN Accounts a ON a.account_id = t.account_id
             WHERE a.user_id = b.user_id
               AND t.category_id = b.category_id
               AND t.transaction_date BETWEEN b.start_date AND b.end_date) AS spent
     FROM Budgets b
     JOIN Categories c ON c.category_id = b.category_id
     WHERE b.user_id = ? AND b.start_date <= ? AND b.end_date >= ?
     ORDER BY c.category_name", "iss", $user_id, $end, $start);
}

/* Income / expense per month for the last $months months that have data */
function monthly_history($db, $user_id, $months = 6) {
  $rows = db_all($db,
    "SELECT DATE_FORMAT(t.transaction_date, '%Y-%m') AS ym,
            SUM(CASE WHEN c.category_type = 'income'  THEN t.amount ELSE 0 END) AS income,
            SUM(CASE WHEN c.category_type = 'expense' THEN t.amount ELSE 0 END) AS expense
     FROM Transactions t
     JOIN Accounts a ON a.account_id = t.account_id
     JOIN Categories c ON c.category_id = t.category_id
     WHERE a.user_id = ?
     GROUP BY ym
     ORDER BY ym DESC
     LIMIT " . (int) $months, "i", $user_id);
  return $rows;
}

/* Name, email, phone and address for the settings page */
function get_profile($db, $user_id) {
  return db_one($db,
    "SELECT c.contact_id, c.first_name, c.last_name, c.email, c.phone,
            c.street_1, c.street_2, c.city, c.state_code, c.post_code,
            cr.username, r.role_type, u.creation_date
     FROM Users u
     JOIN Contacts c ON c.contact_id = u.contact_id
     JOIN Credentials cr ON cr.user_id = u.user_id
     JOIN Roles r ON r.role_id = u.role_id
     WHERE u.user_id = ?", "i", $user_id);
}

//======================================================================
// OWNERSHIP CHECKS (use before every insert / update / delete)
//======================================================================

function user_owns_account($db, $user_id, $account_id) {
  return db_one($db,
    "SELECT 1 AS ok FROM Accounts WHERE account_id = ? AND user_id = ?",
    "ii", $account_id, $user_id) !== null;
}

function user_can_use_category($db, $user_id, $category_id) {
  return db_one($db,
    "SELECT 1 AS ok FROM Categories
     WHERE category_id = ? AND (user_id IS NULL OR user_id = ?)",
    "ii", $category_id, $user_id) !== null;
}

function user_owns_transaction($db, $user_id, $transaction_id) {
  return db_one($db,
    "SELECT 1 AS ok FROM Transactions t
     JOIN Accounts a ON a.account_id = t.account_id
     WHERE t.transaction_id = ? AND a.user_id = ?",
    "ii", $transaction_id, $user_id) !== null;
}

/* Icon and label for each account type */
function account_type_info($type) {
  $types = [
    'checking' => ['bi-bank',        'Checking'],
    'savings'  => ['bi-piggy-bank',  'Savings'],
    'credit'   => ['bi-credit-card', 'Credit Card'],
    'cash'     => ['bi-cash-coin',   'Cash'],
  ];
  return $types[$type] ?? ['bi-wallet2', ucfirst($type)];
}
