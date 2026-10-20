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
    <!-- One head file for all pages -->
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body>
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>

    <div class="d-flex" style="margin-top: 56px; min-height);">
      <!-- ssssidebar -->
      <nav class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 250px; background-color: #050359;">
        <a href="<?php echo BASE_URL; ?>/mockups/dashboard.php" class="d-flex align-items-center mb-3 text-white text-decoration-none border-bottom pb-3">
          <img src="<?php echo ASSET_URL; ?>/img/SimpleFinanceLogo.png" alt="Simple Finance" height="30" class="me-2">
          <span class="fs-5 fw-semibold">Simple Finance</span>
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/mockups/dashboard.php" class="nav-link text-white active" style="background-color: #1a0f8a;">
              <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white">
              <i class="bi bi-wallet2 me-2"></i>Accounts
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white">
              <i class="bi bi-arrow-left-right me-2"></i>Transactions
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white">
              <i class="bi bi-graph-up me-2"></i>Reports
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white">
              <i class="bi bi-gear me-2"></i>Settings
            </a>
          </li>
        </ul>
      </nav>

      <!-- Main Content -->
      <main role="main" class="flex-grow-1 p-4">
        <h1>Simple Finance Dashboard</h1>
        <p>Welcome to Simple Money</p>
      </main>
    </div>

    <!-- One footer file for all pages -->
    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
