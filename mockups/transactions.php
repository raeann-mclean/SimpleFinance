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
        <p>View your recent transactions.</p>

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
              <tr>
                <td>2026-10-20</td>
                <td>Grocery Store</td>
                <td><span class="badge bg-info">Food</span></td>
                <td class="text-danger">-$85.40</td>
              </tr>
              <tr>
                <td>2026-10-19</td>
                <td>Direct Deposit - Payroll</td>
                <td><span class="badge bg-success">Income</span></td>
                <td class="text-success">+$1,500.00</td>
              </tr>
              <tr>
                <td>2026-10-18</td>
                <td>Electric Company</td>
                <td><span class="badge bg-warning text-dark">Utilities</span></td>
                <td class="text-danger">-$120.00</td>
              </tr>
              <tr>
                <td>2026-10-17</td>
                <td>Coffee Shop</td>
                <td><span class="badge bg-info">Food</span></td>
                <td class="text-danger">-$5.75</td>
              </tr>
              <tr>
                <td>2026-10-16</td>
                <td>Online Subscription</td>
                <td><span class="badge bg-secondary">Entertainment</span></td>
                <td class="text-danger">-$14.99</td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
