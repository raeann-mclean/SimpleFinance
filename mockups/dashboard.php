<?php
/**
 * Dashboard Page
 *
 * @author    James Montgomery
 * @copyright 2026
 * @version   2.0
 */

  /* Quick Paths */
  include_once (realpath(dirname(__FILE__, 2).'/php/path.php'));

  /* Start The Session */
  session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <!--head file for all pages -->
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body class="h-100">
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>

    <?php $active_page = 'dashboard'; ?>
    <div class="d-flex vh-100 m-0" style="margin-top: 56px;">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <!-- main -->
      <main role="main" class="flex-grow-1 p-4">
        <h1>Simple Finance Dashboard</h1>
        <p>Welcome to Simple Money</p>

        <!-- Budget Overview -->
        <div class="row g-4 mt-2">
          <div class="col-md-4">
            <div class="card border-success">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-wallet2 me-1"></i>Total Balance</h6>
                <p class="card-text fs-3 fw-bold text-success">$12,310.00</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-danger">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-cart me-1"></i>Spent This Month</h6>
                <p class="card-text fs-3 fw-bold text-danger">$725.00</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-primary">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-cash-stack me-1"></i>Left to Spend</h6>
                <p class="card-text fs-3 fw-bold text-primary">$2,275.00</p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- One footer file for all pages -->
    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
