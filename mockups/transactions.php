<?php
/**
 * Transactions Page
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

    <?php $active_page = 'transactions'; ?>
    <div class="d-flex vh-100 m-0" style="margin-top: 56px;">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <!-- main-->
      <main role="main" class="flex-grow-1 p-4">
        <h1>Transactions</h1>
        <p>View your recent transactions</p>
        <div class="btn-group mb-4" role="group" aria-label="Transaction actions">
          <button type="button" class="btn btn-primary">Add Transaction</button>
          <button type="button" class="btn btn-secondary">Edit Transaction</button>
          <button type="button" class="btn btn-danger">Delete Transaction</button>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Category</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
             
             
            </tbody>
          </table>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
