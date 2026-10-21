<?php
/**
 * Sidebar Navigation Include File
 *
 * Set $active_page before including this file to highlight the current page.
 * Valid values: 'dashboard', 'accounts', 'transactions', 'reports', 'settings'
 */
if (!isset($active_page)) {
    $active_page = '';
}
?>
<nav class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 250px; background-color: #050359;">
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="<?php echo BASE_URL; ?>/mockups/dashboard.php" class="nav-link text-white <?php echo ($active_page === 'dashboard') ? 'active' : ''; ?>" <?php if ($active_page === 'dashboard') echo 'style="background-color: #1a0f8a;"'; ?>>
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo BASE_URL; ?>/mockups/accounts.php" class="nav-link text-white <?php echo ($active_page === 'accounts') ? 'active' : ''; ?>" <?php if ($active_page === 'accounts') echo 'style="background-color: #1a0f8a;"'; ?>>
        <i class="bi bi-wallet2 me-2"></i>Accounts
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo BASE_URL; ?>/mockups/transactions.php" class="nav-link text-white <?php echo ($active_page === 'transactions') ? 'active' : ''; ?>" <?php if ($active_page === 'transactions') echo 'style="background-color: #1a0f8a;"'; ?>>
        <i class="bi bi-arrow-left-right me-2"></i>Transactions
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo BASE_URL; ?>/mockups/reports.php" class="nav-link text-white <?php echo ($active_page === 'reports') ? 'active' : ''; ?>" <?php if ($active_page === 'reports') echo 'style="background-color: #1a0f8a;"'; ?>>
        <i class="bi bi-graph-up me-2"></i>Reports
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo BASE_URL; ?>/mockups/settings.php" class="nav-link text-white <?php echo ($active_page === 'settings') ? 'active' : ''; ?>" <?php if ($active_page === 'settings') echo 'style="background-color: #1a0f8a;"'; ?>>
        <i class="bi bi-gear me-2"></i>Settings
      </a>
    </li>
  </ul>
</nav>
