<?php
/**
 * Accounts Page
 *
 * @author    James Montgomery (design, from mockups/accounts.php)
 * @copyright 2026
 * @version   2.0
 */

  /* Login, role check, database and finance helpers */
  include_once (realpath(dirname(__FILE__, 3).'/php/user_page.php'));

  /* Page Name */
  $page_name = "user";
  $active_page = 'accounts';

  $accounts = get_accounts($db_connection, $user_id);
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
        <h1>Accounts</h1>
        <p>Manage your financial accounts here</p>
        <?php include ROOT_SRC_PATH . '/error_rprt.php'; ?>

        <div class="btn-group mb-4" role="group" aria-label="Account actions">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="bi bi-plus-lg me-1"></i>Add Account
          </button>
        </div>

        <?php if (count($accounts) == 0): ?>
          <p class="text-muted">You don't have any accounts yet. Add one to start tracking transactions.</p>
        <?php endif; ?>

        <div class="row g-4">
          <?php foreach ($accounts as $a):
            [$icon, $type_label] = account_type_info($a['account_type']);
          ?>
            <div class="col-md-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title"><i class="bi <?php echo $icon; ?> me-2"></i><?php echo h($a['account_name']); ?></h5>
                    <form method="post" action="<?php echo SRC_PATH; ?>/account_submit.php"
                          onsubmit="return confirm('Delete <?php echo h(addslashes($a['account_name'])); ?> and its <?php echo (int) $a['transaction_count']; ?> transaction(s)? This cannot be undone.');">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="account_id" value="<?php echo (int) $a['account_id']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete account" aria-label="Delete <?php echo h($a['account_name']); ?>">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                  <p class="card-text fs-4 fw-bold <?php echo $a['balance'] < 0 ? 'text-danger' : 'text-success'; ?>"><?php echo money($a['balance']); ?></p>
                  <p class="card-text text-muted mb-1"><?php echo h($type_label); ?> · <?php echo (int) $a['transaction_count']; ?> transaction<?php echo $a['transaction_count'] == 1 ? '' : 's'; ?></p>
                  <a href="<?php echo USER_URL; ?>/transactions.php?month=all&amp;account=<?php echo (int) $a['account_id']; ?>" class="small">View transactions</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </main>
    </div>

    <!-- Add Account form -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?php echo SRC_PATH; ?>/account_submit.php">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="add">
          <div class="modal-header">
            <h5 class="modal-title" id="addAccountLabel">Add Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="account_name" class="form-label">Account name</label>
              <input type="text" class="form-control" id="account_name" name="account_name" maxlength="255" required placeholder="e.g. Everyday Checking">
            </div>
            <div class="mb-3">
              <label for="account_type" class="form-label">Type</label>
              <select class="form-select" id="account_type" name="account_type" required>
                <option value="checking">Checking</option>
                <option value="savings">Savings</option>
                <option value="credit">Credit Card</option>
                <option value="cash">Cash</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="starting_balance" class="form-label">Starting balance</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="starting_balance" name="starting_balance" step="0.01" value="0.00" required>
              </div>
              <div class="form-text">For a credit card, enter what you already owe as a negative number.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Account</button>
          </div>
        </form>
      </div>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
