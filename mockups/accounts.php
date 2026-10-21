<?php
/**
 * Accounts Page
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

    <?php $active_page = 'accounts'; ?>
    <div class="d-flex vh-100 m-0" style="margin-top: 56px;">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <!-- mainn -->
      <main role="main" class="flex-grow-1 p-4">
        <h1>Accounts</h1>
        <p>Manage your financial accounts here.</p>

        <div class="row g-4">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bank me-2"></i>Checking Account</h5>
                <p class="card-text fs-4 fw-bold text-success">$2,450.00</p>
                <p class="card-text text-muted">****1234</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-piggy-bank me-2"></i>Savings Account</h5>
                <p class="card-text fs-4 fw-bold text-success">$10,200.00</p>
                <p class="card-text text-muted">****5678</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-credit-card me-2"></i>Credit Card</h5>
                <p class="card-text fs-4 fw-bold text-danger">-$340.00</p>
                <p class="card-text text-muted">****9012</p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
