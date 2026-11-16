<?php
/**
 * User Dashboard (finance overview)
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @author    James Montgomery (design, from mockups/dashboard.php)
 * @copyright 2026
 * @version   2.0
 */

  /* Login, role check, database and finance helpers */
  include_once (realpath(dirname(__FILE__, 3).'/php/user_page.php'));

  /* Page Name */
  $page_name = "user";
  $active_page = 'dashboard'; // highlights the sidebar link

  /* Which month to show (?month=YYYY-MM, defaults to the newest month with data) */
  $months = available_months($db_connection, $user_id);
  $selected_month = selected_month($db_connection, $user_id);
  [$month_start, $month_end] = month_range($selected_month);

  /* Data for this user */
  $accounts      = get_accounts($db_connection, $user_id);
  $totals        = period_totals($db_connection, $user_id, $month_start, $month_end);
  $budgets       = get_budgets($db_connection, $user_id, $month_start, $month_end);
  $recent        = get_transactions($db_connection, $user_id, $month_start, $month_end, 5);

  $total_balance = array_sum(array_column($accounts, 'balance'));

  /* Left to spend: what's left in this month's budgets, or income minus spending if there are no budgets */
  if (count($budgets) > 0) {
    $left_label    = "Left in Budgets";
    $left_to_spend = array_sum(array_column($budgets, 'amount_limit')) - array_sum(array_column($budgets, 'spent'));
  } else {
    $left_label    = "Left to Spend";
    $left_to_spend = $totals['income'] - $totals['expense'];
  }
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
        <h1>Simple Finance Dashboard</h1>
        <p>Welcome back, <?php echo h($_SESSION['user_first']); ?></p>
        <?php include ROOT_SRC_PATH . '/error_rprt.php'; ?>
        <?php include TEMPLATE_PATH . '/month_picker.php'; ?>

        <!-- Budget Overview -->
        <div class="row g-4 mt-2">
          <div class="col-md-4">
            <div class="card border-success">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-wallet2 me-1"></i>Total Balance</h6>
                <p class="card-text fs-3 fw-bold <?php echo $total_balance < 0 ? 'text-danger' : 'text-success'; ?>"><?php echo money($total_balance); ?></p>
                <small class="text-muted">across <?php echo count($accounts); ?> account<?php echo count($accounts) == 1 ? '' : 's'; ?>, today</small>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-danger">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-cart me-1"></i>Spent in <?php echo h(month_label($selected_month)); ?></h6>
                <p class="card-text fs-3 fw-bold text-danger"><?php echo money($totals['expense']); ?></p>
                <small class="text-muted">income <?php echo money($totals['income']); ?></small>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-primary">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-cash-stack me-1"></i><?php echo $left_label; ?></h6>
                <p class="card-text fs-3 fw-bold <?php echo $left_to_spend < 0 ? 'text-danger' : 'text-primary'; ?>"><?php echo money($left_to_spend); ?></p>
                <small class="text-muted"><?php echo h(month_label($selected_month)); ?></small>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-4 mt-1">
          <!-- Budgets -->
          <div class="col-lg-5">
            <div class="card h-100">
              <div class="card-header"><i class="bi bi-bullseye me-1"></i>Budgets</div>
              <div class="card-body">
                <?php if (count($budgets) == 0): ?>
                  <p class="text-muted mb-0">No budgets set for <?php echo h(month_label($selected_month)); ?>.</p>
                <?php endif; ?>
                <?php foreach ($budgets as $b):
                  $percent = $b['amount_limit'] > 0 ? min(100, round($b['spent'] / $b['amount_limit'] * 100)) : 0;
                  $bar = $b['spent'] > $b['amount_limit'] ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success');
                ?>
                  <div class="mb-3">
                    <div class="d-flex justify-content-between">
                      <span><?php echo h($b['category_name']); ?></span>
                      <span><?php echo money($b['spent']); ?> / <?php echo money($b['amount_limit']); ?></span>
                    </div>
                    <div class="progress" role="progressbar" aria-label="<?php echo h($b['category_name']); ?> budget" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100">
                      <div class="progress-bar <?php echo $bar; ?>" style="width: <?php echo $percent; ?>%"></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Recent transactions -->
          <div class="col-lg-7">
            <div class="card h-100">
              <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-clock-history me-1"></i>Recent Transactions</span>
                <a href="<?php echo USER_URL; ?>/transactions.php?month=<?php echo h($selected_month); ?>">View all</a>
              </div>
              <ul class="list-group list-group-flush">
                <?php if (count($recent) == 0): ?>
                  <li class="list-group-item text-muted">No transactions in <?php echo h(month_label($selected_month)); ?>.</li>
                <?php endif; ?>
                <?php foreach ($recent as $t): ?>
                  <li class="list-group-item d-flex justify-content-between">
                    <span>
                      <?php echo h($t['description'] ?: $t['category_name']); ?>
                      <br><small class="text-muted"><?php echo h(date('M j', strtotime($t['transaction_date']))); ?> · <?php echo h($t['category_name']); ?> · <?php echo h($t['account_name']); ?></small>
                    </span>
                    <span class="fw-bold <?php echo $t['category_type'] == 'income' ? 'text-success' : 'text-danger'; ?>">
                      <?php echo ($t['category_type'] == 'income' ? '+' : '-') . money($t['amount']); ?>
                    </span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
