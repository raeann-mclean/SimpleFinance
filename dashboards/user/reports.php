<?php
/**
 * Reports Page
 *
 * @author    James Montgomery (design, from mockups/reports.php)
 * @copyright 2026
 * @version   2.0
 */

  /* Login, role check, database and finance helpers */
  include_once (realpath(dirname(__FILE__, 3).'/php/user_page.php'));

  /* Page Name */
  $page_name = "user";
  $active_page = 'reports';

  /* Which month to report on */
  $months = available_months($db_connection, $user_id);
  $selected_month = selected_month($db_connection, $user_id);
  [$month_start, $month_end] = month_range($selected_month);

  $spending = spending_by_category($db_connection, $user_id, $month_start, $month_end);
  $totals   = period_totals($db_connection, $user_id, $month_start, $month_end);
  $history  = monthly_history($db_connection, $user_id, 6);

  $net = $totals['income'] - $totals['expense'];
  $savings_rate = $totals['income'] > 0 ? round($net / $totals['income'] * 100) : null;
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
        <h1>Reports</h1>
        <p>View your financial reports and analytics.</p>
        <div class="mb-4"><?php include TEMPLATE_PATH . '/month_picker.php'; ?></div>

        <div class="row g-4">
          <!-- Spending by category -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bar-chart me-2"></i>Spending by Category</h5>
                <p class="card-text">Where your money went in <?php echo h(month_label($selected_month)); ?>.</p>
                <ul class="list-group list-group-flush">
                  <?php if (count($spending) == 0): ?>
                    <li class="list-group-item text-muted">No spending this month.</li>
                  <?php endif; ?>
                  <?php foreach ($spending as $s):
                    $share = $totals['expense'] > 0 ? round($s['total'] / $totals['expense'] * 100) : 0;
                  ?>
                    <li class="list-group-item">
                      <div class="d-flex justify-content-between">
                        <?php echo h($s['category_name']); ?>
                        <span class="fw-bold"><?php echo money($s['total']); ?> <small class="text-muted fw-normal">(<?php echo $share; ?>%)</small></span>
                      </div>
                      <div class="progress mt-1" style="height: 6px;" role="progressbar" aria-label="<?php echo h($s['category_name']); ?> share of spending" aria-valuenow="<?php echo $share; ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: <?php echo $share; ?>%; background-color: #050359;"></div>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>

          <!-- Income vs expenses -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pie-chart me-2"></i>Income vs Expenses</h5>
                <p class="card-text">Overview of your income and expenses in <?php echo h(month_label($selected_month)); ?>.</p>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between">Total Income <span class="fw-bold text-success"><?php echo money($totals['income']); ?></span></li>
                  <li class="list-group-item d-flex justify-content-between">Total Expenses <span class="fw-bold text-danger"><?php echo money($totals['expense']); ?></span></li>
                  <li class="list-group-item d-flex justify-content-between">Net Savings <span class="fw-bold <?php echo $net < 0 ? 'text-danger' : 'text-primary'; ?>"><?php echo money($net); ?></span></li>
                  <?php if ($savings_rate !== null): ?>
                    <li class="list-group-item d-flex justify-content-between">Savings Rate <span class="fw-bold"><?php echo $savings_rate; ?>%</span></li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>

          <!-- Month by month -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-calendar-range me-2"></i>Month by Month</h5>
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr><th>Month</th><th class="text-end">Income</th><th class="text-end">Expenses</th><th class="text-end">Net</th></tr>
                    </thead>
                    <tbody>
                      <?php if (count($history) == 0): ?>
                        <tr><td colspan="4" class="text-muted">No transactions yet.</td></tr>
                      <?php endif; ?>
                      <?php foreach ($history as $m): $m_net = $m['income'] - $m['expense']; ?>
                        <tr class="<?php echo $m['ym'] === $selected_month ? 'table-active' : ''; ?>">
                          <td><a href="?month=<?php echo h($m['ym']); ?>"><?php echo h(month_label($m['ym'])); ?></a></td>
                          <td class="text-end text-success"><?php echo money($m['income']); ?></td>
                          <td class="text-end text-danger"><?php echo money($m['expense']); ?></td>
                          <td class="text-end fw-bold <?php echo $m_net < 0 ? 'text-danger' : ''; ?>"><?php echo money($m_net); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
