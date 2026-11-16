<?php
/**
 * Transactions Page
 *
 * @author    James Montgomery (design, from mockups/transactions.php)
 * @copyright 2026
 * @version   2.0
 */

  /* Login, role check, database and finance helpers */
  include_once (realpath(dirname(__FILE__, 3).'/php/user_page.php'));

  /* Page Name */
  $page_name = "user";
  $active_page = 'transactions';

  /* Filters: ?month=YYYY-MM|all and ?account=ID */
  $months = available_months($db_connection, $user_id);
  $allow_all = true;
  $selected_month = selected_month($db_connection, $user_id, $allow_all);

  $accounts   = get_accounts($db_connection, $user_id);
  $categories = get_categories($db_connection, $user_id);

  if ($selected_month === 'all') {
    $transactions = get_transactions($db_connection, $user_id);
  } else {
    [$month_start, $month_end] = month_range($selected_month);
    $transactions = get_transactions($db_connection, $user_id, $month_start, $month_end);
  }

  /* Optional account filter (only the user's own transactions are loaded above, so this is safe) */
  $account_filter = isset($_GET['account']) ? (int) $_GET['account'] : 0;
  $account_filter_name = '';
  if ($account_filter > 0) {
    $transactions = array_values(array_filter($transactions, fn($t) => (int) $t['account_id'] === $account_filter));
    foreach ($accounts as $a) {
      if ((int) $a['account_id'] === $account_filter) { $account_filter_name = $a['account_name']; }
    }
  }

  /* Remember the filters so the forms can send the user back to the same view */
  $return_query = http_build_query(array_filter(['month' => $selected_month, 'account' => $account_filter ?: null]));
?>
<!doctype html>
<html lang="en">
  <head>
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body class="<?php echo $page_name; ?>">
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>

    <div class="d-flex min-vh-100 m-0">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <main role="main" class="flex-grow-1 p-4 pb-5">
        <h1>Transactions</h1>
        <p>View your recent transactions</p>
        <?php include ROOT_SRC_PATH . '/error_rprt.php'; ?>

        <div class="d-flex flex-wrap gap-2 mb-4">
          <div class="btn-group" role="group" aria-label="Transaction actions">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionModal"
                    data-mode="add" <?php echo count($accounts) == 0 ? 'disabled' : ''; ?>>
              <i class="bi bi-plus-lg me-1"></i>Add Transaction
            </button>
          </div>
          <?php include TEMPLATE_PATH . '/month_picker.php'; ?>
          <?php if ($account_filter_name !== ''): ?>
            <a class="btn btn-outline-secondary" href="?month=<?php echo h($selected_month); ?>" title="Show all accounts">
              <?php echo h($account_filter_name); ?> <i class="bi bi-x-lg ms-1"></i>
            </a>
          <?php endif; ?>
        </div>
        <?php if (count($accounts) == 0): ?>
          <p class="text-muted"><a href="<?php echo USER_URL; ?>/accounts.php">Add an account</a> before adding transactions.</p>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Category</th>
                <th>Account</th>
                <th class="text-end">Amount</th>
                <th class="text-end"><span class="visually-hidden">Actions</span></th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($transactions) == 0): ?>
                <tr><td colspan="6" class="text-muted text-center">No transactions found.</td></tr>
              <?php endif; ?>
              <?php foreach ($transactions as $t): $is_income = $t['category_type'] == 'income'; ?>
                <tr>
                  <td><?php echo h(date('M j, Y', strtotime($t['transaction_date']))); ?></td>
                  <td><?php echo h($t['description']); ?></td>
                  <td><?php echo h($t['category_name']); ?></td>
                  <td><?php echo h($t['account_name']); ?></td>
                  <td class="text-end fw-bold <?php echo $is_income ? 'text-success' : 'text-danger'; ?>">
                    <?php echo ($is_income ? '+' : '-') . money($t['amount']); ?>
                  </td>
                  <td class="text-end text-nowrap">
                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"
                            aria-label="Edit <?php echo h($t['description']); ?>"
                            data-bs-toggle="modal" data-bs-target="#transactionModal" data-mode="edit"
                            data-id="<?php echo (int) $t['transaction_id']; ?>"
                            data-date="<?php echo h($t['transaction_date']); ?>"
                            data-description="<?php echo h($t['description']); ?>"
                            data-amount="<?php echo h($t['amount']); ?>"
                            data-account="<?php echo (int) $t['account_id']; ?>"
                            data-category="<?php echo (int) $t['category_id']; ?>">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form method="post" action="<?php echo SRC_PATH; ?>/transaction_submit.php" class="d-inline"
                          onsubmit="return confirm('Delete this transaction?');">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="transaction_id" value="<?php echo (int) $t['transaction_id']; ?>">
                      <input type="hidden" name="return" value="<?php echo h($return_query); ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete <?php echo h($t['description']); ?>">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>

    <!-- Add / Edit Transaction form (one modal, filled in by the script below) -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?php echo SRC_PATH; ?>/transaction_submit.php">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" id="tx_action" value="add">
          <input type="hidden" name="transaction_id" id="tx_id" value="">
          <input type="hidden" name="return" value="<?php echo h($return_query); ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="transactionModalLabel">Add Transaction</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="tx_date" class="form-label">Date</label>
              <input type="date" class="form-control" id="tx_date" name="transaction_date" required>
            </div>
            <div class="mb-3">
              <label for="tx_description" class="form-label">Description</label>
              <input type="text" class="form-control" id="tx_description" name="description" maxlength="255" placeholder="e.g. Trader Joe's">
            </div>
            <div class="mb-3">
              <label for="tx_amount" class="form-label">Amount</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="tx_amount" name="amount" step="0.01" min="0.01" required>
              </div>
              <div class="form-text">Always positive. The category decides whether it's income or an expense.</div>
            </div>
            <div class="mb-3">
              <label for="tx_category" class="form-label">Category</label>
              <select class="form-select" id="tx_category" name="category_id" required>
                <?php foreach (['expense' => 'Expenses', 'income' => 'Income'] as $type => $label): ?>
                  <optgroup label="<?php echo $label; ?>">
                    <?php foreach ($categories as $c): if ($c['category_type'] !== $type) continue; ?>
                      <option value="<?php echo (int) $c['category_id']; ?>"><?php echo h($c['category_name']); ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="tx_account" class="form-label">Account</label>
              <select class="form-select" id="tx_account" name="account_id" required>
                <?php foreach ($accounts as $a): ?>
                  <option value="<?php echo (int) $a['account_id']; ?>" <?php echo (int) $a['account_id'] === $account_filter ? 'selected' : ''; ?>><?php echo h($a['account_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="tx_submit">Add Transaction</button>
          </div>
        </form>
      </div>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>

    <script>
      // Fill the modal for "Add" (blank, today's date) or "Edit" (values from the row's button)
      document.getElementById('transactionModal').addEventListener('show.bs.modal', function (event) {
        const b = event.relatedTarget.dataset;
        const editing = b.mode === 'edit';
        const today = new Date();
        const pad = n => String(n).padStart(2, '0');

        document.getElementById('transactionModalLabel').textContent = editing ? 'Edit Transaction' : 'Add Transaction';
        document.getElementById('tx_submit').textContent = editing ? 'Save Changes' : 'Add Transaction';
        document.getElementById('tx_action').value = editing ? 'edit' : 'add';
        document.getElementById('tx_id').value = editing ? b.id : '';
        document.getElementById('tx_date').value = editing ? b.date
          : today.getFullYear() + '-' + pad(today.getMonth() + 1) + '-' + pad(today.getDate());
        document.getElementById('tx_description').value = editing ? b.description : '';
        document.getElementById('tx_amount').value = editing ? b.amount : '';
        if (editing) {
          document.getElementById('tx_category').value = b.category;
          document.getElementById('tx_account').value = b.account;
        }
      });
    </script>
  </body>
</html>
