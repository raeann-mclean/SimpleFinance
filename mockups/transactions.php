<?php
require_once dirname(__DIR__) . '/php/session.php';
$active_page = 'transactions';
$query = '';
if (isset($_GET['q']) && is_string($_GET['q']) && preg_match('/^.{0,120}/us', trim($_GET['q']), $queryMatch)) {
    $query = $queryMatch[0];
}
$transactions = [];
$searchError = false;
try {
    // Bound parameters prevent SQL injection. Account ownership isolates users.
    $sql = 'SELECT t.transaction_date, t.description, t.amount,
                   c.category_name, c.category_type, a.account_name
            FROM Transactions t JOIN Accounts a ON a.account_id = t.account_id
            JOIN Categories c ON c.category_id = t.category_id WHERE a.user_id = ?';
    if ($query !== '') {
        $sql .= " AND (t.description LIKE ? ESCAPE '!' OR c.category_name LIKE ? ESCAPE '!' OR a.account_name LIKE ? ESCAPE '!')";
    }
    $sql .= ' ORDER BY t.transaction_date DESC, t.transaction_id DESC';
    $statement = $db_connection->prepare($sql);
    $userId = (int) $_SESSION['user_id'];
    if ($query !== '') {
        $pattern = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $query) . '%';
        $statement->bind_param('isss', $userId, $pattern, $pattern, $pattern);
    } else { $statement->bind_param('i', $userId); }
    $statement->execute();
    $transactions = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    $statement->close();
} catch (mysqli_sql_exception $error) {
    error_log('Transaction search failed: ' . $error->getMessage());
    $searchError = true;
}
function escapeTransaction(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="en">
  <head><?php include TEMPLATE_PATH . '/head.php'; ?></head>
  <body>
    <?php include TEMPLATE_PATH . '/header.php'; ?>
    <div class="d-flex vh-100 m-0">
      <?php include TEMPLATE_PATH . '/sidebar.php'; ?>
      <main id="main-content" tabindex="-1" role="main" class="flex-grow-1 p-4">
        <h1>Transactions</h1>
        <p>Search your saved transactions by description, category, or account.</p>
        <form class="search-form mb-4" action="<?php echo BASE_URL; ?>/mockups/transactions.php" method="get" role="search" aria-label="Transaction search">
          <label for="transaction-search" class="form-label">Search transactions</label>
          <div class="d-flex flex-wrap gap-2">
            <input id="transaction-search" class="form-control flex-grow-1 w-auto" type="search" name="q" maxlength="120" value="<?php echo escapeTransaction($query); ?>" placeholder="Try rent, groceries, or checking" aria-describedby="search-help">
            <button class="btn btn-primary" type="submit">Search</button>
            <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>/mockups/transactions.php">Clear search</a>
          </div>
          <div id="search-help" class="form-text">Results include only your accounts.</div>
        </form>
        <?php if ($searchError): ?>
          <p class="alert alert-danger" role="alert">Transactions are unavailable. Complete the database setup and try again.</p>
        <?php else: ?>
          <p id="search-results" role="status" aria-live="polite"><?php echo count($transactions); ?> transaction<?php echo count($transactions) === 1 ? '' : 's'; ?> found<?php if ($query !== '') echo ' for “' . escapeTransaction($query) . '”'; ?>.</p>
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <caption>Your transactions, newest first<?php if ($query !== '') echo ', matching ' . escapeTransaction($query); ?>.</caption>
              <thead><tr><th scope="col">Date</th><th scope="col">Description</th><th scope="col">Category</th><th scope="col">Account</th><th scope="col" class="text-end">Amount</th></tr></thead>
              <tbody>
              <?php foreach ($transactions as $transaction): $income = $transaction['category_type'] === 'income'; ?>
                <tr>
                  <td><?php echo escapeTransaction($transaction['transaction_date']); ?></td>
                  <td><?php echo escapeTransaction($transaction['description'] ?? ''); ?></td>
                  <td><?php echo escapeTransaction($transaction['category_name']); ?></td>
                  <td><?php echo escapeTransaction($transaction['account_name']); ?></td>
                  <td class="text-end transaction-amount <?php echo $income ? 'text-success' : 'text-danger'; ?>"><span class="visually-hidden"><?php echo $income ? 'Income ' : 'Expense '; ?></span><?php echo ($income ? '+' : '-') . '$' . number_format((float) $transaction['amount'], 2); ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (!$transactions): ?><tr><td colspan="5">No transactions match your search. Try another term or clear the search.</td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </main>
    </div>
    <?php include TEMPLATE_PATH . '/footer.php'; ?>
  </body>
</html>
