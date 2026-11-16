<?php
//======================================================================
// Account form handler (dashboards/user/accounts.php)
//   action=add    -> create an account for the logged-in user
//   action=delete -> delete one of the user's accounts (and its transactions)
//======================================================================

include_once (realpath(dirname(__FILE__).'/user_page.php'));

$back = USER_URL . '/accounts.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("location: " . $back);
  exit();
}
if (!csrf_check()) {
  redirect_with($back, 'error', 'Your session expired. Please try again.');
}

$action = $_POST['action'] ?? '';

/* ---------- Add ---------- */
if ($action === 'add') {
  $name    = trim($_POST['account_name'] ?? '');
  $type    = $_POST['account_type'] ?? '';
  $balance = $_POST['starting_balance'] ?? '0';

  if ($name === '' || strlen($name) > 255) {
    redirect_with($back, 'error', 'Please enter an account name (255 characters max).');
  }
  if (!in_array($type, ['checking', 'savings', 'credit', 'cash'], true)) {
    redirect_with($back, 'error', 'Please choose an account type.');
  }
  if (!is_numeric($balance) || abs((float) $balance) >= 10000000000) {
    redirect_with($back, 'error', 'Starting balance must be a number.');
  }
  $balance = round((float) $balance, 2);

  $stmt = $db_connection->prepare(
    "INSERT INTO Accounts (user_id, account_name, account_type, starting_balance) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("issd", $user_id, $name, $type, $balance);
  $stmt->execute();
  $stmt->close();
  redirect_with($back, 'success', 'Account "' . $name . '" added.');
}

/* ---------- Delete ---------- */
if ($action === 'delete') {
  $account_id = (int) ($_POST['account_id'] ?? 0);
  if (!user_owns_account($db_connection, $user_id, $account_id)) {
    redirect_with($back, 'error', 'Account not found.');
  }
  // Transactions in this account are removed too (ON DELETE CASCADE in schema.sql)
  $stmt = $db_connection->prepare("DELETE FROM Accounts WHERE account_id = ? AND user_id = ?");
  $stmt->bind_param("ii", $account_id, $user_id);
  $stmt->execute();
  $stmt->close();
  redirect_with($back, 'success', 'Account deleted.');
}

redirect_with($back, 'error', 'Unknown action.');
