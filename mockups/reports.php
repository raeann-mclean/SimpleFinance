<?php
/**
 * Reports Page
 *
 * @author    James Montgomery
 * @copyright 2026
 * @version   1.0
 */

  /* Quick Paths */
  include_once (realpath(dirname(__FILE__, 2).'/php/path.php'));

  /* Start The Session */
  session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body class="h-100">
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>

    <?php $active_page = 'reports'; ?>
    <div class="d-flex vh-100 m-0" style="margin-top: 56px;">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <!-- main -->
      <main role="main" class="flex-grow-1 p-4">
        <h1>Reports</h1>
        <p>View your financial reports and analytics.</p>

        <div class="row g-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bar-chart me-2"></i>Monthly Spending</h5>
                <p class="card-text">Track your spending trends over time.</p>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between">Food <span class="fw-bold">$320.00</span></li>
                  <li class="list-group-item d-flex justify-content-between">Utilities <span class="fw-bold">$180.00</span></li>
                  <li class="list-group-item d-flex justify-content-between">Entertainment <span class="fw-bold">$75.00</span></li>
                  <li class="list-group-item d-flex justify-content-between">Transport <span class="fw-bold">$150.00</span></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pie-chart me-2"></i>Income vs Expenses</h5>
                <p class="card-text">Overview of your income and expenses this month.</p>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between">Total Income <span class="fw-bold text-success">$3,000.00</span></li>
                  <li class="list-group-item d-flex justify-content-between">Total Expenses <span class="fw-bold text-danger">$725.00</span></li>
                  <li class="list-group-item d-flex justify-content-between">Net Savings <span class="fw-bold text-primary">$2,275.00</span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
