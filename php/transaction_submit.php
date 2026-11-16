<?php
//======================================================================
// Transaction form handler (dashboards/user/transactions.php)
//   action=add    -> add a transaction to one of the user's accounts
//   action=edit   -> change one of the user's transactions
//   action=delete -> delete one of the user's transactions
//======================================================================

include_once (realpath(dirname(__FILE__).'/user_page.php'));

/* Send the user back to the same month / account filter they were looking at */
parse_str($_POST['return'] ?? '', $return);
$back = USER_URL . '/transactions.php';
$filters = array_intersect_key($return, ['month' => 1, 'account' => 1]);
if ($filters) {
  $back .= '?' . http_build_query($filters);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("location: " . $back);
  exit();
}
if (!csrf_check()) {
  redirect_with($back, 'error', 'Your session expired. Please try again.');
}

$action = $_POST['action'] ?? '';
$transaction_id = (int) ($_POST['transaction_id'] ?? 0);

/* Edit and delete only work on the user's own transactions */
if (in_array($action, ['edit', 'delete'], true)
    && !user_owns_transaction($db_connection, $user_id, $transaction_id)) {
  redirect_with($back, 'error', 'Transaction not found.');
}

/* ---------- Delete ---------- */
if ($action === 'delete') {
  $stmt = $db_connection->prepare("DELETE FROM Transactions WHERE transaction_id = ?");
  $stmt->bind_param("i", $transaction_id);
  $stmt->execute();
  $stmt->close();
  redirect_with($back, 'success', 'Transaction deleted.');
}

/* ---------- Add / Edit: validate the form ---------- */
if ($action === 'add' || $action === 'edit') {
  $account_id  = (int) ($_POST['account_id'] ?? 0);
  $category_id = (int) ($_POST['category_id'] ?? 0);
  $amount      = $_POST['amount'] ?? '';
  $date        = $_POST['transaction_date'] ?? '';
  $description = trim($_POST['description'] ?? '');

  if (!user_owns_account($db_connection, $user_id, $account_id)) {
    redirect_with($back, 'error', 'Please choose one of your accounts.');
  }
  if (!user_can_use_category($db_connection, $user_id, $category_id)) {
    redirect_with($back, 'error', 'Please choose a category.');
  }
  if (!is_numeric($amount) || (float) $amount <= 0 || (float) $amount >= 10000000000) {
    redirect_with($back, 'error', 'Amount must be a positive number.');
  }
  $amount = round((float) $amount, 2);
  $parsed = DateTime::createFromFormat('Y-m-d', $date);
  if (!$parsed || $parsed->format('Y-m-d') !== $date) {
    redirect_with($back, 'error', 'Please enter a valid date.');
  }
  if (strlen($description) > 255) {
    redirect_with($back, 'error', 'Description is too long (255 characters max).');
  }

  if ($action === 'add') {
    $stmt = $db_connection->prepare(
      "INSERT INTO Transactions (account_id, category_id, amount, transaction_date, description)
       VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iidss", $account_id, $category_id, $amount, $date, $description);
    $stmt->execute();
    $stmt->close();
    redirect_with($back, 'success', 'Transaction added.');
  }

  $stmt = $db_connection->prepare(
    "UPDATE Transactions
     SET account_id = ?, category_id = ?, amount = ?, transaction_date = ?, description = ?
     WHERE transaction_id = ?");
  $stmt->bind_param("iidssi", $account_id, $category_id, $amount, $date, $description, $transaction_id);
  $stmt->execute();
  $stmt->close();
  redirect_with($back, 'success', 'Transaction updated.');
}

redirect_with($back, 'error', 'Unknown action.');
